@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Transfers')
@section('page_title', 'Share Transfers Management')

@section('content')

<div class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <span class="text-xs font-semibold text-primary-600">
        <i class="fa-solid fa-arrow-right-arrow-left mr-1.5"></i> {{ $shareTransfers->total() }} Share Transfers
      </span>
    </div>

    <a href="{{ route('admin.share-transfers.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
      <i class="fa-solid fa-plus text-[13px]"></i> Create Share Transfer
    </a>
  </div>

  <div class="glass p-5">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-primary-700/20">
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">From User</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">To User</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Certificate</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Shares</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Transfer Date</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Status</th>
            <th class="text-center py-3 px-4 text-xs font-semibold text-primary-600">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($shareTransfers as $transfer)
          <tr class="border-b border-primary-700/10 hover:bg-primary-800/30 transition-colors">
            <td class="py-3 px-4 text-sm font-medium text-white">{{ $transfer->fromUser->name ?? 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $transfer->toUser->name ?? 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $transfer->shareCertificate->certificate_number ?? 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $transfer->number_of_shares }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $transfer->transfer_date ? $transfer->transfer_date->format('M d, Y') : 'N/A' }}</td>
            <td class="py-3 px-4">
              @if($transfer->status === 'completed')
                <span class="badge badge-green text-[10px]">Completed</span>
              @elseif($transfer->status === 'approved')
                <span class="badge badge-blue text-[10px]">Approved</span>
              @elseif($transfer->status === 'pending')
                <span class="badge badge-amber text-[10px]">Pending</span>
              @else
                <span class="badge badge-red text-[10px]">Rejected</span>
              @endif
            </td>
            <td class="py-3 px-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <a href="{{ route('admin.share-transfers.show', $transfer) }}" class="text-primary-400 hover:text-white transition-colors">
                  <i class="fa-solid fa-eye text-sm"></i>
                </a>
                <a href="{{ route('admin.share-transfers.edit', $transfer) }}" class="text-primary-400 hover:text-white transition-colors">
                  <i class="fa-solid fa-edit text-sm"></i>
                </a>
                <form action="{{ route('admin.share-transfers.destroy', $transfer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share transfer?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                    <i class="fa-solid fa-trash text-sm"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="py-8 text-center text-primary-400 text-sm">No share transfers found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($shareTransfers->hasPages())
    <div class="flex items-center justify-between mt-5 pt-5 border-t border-primary-700/20">
      <span class="text-xs text-primary-400">Showing {{ $shareTransfers->firstItem() }} to {{ $shareTransfers->lastItem() }} of {{ $shareTransfers->total() }} results</span>
      {{ $shareTransfers->links() }}
    </div>
    @endif
  </div>

</div>

@endsection
