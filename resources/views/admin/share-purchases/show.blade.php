@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Purchases \u203A Details')
@section('page_title', 'Share Purchase Details')

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-3">
    <a href="{{ route('admin.share-purchases.index') }}"
       class="inline-flex items-center gap-2 text-primary-400 hover:text-white transition-colors text-sm">
      <i class="fa-solid fa-arrow-left"></i> Back to Share Purchases
    </a>
  </div>

  <div class="glass p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Purchase Information</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">User:</span>
            <span class="text-sm text-white font-medium">{{ $sharePurchase->user->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Share Product:</span>
            <span class="text-sm text-white font-medium">{{ $sharePurchase->shareProduct->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Number of Shares:</span>
            <span class="text-sm text-white font-medium">{{ $sharePurchase->number_of_shares }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Price Per Share:</span>
            <span class="text-sm text-white font-medium">{{ number_format($sharePurchase->price_per_share, 2) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Total Amount:</span>
            <span class="text-sm text-white font-medium">{{ number_format($sharePurchase->total_amount, 2) }}</span>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Status & Dates</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Payment Status:</span>
            <span class="text-sm font-medium">
              @if($sharePurchase->payment_status === 'paid')
                <span class="badge badge-green text-[10px]">Paid</span>
              @elseif($sharePurchase->payment_status === 'pending')
                <span class="badge badge-amber text-[10px]">Pending</span>
              @else
                <span class="badge badge-red text-[10px]">Cancelled</span>
              @endif
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Purchase Date:</span>
            <span class="text-sm text-white font-medium">{{ $sharePurchase->purchase_date->format('M d, Y') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Created At:</span>
            <span class="text-sm text-white font-medium">{{ $sharePurchase->created_at->format('M d, Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    @if($sharePurchase->notes)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Notes</h3>
      <p class="text-sm text-primary-300">{{ $sharePurchase->notes }}</p>
    </div>
    @endif

    @if($sharePurchase->shareCertificates->count() > 0)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Associated Certificates ({{ $sharePurchase->shareCertificates->count() }})</h3>
      <div class="space-y-2">
        @foreach($sharePurchase->shareCertificates as $certificate)
        <div class="flex items-center justify-between p-3 bg-primary-800/30 rounded-lg">
          <span class="text-sm text-white">{{ $certificate->certificate_number }}</span>
          <span class="text-sm text-primary-300">{{ $certificate->number_of_shares }} shares</span>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-700/20">
      <a href="{{ route('admin.share-purchases.edit', $sharePurchase) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <form action="{{ route('admin.share-purchases.destroy', $sharePurchase) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share purchase?');">
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
