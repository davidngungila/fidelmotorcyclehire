@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Bank Accounts \u203A Create Bank Account')
@section('page_title', 'Create Bank Account')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Bank Account</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Add a new bank account to the system</p>
    </div>
    <a href="{{ route('admin.bank-accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
  </div>

  <div class="glass rounded-xl p-8">
    <form action="{{ route('admin.bank-accounts.store') }}" method="POST" class="space-y-6">
      @csrf
      
      <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Linkage</h3>
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Linked Account *</label>
          <select name="account_id" required
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Account</option>
            @foreach($accounts as $account)
              <option value="{{ $account->id }}">{{ $account->account_code }} - {{ $account->account_name }}</option>
            @endforeach
          </select>
          @error('account_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Bank Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Bank Name *</label>
            <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter bank name">
            @error('bank_name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Number *</label>
            <input type="text" name="account_number" value="{{ old('account_number') }}" required
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter account number">
            @error('account_number')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Type *</label>
            <select name="account_type" required
              class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select Type</option>
              <option value="checking">Checking</option>
              <option value="savings">Savings</option>
              <option value="investment">Investment</option>
            </select>
            @error('account_type')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Currency *</label>
            <select name="currency" required
              class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select Currency</option>
              <option value="USD">USD - US Dollar</option>
              <option value="EUR">EUR - Euro</option>
              <option value="GBP">GBP - British Pound</option>
              <option value="KES">KES - Kenyan Shilling</option>
              <option value="UGX">UGX - Ugandan Shilling</option>
              <option value="TZS">TZS - Tanzanian Shilling</option>
              <option value="RWF">RWF - Rwandan Franc</option>
            </select>
            @error('currency')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Branch Name</label>
            <input type="text" name="branch_name" value="{{ old('branch_name') }}"
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter branch name">
            @error('branch_name')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Balance Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Opening Balance *</label>
            <input type="number" name="opening_balance" step="0.01" min="0" value="{{ old('opening_balance', 0) }}" required
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="0.00">
            @error('opening_balance')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Current Balance *</label>
            <input type="number" name="current_balance" step="0.01" min="0" value="{{ old('current_balance', 0) }}" required
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="0.00">
            @error('current_balance')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">International Banking</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">SWIFT Code</label>
            <input type="text" name="swift_code" value="{{ old('swift_code') }}" maxlength="11"
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter SWIFT code">
            @error('swift_code')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">IBAN</label>
            <input type="text" name="iban" value="{{ old('iban') }}" maxlength="34"
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter IBAN">
            @error('iban')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <div class="pb-6">
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
        <textarea name="notes" rows="3"
          class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Enter any additional notes">{{ old('notes') }}</textarea>
        @error('notes')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center">
        <input type="checkbox" name="is_active" id="is_active" value="1" checked
          class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
        <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</label>
      </div>

      <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.bank-accounts.index') }}" class="px-6 py-3 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          Cancel
        </a>
        <button type="submit" class="px-8 py-3 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          <i class="fa-solid fa-check mr-2"></i> Create Bank Account
        </button>
      </div>
    </form>
  </div>
</div>
