@extends('layouts.admin')

@section('breadcrumb', 'System › Share Dividends')
@section('page_title', 'Share Dividends Management')

@section('content')

<div class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
        <i class="fa-solid fa-money-bill-trend-up mr-1.5"></i> {{ $shareDividends->total() }} Share Dividends
      </span>
    </div>

    <a href="{{ route('admin.share-dividends.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
      <i class="fa-solid fa-plus text-[13px]"></i> Create Share Dividend
    </a>
  </div>

  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-primary-50 dark:bg-primary-900/20">
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">User</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Share Product</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Shares</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Dividend/Share</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Total Dividend</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Declaration Date</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Status</th>
            <th class="text-center py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($shareDividends as $dividend)
          <tr class="border-b border-primary-100 dark:border-primary-800 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors">
            <td class="py-3 px-4 text-sm font-medium text-primary-900 dark:text-white">{{ $dividend->user->name ?? 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $dividend->shareProduct->name ?? 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $dividend->number_of_shares }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ number_format($dividend->dividend_per_share, 2) }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ number_format($dividend->total_dividend, 2) }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $dividend->declaration_date ? $dividend->declaration_date->format('M d, Y') : 'N/A' }}</td>
            <td class="py-3 px-4">
              @if($dividend->status === 'paid')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Paid</span>
              @elseif($dividend->status === 'declared')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Declared</span>
              @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pending</span>
              @endif
            </td>
            <td class="py-3 px-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <a href="{{ route('admin.share-dividends.show', $dividend) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-100 hover:bg-teal-200 dark:bg-teal-900/40 dark:hover:bg-teal-900/60 text-teal-700 dark:text-teal-300 text-xs font-medium transition-colors">
                  <i class="fa-solid fa-eye text-[10px]"></i> View
                </a>
                <a href="{{ route('admin.share-dividends.edit', $dividend) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-medium transition-colors">
                  <i class="fa-solid fa-edit text-[10px]"></i> Edit
                </a>
                <form action="{{ route('admin.share-dividends.destroy', $dividend) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share dividend?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-100 hover:bg-red-200 dark:bg-red-900/40 dark:hover:bg-red-900/60 text-red-700 dark:text-red-300 text-xs font-medium transition-colors">
                    <i class="fa-solid fa-trash text-[10px]"></i> Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="py-8 text-center text-primary-600 dark:text-primary-400 text-sm">No share dividends found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($shareDividends->hasPages())
    <div class="flex items-center justify-between mt-5 pt-5 border-t border-primary-100 dark:border-primary-800">
      <span class="text-xs text-primary-600 dark:text-primary-400">Showing {{ $shareDividends->firstItem() }} to {{ $shareDividends->lastItem() }} of {{ $shareDividends->total() }} results</span>
      {{ $shareDividends->links() }}
    </div>
    @endif
  </div>

</div>

@endsection
