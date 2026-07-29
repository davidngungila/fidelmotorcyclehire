@extends('layouts.admin')

@section('page_title', 'Edit Investment Product')

@section('breadcrumb', 'Investments › Investment Products › Edit')

@section('content')
<div class="glass p-8">
  <form method="POST" action="{{ route('admin.investment-products.update', $product->id) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Name</label>
      <input type="text" name="name" value="{{ old('name', $product->name) }}" required
             placeholder="Enter product name"
             class="form-input py-2.5 px-4">
      @error('name')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Code</label>
        <input type="text" name="code" value="{{ old('code', $product->code) }}" required
               placeholder="Enter product code (e.g., INV-001)"
               class="form-input py-2.5 px-4">
        @error('code')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Type</label>
        <select name="type" required class="form-input py-2.5 px-4">
          <option value="">Select product type</option>
          <option value="fixed" {{ old('type', $product->type) === 'fixed' ? 'selected' : '' }}>Fixed</option>
          <option value="flexible" {{ old('type', $product->type) === 'flexible' ? 'selected' : '' }}>Flexible</option>
          <option value="mutual_fund" {{ old('type', $product->type) === 'mutual_fund' ? 'selected' : '' }}>Mutual Fund</option>
          <option value="bonds" {{ old('type', $product->type) === 'bonds' ? 'selected' : '' }}>Bonds</option>
          <option value="stocks" {{ old('type', $product->type) === 'stocks' ? 'selected' : '' }}>Stocks</option>
        </select>
        @error('type')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interest Rate (%)</label>
        <input type="number" name="interest_rate" step="0.01" min="0" max="100" value="{{ old('interest_rate', $product->interest_rate) }}" required
               placeholder="Enter interest rate"
               class="form-input py-2.5 px-4">
        @error('interest_rate')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Min Investment (TZS)</label>
        <input type="number" name="min_investment" step="0.01" min="0" value="{{ old('min_investment', $product->min_investment) }}" required
               placeholder="Enter minimum investment"
               class="form-input py-2.5 px-4">
        @error('min_investment')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Max Investment (TZS)</label>
        <input type="number" name="max_investment" step="0.01" min="0" value="{{ old('max_investment', $product->max_investment) }}"
               placeholder="Enter maximum investment (optional)"
               class="form-input py-2.5 px-4">
        @error('max_investment')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Duration (Months)</label>
        <input type="number" name="duration_months" min="1" value="{{ old('duration_months', $product->duration_months) }}"
               placeholder="Enter duration in months (optional)"
               class="form-input py-2.5 px-4">
        @error('duration_months')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
        <select name="status" required class="form-input py-2.5 px-4">
          <option value="">Select status</option>
          <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="flex items-center gap-3">
      <input type="checkbox" name="auto_renew" id="auto_renew" {{ old('auto_renew', $product->auto_renew) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
      <label for="auto_renew" class="text-sm text-gray-700 dark:text-gray-300">Auto-renew investment</label>
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
      <textarea name="description" rows="4"
                placeholder="Enter product description (optional)"
                class="form-input py-2.5 px-4">{{ old('description', $product->description) }}</textarea>
      @error('description')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center gap-3 pt-4">
      <a href="{{ route('admin.investment-products.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Update Investment Product
      </button>
    </div>
  </form>
</div>
@endsection
