@extends('layouts.admin')

@section('breadcrumb', 'Members › Loans › Details')
@section('page_title', 'Loan Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);

  $loanNo = $loan['loan_number'] ?? ($loan['LoanNumber'] ?? $loanNumber);
  $product = $loan['loan_product'] ?? ($loan['LoanProduct'] ?? '-');
  $statusBadge = $dashboardService->loanStatusBadge($loan['status'] ?? ($loan['Status'] ?? null));
  $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? '-');
  $memberName = $member['name'] ?? ($member['Name'] ?? 'Unknown');
  $memberPhone = $member['phone'] ?? ($member['Phone'] ?? '-');
  $memberEmail = $member['email'] ?? ($member['Email'] ?? '-');
  $memberBranch = $member['branch'] ?? ($member['Branch'] ?? '-');
@endphp

@section('content')

<div x-data="loanShow()" class="space-y-6">

  <div class="glass p-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-80 h-80 rounded-full bg-gradient-to-br from-orange-200/30 to-transparent dark:from-orange-900/20 -mr-40 -mt-40"></div>

    <div class="flex flex-col lg:flex-row lg:items-start gap-6 relative z-10">
      <div class="flex-shrink-0 w-full lg:w-auto">
        <div class="flex items-start gap-5">
          <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center shadow-xl ring-4 ring-white dark:ring-primary-900/40 flex-shrink-0">
            <i class="fa-solid fa-hand-holding-dollar text-3xl"></i>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2 mb-2 flex-wrap">
              <p class="text-[10px] font-bold uppercase tracking-wider text-orange-500">Loan Number</p>
              <span class="badge badge-orange" style="background:#fff7ed;color:#c2410c;">
                <i class="fa-solid fa-hashtag text-[9px] mr-1"></i> Mono
              </span>
            </div>
            <h1 class="font-mono text-3xl lg:text-4xl font-black text-primary-900 dark:text-white tracking-wider break-all">{{ $loanNo }}</h1>
            <div class="flex items-center gap-2 mt-3 flex-wrap">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-xs font-bold">
                <i class="fa-solid fa-tag text-[10px]"></i>
                {{ $product }}
              </span>
              <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full lg:ml-auto lg:w-auto lg:max-w-md">
        <div class="p-4 rounded-2xl bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-900/50">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-lg font-bold flex-shrink-0 shadow-sm">
              {{ strtoupper(substr($memberName, 0, 1) ?? 'M') }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between gap-2 flex-wrap">
                <p class="text-sm font-bold text-primary-900 dark:text-white truncate">{{ $memberName }}</p>
                <a href="{{ route('admin.members.show', encryptId($memberNo)) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-white dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 text-[10px] font-bold hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors">
                  <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                  View Profile
                </a>
              </div>
              <div class="mt-2 space-y-1">
                <div class="flex items-center gap-2 text-[11px]">
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-white dark:bg-primary-900/50 font-mono font-bold text-primary-700 dark:text-primary-300">
                    <i class="fa-solid fa-id-card text-[9px] opacity-60"></i>
                    {{ $memberNo }}
                  </span>
                  <span class="text-primary-500 dark:text-primary-400">
                    <i class="fa-solid fa-location-dot text-[9px]"></i> {{ $memberBranch }}
                  </span>
                </div>
                <div class="flex items-center gap-3 text-[11px] text-primary-600 dark:text-primary-400 flex-wrap">
                  @if($memberPhone !== '-')
                    <span><i class="fa-solid fa-phone text-[9px]"></i> {{ $memberPhone }}</span>
                  @endif
                  @if($memberEmail !== '-')
                    <span class="truncate max-w-[180px]"><i class="fa-solid fa-envelope text-[9px]"></i> {{ $memberEmail }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        <a href="{{ route('admin.loans.index') }}" class="mt-3 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800/50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold transition-colors">
          <i class="fa-solid fa-arrow-left text-[11px]"></i> Back to Loans
        </a>
      </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50">
      <div class="p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500 dark:text-primary-400 mb-1"><i class="fa-solid fa-sack-dollar mr-1"></i>Loan Amount</p>
        <p class="text-sm font-black text-primary-900 dark:text-white">{{ $fmt($loanAmount) }}</p>
      </div>
      <div class="p-3 rounded-xl bg-orange-50 dark:bg-orange-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-orange-600 dark:text-orange-400 mb-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>Outstanding</p>
        <p class="text-sm font-black text-orange-700 dark:text-orange-400">{{ $fmt($outstanding) }}</p>
      </div>
      <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-green-600 dark:text-green-400 mb-1"><i class="fa-solid fa-circle-check mr-1"></i>Paid</p>
        <p class="text-sm font-black text-green-700 dark:text-green-400">{{ $fmt($paidAmount) }}</p>
      </div>
      <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1"><i class="fa-solid fa-percent mr-1"></i>Interest Rate</p>
        <p class="text-sm font-black text-blue-700 dark:text-blue-400">{{ number_format($interestRate, 2) }}%</p>
      </div>
      <div class="p-3 rounded-xl bg-purple-50 dark:bg-purple-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 mb-1"><i class="fa-solid fa-calendar-check mr-1"></i>Installment</p>
        <p class="text-sm font-black text-purple-700 dark:text-purple-400">{{ $fmt($installment) }}/mo</p>
      </div>
      <div class="p-3 rounded-xl bg-yellow-50 dark:bg-yellow-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-yellow-700 dark:text-yellow-400 mb-1"><i class="fa-solid fa-plane-departure mr-1"></i>Disbursement</p>
        <p class="text-sm font-black text-yellow-800 dark:text-yellow-400 font-mono text-xs mt-1">{{ $disbursementDate }}</p>
      </div>
      <div class="p-3 rounded-xl bg-pink-50 dark:bg-pink-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-pink-600 dark:text-pink-400 mb-1"><i class="fa-solid fa-flag-checkered mr-1"></i>Maturity</p>
        <p class="text-sm font-black text-pink-700 dark:text-pink-400 font-mono text-xs mt-1">{{ $maturityDate }}</p>
      </div>
    </div>

    <div class="mt-6 p-4 rounded-2xl bg-primary-50 dark:bg-primary-900/30">
      <div class="flex items-center justify-between mb-2">
        <p class="text-xs font-bold text-primary-700 dark:text-primary-300 flex items-center gap-1.5">
          <i class="fa-solid fa-chart-simple text-[11px]"></i> Repayment Progress
        </p>
        <span class="font-mono text-xs font-black text-primary-900 dark:text-white">{{ number_format($progress, 2) }}%</span>
      </div>
      <div class="progress-bar h-2.5">
        <div class="progress-fill" style="width: {{ $progress }}%"></div>
      </div>
    </div>
  </div>

  <div class="glass p-1.5 rounded-2xl">
    <nav class="flex items-center gap-1 p-1 overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden">
      <template x-for="tab in tabs" :key="tab.id">
        <button @click="activeTab = tab.id; updateHash(tab.id)"
                :class="[
                  activeTab === tab.id
                    ? 'bg-primary-600 text-white shadow-md'
                    : 'text-primary-700 dark:text-primary-300 hover:bg-primary-100 dark:hover:bg-primary-900/40',
                  'flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap'
                ]">
          <i :class="tab.icon" class="text-[11px]"></i>
          <span x-text="tab.label"></span>
        </button>
      </template>
    </nav>
  </div>

  <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="glass p-6">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
          <i class="fa-solid fa-file-lines text-orange-500 text-xs"></i> Loan Information
        </h3>
        <div class="space-y-4">
          <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Loan Number</span>
            <span class="font-mono text-sm font-bold text-primary-900 dark:text-white">{{ $loanNo }}</span>
          </div>
          <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Product Type</span>
            <span class="text-sm font-bold text-primary-900 dark:text-white">{{ $product }}</span>
          </div>
          <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Principal Amount</span>
            <span class="text-sm font-bold text-primary-900 dark:text-white">{{ $fmt($loanAmount) }}</span>
          </div>
          <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Interest Rate</span>
            <span class="text-sm font-bold text-primary-900 dark:text-white">{{ number_format($interestRate, 2) }}% p.a.</span>
          </div>
          <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Monthly Installment</span>
            <span class="text-sm font-bold text-primary-900 dark:text-white">{{ $fmt($installment) }}</span>
          </div>
          <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Disbursement Date</span>
            <span class="text-sm font-mono font-bold text-primary-900 dark:text-white">{{ $disbursementDate }}</span>
          </div>
          <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Maturity Date</span>
            <span class="text-sm font-mono font-bold text-primary-900 dark:text-white">{{ $maturityDate }}</span>
          </div>
          <div class="flex items-start justify-between">
            <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Status</span>
            <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
          </div>
        </div>
      </div>

      <div class="glass p-6">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
          <i class="fa-solid fa-chart-pie text-green-500 text-xs"></i> Repayment Summary
        </h3>
        <div class="space-y-5">
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">Paid</span>
              <span class="text-xs font-black text-green-600 dark:text-green-400">{{ $fmt($paidAmount) }}</span>
            </div>
            <div class="h-3 rounded-full bg-primary-100 dark:bg-primary-900/40 overflow-hidden">
              <div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full" style="width: {{ $progress }}%"></div>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-3 pt-4 border-t border-primary-100 dark:border-primary-900/50">
            <div class="text-center p-3 rounded-xl bg-green-50 dark:bg-green-900/30">
              <p class="text-[10px] font-bold uppercase tracking-wider text-green-600 dark:text-green-400 mb-1">Paid</p>
              <p class="text-sm font-black text-green-700 dark:text-green-400">{{ $fmt($paidAmount) }}</p>
            </div>
            <div class="text-center p-3 rounded-xl bg-orange-50 dark:bg-orange-900/30">
              <p class="text-[10px] font-bold uppercase tracking-wider text-orange-600 dark:text-orange-400 mb-1">Remaining</p>
              <p class="text-sm font-black text-orange-700 dark:text-orange-400">{{ $fmt($outstanding) }}</p>
            </div>
            <div class="text-center p-3 rounded-xl bg-blue-50 dark:bg-blue-900/30">
              <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1">Total</p>
              <p class="text-sm font-black text-blue-700 dark:text-blue-400">{{ $fmt($loanAmount) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div x-show="activeTab === 'schedule'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-6">
      <div class="flex items-center justify-between mb-5">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
          <i class="fa-solid fa-calendar-days text-blue-500 text-xs"></i>
          Repayment Schedule
          <span class="badge badge-blue ml-2">{{ count($repaymentSchedule) }} Installments</span>
        </h3>
      </div>

      <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-xl">
        <table class="data-table">
          <thead>
            <tr>
              <th class="w-16">#</th>
              <th>Due Date</th>
              <th class="text-right">Amount</th>
              <th class="text-right">Principal</th>
              <th class="text-right">Interest</th>
              <th class="text-right">Balance After</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($repaymentSchedule as $item)
              <tr>
                <td class="font-mono text-xs font-bold text-primary-500 dark:text-primary-400">{{ str_pad((string)$item['installment_no'], 3, '0', STR_PAD_LEFT) }}</td>
                <td class="font-mono text-xs text-primary-700 dark:text-primary-300">{{ $item['due_date'] }}</td>
                <td class="text-right font-bold text-primary-900 dark:text-white text-xs">{{ $fmt($item['amount']) }}</td>
                <td class="text-right text-xs text-primary-700 dark:text-primary-300">{{ $fmt($item['principal']) }}</td>
                <td class="text-right text-xs text-primary-700 dark:text-primary-300">{{ $fmt($item['interest']) }}</td>
                <td class="text-right font-bold text-orange-600 dark:text-orange-400 text-xs">{{ $fmt($item['balance_after']) }}</td>
                <td>
                  @if($item['status'] === 'Paid')
                    <span class="badge badge-green"><i class="fa-solid fa-check mr-1 text-[9px]"></i> Paid</span>
                  @else
                    <span class="badge badge-yellow"><i class="fa-solid fa-clock mr-1 text-[9px]"></i> Pending</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-12 text-primary-500 dark:text-primary-400">
                  <i class="fa-solid fa-file-circle-exclamation text-3xl mb-3 block opacity-30"></i>
                  <p class="text-sm font-semibold mb-1">No records found</p>
                  <p class="text-xs">Repayment schedule not available</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-6">
      <div class="flex items-center justify-between mb-5">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
          <i class="fa-solid fa-clock-rotate-left text-green-500 text-xs"></i>
          Repayment History
          <span class="badge badge-green ml-2">{{ count($repaymentHistory) }} Payments</span>
        </h3>
      </div>

      <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-xl">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Reference</th>
              <th class="text-right">Amount Paid</th>
              <th>Method</th>
              <th class="text-right">Balance After</th>
            </tr>
          </thead>
          <tbody>
            @forelse($repaymentHistory as $item)
              <tr>
                <td class="font-mono text-xs text-primary-700 dark:text-primary-300">{{ $item['payment_date'] }}</td>
                <td>
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-primary-50 dark:bg-primary-900/30 font-mono text-[11px] font-bold text-primary-700 dark:text-primary-300">
                    {{ $item['transaction_ref'] }}
                  </span>
                </td>
                <td class="text-right font-bold text-green-600 dark:text-green-400 text-xs">+{{ $fmt($item['amount']) }}</td>
                <td><span class="badge badge-blue">{{ $item['method'] }}</span></td>
                <td class="text-right font-bold text-primary-900 dark:text-white text-xs">{{ $fmt($item['balance_after']) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-12 text-primary-500 dark:text-primary-400">
                  <i class="fa-solid fa-file-circle-exclamation text-3xl mb-3 block opacity-30"></i>
                  <p class="text-sm font-semibold mb-1">No records found</p>
                  <p class="text-xs">No repayment payments recorded yet</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div x-show="activeTab === 'statement'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-6">
      <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
        <div>
          <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-purple-500 text-xs"></i>
            Loan Account Statement
          </h3>
          <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">Member: <span class="font-semibold">{{ $memberName }} ({{ $memberNo }})</span></p>
        </div>
        <div class="flex items-center gap-2">
          <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-print text-[11px]"></i> Print
          </button>
          <button class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/40 dark:hover:bg-purple-900/60 text-purple-700 dark:text-purple-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-file-pdf text-[11px]"></i> Export PDF
          </button>
        </div>
      </div>

      <div class="border-2 border-primary-200 dark:border-primary-900/60 rounded-2xl overflow-hidden">
        <div class="p-5 bg-gradient-to-r from-primary-50 to-white dark:from-primary-900/40 dark:to-primary-900/20 border-b border-primary-200 dark:border-primary-900/60">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <p class="font-black text-lg text-primary-900 dark:text-white">FEEDTAN DIGITAL</p>
              <p class="text-[10px] text-primary-500 dark:text-primary-400 font-semibold tracking-wide">LOAN ACCOUNT STATEMENT</p>
            </div>
            <div class="text-right">
              <p class="text-[10px] text-primary-500 dark:text-primary-400 font-bold uppercase">Statement Date</p>
              <p class="font-mono text-sm font-bold text-primary-900 dark:text-white">{{ date('Y-m-d') }}</p>
            </div>
          </div>
          <div class="grid grid-cols-3 gap-4 mt-4 pt-4 border-t border-primary-200/60 dark:border-primary-900/40">
            <div>
              <p class="text-[9px] font-bold uppercase tracking-wider text-primary-500 dark:text-primary-400 mb-0.5">Account</p>
              <p class="font-mono text-xs font-bold text-primary-900 dark:text-white">{{ $loanNo }}</p>
            </div>
            <div>
              <p class="text-[9px] font-bold uppercase tracking-wider text-primary-500 dark:text-primary-400 mb-0.5">Product</p>
              <p class="text-xs font-bold text-primary-900 dark:text-white">{{ $product }}</p>
            </div>
            <div>
              <p class="text-[9px] font-bold uppercase tracking-wider text-primary-500 dark:text-primary-400 mb-0.5">Status</p>
              <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
            </div>
          </div>
        </div>

        <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden">
          <table class="data-table !border-0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Reference</th>
                <th>Description</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
                <th class="text-right">Balance</th>
              </tr>
            </thead>
            <tbody>
              @forelse($loanStatement as $item)
                <tr>
                  <td class="font-mono text-xs text-primary-700 dark:text-primary-300">{{ $item['date'] }}</td>
                  <td>
                    @if($item['type'] === 'Disbursement')
                      <span class="badge badge-orange" style="background:#fff7ed;color:#c2410c;">{{ $item['type'] }}</span>
                    @else
                      <span class="badge badge-green">{{ $item['type'] }}</span>
                    @endif
                  </td>
                  <td class="font-mono text-[11px] font-bold text-primary-700 dark:text-primary-300">{{ $item['reference'] }}</td>
                  <td class="text-xs text-primary-700 dark:text-primary-300">{{ $item['description'] }}</td>
                  <td class="text-right text-xs font-mono font-bold text-orange-600 dark:text-orange-400">
                    {{ (float)$item['debit'] > 0 ? $fmt($item['debit']) : '-' }}
                  </td>
                  <td class="text-right text-xs font-mono font-bold text-green-600 dark:text-green-400">
                    {{ (float)$item['credit'] > 0 ? $fmt($item['credit']) : '-' }}
                  </td>
                  <td class="text-right text-xs font-mono font-black text-primary-900 dark:text-white">{{ $fmt($item['balance']) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-12 text-primary-500 dark:text-primary-400">
                    <i class="fa-solid fa-file-circle-exclamation text-3xl mb-3 block opacity-30"></i>
                    <p class="text-sm font-semibold mb-1">No records found</p>
                    <p class="text-xs">Statement data not available</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script>
  function loanShow() {
    return {
      activeTab: 'overview',
      tabs: [
        { id: 'overview', label: 'Overview', icon: 'fa-solid fa-circle-info' },
        { id: 'schedule', label: 'Repayment Schedule', icon: 'fa-solid fa-calendar-days' },
        { id: 'history', label: 'Repayment History', icon: 'fa-solid fa-clock-rotate-left' },
        { id: 'statement', label: 'Loan Statement', icon: 'fa-solid fa-file-invoice-dollar' },
      ],
      init() {
        const hash = window.location.hash.replace('#tab-', '');
        const validTabs = this.tabs.map(t => t.id);
        if (hash && validTabs.includes(hash)) {
          this.activeTab = hash;
        }
      },
      updateHash(tabId) {
        if (history.pushState) {
          history.pushState(null, null, '#tab-' + tabId);
        } else {
          window.location.hash = 'tab-' + tabId;
        }
      }
    };
  }
</script>
@endpush
