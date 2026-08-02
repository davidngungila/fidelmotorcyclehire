@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Bank Accounts \u203A View Bank Account')
@section('page_title', 'View Bank Account')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bankAccount->bank_name }}</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $bankAccount->account_number }}</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.bank-accounts.edit', $bankAccount->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <a href="{{ route('admin.bank-accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Details</h3>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Bank Name:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $bankAccount->bank_name }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Account Number:</span>
            <div class="font-mono font-bold text-gray-900 dark:text-white">{{ $bankAccount->account_number }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Account Type:</span>
            <div>
              <span class="badge badge-{{ $bankAccount->account_type === 'checking' ? 'blue' : ($bankAccount->account_type === 'savings' ? 'green' : 'purple') }}">
                {{ ucfirst($bankAccount->account_type) }}
              </span>
            </div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Currency:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ strtoupper($bankAccount->currency) }}</div>
          </div>
          @if($bankAccount->branch_name)
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Branch Name:</span>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $bankAccount->branch_name }}</div>
            </div>
          @endif
          @if($bankAccount->swift_code)
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">SWIFT Code:</span>
              <div class="font-mono font-semibold text-gray-900 dark:text-white">{{ $bankAccount->swift_code }}</div>
            </div>
          @endif
          @if($bankAccount->iban)
            <div class="col-span-2">
              <span class="text-sm text-gray-600 dark:text-gray-400">IBAN:</span>
              <div class="font-mono font-semibold text-gray-900 dark:text-white">{{ $bankAccount->iban }}</div>
            </div>
          @endif
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
            <div>
              @if($bankAccount->is_active)
                <span class="badge badge-green">Active</span>
              @else
                <span class="badge badge-red">Inactive</span>
              @endif
            </div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Created At:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $bankAccount->created_at->format('M d, Y H:i') }}</div>
          </div>
        </div>
        @if($bankAccount->notes)
          <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <span class="text-sm text-gray-600 dark:text-gray-400">Notes:</span>
            <div class="text-sm text-gray-900 dark:text-white mt-1">{{ $bankAccount->notes }}</div>
          </div>
        @endif
      </div>

      @if($bankAccount->account)
        <div class="glass rounded-xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Linked Chart of Account</h3>
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
              <i class="fa-solid fa-book text-primary-700 dark:text-primary-300"></i>
            </div>
            <div>
              <div class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $bankAccount->account->account_code }}</div>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $bankAccount->account->account_name }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ $bankAccount->account->account_type }} - {{ $bankAccount->account->account_subtype }}</div>
            </div>
          </div>
        </div>
      @endif
    </div>

    <div class="space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Balance Summary</h3>
        <div class="space-y-3">
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Opening Balance:</span>
            <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($bankAccount->opening_balance, 2) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Current Balance:</span>
            <span class="font-mono font-bold text-2xl text-primary-700 dark:text-primary-300">{{ number_format($bankAccount->current_balance, 2) }}</span>
          </div>
          <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">Net Change:</span>
              <span class="font-mono font-bold {{ $bankAccount->current_balance >= $bankAccount->opening_balance ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ number_format($bankAccount->current_balance - $bankAccount->opening_balance, 2) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="space-y-2">
          <a href="{{ route('admin.journal-entries.create') }}" class="block w-full text-center px-4 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
            <i class="fa-solid fa-plus mr-2"></i> New Journal Entry
          </a>
          <a href="{{ route('admin.ledger.show', $bankAccount->account_id) }}" class="block w-full text-center px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
            <i class="fa-solid fa-book mr-2"></i> View Ledger
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
