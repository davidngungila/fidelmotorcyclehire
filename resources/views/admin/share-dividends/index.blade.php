@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Dividends')
@section('page_title', 'Share Dividends Management')

@section('content')

<div class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <span class="text-xs font-semibold text-primary-600">
        <i class="fa-solid fa-money-bill-trend-up mr-1.5"></i> {{ $shareDividends->total() }} Share Dividends
      </span>
    </div>

    <a href="{{ route('admin.share-dividends.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
      <i class="fa-solid fa-plus text-[13px]"></i> Create Share Dividend
    </a>
  </div>

  <div class="glass p-5">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-primary-700/20">
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">User</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Share Product</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Shares</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Dividend/Share</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Total Dividend</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Declaration Date</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Status</th>
            <th class="text-center py-3 px-4 text-xs font-semibold text-primary-600">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($shareDividends as $dividend)
          <tr class="border-b border-primary-700/10 hover:bg-primary-800/30 transition-colors">
            <td class="py-3 px-4 text-sm font-medium text-white">{{ $dividend->user->name ?? 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $dividend->shareProduct->name ?? 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $dividend->number_of_shares }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ number_format($dividend->dividend_per_share, 2) }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ number_format($dividend->total_dividend, 2) }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $dividend->declaration_date->format('M d, Y') }}</td>
            <td class="py-3 px-4">
              @if($dividend->status === 'paid')
                <span class="badge badge-green text-[10px]">Paid</span>
              @elseif($dividend->status === 'declared')
                <span class="badge badge-blue text-[10px]">Declared</span>
              @else
                <span class="badge badge-amber text-[10px]">Pending</span>
              @endif
            </td>
            <td class="py-3 px-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <a href="{{ route('admin.share-dividends.show', $dividend) }}" class="text-primary-400 hover:text-white transition-colors">
                  <i class="fa-solid fa-eye text-sm"></i>
                </a>
                <a href="{{ route('admin.share-dividends.edit', $dividend) }}" class="text-primary-400 hover:text-white transition-colors">
                  <i class="fa-solid fa-edit text-sm"></i>
                </a>
                <form action="{{ route('admin.share-dividends.destroy', $dividend) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share dividend?');">
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
            <td colspan="8" class="py-8 text-center text-primary-400 text-sm">No share dividends found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($shareDividends->hasPages())
    <div class="flex items-center justify-between mt-5 pt-5 border-t border-primary-700/20">
      <span class="text-xs text-primary-400">Showing {{ $shareDividends->firstItem() }} to {{ $shareDividends->lastItem() }} of {{ $shareDividends->total() }} results</span>
      {{ $shareDividends->links() }}
    </div>
    @endif
  </div>

</div>

@endsection
