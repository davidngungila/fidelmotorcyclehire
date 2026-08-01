@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Products')
@section('page_title', 'Share Products Management')

@section('content')

<div class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <span class="text-xs font-semibold text-primary-600">
        <i class="fa-solid fa-box mr-1.5"></i> {{ $shareProducts->total() }} Share Products
      </span>
    </div>

    <a href="{{ route('admin.share-products.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
      <i class="fa-solid fa-plus text-[13px]"></i> Create Share Product
    </a>
  </div>

  <div class="glass p-5">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-primary-700/20">
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Name</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Code</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Price/Share</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Min Shares</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Dividend Rate</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Status</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600">Issue Date</th>
            <th class="text-center py-3 px-4 text-xs font-semibold text-primary-600">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($shareProducts as $product)
          <tr class="border-b border-primary-700/10 hover:bg-primary-800/30 transition-colors">
            <td class="py-3 px-4 text-sm font-medium text-white">{{ $product->name }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $product->code }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ number_format($product->price_per_share, 2) }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $product->minimum_shares }}</td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $product->dividend_rate ? number_format($product->dividend_rate, 2) . '%' : 'N/A' }}</td>
            <td class="py-3 px-4">
              @if($product->status === 'active')
                <span class="badge badge-green text-[10px]">Active</span>
              @elseif($product->status === 'inactive')
                <span class="badge badge-amber text-[10px]">Inactive</span>
              @else
                <span class="badge badge-red text-[10px]">Closed</span>
              @endif
            </td>
            <td class="py-3 px-4 text-sm text-primary-300">{{ $product->issue_date ? $product->issue_date->format('M d, Y') : 'N/A' }}</td>
            <td class="py-3 px-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <a href="{{ route('admin.share-products.show', $product) }}" class="text-primary-400 hover:text-white transition-colors">
                  <i class="fa-solid fa-eye text-sm"></i>
                </a>
                <a href="{{ route('admin.share-products.edit', $product) }}" class="text-primary-400 hover:text-white transition-colors">
                  <i class="fa-solid fa-edit text-sm"></i>
                </a>
                <form action="{{ route('admin.share-products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share product?');">
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
            <td colspan="8" class="py-8 text-center text-primary-400 text-sm">No share products found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($shareProducts->hasPages())
    <div class="flex items-center justify-between mt-5 pt-5 border-t border-primary-700/20">
      <span class="text-xs text-primary-400">Showing {{ $shareProducts->firstItem() }} to {{ $shareProducts->lastItem() }} of {{ $shareProducts->total() }} results</span>
      {{ $shareProducts->links() }}
    </div>
    @endif
  </div>

</div>

@endsection
