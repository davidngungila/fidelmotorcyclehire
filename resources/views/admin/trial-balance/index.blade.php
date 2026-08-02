@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Trial Balance')
@section('page_title', 'Trial Balance')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Trial Balance</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">As of {{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
      <form action="{{ route('admin.trial-balance.index') }}" method="GET" class="flex items-center gap-2">
        <input type="date" name="as_of_date" value="{{ $asOfDate }}"
          class="form-input py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
        <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          Generate
        </button>
      </form>
    </div>
  </div>

  <div class="glass rounded-xl p-6">
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead>
          <tr>
            <th>Account Code</th>
            <th>Account Name</th>
            <th>Type</th>
            <th class="text-right">Debit</th>
            <th class="text-right">Credit</th>
          </tr>
        </thead>
        <tbody>
          @foreach($accounts as $account)
            <tr>
              <td class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $account->account_code }}</td>
              <td>
                <div class="font-semibold text-gray-900 dark:text-white">{{ $account->account_name }}</div>
              </td>
              <td>
                <span class="badge badge-{{ $account->account_type === 'asset' || $account->account_type === 'expense' ? 'blue' : 'green' }}">
                  {{ ucfirst($account->account_type) }}
                </span>
              </td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ $account->isDebitAccount() ? number_format($account->current_balance, 2) : '-' }}
              </td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ $account->isCreditAccount() ? number_format($account->current_balance, 2) : '-' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-scale-balanced text-3xl mb-3 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No accounts with balances found</p>
                <p class="text-xs">Post journal entries to generate trial balance</p>
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="bg-gray-50 dark:bg-gray-800 font-bold">
            <td colspan="3" class="text-right">Totals:</td>
            <td class="text-right font-mono text-gray-900 dark:text-white">{{ number_format($totalDebit, 2) }}</td>
            <td class="text-right font-mono text-gray-900 dark:text-white">{{ number_format($totalCredit, 2) }}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <div class="glass rounded-xl p-6">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Balance Status</h3>
        @if($isBalanced)
          <p class="text-green-600 dark:text-green-400 font-semibold mt-1">
            <i class="fa-solid fa-check-circle mr-2"></i>Trial balance is balanced
          </p>
        @else
          <p class="text-red-600 dark:text-red-400 font-semibold mt-1">
            <i class="fa-solid fa-exclamation-circle mr-2"></i>Trial balance is not balanced
          </p>
        @endif
      </div>
      <div class="text-right">
        <div class="text-sm text-gray-600 dark:text-gray-400">Difference:</div>
        <div class="font-mono font-bold text-xl {{ $isBalanced ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
          {{ number_format(abs($totalDebit - $totalCredit), 2) }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
