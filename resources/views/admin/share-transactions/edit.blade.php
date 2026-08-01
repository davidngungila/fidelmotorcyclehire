@extends('layouts.admin')

@section('breadcrumb', 'System › Share Transactions › Edit')
@section('page_title', 'Edit Share Transaction')

@section('content')
<div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-8">
  <form method="POST" action="{{ route('admin.share-transactions.update', $shareTransaction) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">User</label>
        <select name="user_id" required class="form-input py-2.5 px-4">
          <option value="">Select user</option>
          @foreach($users as $user)
          <option value="{{ $user->id }}" {{ old('user_id', $shareTransaction->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
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
          <option value="{{ $product->id }}" {{ old('share_product_id', $shareTransaction->share_product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }} - {{ $product->code }}</option>
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
          <option value="purchase" {{ old('transaction_type', $shareTransaction->transaction_type) === 'purchase' ? 'selected' : '' }}>Purchase</option>
          <option value="sale" {{ old('transaction_type', $shareTransaction->transaction_type) === 'sale' ? 'selected' : '' }}>Sale</option>
          <option value="transfer" {{ old('transaction_type', $shareTransaction->transaction_type) === 'transfer' ? 'selected' : '' }}>Transfer</option>
          <option value="dividend" {{ old('transaction_type', $shareTransaction->transaction_type) === 'dividend' ? 'selected' : '' }}>Dividend</option>
        </select>
        @error('transaction_type')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Number of Shares</label>
        <input type="number" name="number_of_shares" min="1" value="{{ old('number_of_shares', $shareTransaction->number_of_shares) }}" required
               placeholder="Enter number of shares"
               class="form-input py-2.5 px-4">
        @error('number_of_shares')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Price Per Share</label>
        <input type="number" name="price_per_share" step="0.01" min="0" value="{{ old('price_per_share', $shareTransaction->price_per_share) }}" required
               placeholder="Enter price per share"
               class="form-input py-2.5 px-4">
        @error('price_per_share')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Amount</label>
        <input type="number" name="total_amount" step="0.01" min="0" value="{{ old('total_amount', $shareTransaction->total_amount) }}" required
               placeholder="Enter total amount"
               class="form-input py-2.5 px-4">
        @error('total_amount')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Transaction Date</label>
        <input type="date" name="transaction_date" value="{{ old('transaction_date', $shareTransaction->transaction_date?->format('Y-m-d')) }}" required
               class="form-input py-2.5 px-4">
        @error('transaction_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
        <select name="status" required class="form-input py-2.5 px-4">
          <option value="">Select status</option>
          <option value="pending" {{ old('status', $shareTransaction->status) === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="completed" {{ old('status', $shareTransaction->status) === 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="cancelled" {{ old('status', $shareTransaction->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                class="form-input py-2.5 px-4">{{ old('description', $shareTransaction->description) }}</textarea>
      @error('description')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
      <textarea name="notes" rows="3"
                placeholder="Enter notes (optional)"
                class="form-input py-2.5 px-4">{{ old('notes', $shareTransaction->notes) }}</textarea>
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
        Update Share Transaction
      </button>
    </div>
  </form>
</div>
@endsection
