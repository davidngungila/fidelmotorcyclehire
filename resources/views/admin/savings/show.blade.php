@extends('layouts.admin')

@section('breadcrumb', 'Members › Savings › Details')
@section('page_title', 'Member Savings')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);

  $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? $memberNumber);
  $memberName = $member['name'] ?? ($member['Name'] ?? 'Unknown');
  $memberStatus = $dashboardService->memberStatusBadge($member['status'] ?? null);
@endphp

@section('content')

<div x-data class="space-y-6">

  <div class="glass p-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-80 h-80 rounded-full bg-gradient-to-br from-blue-200/30 to-transparent dark:from-blue-900/20 -mr-40 -mt-40"></div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 relative z-10">
      <div class="flex items-start gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center shadow-xl ring-4 ring-white dark:ring-primary-900/40 flex-shrink-0">
          <i class="fa-solid fa-piggy-bank text-2xl"></i>
        </div>
        <div class="min-w-0">
          <h1 class="text-xl lg:text-2xl font-bold text-primary-900 dark:text-white">
            {{ $memberName }}
            <span class="badge {{ $memberStatus['class'] }} ml-2 align-middle">{{ $memberStatus['label'] }}</span>
          </h1>
          <div class="flex items-center gap-3 mt-2 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-primary-100 dark:bg-primary-900/50 font-mono text-xs font-bold text-primary-700 dark:text-primary-300">
              <i class="fa-solid fa-id-card text-[10px]"></i>
              {{ $memberNo }}
            </span>
            <span class="text-xs text-primary-600 dark:text-primary-400">
              <i class="fa-solid fa-building-columns mr-1"></i> Savings Account
            </span>
          </div>
        </div>
      </div>
      <a href="{{ route('admin.savings.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800/50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold transition-colors self-start md:self-center">
        <i class="fa-solid fa-arrow-left text-[11px]"></i> Back to Savings
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="glass p-5" style="background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(59,130,246,0.02));">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
          <i class="fa-solid fa-wallet"></i>
        </div>
        <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Current Balance</p>
      </div>
      <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $fmt($balance) }}</p>
    </div>
    <div class="glass p-5" style="background: linear-gradient(135deg, rgba(34,197,94,0.1), rgba(34,197,94,0.02));">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 flex items-center justify-center">
          <i class="fa-solid fa-percent"></i>
        </div>
        <p class="text-[11px] font-bold uppercase tracking-wider text-green-600 dark:text-green-400">Interest Earned</p>
      </div>
      <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $fmt($interestEarned) }}</p>
    </div>
    <div class="glass p-5" style="background: linear-gradient(135deg, rgba(139,92,246,0.1), rgba(139,92,246,0.02));">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center">
          <i class="fa-solid fa-scale-balanced"></i>
        </div>
        <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Running Balance</p>
      </div>
      <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $fmt($runningBalance) }}</p>
    </div>
  </div>

  <div class="glass p-6">
    <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
      <i class="fa-solid fa-clock-rotate-left text-primary-500 text-xs"></i>
      Transaction History
    </h3>
    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-xl">
      <table class="data-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Description</th>
            <th class="text-right">Amount (TSh)</th>
            <th class="text-right">Running Balance</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $tx)
            @php
              $txDate = $tx['date'] ?? '-';
              $txType = strtolower((string)($tx['type'] ?? 'deposit'));
              $txAmount = (float)($tx['amount'] ?? 0);
              $txDesc = $tx['description'] ?? '-';
              $txBalance = (float)($tx['balance_after'] ?? ($tx['running_balance'] ?? 0));
              $isDeposit = str_contains($txType, 'deposit') || str_contains($txType, 'interest') || str_contains($txType, 'credit');
              $isWithdrawal = str_contains($txType, 'withdrawal') || str_contains($txType, 'debit');
            @endphp
            <tr>
              <td class="font-mono text-xs text-primary-700 dark:text-primary-300">{{ $txDate }}</td>
              <td>
                @if(str_contains($txType, 'deposit'))
                  <span class="badge badge-green"><i class="fa-solid fa-arrow-down mr-1 text-[9px]"></i> Deposit</span>
                @elseif(str_contains($txType, 'withdrawal'))
                  <span class="badge badge-red"><i class="fa-solid fa-arrow-up mr-1 text-[9px]"></i> Withdrawal</span>
                @elseif(str_contains($txType, 'interest'))
                  <span class="badge badge-blue"><i class="fa-solid fa-percent mr-1 text-[9px]"></i> Interest</span>
                @else
                  <span class="badge badge-gray">{{ ucfirst($txType) }}</span>
                @endif
              </td>
              <td class="text-xs text-primary-700 dark:text-primary-300">{{ $txDesc }}</td>
              <td class="text-right">
                <span class="text-xs font-bold {{ $isDeposit ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                  {{ $isDeposit ? '+' : '-' }}{{ $fmt(abs($txAmount)) }}
                </span>
              </td>
              <td class="text-right text-xs font-black text-primary-900 dark:text-white">{{ $fmt($txBalance) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-16 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-file-circle-exclamation text-4xl mb-4 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No records found</p>
                <p class="text-xs">No savings transactions recorded yet</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

@endsection
