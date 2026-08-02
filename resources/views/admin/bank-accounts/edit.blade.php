@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Bank Accounts \u203A Edit Bank Account')
@section('page_title', 'Edit Bank Account')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Bank Account</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $bankAccount->bank_name }} - {{ $bankAccount->account_number }}</p>
    </div>
    <a href="{{ route('admin.bank-accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
  </div>

  <div class="glass rounded-xl p-8">
    <form action="{{ route('admin.bank-accounts.update', $bankAccount->id) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Linked Account *</label>
          <select name="account_id" required
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Account</option>
            @foreach($accounts as $account)
              <option value="{{ $account->id }}" {{ old('account_id', $bankAccount->account_id) == $account->id ? 'selected' : '' }}>
                {{ $account->account_code }} - {{ $account->account_name }}
              </option>
            @endforeach
          </select>
          @error('account_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Bank Name *</label>
          <input type="text" name="bank_name" value="{{ old('bank_name', $bankAccount->bank_name) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="Enter bank name">
          @error('bank_name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Number *</label>
          <input type="text" name="account_number" value="{{ old('account_number', $bankAccount->account_number) }}" required
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
            <option value="checking" {{ old('account_type', $bankAccount->account_type) === 'checking' ? 'selected' : '' }}>Checking</option>
            <option value="savings" {{ old('account_type', $bankAccount->account_type) === 'savings' ? 'selected' : '' }}>Savings</option>
            <option value="investment" {{ old('account_type', $bankAccount->account_type) === 'investment' ? 'selected' : '' }}>Investment</option>
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
            <option value="USD" {{ old('currency', $bankAccount->currency) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
            <option value="EUR" {{ old('currency', $bankAccount->currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
            <option value="GBP" {{ old('currency', $bankAccount->currency) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
            <option value="KES" {{ old('currency', $bankAccount->currency) === 'KES' ? 'selected' : '' }}>KES - Kenyan Shilling</option>
            <option value="UGX" {{ old('currency', $bankAccount->currency) === 'UGX' ? 'selected' : '' }}>UGX - Ugandan Shilling</option>
            <option value="TZS" {{ old('currency', $bankAccount->currency) === 'TZS' ? 'selected' : '' }}>TZS - Tanzanian Shilling</option>
            <option value="RWF" {{ old('currency', $bankAccount->currency) === 'RWF' ? 'selected' : '' }}>RWF - Rwandan Franc</option>
          </select>
          @error('currency')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Branch Name</label>
          <input type="text" name="branch_name" value="{{ old('branch_name', $bankAccount->branch_name) }}"
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="Enter branch name">
          @error('branch_name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Opening Balance *</label>
          <input type="number" name="opening_balance" step="0.01" min="0" value="{{ old('opening_balance', $bankAccount->opening_balance) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="0.00">
          @error('opening_balance')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Current Balance *</label>
          <input type="number" name="current_balance" step="0.01" min="0" value="{{ old('current_balance', $bankAccount->current_balance) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="0.00">
          @error('current_balance')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">SWIFT Code</label>
          <input type="text" name="swift_code" value="{{ old('swift_code', $bankAccount->swift_code) }}" maxlength="11"
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="Enter SWIFT code">
          @error('swift_code')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">IBAN</label>
          <input type="text" name="iban" value="{{ old('iban', $bankAccount->iban) }}" maxlength="34"
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="Enter IBAN">
          @error('iban')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
        <textarea name="notes" rows="3"
          class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Enter any additional notes">{{ old('notes', $bankAccount->notes) }}</textarea>
        @error('notes')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center">
        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $bankAccount->is_active ? 'checked' : '' }}
          class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
        <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</label>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.bank-accounts.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          Update Bank Account
        </button>
      </div>
    </form>
  </div>
</div>
