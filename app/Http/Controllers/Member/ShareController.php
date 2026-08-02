<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\SharePurchase;
use App\Models\ShareCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $sharePurchases = SharePurchase::where('user_id', $user->id)
            ->with('shareProduct')
            ->latest()
            ->get();
        
        $shareCertificates = ShareCertificate::where('user_id', $user->id)
            ->with('shareProduct')
            ->latest()
            ->get();
        
        $totalShares = $sharePurchases->sum('number_of_shares');
        $totalValue = $sharePurchases->sum(function($purchase) {
            return $purchase->number_of_shares * ($purchase->shareProduct->share_value ?? 10000);
        });
        
        return view('member.shares.index', compact(
            'sharePurchases',
            'shareCertificates',
            'totalShares',
            'totalValue'
        ));
    }
}
