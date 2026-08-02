<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\LoanCompletionCertificate;
use App\Models\ShareCertificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $memberNumber = $user->member_number;

        $loanCertificates = LoanCompletionCertificate::whereHas('loan', function($query) use ($memberNumber) {
            $query->where('member_number', $memberNumber);
        })->with('loan')->orderBy('completion_date', 'desc')->get();

        $shareCertificates = ShareCertificate::whereHas('sharePurchase', function($query) use ($memberNumber) {
            $query->where('member_number', $memberNumber);
        })->with(['sharePurchase.shareProduct'])->orderBy('issue_date', 'desc')->get();

        return view('member.certificates.index', compact('loanCertificates', 'shareCertificates'));
    }

    public function getLoanCertificate($id)
    {
        $certificate = LoanCompletionCertificate::with(['loan', 'loan.member'])
            ->whereHas('loan', function($query) {
                $query->where('member_number', auth()->user()->member_number);
            })
            ->findOrFail($id);

        return response()->json([
            'certificate_number' => $certificate->certificate_number,
            'completion_date' => $certificate->completion_date->format('F d, Y'),
            'loan_number' => $certificate->loan->loan_number,
            'loan_amount' => number_format($certificate->loan->principal_amount, 2),
            'purpose' => ucfirst($certificate->loan->purpose),
            'disbursement_date' => $certificate->loan->disbursement_date ? $certificate->loan->disbursement_date->format('F d, Y') : 'N/A',
            'member_name' => $certificate->loan->member->name ?? 'N/A',
            'member_number' => $certificate->loan->member_number,
            'notes' => $certificate->notes,
        ]);
    }

    public function getShareCertificate($id)
    {
        $certificate = ShareCertificate::with(['sharePurchase', 'sharePurchase.shareProduct', 'sharePurchase.member'])
            ->whereHas('sharePurchase', function($query) {
                $query->where('member_number', auth()->user()->member_number);
            })
            ->findOrFail($id);

        return response()->json([
            'certificate_number' => $certificate->certificate_number,
            'issue_date' => $certificate->issue_date->format('F d, Y'),
            'number_of_shares' => $certificate->number_of_shares,
            'share_product' => $certificate->sharePurchase->shareProduct->name ?? 'N/A',
            'share_value' => number_format($certificate->share_value_per_share, 2),
            'total_value' => number_format($certificate->number_of_shares * $certificate->share_value_per_share, 2),
            'member_name' => $certificate->sharePurchase->member->name ?? 'N/A',
            'member_number' => $certificate->sharePurchase->member_number,
            'notes' => $certificate->notes,
        ]);
    }

    public function showLoanCertificate($id)
    {
        $certificate = LoanCompletionCertificate::with(['loan', 'loan.member'])
            ->whereHas('loan', function($query) {
                $query->where('member_number', auth()->user()->member_number);
            })
            ->findOrFail($id);

        return view('member.certificates.loan-show', compact('certificate'));
    }

    public function printLoanCertificate($id)
    {
        $certificate = LoanCompletionCertificate::with(['loan', 'loan.member'])
            ->whereHas('loan', function($query) {
                $query->where('member_number', auth()->user()->member_number);
            })
            ->findOrFail($id);

        return view('member.certificates.loan-print', compact('certificate'));
    }

    public function showShareCertificate($id)
    {
        $certificate = ShareCertificate::with(['sharePurchase', 'sharePurchase.shareProduct', 'sharePurchase.member'])
            ->whereHas('sharePurchase', function($query) {
                $query->where('member_number', auth()->user()->member_number);
            })
            ->findOrFail($id);

        return view('member.certificates.share-show', compact('certificate'));
    }

    public function printShareCertificate($id)
    {
        $certificate = ShareCertificate::with(['sharePurchase', 'sharePurchase.shareProduct', 'sharePurchase.member'])
            ->whereHas('sharePurchase', function($query) {
                $query->where('member_number', auth()->user()->member_number);
            })
            ->findOrFail($id);

        return view('member.certificates.share-print', compact('certificate'));
    }
}
