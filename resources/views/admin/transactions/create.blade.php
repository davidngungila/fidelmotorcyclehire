@extends('layouts.admin')

@section('breadcrumb', 'Members › Transactions › New')
@section('page_title', 'Record New Transaction')

@section('content')

<div class="space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.transactions.index') }}" 
       class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to Transactions</span>
    </a>
  </div>

  <div class="glass p-6">
    <div class="max-w-2xl">
      <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Record New Transaction</h2>
      
      <form method="POST" action="{{ route('admin.transactions.store') }}" class="space-y-5">
        @csrf

        <div>
          <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Date</label>
          <input
            type="date"
            id="date"
            name="date"
            value="{{ old('date', now()->format('Y-m-d')) }}"
            required
            class="form-input py-2.5 px-3 text-sm"
          >
        </div>

        <div>
          <label for="membercode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Member Code</label>
          <select
            id="membercode"
            name="membercode"
            required
            class="form-input py-2.5 px-3 text-sm"
          >
            <option value="">Select a member</option>
            @foreach($members as $member)
              <option value="{{ $member['member_number'] ?? $member['MemberNumber'] }}" {{ old('membercode') === ($member['member_number'] ?? $member['MemberNumber']) ? 'selected' : '' }}>
                {{ $member['member_number'] ?? $member['MemberNumber'] }} - {{ $member['name'] ?? $member['Name'] ?? 'Unknown' }}
              </option>
            @endforeach
          </select>
        </div>

        <div>
          <label for="transactiontype" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Transaction Type</label>
          <select
            id="transactiontype"
            name="transactiontype"
            required
            class="form-input py-2.5 px-3 text-sm"
          >
            <option value="">Select transaction type</option>
            <option value="Deposit" {{ old('transactiontype') === 'Deposit' ? 'selected' : '' }}>Deposit</option>
            <option value="Withdrawal" {{ old('transactiontype') === 'Withdrawal' ? 'selected' : '' }}>Withdrawal</option>
            <option value="Interest" {{ old('transactiontype') === 'Interest' ? 'selected' : '' }}>Interest</option>
          </select>
        </div>

        <div>
          <label for="referenceno" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Reference Number</label>
          <input
            type="text"
            id="referenceno"
            name="referenceno"
            value="{{ old('referenceno') }}"
            required
            placeholder="e.g., TXN-001"
            class="form-input py-2.5 px-3 text-sm"
          >
        </div>

        <div>
          <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Amount</label>
          <input
            type="number"
            id="amount"
            name="amount"
            value="{{ old('amount') }}"
            required
            step="0.01"
            placeholder="0.00"
            class="form-input py-2.5 px-3 text-sm"
          >
        </div>

        <div class="flex items-center gap-3 pt-4">
          <button
            type="submit"
            class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors"
          >
            <i class="fa-solid fa-save mr-1.5"></i> Record Transaction
          </button>
          <a href="{{ route('admin.transactions.index') }}" 
             class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
