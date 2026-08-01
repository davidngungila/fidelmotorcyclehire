@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Certificates \u203A Details')
@section('page_title', 'Share Certificate Details')

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-3">
    <a href="{{ route('admin.share-certificates.index') }}"
       class="inline-flex items-center gap-2 text-primary-400 hover:text-white transition-colors text-sm">
      <i class="fa-solid fa-arrow-left"></i> Back to Share Certificates
    </a>
  </div>

  <div class="glass p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Certificate Information</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Certificate Number:</span>
            <span class="text-sm text-white font-medium">{{ $shareCertificate->certificate_number }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">User:</span>
            <span class="text-sm text-white font-medium">{{ $shareCertificate->user->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Share Product:</span>
            <span class="text-sm text-white font-medium">{{ $shareCertificate->shareProduct->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Number of Shares:</span>
            <span class="text-sm text-white font-medium">{{ $shareCertificate->number_of_shares }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Share Purchase:</span>
            <span class="text-sm text-white font-medium">{{ $shareCertificate->sharePurchase->id ?? 'N/A' }}</span>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Status & Dates</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Status:</span>
            <span class="text-sm font-medium">
              @if($shareCertificate->status === 'active')
                <span class="badge badge-green text-[10px]">Active</span>
              @elseif($shareCertificate->status === 'inactive')
                <span class="badge badge-amber text-[10px]">Inactive</span>
              @elseif($shareCertificate->status === 'transferred')
                <span class="badge badge-blue text-[10px]">Transferred</span>
              @else
                <span class="badge badge-red text-[10px]">Cancelled</span>
              @endif
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Issue Date:</span>
            <span class="text-sm text-white font-medium">{{ $shareCertificate->issue_date->format('M d, Y') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Expiry Date:</span>
            <span class="text-sm text-white font-medium">{{ $shareCertificate->expiry_date ? $shareCertificate->expiry_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Created At:</span>
            <span class="text-sm text-white font-medium">{{ $shareCertificate->created_at->format('M d, Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    @if($shareCertificate->notes)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Notes</h3>
      <p class="text-sm text-primary-300">{{ $shareCertificate->notes }}</p>
    </div>
    @endif

    @if($shareCertificate->shareTransfers->count() > 0)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Transfer History ({{ $shareCertificate->shareTransfers->count() }})</h3>
      <div class="space-y-2">
        @foreach($shareCertificate->shareTransfers as $transfer)
        <div class="flex items-center justify-between p-3 bg-primary-800/30 rounded-lg">
          <span class="text-sm text-white">{{ $transfer->fromUser->name }} → {{ $transfer->toUser->name }}</span>
          <span class="text-sm text-primary-300">{{ $transfer->transfer_date->format('M d, Y') }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    @if($shareCertificate->shareDividends->count() > 0)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Dividend History ({{ $shareCertificate->shareDividends->count() }})</h3>
      <div class="space-y-2">
        @foreach($shareCertificate->shareDividends as $dividend)
        <div class="flex items-center justify-between p-3 bg-primary-800/30 rounded-lg">
          <span class="text-sm text-white">{{ number_format($dividend->total_dividend, 2) }}</span>
          <span class="text-sm text-primary-300">{{ $dividend->declaration_date->format('M d, Y') }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-700/20">
      <a href="{{ route('admin.share-certificates.edit', $shareCertificate) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <form action="{{ route('admin.share-certificates.destroy', $shareCertificate) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share certificate?');">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white text-sm font-bold transition-all">
          <i class="fa-solid fa-trash"></i> Delete
        </button>
      </form>
    </div>
  </div>

</div>

@endsection
