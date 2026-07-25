@extends('layouts.admin')

@section('breadcrumb', 'Shares')
@section('page_title', 'Shares Management')

@section('page-header')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <h1 class="text-xl lg:text-2xl font-bold text-primary-900 dark:text-white">Shares Management</h1>
    <p class="text-sm mt-1 text-primary-600 dark:text-primary-400">View and manage member shares</p>
  </div>
</div>
@endsection

@section('content')

<div class="glass p-5 lg:p-6">
  <form method="GET" action="{{ route('admin.shares.index') }}" class="space-y-4">
    <div class="flex flex-col sm:flex-row gap-4">
      <div class="flex-1">
        <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Search by share number, member name, or branch..."
               class="form-input">
      </div>
      <div class="flex gap-2">
        <select name="per_page" class="form-input w-auto">
          <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 per page</option>
          <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 per page</option>
          <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per page</option>
          <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 per page</option>
        </select>
        <button type="submit" class="px-4 py-2 rounded-xl bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold transition-colors">
          <i class="fa-solid fa-search mr-1"></i> Search
        </button>
        @if($searchQuery)
          <a href="{{ route('admin.shares.index') }}" class="px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs font-bold transition-colors">
            Clear
          </a>
        @endif
      </div>
    </div>
  </form>
</div>

<div class="glass p-5 lg:p-6 mt-6">
  <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-xl">
    <table class="data-table">
      <thead>
        <tr>
          <th>Share #</th>
          <th>Member</th>
          <th>Branch</th>
          <th class="text-right">Quantity</th>
          <th class="text-right">Value per Share</th>
          <th class="text-right">Total Value</th>
          <th class="text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($shares as $share)
          @php
            $shareNo = $share['share_number'] ?? ($share['ShareNumber'] ?? '-');
            $memberName = $share['member_name'] ?? 'Unknown';
            $memberNo = $share['member_number'] ?? '-';
            $branch = $share['member_branch'] ?? '-';
            $quantity = $share['quantity'] ?? ($share['Quantity'] ?? 0);
            $valuePerShare = $share['value_per_share'] ?? ($share['ValuePerShare'] ?? 0);
            $totalValue = $quantity * $valuePerShare;
          @endphp
          <tr>
            <td class="font-mono text-xs font-bold text-primary-700 dark:text-primary-300">{{ $shareNo }}</td>
            <td>
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                  {{ strtoupper(substr($memberName, 0, 1)) }}
                </div>
                <div>
                  <p class="text-sm font-semibold text-primary-900 dark:text-white">{{ $memberName }}</p>
                  <p class="text-[11px] text-primary-500 dark:text-primary-400 font-mono">{{ $memberNo }}</p>
                </div>
              </div>
            </td>
            <td class="text-xs text-primary-700 dark:text-primary-300">{{ $branch }}</td>
            <td class="text-right text-xs font-bold text-primary-900 dark:text-white">{{ number_format($quantity) }}</td>
            <td class="text-right text-xs font-bold text-primary-900 dark:text-white">{{ number_format($valuePerShare, 2) }} TSh</td>
            <td class="text-right text-xs font-bold text-green-600 dark:text-green-400">{{ number_format($totalValue, 2) }} TSh</td>
            <td class="text-right">
              <a href="{{ route('admin.shares.show', encryptId($memberNo)) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
                <i class="fa-solid fa-eye text-[10px]"></i> View
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-12 text-primary-500 dark:text-primary-400">
              <i class="fa-solid fa-building-columns text-3xl mb-3 block opacity-30"></i>
              <p class="text-sm font-semibold mb-1">No shares found</p>
              <p class="text-xs">Share records will appear here</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($shares->hasPages())
    <div class="mt-6 flex items-center justify-between gap-4">
      <p class="text-xs text-primary-600 dark:text-primary-400">
        Showing {{ $shares->firstItem() }} to {{ $shares->lastItem() }} of {{ $shares->total() }} shares
      </p>
      {{ $shares->links('vendor.pagination.tailwind') }}
    </div>
  @endif
</div>

@endsection
