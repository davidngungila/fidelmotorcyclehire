<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ActivityLog;
use App\Models\JournalEntry;
use App\Models\SwfContribution;
use App\Models\SwfMember;
use App\Services\EncryptedIdService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SwfContributionController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function create(string $encryptedSwfMemberId): View
    {
        $id = $this->encryptedIdService->decrypt($encryptedSwfMemberId);
        $swfMember = SwfMember::with('user')->findOrFail($id);
        
        return view('admin.swf.contributions.create', [
            'swfMember' => $swfMember,
            'encryptedSwfMemberId' => $encryptedSwfMemberId,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'swf_member_id' => 'required|exists:swf_members,id',
            'amount' => 'required|numeric|min:0',
            'contribution_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        try {
            $swfMember = SwfMember::findOrFail($request->input('swf_member_id'));
            
            $contribution = SwfContribution::create([
                'swf_member_id' => $swfMember->id,
                'amount' => $request->input('amount'),
                'contribution_date' => $request->input('contribution_date'),
                'payment_method' => $request->input('payment_method'),
                'reference_number' => $request->input('reference_number'),
                'notes' => $request->input('notes'),
            ]);

            // Update member total contributions
            $swfMember->total_contributions += $contribution->amount;
            $swfMember->save();

            // Create journal entry for SWF contribution (double-entry)
            $this->createSwfContributionJournalEntry($contribution, $swfMember);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'subject_type' => 'swf_contribution',
                'subject_id' => $contribution->id,
                'description' => "Admin recorded SWF contribution: {$contribution->amount} TSh",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'properties' => [
                    'swf_member_id' => $swfMember->id,
                    'membership_number' => $swfMember->membership_number,
                    'amount' => $contribution->amount,
                ],
            ]);

            $this->success('Contribution recorded successfully!');
            $encryptedId = $this->encryptedIdService->encrypt($swfMember->id);
            return redirect()->route('admin.swf.members.show', $encryptedId);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to record contribution: ' . $e->getMessage());
        }
    }

    private function createSwfContributionJournalEntry(SwfContribution $contribution, SwfMember $swfMember)
    {
        // Get SWF fund account (liability) and cash/bank account
        $swfFundAccount = Account::where('account_type', 'liability')
            ->where('account_subtype', 'swf_fund')
            ->where('is_active', true)
            ->first();

        $cashAccount = Account::where('account_type', 'asset')
            ->where('account_subtype', 'current_asset')
            ->where('is_active', true)
            ->first();

        if (!$swfFundAccount || !$cashAccount) {
            \Log::error('Required accounts not found for SWF contribution journal entry', [
                'contribution_id' => $contribution->id,
            ]);
            return;
        }

        $userName = $swfMember->user ? $swfMember->user->name : 'Unknown';

        // Create journal entry
        $journalEntry = JournalEntry::create([
            'entry_number' => 'SWF-' . date('Ymd') . '-' . str_pad((string) ($contribution->id), 4, '0', STR_PAD_LEFT),
            'entry_date' => $contribution->contribution_date,
            'entry_type' => 'swf_contribution',
            'description' => "SWF contribution from {$userName} ({$swfMember->membership_number})",
            'reference' => $contribution->reference_number ?? 'SWF-' . $contribution->id,
            'total_debit' => $contribution->amount,
            'total_credit' => $contribution->amount,
            'status' => 'posted',
            'created_by' => Auth::id(),
        ]);

        // Create journal entry lines (double-entry)
        // Debit: Cash/Bank (Asset increases)
        $journalEntry->lines()->create([
            'account_id' => $cashAccount->id,
            'debit_amount' => $contribution->amount,
            'credit_amount' => 0,
            'description' => "SWF contribution payment from {$userName}",
        ]);

        // Credit: SWF Fund (Liability increases)
        $journalEntry->lines()->create([
            'account_id' => $swfFundAccount->id,
            'debit_amount' => 0,
            'credit_amount' => $contribution->amount,
            'description' => "SWF fund contribution from {$userName}",
        ]);

        // Post the journal entry to update account balances
        $journalEntry->post();
    }
}
