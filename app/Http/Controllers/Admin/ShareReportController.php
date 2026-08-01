<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShareProduct;
use App\Models\SharePurchase;
use App\Models\ShareCertificate;
use App\Models\ShareTransfer;
use App\Models\ShareDividend;
use App\Models\ShareTransaction;
use Illuminate\Http\Request;

class ShareReportController extends Controller
{
    public function index()
    {
        $totalProducts = ShareProduct::count();
        $totalPurchases = SharePurchase::count();
        $totalCertificates = ShareCertificate::count();
        $totalTransfers = ShareTransfer::count();
        $totalDividends = ShareDividend::count();
        $totalTransactions = ShareTransaction::count();
        $totalInvestment = SharePurchase::where('payment_status', 'paid')->sum('total_amount');
        $totalDividendsPaid = ShareDividend::where('status', 'paid')->sum('total_dividend');

        return view('admin.share-reports.index', compact(
            'totalProducts',
            'totalPurchases',
            'totalCertificates',
            'totalTransfers',
            'totalDividends',
            'totalTransactions',
            'totalInvestment',
            'totalDividendsPaid'
        ));
    }
}
