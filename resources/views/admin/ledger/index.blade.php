@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A General Ledger')
@section('page_title', 'General Ledger')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">General Ledger</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">View ledger entries for all accounts</p>
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
            <th class="text-right">Current Balance</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($accounts as $account)
            <tr>
              <td class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $account->account_code }}</td>
              <td>
                <div class="font-semibold text-gray-900 dark:text-white">{{ $account->account_name }}</div>
                @if($account->description)
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($account->description, 40) }}</div>
                @endif
              </td>
              <td>
                <span class="badge badge-{{ $account->account_type === 'asset' || $account->account_type === 'expense' ? 'blue' : 'green' }}">
                  {{ ucfirst($account->account_type) }}
                </span>
              </td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ number_format($account->current_balance, 2) }}
              </td>
              <td class="text-right">
                <a href="{{ route('admin.ledger.show', $account->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-semibold transition-all">
                  <i class="fa-solid fa-eye"></i> View Ledger
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-book text-3xl mb-3 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No accounts found</p>
                <p class="text-xs">Create accounts to view their ledger entries</p>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
