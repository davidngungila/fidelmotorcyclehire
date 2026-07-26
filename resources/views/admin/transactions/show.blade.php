@extends('layouts.admin')

@section('breadcrumb', 'Members › Transactions › Details')
@section('page_title', 'Transaction Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
@endphp

@section('content')

<div class="space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.transactions.index') }}" 
       class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to Transactions</span>
    </a>
  </div>

  <div class="glass p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
          {{ $member['name'] ?? $member['Name'] ?? 'Unknown Member' }}
        </h2>
        <p class="text-sm text-gray-500">
          <i class="fa-solid fa-id-card mr-1.5"></i> {{ $memberCode }}
        </p>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('admin.members.show', encryptId($memberCode)) }}" 
           class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
          <i class="fa-solid fa-user mr-1.5"></i> View Member Profile
        </a>
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th>Date</th>
            <th>Transaction Type</th>
            <th>Reference No</th>
            <th class="text-right">Amount</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $index => $txn)
            <tr class="group">
              <td class="text-xs text-gray-500">{{ $index + 1 }}</td>
              <td class="text-sm font-medium">{{ $txn['date'] ?? '-' }}</td>
              <td>
                <span class="badge @if(strtolower($txn['transactiontype'] ?? '') === 'deposit') badge-green @elseif(strtolower($txn['transactiontype'] ?? '') === 'withdrawal') badge-red @else badge-blue @endif text-[10px]">
                  {{ $txn['transactiontype'] ?? '-' }}
                </span>
              </td>
              <td class="text-sm font-mono">{{ $txn['referenceno'] ?? '-' }}</td>
              <td class="text-sm font-semibold text-right @if(($txn['amount'] ?? 0) < 0) text-red-600 @else text-green-600 @endif">
                {{ $fmt($txn['amount'] ?? 0) }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-8 text-gray-500">
                <i class="fa-solid fa-inbox text-3xl mb-2"></i>
                <p>No transactions found for this member</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(!empty($transactions))
      <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-500">
            Total Transactions: {{ count($transactions) }}
          </div>
          <div class="text-sm font-semibold">
            Total Amount: 
            <span class="text-green-600">
              {{ $fmt(collect($transactions)->sum('amount')) }}
            </span>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>

@endsection
