@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Dividends \u203A Details')
@section('page_title', 'Share Dividend Details')

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-3">
    <a href="{{ route('admin.share-dividends.index') }}"
       class="inline-flex items-center gap-2 text-primary-400 hover:text-white transition-colors text-sm">
      <i class="fa-solid fa-arrow-left"></i> Back to Share Dividends
    </a>
  </div>

  <div class="glass p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Dividend Information</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">User:</span>
            <span class="text-sm text-white font-medium">{{ $shareDividend->user->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Share Product:</span>
            <span class="text-sm text-white font-medium">{{ $shareDividend->shareProduct->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Share Certificate:</span>
            <span class="text-sm text-white font-medium">{{ $shareDividend->shareCertificate->certificate_number ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Number of Shares:</span>
            <span class="text-sm text-white font-medium">{{ $shareDividend->number_of_shares }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Dividend Per Share:</span>
            <span class="text-sm text-white font-medium">{{ number_format($shareDividend->dividend_per_share, 2) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Total Dividend:</span>
            <span class="text-sm text-white font-medium">{{ number_format($shareDividend->total_dividend, 2) }}</span>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Status & Dates</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Status:</span>
            <span class="text-sm font-medium">
              @if($shareDividend->status === 'paid')
                <span class="badge badge-green text-[10px]">Paid</span>
              @elseif($shareDividend->status === 'declared')
                <span class="badge badge-blue text-[10px]">Declared</span>
              @else
                <span class="badge badge-amber text-[10px]">Pending</span>
              @endif
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Declaration Date:</span>
            <span class="text-sm text-white font-medium">{{ $shareDividend->declaration_date ? $shareDividend->declaration_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Payment Date:</span>
            <span class="text-sm text-white font-medium">{{ $shareDividend->payment_date ? $shareDividend->payment_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Created At:</span>
            <span class="text-sm text-white font-medium">{{ $shareDividend->created_at->format('M d, Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    @if($shareDividend->notes)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Notes</h3>
      <p class="text-sm text-primary-300">{{ $shareDividend->notes }}</p>
    </div>
    @endif

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-700/20">
      <a href="{{ route('admin.share-dividends.edit', $shareDividend) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <form action="{{ route('admin.share-dividends.destroy', $shareDividend) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share dividend?');">
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
