<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShareDividend;
use App\Models\ShareProduct;
use App\Models\ShareCertificate;
use App\Models\User;
use App\Services\EncryptedIdService;
use Illuminate\Http\Request;

class ShareDividendController extends Controller
{
    protected $encryptedIdService;

    public function __construct(EncryptedIdService $encryptedIdService)
    {
        $this->encryptedIdService = $encryptedIdService;
    }

    public function index()
    {
        $shareDividends = ShareDividend::with(['shareProduct', 'user', 'shareCertificate'])->latest()->paginate(10);
        return view('admin.share-dividends.index', compact('shareDividends'));
    }

    public function create()
    {
        $shareProducts = ShareProduct::where('status', 'active')->get();
        $users = User::where('role', 'member')->get();
        $shareCertificates = ShareCertificate::where('status', 'active')->get();
        return view('admin.share-dividends.create', compact('shareProducts', 'users', 'shareCertificates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'share_product_id' => 'required|exists:share_products,id',
            'user_id' => 'required|exists:users,id',
            'share_certificate_id' => 'nullable|exists:share_certificates,id',
            'number_of_shares' => 'required|integer|min:1',
            'dividend_per_share' => 'required|numeric|min:0',
            'total_dividend' => 'required|numeric|min:0',
            'declaration_date' => 'required|date',
            'payment_date' => 'nullable|date|after:declaration_date',
            'status' => 'required|in:declared,paid,pending',
            'notes' => 'nullable|string',
        ]);

        ShareDividend::create($validated);

        return redirect()->route('admin.share-dividends.index')
            ->with('success', 'Share dividend created successfully.');
    }

    public function show($encryptedId)
    {
        try {
            $shareDividendId = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'Invalid share dividend ID.');
        }

        $shareDividend = ShareDividend::with(['shareProduct', 'user', 'shareCertificate'])->findOrFail($shareDividendId);
        return view('admin.share-dividends.show', compact('shareDividend'));
    }

    public function edit($encryptedId)
    {
        try {
            $shareDividendId = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'Invalid share dividend ID.');
        }

        $shareDividend = ShareDividend::findOrFail($shareDividendId);
        $shareProducts = ShareProduct::where('status', 'active')->get();
        $users = User::where('role', 'member')->get();
        $shareCertificates = ShareCertificate::where('status', 'active')->get();
        return view('admin.share-dividends.edit', compact('shareDividend', 'shareProducts', 'users', 'shareCertificates'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $shareDividendId = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'Invalid share dividend ID.');
        }

        $shareDividend = ShareDividend::findOrFail($shareDividendId);

        $validated = $request->validate([
            'share_product_id' => 'required|exists:share_products,id',
            'user_id' => 'required|exists:users,id',
            'share_certificate_id' => 'nullable|exists:share_certificates,id',
            'number_of_shares' => 'required|integer|min:1',
            'dividend_per_share' => 'required|numeric|min:0',
            'total_dividend' => 'required|numeric|min:0',
            'declaration_date' => 'required|date',
            'payment_date' => 'nullable|date|after:declaration_date',
            'status' => 'required|in:declared,paid,pending',
            'notes' => 'nullable|string',
        ]);

        $shareDividend->update($validated);

        return redirect()->route('admin.share-dividends.index')
            ->with('success', 'Share dividend updated successfully.');
    }

    public function destroy($encryptedId)
    {
        try {
            $shareDividendId = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'Invalid share dividend ID.');
        }

        $shareDividend = ShareDividend::findOrFail($shareDividendId);
        $shareDividend->delete();

        return redirect()->route('admin.share-dividends.index')
            ->with('success', 'Share dividend deleted successfully.');
    }
}
