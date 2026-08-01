@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Products \u203A Details')
@section('page_title', 'Share Product Details')

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-3">
    <a href="{{ route('admin.share-products.index') }}"
       class="inline-flex items-center gap-2 text-primary-400 hover:text-white transition-colors text-sm">
      <i class="fa-solid fa-arrow-left"></i> Back to Share Products
    </a>
  </div>

  <div class="glass p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Product Information</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Name:</span>
            <span class="text-sm text-white font-medium">{{ $shareProduct->name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Code:</span>
            <span class="text-sm text-white font-medium">{{ $shareProduct->code }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Price Per Share:</span>
            <span class="text-sm text-white font-medium">{{ number_format($shareProduct->price_per_share, 2) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Minimum Shares:</span>
            <span class="text-sm text-white font-medium">{{ $shareProduct->minimum_shares }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Maximum Shares:</span>
            <span class="text-sm text-white font-medium">{{ $shareProduct->maximum_shares ?? 'Unlimited' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Dividend Rate:</span>
            <span class="text-sm text-white font-medium">{{ $shareProduct->dividend_rate ? number_format($shareProduct->dividend_rate, 2) . '%' : 'N/A' }}</span>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-400 mb-4">Status & Dates</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Status:</span>
            <span class="text-sm font-medium">
              @if($shareProduct->status === 'active')
                <span class="badge badge-green text-[10px]">Active</span>
              @elseif($shareProduct->status === 'inactive')
                <span class="badge badge-amber text-[10px]">Inactive</span>
              @else
                <span class="badge badge-red text-[10px]">Closed</span>
              @endif
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Issue Date:</span>
            <span class="text-sm text-white font-medium">{{ $shareProduct->issue_date ? $shareProduct->issue_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Maturity Date:</span>
            <span class="text-sm text-white font-medium">{{ $shareProduct->maturity_date ? $shareProduct->maturity_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-primary-400">Created At:</span>
            <span class="text-sm text-white font-medium">{{ $shareProduct->created_at->format('M d, Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    @if($shareProduct->description)
    <div class="mt-6 pt-6 border-t border-primary-700/20">
      <h3 class="text-sm font-semibold text-primary-400 mb-3">Description</h3>
      <p class="text-sm text-primary-300">{{ $shareProduct->description }}</p>
    </div>
    @endif

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-700/20">
      <a href="{{ route('admin.share-products.edit', $shareProduct) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <form action="{{ route('admin.share-products.destroy', $shareProduct) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share product?');">
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
