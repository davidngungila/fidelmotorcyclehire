<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\LoanCompletionCertificate;
use App\Models\ShareCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get loan completion certificates for the user
        $loanCertificates = LoanCompletionCertificate::with(['loan', 'loan.loanProduct'])
            ->where('user_id', $user->id)
            ->orderBy('issue_date', 'desc')
            ->get();
        
        // Get share certificates for the user
        $shareCertificates = ShareCertificate::with(['sharePurchase', 'sharePurchase.shareProduct'])
            ->whereHas('sharePurchase', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('issue_date', 'desc')
            ->get();
        
        return view('member.certificates.index', compact('loanCertificates', 'shareCertificates'));
    }

    public function showLoanCertificate($id)
    {
        $user = Auth::user();
        $certificate = LoanCompletionCertificate::with(['loan', 'loan.loanProduct'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        return view('member.certificates.loan-show', compact('certificate'));
    }

    public function printLoanCertificate($id)
    {
        $user = Auth::user();
        $certificate = LoanCompletionCertificate::with(['loan', 'loan.user', 'loan.loanProduct'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        return view('admin.loan-completion-certificates.print', compact('certificate'));
    }

    public function showShareCertificate($id)
    {
        $user = Auth::user();
        $certificate = ShareCertificate::with(['sharePurchase', 'sharePurchase.shareProduct'])
            ->where('id', $id)
            ->whereHas('sharePurchase', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->firstOrFail();
        
        return view('member.certificates.share-show', compact('certificate'));
    }

    public function printShareCertificate($id)
    {
        $user = Auth::user();
        $certificate = ShareCertificate::with(['sharePurchase', 'sharePurchase.shareProduct', 'sharePurchase.user'])
            ->where('id', $id)
            ->whereHas('sharePurchase', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->firstOrFail();
        
        return view('admin.share-certificates.print', compact('certificate'));
    }
}
