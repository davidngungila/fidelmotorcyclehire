<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SwfContribution;
use App\Models\SwfMember;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SwfContributionController extends Controller
{
    use FlashMessages;

    public function create($swfMemberId): View
    {
        $swfMember = SwfMember::with('user')->findOrFail($swfMemberId);
        
        return view('admin.swf.contributions.create', [
            'swfMember' => $swfMember,
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
            return redirect()->route('admin.swf.members.show', $swfMember->id);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to record contribution: ' . $e->getMessage());
        }
    }
}
