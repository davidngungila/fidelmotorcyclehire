@extends('layouts.admin')

@section('breadcrumb', 'Savings \u203A Add Transaction')
@section('page_title', 'Add Savings Transaction')

@section('content')

<div class="glass p-8">
  <form method="POST" action="{{ route('admin.savings.store') }}" class="space-y-6">
    @csrf

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Member</label>
      <select name="member_number" required class="form-input py-2.5 px-4">
        <option value="">Select a member</option>
        @foreach($members as $member)
          <option value="{{ $member->member_number }}">{{ $member->name }} ({{ $member->member_number }})</option>
        @endforeach
      </select>
      @error('member_number')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Transaction Type</label>
      <select name="transaction_type" required class="form-input py-2.5 px-4">
        <option value="">Select transaction type</option>
        <option value="deposit">Deposit</option>
        <option value="withdrawal">Withdrawal</option>
        <option value="interest">Interest</option>
        <option value="flexi-deposit">Flexi Deposit</option>
        <option value="rda-deposit">RDA Deposit</option>
        <option value="opening balance">Opening Balance</option>
      </select>
      @error('transaction_type')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Amount (TSh)</label>
        <input type="number" name="amount" step="0.01" min="0" required
               placeholder="Enter amount"
               class="form-input py-2.5 px-4">
        @error('amount')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date</label>
        <input type="date" name="date" required
               value="{{ now()->format('Y-m-d') }}"
               class="form-input py-2.5 px-4">
        @error('date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Reference Number (Optional)</label>
      <input type="text" name="reference_no"
             placeholder="Enter reference number"
             class="form-input py-2.5 px-4">
      @error('reference_no')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center gap-3 pt-4">
      <a href="{{ route('admin.savings.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Add Transaction
      </button>
    </div>
  </form>
</div>

@endsection
