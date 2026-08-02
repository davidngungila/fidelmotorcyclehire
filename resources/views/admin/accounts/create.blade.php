@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Chart of Accounts \u203A Create Account')
@section('page_title', 'Create Account')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Account</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Add a new account to your chart of accounts</p>
    </div>
    <a href="{{ route('admin.accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
  </div>

  <div class="glass rounded-xl p-8">
    <form action="{{ route('admin.accounts.store') }}" method="POST" class="space-y-6">
      @csrf
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Code *</label>
          <input type="text" name="account_code" value="{{ old('account_code') }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="e.g., 1001">
          @error('account_code')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Name *</label>
          <input type="text" name="account_name" value="{{ old('account_name') }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="e.g., Cash">
          @error('account_name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Type *</label>
          <select name="account_type" required
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Type</option>
            <option value="asset">Asset</option>
            <option value="liability">Liability</option>
            <option value="equity">Equity</option>
            <option value="revenue">Revenue</option>
            <option value="expense">Expense</option>
          </select>
          @error('account_type')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Subtype</label>
          <select name="account_subtype"
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Subtype</option>
            <option value="current_asset">Current Asset</option>
            <option value="fixed_asset">Fixed Asset</option>
            <option value="current_liability">Current Liability</option>
            <option value="long_term_liability">Long Term Liability</option>
            <option value="owners_equity">Owner's Equity</option>
            <option value="operating_revenue">Operating Revenue</option>
            <option value="non_operating_revenue">Non-Operating Revenue</option>
            <option value="operating_expense">Operating Expense</option>
            <option value="non_operating_expense">Non-Operating Expense</option>
          </select>
          @error('account_subtype')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Opening Balance *</label>
          <input type="number" name="opening_balance" value="{{ old('opening_balance', 0) }}" step="0.01" min="0" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="0.00">
          @error('opening_balance')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Parent Account</label>
          <select name="parent_account_id"
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">No Parent (Root Account)</option>
            @foreach($parentAccounts as $parent)
              <option value="{{ $parent->id }}" {{ old('parent_account_id') == $parent->id ? 'selected' : '' }}>
                {{ str_repeat('—', $parent->level - 1) }} {{ $parent->account_code }} - {{ $parent->account_name }}
              </option>
            @endforeach
          </select>
          @error('parent_account_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
        <textarea name="description" rows="3"
          class="form-textarea py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Account description...">{{ old('description') }}</textarea>
        @error('description')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center gap-6">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="is_active" value="1" checked
            class="w-4 h-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
          <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
        </label>

        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="is_system_account" value="1"
            class="w-4 h-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
          <span class="text-sm text-gray-700 dark:text-gray-300">System Account</span>
        </label>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.accounts.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          Create Account
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
