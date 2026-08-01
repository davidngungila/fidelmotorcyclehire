@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Products \u203A Create')
@section('page_title', 'Create Share Product')

@section('content')

<div class="space-y-6">

  <div class="glass p-6">
    <form action="{{ route('admin.share-products.store') }}" method="POST" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Product Name *</label>
          <input type="text" name="name" required
                 class="form-input"
                 placeholder="Enter product name">
          @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Product Code *</label>
          <input type="text" name="code" required
                 class="form-input"
                 placeholder="e.g., SP-001">
          @error('code') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Price Per Share *</label>
          <input type="number" name="price_per_share" step="0.01" min="0" required
                 class="form-input"
                 placeholder="0.00">
          @error('price_per_share') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Minimum Shares *</label>
          <input type="number" name="minimum_shares" min="1" required
                 class="form-input"
                 placeholder="1">
          @error('minimum_shares') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Maximum Shares</label>
          <input type="number" name="maximum_shares" min="1"
                 class="form-input"
                 placeholder="Optional">
          @error('maximum_shares') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Dividend Rate (%)</label>
          <input type="number" name="dividend_rate" step="0.01" min="0" max="100"
                 class="form-input"
                 placeholder="0.00">
          @error('dividend_rate') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Status *</label>
          <select name="status" required class="form-input">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="closed">Closed</option>
          </select>
          @error('status') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Issue Date</label>
          <input type="date" name="issue_date"
                 class="form-input">
          @error('issue_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Maturity Date</label>
          <input type="date" name="maturity_date"
                 class="form-input">
          @error('maturity_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-primary-300 mb-2">Description</label>
        <textarea name="description" rows="4"
                  class="form-input"
                  placeholder="Enter product description"></textarea>
        @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="flex items-center gap-3 pt-4">
        <a href="{{ route('admin.share-products.index') }}"
           class="px-5 py-2.5 rounded-xl bg-primary-700 hover:bg-primary-600 text-white text-sm font-bold transition-all">
          Cancel
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
          Create Share Product
        </button>
      </div>
    </form>
  </div>

</div>

@endsection
