@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Transactions \u203A Details')
@section('page_title', 'Share Transaction Details')

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-3">
    <a href="{{ route('admin.share-transactions.index') }}"
       class="inline-flex items-center gap-2 text-primary-400 hover:text-white transition-colors text-sm">
      <i class="fa-solid fa-arrow-left"></i> Back to Share Transactions
    </a>
  </div>

  <div class="glass p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Transaction Information</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">User:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransaction->user->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Share Product:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransaction->shareProduct->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Transaction Type:</span>
            <span class="text-sm text-white font-medium">{{ ucfirst($shareTransaction->transaction_type) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Number of Shares:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransaction->number_of_shares }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Price Per Share:</span>
            <span class="text-sm text-white font-medium">{{ number_format($shareTransaction->price_per_share, 2) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Total Amount:</span>
            <span class="text-sm text-white font-medium">{{ number_format($shareTransaction->total_amount, 2) }}</span>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Status & Dates</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Status:</span>
            <span class="text-sm font-medium">
              @if($shareTransaction->status === 'completed')
                <span class="badge badge-green text-[10px]">Completed</span>
              @elseif($shareTransaction->status === 'pending')
                <span class="badge badge-amber text-[10px]">Pending</span>
              @else
                <span class="badge badge-red text-[10px]">Cancelled</span>
              @endif
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Transaction Date:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransaction->transaction_date->format('M d, Y') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Created At:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransaction->created_at->format('M d, Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    @if($shareTransaction->description)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Description</h3>
      <p class="text-sm text-primary-300">{{ $shareTransaction->description }}</p>
    </div>
    @endif

    @if($shareTransaction->notes)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Notes</h3>
      <p class="text-sm text-primary-300">{{ $shareTransaction->notes }}</p>
    </div>
    @endif

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-700/20">
      <a href="{{ route('admin.share-transactions.edit', $shareTransaction) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <form action="{{ route('admin.share-transactions.destroy', $shareTransaction) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share transaction?');">
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
