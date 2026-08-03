@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A General Ledger \u203A ' . $account->account_name)
@section('page_title', 'Ledger: ' . $account->account_name)

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $account->account_name }}</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $account->account_code }} - {{ ucfirst($account->account_type) }}</p>
    </div>
    <a href="{{ route('admin.ledger.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
      <i class="fa-solid fa-arrow-left"></i> Back to Ledger
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="glass rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Account Details</h3>
          <span class="badge badge-{{ $account->account_type === 'asset' || $account->account_type === 'expense' ? 'blue' : 'green' }}">
            {{ ucfirst($account->account_type) }}
          </span>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Account Code:</span>
            <div class="font-mono font-bold text-gray-900 dark:text-white">{{ $account->account_code }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Account Name:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $account->account_name }}</div>
          </div>
          @if($account->description)
            <div class="col-span-2">
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

      <div class="glass rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ledger Entries</h3>
          <form action="{{ route('admin.ledger.filter') }}" method="GET" class="flex items-center gap-2">
            <input type="hidden" name="account_id" value="{{ $account->id }}">
            <input type="date" name="start_date" value="{{ request('start_date') }}"
              class="form-input py-1.5 px-3 rounded-lg border	border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <input type="date" name="end_date" value="{{ request('end_date') }}"
              class="form-input py-1.5 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <button type="submit" class="px-3 py-1.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
              Filter
            </button>
            @if(request('start_date') || request('end_date'))
              <a href="{{ route('admin.ledger.show', app('App\Services\EncryptedIdService')->encrypt($account->id)) }}" class="text-sm text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">
                Clear
              </a>
            @endif
          </form>
        </div>

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
    </div>

    <div class="space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Summary</h3>
        <div class="space-y-3">
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Debits:</span>
            <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($ledgerEntries->sum('debit_amount'), 2) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Credits:</span>
            <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($ledgerEntries->sum('credit_amount'), 2) }}</span>
          </div>
          <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">Net Change:</span>
              <span class="font-mono font-bold {{ $ledgerEntries->sum('debit_amount') > $ledgerEntries->sum('credit_amount') ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ number_format($ledgerEntries->sum('debit_amount') - $ledgerEntries->sum('credit_amount'), 2) }}
              </span>
            </div>
          </div>
          <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">Current Balance:</span>
              <span class="font-mono font-bold text-primary-700 dark:text-primary-300">{{ number_format($account->current_balance, 2) }}</span>
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
          <a href="{{ route('admin.accounts.show', $account->id) }}" class="block w-full text-center px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
            <i class="fa-solid fa-eye mr-2"></i> View Account Details
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
