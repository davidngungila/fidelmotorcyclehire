<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShareTransfer;
use App\Models\ShareCertificate;
use App\Models\User;
use Illuminate\Http\Request;

class ShareTransferController extends Controller
{
    public function index()
    {
        $shareTransfers = ShareTransfer::with(['fromUser', 'toUser', 'shareCertificate'])->latest()->paginate(10);
        return view('admin.share-transfers.index', compact('shareTransfers'));
    }

    public function create()
    {
        $users = User::where('role', 'member')->get();
        $shareCertificates = ShareCertificate::where('status', 'active')->get();
        return view('admin.share-transfers.create', compact('users', 'shareCertificates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id|different:from_user_id',
            'share_certificate_id' => 'required|exists:share_certificates,id',
            'number_of_shares' => 'required|integer|min:1',
            'transfer_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected,completed',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        ShareTransfer::create($validated);

        return redirect()->route('admin.share-transfers.index')
            ->with('success', 'Share transfer created successfully.');
    }

    public function show(ShareTransfer $shareTransfer)
    {
        $shareTransfer->load(['fromUser', 'toUser', 'shareCertificate']);
        return view('admin.share-transfers.show', compact('shareTransfer'));
    }

    public function edit(ShareTransfer $shareTransfer)
    {
        $users = User::where('role', 'member')->get();
        $shareCertificates = ShareCertificate::where('status', 'active')->get();
        return view('admin.share-transfers.edit', compact('shareTransfer', 'users', 'shareCertificates'));
    }

    public function update(Request $request, ShareTransfer $shareTransfer)
    {
        $validated = $request->validate([
            'from_user_id' => 'required|exists:users,id',
            'to_user_id' => 'required|exists:users,id|different:from_user_id',
            'share_certificate_id' => 'required|exists:share_certificates,id',
            'number_of_shares' => 'required|integer|min:1',
            'transfer_date' => 'required|date',
            'status' => 'required|in:pending,approved,rejected,completed',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $shareTransfer->update($validated);

        return redirect()->route('admin.share-transfers.index')
            ->with('success', 'Share transfer updated successfully.');
    }

    public function destroy(ShareTransfer $shareTransfer)
    {
        $shareTransfer->delete();

        return redirect()->route('admin.share-transfers.index')
            ->with('success', 'Share transfer deleted successfully.');
    }
}
