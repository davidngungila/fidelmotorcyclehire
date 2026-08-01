@extends('layouts.admin')

@section('breadcrumb', 'Deposits \u203A New')
@section('page_title', 'Create Deposit')

@section('content')

<div class="glass p-8">
  <form method="POST" action="{{ route('admin.deposits.store') }}" class="space-y-6">
    @csrf

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Member</label>
      <select name="user_id" required class="form-input py-2.5 px-4">
        <option value="">Select a member</option>
        @foreach($members as $member)
          <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->member_number }})</option>
        @endforeach
      </select>
      @error('user_id')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Savings Product (Optional)</label>
      <select name="product_id" class="form-input py-2.5 px-4">
        <option value="">No product</option>
        @foreach($products as $product)
          <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->interest_rate }}% Interest</option>
        @endforeach
      </select>
      @error('product_id')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Amount (TSh)</label>
        <input type="number" name="amount" step="0.01" min="0" required
               placeholder="Enter deposit amount"
               class="form-input py-2.5 px-4">
        @error('amount')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interest Rate (%)</label>
        <input type="number" name="interest_rate" step="0.01" min="0" required
               placeholder="Enter interest rate"
               class="form-input py-2.5 px-4">
        @error('interest_rate')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
        <input type="date" name="start_date" required
               value="{{ now()->format('Y-m-d') }}"
               class="form-input py-2.5 px-4">
        @error('start_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maturity Date</label>
        <input type="date" name="maturity_date" required
               placeholder="Enter maturity date"
               class="form-input py-2.5 px-4">
        @error('maturity_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
      <textarea name="notes" rows="3"
                placeholder="Enter any notes..."
                class="form-input py-2.5 px-4"></textarea>
      @error('notes')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center gap-3 pt-4">
      <a href="{{ route('admin.deposits.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Create Deposit
      </button>
    </div>
  </form>
</div>

@endsection
