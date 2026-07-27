@extends('layouts.admin')

@section('breadcrumb', 'Transactions \u203A Edit')
@section('page_title', 'Edit Transaction')

@section('content')

<div class="max-w-2xl">
  <div class="glass p-8">
    <form method="POST" action="{{ route('admin.transactions.update', $transaction->id) }}" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date</label>
          <input type="date" name="date" value="{{ $transaction->date->format('Y-m-d') }}" required
                 class="form-input py-2.5 px-4">
          @error('date')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Member Code</label>
          <input type="text" name="membercode" value="{{ $transaction->membercode }}" required
                 placeholder="Enter member code"
                 class="form-input py-2.5 px-4">
          @error('membercode')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Transaction Type</label>
        <select name="transaction_type" required class="form-input py-2.5 px-4">
          <option value="">Select transaction type</option>
          <option value="deposit" {{ $transaction->transaction_type == 'deposit' ? 'selected' : '' }}>Deposit</option>
          <option value="withdrawal" {{ $transaction->transaction_type == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
          <option value="transfer" {{ $transaction->transaction_type == 'transfer' ? 'selected' : '' }}>Transfer</option>
        </select>
        @error('transaction_type')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Reference No</label>
        <input type="text" name="reference_no" value="{{ $transaction->reference_no }}"
               placeholder="Enter reference number (optional)"
               class="form-input py-2.5 px-4">
        @error('reference_no')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Amount</label>
        <input type="number" name="amount" step="0.01" min="0" value="{{ $transaction->amount }}" required
               placeholder="Enter amount"
               class="form-input py-2.5 px-4">
        @error('amount')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center gap-3 pt-4">
        <a href="{{ route('admin.transactions.index') }}"
           class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
          Cancel
        </a>
        <button type="submit"
                class="flex-1 px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
          Update Transaction
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
