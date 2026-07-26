<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\AdminDashboardService;
use App\Services\EncryptedIdService;
use App\Services\MemberService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected GoogleSheetRepositoryInterface $googleSheetRepository,
        protected MemberService $memberService,
        protected AdminDashboardService $dashboardService,
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $sortColumn = $request->input('sort', 'date');
        $sortDirection = $request->input('sort_direction', 'desc');
        $searchQuery = $request->input('q', '');

        $allTransactions = $this->googleSheetRepository->getAllTransactions();
        $allMembers = $this->googleSheetRepository->getAllMembers();

        // Filter by search query
        if ($searchQuery !== '') {
            $searchQueryLower = strtolower($searchQuery);
            $allTransactions = array_filter($allTransactions, function ($txn) use ($searchQueryLower) {
                $memberCode = strtolower((string) ($txn['membercode'] ?? ''));
                $txnType = strtolower((string) ($txn['transactiontype'] ?? ''));
                $refNo = strtolower((string) ($txn['referenceno'] ?? ''));
                
                return str_contains($memberCode, $searchQueryLower) ||
                       str_contains($txnType, $searchQueryLower) ||
                       str_contains($refNo, $searchQueryLower);
            });
        }

        // Sort transactions
        usort($allTransactions, function ($a, $b) use ($sortColumn, $sortDirection) {
            $aVal = $a[$sortColumn] ?? '';
            $bVal = $b[$sortColumn] ?? '';
            
            if ($sortColumn === 'amount') {
                $aVal = (float) $aVal;
                $bVal = (float) $bVal;
            }
            
            $cmp = $aVal <=> $bVal;
            return $sortDirection === 'asc' ? $cmp : -$cmp;
        });

        // Add member names to transactions
        $memberMap = [];
        foreach ($allMembers as $member) {
            $memberNo = strtoupper($member['member_number'] ?? $member['MemberNumber'] ?? '');
            if ($memberNo) {
                $memberMap[$memberNo] = $member['name'] ?? $member['Name'] ?? 'Unknown';
            }
        }

        foreach ($allTransactions as &$txn) {
            $memberCode = strtoupper($txn['membercode'] ?? '');
            $txn['member_name'] = $memberMap[$memberCode] ?? 'Unknown';
        }

        // Paginate
        $currentPage = (int) $request->input('page', 1);
        $total = count($allTransactions);
        $transactions = array_slice($allTransactions, ($currentPage - 1) * $perPage, $perPage);

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $currentPage,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
            'searchQuery' => $searchQuery,
            'memberService' => $this->memberService,
        ]);
    }

    public function show(Request $request, string $encryptedId)
    {
        $memberCode = $this->encryptedIdService->decrypt($encryptedId);
        
        if (! Gate::allows('admin')) {
            abort(403);
        }

        $transactions = $this->googleSheetRepository->getMemberTransactions($memberCode);
        $member = $this->googleSheetRepository->getMemberByNumber($memberCode);

        if (! $member) {
            $this->error('Member not found');
            return redirect()->route('admin.transactions.index');
        }

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'view',
            'subject' => 'transactions',
            'subject_id' => $memberCode,
            'description' => "Viewed transactions for member {$memberCode}",
        ]);

        return view('admin.transactions.show', [
            'transactions' => $transactions,
            'member' => $member,
            'memberCode' => $memberCode,
        ]);
    }

    public function create()
    {
        $allMembers = $this->googleSheetRepository->getAllMembers();
        
        return view('admin.transactions.create', [
            'members' => $allMembers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'membercode' => 'required|string',
            'transactiontype' => 'required|string|in:Deposit,Withdrawal,Interest',
            'referenceno' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        // Log the activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'subject' => 'transaction',
            'subject_id' => $validated['referenceno'],
            'description' => "Created new transaction: {$validated['transactiontype']} for member {$validated['membercode']}",
        ]);

        // For now, just show success message
        // In production, this would append to Google Sheets
        $this->success('Transaction recorded successfully. Note: This is a demo - actual Google Sheets integration would be needed for persistent storage.');
        
        return redirect()->route('admin.transactions.index');
    }
}
