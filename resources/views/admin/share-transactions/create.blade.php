@extends('layouts.admin')

@section('breadcrumb', 'System › Share Transactions › New')
@section('page_title', 'Create Share Transaction')

@section('content')
<div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-8">
  <form method="POST" action="{{ route('admin.share-transactions.store') }}" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">User</label>
        <select name="user_id" required class="form-input py-2.5 px-4">
          <option value="">Select user</option>
          @foreach($users as $user)
          <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
          @endforeach
        </select>
        @error('user_id')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Share Product</label>
        <select name="share_product_id" required class="form-input py-2.5 px-4">
          <option value="">Select share product</option>
          @foreach($shareProducts as $product)
          <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->code }}</option>
          @endforeach
        </select>
        @error('share_product_id')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Transaction Type</label>
        <select name="transaction_type" required class="form-input py-2.5 px-4">
          <option value="">Select type</option>
          <option value="purchase">Purchase</option>
          <option value="sale">Sale</option>
          <option value="transfer">Transfer</option>
          <option value="dividend">Dividend</option>
        </select>
        @error('transaction_type')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Number of Shares</label>
        <input type="number" name="number_of_shares" min="1" required
               placeholder="Enter number of shares"
               class="form-input py-2.5 px-4">
        @error('number_of_shares')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Price Per Share</label>
        <input type="number" name="price_per_share" step="0.01" min="0" required
               placeholder="Enter price per share"
               class="form-input py-2.5 px-4">
        @error('price_per_share')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Amount</label>
        <input type="number" name="total_amount" step="0.01" min="0" required
               placeholder="Enter total amount"
               class="form-input py-2.5 px-4">
        @error('total_amount')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Transaction Date</label>
        <input type="date" name="transaction_date" required
               class="form-input py-2.5 px-4">
        @error('transaction_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
        <select name="status" required class="form-input py-2.5 px-4">
          <option value="">Select status</option>
          <option value="pending">Pending</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        @error('status')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
      <textarea name="description" rows="3"
                placeholder="Enter description (optional)"
                class="form-input py-2.5 px-4"></textarea>
      @error('description')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
      <textarea name="notes" rows="3"
                placeholder="Enter notes (optional)"
                class="form-input py-2.5 px-4"></textarea>
      @error('notes')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center gap-3 pt-4">
      <a href="{{ route('admin.share-transactions.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Create Share Transaction
      </button>
    </div>
  </form>
</div>
@endsection
