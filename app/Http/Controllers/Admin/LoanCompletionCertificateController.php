<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\LoanCompletionCertificate;
use Illuminate\Http\Request;

class LoanCompletionCertificateController extends Controller
{
    public function index()
    {
        $certificates = LoanCompletionCertificate::with(['loan', 'user'])
            ->orderBy('issue_date', 'desc')
            ->get();
        
        return view('admin.loan-completion-certificates.index', compact('certificates'));
    }

    public function create($loanId)
    {
        $loan = Loan::with(['user', 'loanProduct'])->findOrFail($loanId);
        
        if ($loan->status !== 'completed' && $loan->status !== 'paid') {
            return back()->with('error', 'Can only generate completion certificates for completed loans.');
        }
        
        if ($loan->completionCertificate) {
            return back()->with('error', 'A completion certificate already exists for this loan.');
        }
        
        return view('admin.loan-completion-certificates.create', compact('loan'));
    }

    public function store(Request $request, $loanId)
    {
        $loan = Loan::findOrFail($loanId);
        
        if ($loan->status !== 'completed' && $loan->status !== 'paid') {
            return back()->with('error', 'Can only generate completion certificates for completed loans.');
        }
        
        if ($loan->completionCertificate) {
            return back()->with('error', 'A completion certificate already exists for this loan.');
        }
        
        $certificate = new LoanCompletionCertificate([
            'loan_id' => $loan->id,
            'user_id' => $loan->user_id,
            'certificate_number' => (new LoanCompletionCertificate())->generateCertificateNumber(),
            'completion_date' => $loan->maturity_date ?? now(),
            'original_amount' => $loan->principal_amount,
            'total_paid' => $loan->amount_paid,
            'total_interest_paid' => $loan->total_amount_due - $loan->principal_amount,
            'issue_date' => now(),
            'issued_by' => auth()->user()->name,
            'notes' => $request->notes,
            'is_active' => true,
        ]);
        
        $certificate->save();
        
        return redirect()->route('admin.loan-completion-certificates.show', $certificate->id)
            ->with('success', 'Loan completion certificate generated successfully.');
    }

    public function show($id)
    {
        $certificate = LoanCompletionCertificate::with(['loan', 'loan.user', 'loan.loanProduct'])
            ->findOrFail($id);
        
        return view('admin.loan-completion-certificates.show', compact('certificate'));
    }

    public function destroy($id)
    {
        $certificate = LoanCompletionCertificate::findOrFail($id);
        $certificate->delete();
        
        return redirect()->route('admin.loan-completion-certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }

    public function print($id)
    {
        $certificate = LoanCompletionCertificate::with(['loan', 'loan.user', 'loan.loanProduct'])
            ->findOrFail($id);
        
        return view('admin.loan-completion-certificates.print', compact('certificate'));
    }
}
