@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Transfers \u203A Details')
@section('page_title', 'Share Transfer Details')

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-3">
    <a href="{{ route('admin.share-transfers.index') }}"
       class="inline-flex items-center gap-2 text-primary-400 hover:text-white transition-colors text-sm">
      <i class="fa-solid fa-arrow-left"></i> Back to Share Transfers
    </a>
  </div>

  <div class="glass p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Transfer Information</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">From User:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransfer->fromUser->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">To User:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransfer->toUser->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Certificate:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransfer->shareCertificate->certificate_number ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Number of Shares:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransfer->number_of_shares }}</span>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Status & Dates</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Status:</span>
            <span class="text-sm font-medium">
              @if($shareTransfer->status === 'completed')
                <span class="badge badge-green text-[10px]">Completed</span>
              @elseif($shareTransfer->status === 'approved')
                <span class="badge badge-blue text-[10px]">Approved</span>
              @elseif($shareTransfer->status === 'pending')
                <span class="badge badge-amber text-[10px]">Pending</span>
              @else
                <span class="badge badge-red text-[10px]">Rejected</span>
              @endif
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Transfer Date:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransfer->transfer_date->format('M d, Y') }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Created At:</span>
            <span class="text-sm text-white font-medium">{{ $shareTransfer->created_at->format('M d, Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    @if($shareTransfer->reason)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Reason</h3>
      <p class="text-sm text-primary-300">{{ $shareTransfer->reason }}</p>
    </div>
    @endif

    @if($shareTransfer->notes)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Notes</h3>
      <p class="text-sm text-primary-300">{{ $shareTransfer->notes }}</p>
    </div>
    @endif

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-700/20">
      <a href="{{ route('admin.share-transfers.edit', $shareTransfer) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <form action="{{ route('admin.share-transfers.destroy', $shareTransfer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share transfer?');">
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
