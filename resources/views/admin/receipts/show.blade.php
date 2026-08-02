@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Receipts \u203A View Receipt')
@section('page_title', 'View Receipt')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Receipt #{{ $receipt->entry_number }}</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $receipt->entry_date->format('F d, Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.receipts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Receipt Details</h3>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Receipt Number:</span>
            <div class="font-mono font-bold text-gray-900 dark:text-white">{{ $receipt->entry_number }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Receipt Date:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $receipt->entry_date->format('F d, Y') }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
            <div>
              @if($receipt->status === 'posted')
                <span class="badge badge-green">Posted</span>
              @elseif($receipt->status === 'draft')
                <span class="badge badge-yellow">Draft</span>
              @else
                <span class="badge badge-red">Voided</span>
              @endif
            </div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Reference:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $receipt->reference }}</div>
          </div>
          <div class="col-span-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Description:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $receipt->description }}</div>
          </div>
          @if($receipt->posted_at)
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Posted At:</span>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $receipt->posted_at->format('M d, Y H:i') }}</div>
            </div>
          @endif
        </div>
      </div>

      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Receipt Lines</h3>
        <div class="overflow-x-auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>Account</th>
                <th>Description</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
              </tr>
            </thead>
            <tbody>
              @foreach($receipt->lines as $line)
                <tr>
                  <td>
                    <div class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $line->account->account_code }}</div>
                    <div class="font-semibold text-gray-900 dark:text-white">{{ $line->account->account_name }}</div>
                  </td>
                  <td class="text-sm text-gray-600 dark:text-gray-400">{{ $line->description }}</td>
                  <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                    {{ $line->debit_amount > 0 ? number_format($line->debit_amount, 2) : '-' }}
                  </td>
                  <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                    {{ $line->credit_amount > 0 ? number_format($line->credit_amount, 2) : '-' }}
                  </td>
                </tr>
              @endforeach
              <tr class="bg-gray-50 dark:bg-gray-800 font-bold">
                <td colspan="2" class="text-right">Totals:</td>
                <td class="text-right font-mono text-gray-900 dark:text-white">{{ number_format($receipt->total_debit, 2) }}</td>
                <td class="text-right font-mono text-gray-900 dark:text-white">{{ number_format($receipt->total_credit, 2) }}</td>
              </tr>
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
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Amount:</span>
            <span class="font-mono font-bold text-2xl text-primary-700 dark:text-primary-300">{{ number_format($receipt->total_debit, 2) }}</span>
          </div>
        </div>
      </div>

      @if($receipt->createdBy)
        <div class="glass rounded-xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Created By</h3>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
              <span class="text-primary-700 dark:text-primary-300 font-semibold">{{ strtoupper(substr($receipt->createdBy->name, 0, 1)) }}</span>
            </div>
            <div>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $receipt->createdBy->name }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ $receipt->created_at->format('M d, Y H:i') }}</div>
            </div>
          </div>
        </div>
      @endif

      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="space-y-2">
          <a href="{{ route('admin.journal-entries.create') }}" class="block w-full text-center px-4 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
            <i class="fa-solid fa-plus mr-2"></i> New Journal Entry
          </a>
          <a href="{{ route('admin.ledger.index') }}" class="block w-full text-center px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
            <i class="fa-solid fa-book mr-2"></i> View Ledger
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
