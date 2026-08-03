@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Chart of Accounts \u203A Edit Account')
@section('page_title', 'Edit Account')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Account</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Update account details</p>
    </div>
    <a href="{{ route('admin.accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
  </div>

  <div class="glass rounded-xl p-8">
    <form action="{{ route('admin.accounts.update', app('App\Services\EncryptedIdService')->encrypt($account->id)) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Code *</label>
          <input type="text" name="account_code" value="{{ old('account_code', $account->account_code) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="e.g., 1001">
          @error('account_code')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Name *</label>
          <input type="text" name="account_name" value="{{ old('account_name', $account->account_name) }}" required
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
            <option value="asset" {{ $account->account_type === 'asset' ? 'selected' : '' }}>Asset</option>
            <option value="liability" {{ $account->account_type === 'liability' ? 'selected' : '' }}>Liability</option>
            <option value="equity" {{ $account->account_type === 'equity' ? 'selected' : '' }}>Equity</option>
            <option value="revenue" {{ $account->account_type === 'revenue' ? 'selected' : '' }}>Revenue</option>
            <option value="expense" {{ $account->account_type === 'expense' ? 'selected' : '' }}>Expense</option>
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
            <option value="current_asset" {{ $account->account_subtype === 'current_asset' ? 'selected' : '' }}>Current Asset</option>
            <option value="fixed_asset" {{ $account->account_subtype === 'fixed_asset' ? 'selected' : '' }}>Fixed Asset</option>
            <option value="loan_receivable" {{ $account->account_subtype === 'loan_receivable' ? 'selected' : '' }}>Loan Receivable</option>
            <option value="investment" {{ $account->account_subtype === 'investment' ? 'selected' : '' }}>Investment</option>
            <option value="current_liability" {{ $account->account_subtype === 'current_liability' ? 'selected' : '' }}>Current Liability</option>
            <option value="long_term_liability" {{ $account->account_subtype === 'long_term_liability' ? 'selected' : '' }}>Long Term Liability</option>
            <option value="savings_deposit" {{ $account->account_subtype === 'savings_deposit' ? 'selected' : '' }}>Savings Deposit</option>
            <option value="swf_fund" {{ $account->account_subtype === 'swf_fund' ? 'selected' : '' }}>SWF Fund</option>
            <option value="owners_equity" {{ $account->account_subtype === 'owners_equity' ? 'selected' : '' }}>Owner's Equity</option>
            <option value="share_capital" {{ $account->account_subtype === 'share_capital' ? 'selected' : '' }}>Share Capital</option>
            <option value="operating_revenue" {{ $account->account_subtype === 'operating_revenue' ? 'selected' : '' }}>Operating Revenue</option>
            <option value="non_operating_revenue" {{ $account->account_subtype === 'non_operating_revenue' ? 'selected' : '' }}>Non-Operating Revenue</option>
            <option value="interest_income" {{ $account->account_subtype === 'interest_income' ? 'selected' : '' }}>Interest Income</option>
            <option value="operating_expense" {{ $account->account_subtype === 'operating_expense' ? 'selected' : '' }}>Operating Expense</option>
            <option value="non_operating_expense" {{ $account->account_subtype === 'non_operating_expense' ? 'selected' : '' }}>Non-Operating Expense</option>
          </select>
          @error('account_subtype')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Parent Account</label>
          <select name="parent_account_id"
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">No Parent (Root Account)</option>
            @foreach($parentAccounts as $parent)
              <option value="{{ $parent->id }}" {{ old('parent_account_id', $account->parent_account_id) == $parent->id ? 'selected' : '' }}>
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
          placeholder="Account description...">{{ old('description', $account->description) }}</textarea>
        @error('description')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center gap-6">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" name="is_active" value="1" {{ $account->is_active ? 'checked' : '' }}
            class="w-4 h-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
          <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
        </label>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.accounts.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          Update Account
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
