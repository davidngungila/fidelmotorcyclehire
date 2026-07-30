@extends('layouts.admin')

@section('page_title', 'Edit Savings Product')

@section('breadcrumb', 'Savings & Deposits › Products › Edit')

@section('content')
<div class="space-y-6">
  <!-- Product Details Card -->
  <div class="glass p-6">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-xl font-bold text-primary-900 dark:text-white">{{ $product->name }}</h2>
        <p class="text-sm text-primary-600 dark:text-primary-400 mt-1">Product Code: <span class="font-mono font-semibold">{{ $product->code }}</span></p>
      </div>
      <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold {{ $product->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
        {{ ucfirst($product->status) }}
      </span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div class="bg-primary-50 dark:bg-primary-900/30 rounded-lg p-4">
        <p class="text-xs text-primary-600 dark:text-primary-400 font-medium mb-1">Interest Rate</p>
        <p class="text-lg font-bold text-primary-900 dark:text-white">{{ number_format($product->interest_rate, 2) }}%</p>
      </div>
      <div class="bg-primary-50 dark:bg-primary-900/30 rounded-lg p-4">
        <p class="text-xs text-primary-600 dark:text-primary-400 font-medium mb-1">Min Deposit</p>
        <p class="text-lg font-bold text-primary-900 dark:text-white">TSh {{ number_format($product->min_deposit, 0) }}</p>
      </div>
      <div class="bg-primary-50 dark:bg-primary-900/30 rounded-lg p-4">
        <p class="text-xs text-primary-600 dark:text-primary-400 font-medium mb-1">Interest Frequency</p>
        <p class="text-lg font-bold text-primary-900 dark:text-white">{{ ucfirst($product->interest_frequency) }}</p>
      </div>
      <div class="bg-primary-50 dark:bg-primary-900/30 rounded-lg p-4">
        <p class="text-xs text-primary-600 dark:text-primary-400 font-medium mb-1">Auto Credit</p>
        <p class="text-lg font-bold text-primary-900 dark:text-white">{{ $product->auto_interest_credit ? 'Yes' : 'No' }}</p>
      </div>
    </div>
  </div>

  <!-- Edit Form -->
  <div class="glass p-8">
    <h3 class="text-lg font-bold text-primary-900 dark:text-white mb-6">Edit Product Details</h3>
    <form method="POST" action="{{ route('admin.products.update', $encryptedId) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Name</label>
      <input type="text" name="name" required
             value="{{ old('name', $product->name) }}"
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
               value="{{ old('code', $product->code) }}"
               placeholder="Enter product code (e.g., BS, FF, EF, RDA)"
               class="form-input py-2.5 px-4">
        @error('code')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interest Rate (%)</label>
        <input type="number" name="interest_rate" step="0.01" min="0" max="100" required
               value="{{ old('interest_rate', $product->interest_rate) }}"
               placeholder="Enter interest rate"
               class="form-input py-2.5 px-4">
        @error('interest_rate')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Minimum Balance (TZS)</label>
        <input type="number" name="min_balance" step="0.01" min="0" required
               value="{{ old('min_balance', $product->min_balance) }}"
               placeholder="Enter minimum balance"
               class="form-input py-2.5 px-4">
        @error('min_balance')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Minimum Deposit (TZS)</label>
        <input type="number" name="min_deposit" step="0.01" min="0" required
               value="{{ old('min_deposit', $product->min_deposit) }}"
               placeholder="Enter minimum deposit"
               class="form-input py-2.5 px-4">
        @error('min_deposit')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maximum Deposit (TZS)</label>
        <input type="number" name="max_deposit" step="0.01" min="0"
               value="{{ old('max_deposit', $product->max_deposit) }}"
               placeholder="Enter maximum deposit (optional)"
               class="form-input py-2.5 px-4">
        @error('max_deposit')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Min Withdrawal Period (Days)</label>
        <input type="number" name="min_withdrawal_period_days" min="0" required
               value="{{ old('min_withdrawal_period_days', $product->min_withdrawal_period_days) }}"
               placeholder="Enter minimum withdrawal period"
               class="form-input py-2.5 px-4">
        @error('min_withdrawal_period_days')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Premature Withdrawal Fee (%)</label>
        <input type="number" name="premature_withdrawal_fee" step="0.01" min="0" max="100" required
               value="{{ old('premature_withdrawal_fee', $product->premature_withdrawal_fee) }}"
               placeholder="Enter premature withdrawal fee"
               class="form-input py-2.5 px-4">
        @error('premature_withdrawal_fee')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notice Period (Days)</label>
        <input type="number" name="notice_period_days" min="0" required
               value="{{ old('notice_period_days', $product->notice_period_days) }}"
               placeholder="Enter notice period"
               class="form-input py-2.5 px-4">
        @error('notice_period_days')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interest Frequency</label>
        <select name="interest_frequency" required class="form-input py-2.5 px-4">
          <option value="">Select interest frequency</option>
          <option value="monthly" {{ old('interest_frequency', $product->interest_frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
          <option value="quarterly" {{ old('interest_frequency', $product->interest_frequency) === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
          <option value="annually" {{ old('interest_frequency', $product->interest_frequency) === 'annually' ? 'selected' : '' }}>Annually</option>
        </select>
        @error('interest_frequency')
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="flex items-center gap-3">
        <input type="checkbox" name="auto_interest_credit" id="auto_interest_credit" value="1" {{ old('auto_interest_credit', $product->auto_interest_credit) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
        <label for="auto_interest_credit" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Auto Credit Interest</label>
      </div>

      <div class="flex items-center gap-3">
        <input type="checkbox" name="requires_notice" id="requires_notice" value="1" {{ old('requires_notice', $product->requires_notice) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
        <label for="requires_notice" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Requires Notice for Withdrawal</label>
      </div>
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
      <a href="{{ route('admin.products.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Update Savings Product
      </button>
    </div>
  </form>
</div>
@endsection
