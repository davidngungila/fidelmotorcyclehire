@extends('layouts.admin')

@section('breadcrumb', 'System › Share Products › Edit')
@section('page_title', 'Edit Share Product')

@section('content')
<div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-8">
  <form method="POST" action="{{ route('admin.share-products.update', $shareProduct) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Name</label>
        <input type="text" name="name" value="{{ old('name', $shareProduct->name) }}" required
               placeholder="Enter product name"
               class="form-input py-2.5 px-4">
        @error('name')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Code</label>
        <input type="text" name="code" value="{{ old('code', $shareProduct->code) }}" required
               placeholder="Enter product code (e.g., SP-001)"
               class="form-input py-2.5 px-4">
        @error('code')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Price Per Share</label>
        <input type="number" name="price_per_share" step="0.01" min="0" value="{{ old('price_per_share', $shareProduct->price_per_share) }}" required
               placeholder="Enter price per share"
               class="form-input py-2.5 px-4">
        @error('price_per_share')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Minimum Shares</label>
        <input type="number" name="minimum_shares" min="1" value="{{ old('minimum_shares', $shareProduct->minimum_shares) }}" required
               placeholder="Enter minimum shares"
               class="form-input py-2.5 px-4">
        @error('minimum_shares')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maximum Shares</label>
        <input type="number" name="maximum_shares" min="1" value="{{ old('maximum_shares', $shareProduct->maximum_shares) }}"
               placeholder="Enter maximum shares (optional)"
               class="form-input py-2.5 px-4">
        @error('maximum_shares')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Dividend Rate (%)</label>
        <input type="number" name="dividend_rate" step="0.01" min="0" max="100" value="{{ old('dividend_rate', $shareProduct->dividend_rate) }}"
               placeholder="Enter dividend rate (optional)"
               class="form-input py-2.5 px-4">
        @error('dividend_rate')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
        <select name="status" required class="form-input py-2.5 px-4">
          <option value="">Select status</option>
          <option value="active" {{ $shareProduct->status === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ $shareProduct->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
          <option value="closed" {{ $shareProduct->status === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
        @error('status')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Issue Date</label>
        <input type="date" name="issue_date" value="{{ old('issue_date', $shareProduct->issue_date?->format('Y-m-d')) }}"
               class="form-input py-2.5 px-4">
        @error('issue_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maturity Date</label>
        <input type="date" name="maturity_date" value="{{ old('maturity_date', $shareProduct->maturity_date?->format('Y-m-d')) }}"
               class="form-input py-2.5 px-4">
        @error('maturity_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
      <textarea name="description" rows="4"
                placeholder="Enter product description (optional)"
                class="form-input py-2.5 px-4">{{ old('description', $shareProduct->description) }}</textarea>
      @error('description')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center gap-3 pt-4">
      <a href="{{ route('admin.share-products.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Update Share Product
      </button>
    </div>
  </form>
</div>
@endsection
