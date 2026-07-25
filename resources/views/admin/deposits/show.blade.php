@extends('layouts.admin')

@section('breadcrumb', 'Members › Deposits › Details')
@section('page_title', 'Deposit Certificate Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);

  $certNo = $deposit['certificate_number'] ?? ($deposit['CertificateNumber'] ?? $certificateNumber);
  $product = $deposit['product'] ?? ($deposit['Product'] ?? '-');
  $statusBadge = $dashboardService->depositStatusBadge($deposit['status'] ?? ($deposit['Status'] ?? null));
  $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? '-');
  $memberName = $member['name'] ?? ($member['Name'] ?? 'Unknown');
  $memberStatus = $dashboardService->memberStatusBadge($member['status'] ?? null);
@endphp

@section('content')

<div x-data class="space-y-6">

  <div class="glass p-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-80 h-80 rounded-full bg-gradient-to-br from-purple-200/30 to-transparent dark:from-purple-900/20 -mr-40 -mt-40"></div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 relative z-10">
      <div class="flex items-start gap-4">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 text-white flex items-center justify-center shadow-xl ring-4 ring-white dark:ring-primary-900/40 flex-shrink-0">
          <i class="fa-solid fa-money-bill-trend-up text-2xl"></i>
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
              <i class="fa-solid fa-money-bill-trend-up mr-1"></i> Deposit Certificate: {{ $certNo }}
            </span>
          </div>
        </div>
      </div>
      <a href="{{ route('admin.deposits.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800/50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold transition-colors self-start md:self-center">
        <i class="fa-solid fa-arrow-left text-[11px]"></i> Back to Deposits
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div class="glass p-5" style="background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(59,130,246,0.02));">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
          <i class="fa-solid fa-money-bill"></i>
        </div>
        <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Principal Amount</p>
      </div>
      <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $fmt($amount) }}</p>
    </div>
    <div class="glass p-5" style="background: linear-gradient(135deg, rgba(139,92,246,0.1), rgba(139,92,246,0.02));">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center">
          <i class="fa-solid fa-percent"></i>
        </div>
        <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Interest Rate</p>
      </div>
      <p class="text-2xl font-black text-primary-900 dark:text-white">{{ number_format($interestRate, 2) }}%</p>
    </div>
    <div class="glass p-5" style="background: linear-gradient(135deg, rgba(34,197,94,0.1), rgba(34,197,94,0.02));">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 flex items-center justify-center">
          <i class="fa-solid fa-coins"></i>
        </div>
        <p class="text-[11px] font-bold uppercase tracking-wider text-green-600 dark:text-green-400">Interest Amount</p>
      </div>
      <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $fmt($interest) }}</p>
    </div>
    <div class="glass p-5" style="background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(59,130,246,0.02));">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
          <i class="fa-solid fa-chart-line"></i>
        </div>
        <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Current Value</p>
      </div>
      <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $fmt($currentValue) }}</p>
    </div>
  </div>

  <div class="glass p-6">
    <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
      <i class="fa-solid fa-clock-rotate-left text-purple-500 text-xs"></i>
      Transaction History
    </h3>
    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-xl">
      <table class="data-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Account</th>
            <th class="text-right">Amount (TSh)</th>
            <th class="text-right">Balance</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions ?? [] as $tx)
            @php
              $txDate = $tx['date'] ?? '-';
              $txType = strtolower((string)($tx['type'] ?? 'credit'));
              $txAccount = $tx['account'] ?? '-';
              $txAmount = (float)($tx['amount'] ?? 0);
              $txBalance = (float)($tx['balance'] ?? 0);
              $isCredit = str_contains($txType, 'credit') || str_contains($txType, 'deposit');
            @endphp
            <tr>
              <td class="font-mono text-xs text-primary-700 dark:text-primary-300">{{ $txDate }}</td>
              <td>
                @if($isCredit)
                  <span class="badge badge-green"><i class="fa-solid fa-arrow-down mr-1 text-[9px]"></i> Credit</span>
                @else
                  <span class="badge badge-red"><i class="fa-solid fa-arrow-up mr-1 text-[9px]"></i> Debit</span>
                @endif
              </td>
              <td class="text-xs text-primary-700 dark:text-primary-300">{{ $txAccount }}</td>
              <td class="text-right">
                <span class="text-xs font-bold {{ $isCredit ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                  {{ $isCredit ? '+' : '-' }}{{ $fmt(abs($txAmount)) }}
                </span>
              </td>
              <td class="text-right text-xs font-black text-primary-900 dark:text-white">{{ $fmt($txBalance) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-16 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-file-circle-exclamation text-4xl mb-4 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No records found</p>
                <p class="text-xs">No deposit transactions recorded yet</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

@endsection
