@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Chart of Accounts \u203A Account Details')
@section('page_title', 'Account Details')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Account Details</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">View account information and transactions</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-600 hover:bg-gray-500 text-white text-sm font-semibold transition-all">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
      @if(!$account->is_system_account)
        <a href="{{ route('admin.accounts.edit', $account->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          <i class="fa-solid fa-edit"></i> Edit
        </a>
      @endif
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Account Details -->
    <div class="lg:col-span-2 space-y-6">
      <div class="glass rounded-xl p-6">
        <div class="flex items-start justify-between mb-6">
          <div>
            <div class="flex items-center gap-3">
              <span class="font-mono text-lg font-bold text-primary-700 dark:text-primary-300">{{ $account->account_code }}</span>
              @if($account->is_system_account)
                <span class="badge badge-purple">System</span>
              @elseif($account->is_active)
                <span class="badge badge-green">Active</span>
              @else
                <span class="badge badge-red">Inactive</span>
              @endif
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-2">{{ $account->account_name }}</h2>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Account Type</label>
            <p class="font-semibold text-gray-900 dark:text-white mt-1">
              <span class="badge badge-{{ $account->account_type === 'asset' || $account->account_type === 'expense' ? 'blue' : 'green' }}">
                {{ ucfirst($account->account_type) }}
              </span>
            </p>
          </div>
          <div>
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Subtype</label>
            <p class="font-semibold text-gray-900 dark:text-white mt-1">
              {{ $account->account_subtype ? str_replace('_', ' ', ucfirst($account->account_subtype)) : '-' }}
            </p>
          </div>
          <div>
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Opening Balance</label>
            <p class="font-mono font-bold text-gray-900 dark:text-white mt-1">{{ number_format($account->opening_balance, 2) }}</p>
          </div>
          <div>
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Current Balance</label>
            <p class="font-mono font-bold text-primary-700 dark:text-primary-300 mt-1 text-lg">{{ number_format($account->current_balance, 2) }}</p>
          </div>
          <div>
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Level</label>
            <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $account->level }}</p>
          </div>
          <div>
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Parent Account</label>
            <p class="font-semibold text-gray-900 dark:text-white mt-1">
              @if($account->parentAccount)
                {{ $account->parentAccount->account_code }} - {{ $account->parentAccount->account_name }}
              @else
                -
              @endif
            </p>
          </div>
        </div>

        @if($account->description)
          <div class="mt-6">
            <label class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Description</label>
            <p class="text-gray-700 dark:text-gray-300 mt-1">{{ $account->description }}</p>
          </div>
        @endif
      </div>

      <!-- Journal Entry Lines -->
      @if($account->journalEntryLines && $account->journalEntryLines->count() > 0)
        <div class="glass rounded-xl p-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Recent Transactions</h3>
          <div class="overflow-x-auto">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Description</th>
                  <th class="text-right">Debit</th>
                  <th class="text-right">Credit</th>
                </tr>
              </thead>
              <tbody>
                @foreach($account->journalEntryLines->take(10) as $line)
                  <tr>
                    <td>{{ $line->journalEntry->entry_date->format('Y-m-d') }}</td>
                    <td>{{ $line->description }}</td>
                    <td class="text-right font-mono">
                      @if($line->debit_amount > 0)
                        {{ number_format($line->debit_amount, 2) }}
                      @else
                        -
                      @endif
                    </td>
                    <td class="text-right font-mono">
                      @if($line->credit_amount > 0)
                        {{ number_format($line->credit_amount, 2) }}
                      @else
                        -
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif
    </div>

    <!-- Child Accounts -->
    <div class="lg:col-span-1">
      @if($account->childAccounts && $account->childAccounts->count() > 0)
        <div class="glass rounded-xl p-6">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Child Accounts</h3>
          <div class="space-y-3">
            @foreach($account->childAccounts as $child)
              <a href="{{ route('admin.accounts.show', app('App\Services\EncryptedIdService')->encrypt($child->id)) }}" class="block p-3 rounded-lg bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center justify-between">
                  <div>
                    <span class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $child->account_code }}</span>
                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $child->account_name }}</p>
                  </div>
                  <span class="font-mono text-sm text-gray-600 dark:text-gray-400">{{ number_format($child->current_balance, 2) }}</span>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
