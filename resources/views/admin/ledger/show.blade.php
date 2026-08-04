@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A General Ledger \u203A ' . $account->account_name)
@section('page_title', 'Ledger: ' . $account->account_name)

@section('content')
<div class="space-y-6 overflow-x-hidden">
  <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $account->account_name }}</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $account->account_code }} - {{ ucfirst($account->account_type) }}</p>
    </div>
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full md:w-auto">
      <form action="{{ route('admin.ledger.filter') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
        <input type="hidden" name="account_id" value="{{ $account->id }}">
        <input type="date" name="start_date" value="{{ request('start_date') }}"
          class="form-input py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent flex-1 sm:flex-none">
        <input type="date" name="end_date" value="{{ request('end_date') }}"
          class="form-input py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent flex-1 sm:flex-none">
        <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all whitespace-nowrap">
          Filter
        </button>
        @if(request('start_date') || request('end_date'))
          <a href="{{ route('admin.ledger.show', app('App\Services\EncryptedIdService')->encrypt($account->id)) }}" class="text-sm text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 whitespace-nowrap">
            Clear
          </a>
        @endif
      </form>
      <a href="{{ route('admin.ledger.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all whitespace-nowrap">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="glass rounded-xl p-6 overflow-hidden">
    <div class="space-y-6">
      
      <!-- Account Details Section -->
      <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Account Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Account Code:</span>
            <div class="font-mono font-bold text-gray-900 dark:text-white">{{ $account->account_code }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Account Name:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $account->account_name }}</div>
          </div>
          @if($account->description)
            <div class="col-span-1 md:col-span-2">
              <span class="text-sm text-gray-600 dark:text-gray-400">Description:</span>
              <div class="text-sm text-gray-900 dark:text-white">{{ $account->description }}</div>
            </div>
          @endif
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Opening Balance:</span>
            <div class="font-mono font-semibold text-gray-900 dark:text-white">{{ number_format($account->opening_balance, 2) }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Current Balance:</span>
            <div class="font-mono font-bold text-primary-700 dark:text-primary-300">{{ number_format($account->current_balance, 2) }}</div>
          </div>
        </div>
      </div>

      <!-- Ledger Entries Section -->
      <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Ledger Entries</h3>
        <div class="overflow-x-auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Reference</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
                <th class="text-right">Balance</th>
              </tr>
            </thead>
            <tbody>
              @if($ledgerEntries->isEmpty())
                <tr>
                  <td colspan="6" class="text-center py-12 text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-receipt text-3xl mb-3 block opacity-30"></i>
                    <p class="text-sm font-semibold mb-1">No ledger entries found</p>
                    <p class="text-xs">Post journal entries to see ledger activity</p>
                  </td>
                </tr>
              @else
                @foreach($ledgerEntries as $entry)
                <tr>
                  <td>{{ $entry->transaction_date->format('M d, Y') }}</td>
                  <td>
                    <div class="font-semibold text-gray-900 dark:text-white">{{ $entry->description }}</div>
                  </td>
                  <td class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $entry->reference }}</td>
                  <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                    {{ $entry->debit_amount > 0 ? number_format($entry->debit_amount, 2) : '-' }}
                  </td>
                  <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                    {{ $entry->credit_amount > 0 ? number_format($entry->credit_amount, 2) : '-' }}
                  </td>
                  <td class="text-right font-mono font-bold text-primary-700 dark:text-primary-300">
                    {{ number_format($entry->balance, 2) }}
                  </td>
                </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>

      <!-- Summary Section -->
      <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Debits</span>
            <div class="font-mono font-bold text-xl text-gray-900 dark:text-white">{{ number_format($ledgerEntries->sum('debit_amount'), 2) }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Credits</span>
            <div class="font-mono font-bold text-xl text-gray-900 dark:text-white">{{ number_format($ledgerEntries->sum('credit_amount'), 2) }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Net Change</span>
            <div class="font-mono font-bold text-xl {{ $ledgerEntries->sum('debit_amount') > $ledgerEntries->sum('credit_amount') ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
              {{ number_format($ledgerEntries->sum('debit_amount') - $ledgerEntries->sum('credit_amount'), 2) }}
            </div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Current Balance</span>
            <div class="font-mono font-bold text-xl text-primary-700 dark:text-primary-300">{{ number_format($account->current_balance, 2) }}</div>
          </div>
        </div>
      </div>

      <!-- Quick Actions Section -->
      <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.journal-entries.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          <i class="fa-solid fa-plus"></i> New Journal Entry
        </a>
        <a href="{{ route('admin.accounts.show', app('App\Services\EncryptedIdService')->encrypt($account->id)) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          <i class="fa-solid fa-eye"></i> View Account Details
        </a>
      </div>
    </div>
  </div>
</div>
