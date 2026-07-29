@extends('layouts.admin')

@section('page_title', 'Create Product')

@section('breadcrumb', 'Savings & Deposits › Products › New')

@section('content')
<div class="glass p-8">
  <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-6">
    @csrf

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Name</label>
      <input type="text" name="name" required
             placeholder="Enter product name"
             class="form-input py-2.5 px-4">
      @error('name')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Code</label>
        <input type="text" name="code" required
               placeholder="Enter product code (e.g., SAV-001)"
               class="form-input py-2.5 px-4">
        @error('code')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Type</label>
        <select name="type" required class="form-input py-2.5 px-4">
          <option value="">Select product type</option>
          <option value="savings">Savings</option>
          <option value="deposit">Deposit</option>
          <option value="fixed_deposit">Fixed Deposit</option>
        </select>
        @error('type')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interest Rate (%)</label>
        <input type="number" name="interest_rate" step="0.01" min="0" max="100" required
               placeholder="Enter interest rate"
               class="form-input py-2.5 px-4">
        @error('interest_rate')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Minimum Balance (TZS)</label>
        <input type="number" name="min_balance" step="0.01" min="0" required
               placeholder="Enter minimum balance"
               class="form-input py-2.5 px-4">
        @error('min_balance')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maximum Balance (TZS)</label>
        <input type="number" name="max_balance" step="0.01" min="0"
               placeholder="Enter maximum balance (optional)"
               class="form-input py-2.5 px-4">
        @error('max_balance')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Duration (Months)</label>
        <input type="number" name="duration_months" min="1"
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
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
        @error('status')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
      <textarea name="description" rows="4"
                placeholder="Enter product description (optional)"
                class="form-input py-2.5 px-4"></textarea>
      @error('description')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center gap-3 pt-4">
      <a href="{{ route('admin.products.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Create Product
      </button>
    </div>
  </form>
</div>
@endsection
