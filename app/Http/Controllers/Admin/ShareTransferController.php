<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShareTransfer;
use App\Models\ShareCertificate;
use App\Models\User;
use App\Services\EncryptedIdService;
use Illuminate\Http\Request;

class ShareTransferController extends Controller
{
    protected $encryptedIdService;

    public function __construct(EncryptedIdService $encryptedIdService)
    {
        $this->encryptedIdService = $encryptedIdService;
    }

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

    public function show($encryptedId)
    {
        try {
            $shareTransferId = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'Invalid share transfer ID.');
        }

        $shareTransfer = ShareTransfer::with(['fromUser', 'toUser', 'shareCertificate'])->findOrFail($shareTransferId);
        return view('admin.share-transfers.show', compact('shareTransfer'));
    }

    public function edit($encryptedId)
    {
        try {
            $shareTransferId = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'Invalid share transfer ID.');
        }

        $shareTransfer = ShareTransfer::findOrFail($shareTransferId);
        $users = User::where('role', 'member')->get();
        $shareCertificates = ShareCertificate::where('status', 'active')->get();
        return view('admin.share-transfers.edit', compact('shareTransfer', 'users', 'shareCertificates'));
    }

    public function update(Request $request, $encryptedId)
    {
        try {
            $shareTransferId = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'Invalid share transfer ID.');
        }

        $shareTransfer = ShareTransfer::findOrFail($shareTransferId);

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

    public function destroy($encryptedId)
    {
        try {
            $shareTransferId = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            abort(404, 'Invalid share transfer ID.');
        }

        $shareTransfer = ShareTransfer::findOrFail($shareTransferId);
        $shareTransfer->delete();

        return redirect()->route('admin.share-transfers.index')
            ->with('success', 'Share transfer deleted successfully.');
    }
}
