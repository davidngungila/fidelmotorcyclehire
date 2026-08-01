<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShareCertificate;
use App\Models\ShareProduct;
use App\Models\SharePurchase;
use App\Models\User;
use Illuminate\Http\Request;

class ShareCertificateController extends Controller
{
    public function index()
    {
        $shareCertificates = ShareCertificate::with(['user', 'shareProduct', 'sharePurchase'])->latest()->paginate(10);
        return view('admin.share-certificates.index', compact('shareCertificates'));
    }

    public function create()
    {
        $users = User::where('role', 'member')->get();
        $shareProducts = ShareProduct::where('status', 'active')->get();
        $sharePurchases = SharePurchase::where('payment_status', 'paid')->get();
        return view('admin.share-certificates.create', compact('users', 'shareProducts', 'sharePurchases'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'share_product_id' => 'required|exists:share_products,id',
            'share_purchase_id' => 'nullable|exists:share_purchases,id',
            'certificate_number' => 'required|string|unique:share_certificates,certificate_number|max:50',
            'number_of_shares' => 'required|integer|min:1',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'status' => 'required|in:active,inactive,transferred,cancelled',
            'notes' => 'nullable|string',
        ]);

        ShareCertificate::create($validated);

        return redirect()->route('admin.share-certificates.index')
            ->with('success', 'Share certificate created successfully.');
    }

    public function show(ShareCertificate $shareCertificate)
    {
        $shareCertificate->load(['user', 'shareProduct', 'sharePurchase', 'shareTransfers', 'shareDividends']);
        return view('admin.share-certificates.show', compact('shareCertificate'));
    }

    public function edit(ShareCertificate $shareCertificate)
    {
        $users = User::where('role', 'member')->get();
        $shareProducts = ShareProduct::where('status', 'active')->get();
        $sharePurchases = SharePurchase::where('payment_status', 'paid')->get();
        return view('admin.share-certificates.edit', compact('shareCertificate', 'users', 'shareProducts', 'sharePurchases'));
    }

    public function update(Request $request, ShareCertificate $shareCertificate)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'share_product_id' => 'required|exists:share_products,id',
            'share_purchase_id' => 'nullable|exists:share_purchases,id',
            'certificate_number' => 'required|string|unique:share_certificates,certificate_number,' . $shareCertificate->id . '|max:50',
            'number_of_shares' => 'required|integer|min:1',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'status' => 'required|in:active,inactive,transferred,cancelled',
            'notes' => 'nullable|string',
        ]);

        $shareCertificate->update($validated);

        return redirect()->route('admin.share-certificates.index')
            ->with('success', 'Share certificate updated successfully.');
    }

    public function destroy(ShareCertificate $shareCertificate)
    {
        $shareCertificate->delete();

        return redirect()->route('admin.share-certificates.index')
            ->with('success', 'Share certificate deleted successfully.');
    }
}
