@extends('layouts.admin')

@section('breadcrumb', 'Members › Investments › View')
@section('page_title', 'Investment Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
@endphp

@section('content')

<div class="space-y-6">
  <!-- Member Header -->
  @if($member)
    <div class="glass p-6">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-400 to-teal-600 text-white flex items-center justify-center text-2xl font-bold shadow-lg">
            {{ strtoupper(substr($member['name'] ?? 'M', 0, 1)) }}
          </div>
          <div>
            <h2 class="text-xl font-bold text-primary-900 dark:text-white">{{ $member['name'] ?? 'Unknown' }}</h2>
            <div class="flex items-center gap-3 mt-1">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-teal-50 dark:bg-teal-900/40 font-mono text-xs font-bold text-teal-700 dark:text-teal-300">
                <i class="fa-solid fa-id-card text-[10px]"></i>
                {{ $memberNumber }}
              </span>
              <span class="text-xs text-primary-500 dark:text-primary-400">{{ $member['email'] ?? '-' }}</span>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('admin.members.show, encryptId($memberNumber)) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Profile
          </a>
        </div>
      </div>
    </div>
  @endif

  <!-- Summary Cards -->
  @if(isset($totalInvested))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="glass p-5 rounded-2xl">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-10 h-10 rounded-xl bg-teal-100 dark:bg-teal-900/40 flex items-center justify-center">
            <i class="fa-solid fa-coins text-teal-600 dark:text-teal-400 text-sm"></i>
          </div>
          <p class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase">Total Invested</p>
        </div>
        <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $fmt($totalInvested) }}</p>
      </div>
      <div class="glass p-5 rounded-2xl">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
            <i class="fa-solid fa-chart-line text-green-600 dark:text-green-400 text-sm"></i>
          </div>
          <p class="text-xs font-bold text-green-600 dark:text-green-400 uppercase">Current Value</p>
        </div>
        <p class="text-2xl font-black text-primary-900 dark:text-white">{{ $fmt($totalCurrentValue) }}</p>
      </div>
      <div class="glass p-5 rounded-2xl">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
            <i class="fa-solid fa-arrow-trend-up text-green-600 dark:text-green-400 text-sm"></i>
          </div>
          <p class="text-xs font-bold text-green-600 dark:text-green-400 uppercase">Total Profit</p>
        </div>
        <p class="text-2xl font-black text-green-600 dark:text-green-400">
          {{ $totalProfit >= 0 ? '+' : '' }}{{ $fmt($totalProfit) }}
        </p>
      </div>
      <div class="glass p-5 rounded-2xl">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
            <i class="fa-solid fa-percent text-primary-600 dark:text-primary-400 text-sm"></i>
          </div>
          <p class="text-xs font-bold text-primary-600 dark:text-primary-400 uppercase">Overall Return</p>
        </div>
        <p class="text-2xl font-black text-primary-900 dark:text-white">{{ number_format($overallReturn, 2) }}%</p>
      </div>
    </div>
  @endif

  <!-- Investments List -->
  @if(isset($investments) && $investments->count() > 0)
    @foreach($investments as $item)
      @php
        $investment = $item->investment;
        $productName = $item->product_name;
        $duration = $item->duration;
        $profit = $item->profit;
        $profitPct = $item->profit_pct;
        $status = $item->status;
      @endphp
      <div class="glass p-6 rounded-2xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
          <div>
            <h3 class="text-lg font-bold text-primary-900 dark:text-white flex items-center gap-2">
              <i class="fa-solid fa-chart-line text-teal-500"></i>
              {{ $investment->investment_number }}
            </h3>
            <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">
              {{ $productName }}
            </p>
          </div>
          <div class="flex items-center gap-3">
            <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
          <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Amount Invested</p>
            <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt($investment->amount) }}</p>
          </div>
          <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Interest Rate</p>
            <p class="text-lg font-black text-primary-900 dark:text-white">{{ number_format($investment->interest_rate, 2) }}%</p>
          </div>
          <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Expected Return</p>
            <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt($investment->expected_return) }}</p>
          </div>
          <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Actual Return</p>
            <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt($investment->actual_return) }}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
          <div class="bg-primary-50 dark:bg-primary-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase mb-1">Investment Date</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $investment->investment_date ? $investment->investment_date->format('M d, Y') : '-' }}</p>
          </div>
          <div class="bg-primary-50 dark:bg-primary-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase mb-1">Maturity Date</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $investment->maturity_date ? $investment->maturity_date->format('M d, Y') : '-' }}</p>
          </div>
          <div class="bg-primary-50 dark:bg-primary-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase mb-1">Duration</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $duration ?: '-' }}</p>
          </div>
        </div>

        @if($investment->notes)
          <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4 mb-5">
            <p class="text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Notes</p>
            <p class="text-sm text-primary-900 dark:text-white">{{ $investment->notes }}</p>
          </div>
        @endif

        <div class="flex items-center gap-4">
          <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
              <span class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase">Profit/Loss</span>
              <span class="badge {{ $profit >= 0 ? 'badge-green' : 'badge-red' }}">
                {{ $profit >= 0 ? '+' : '' }}{{ number_format($profitPct, 2) }}%
              </span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill {{ $profit >= 0 ? 'bg-teal-500' : 'bg-red-500' }}" style="width: {{ min(abs($profitPct), 100) }}%"></div>
            </div>
            <p class="text-xs font-bold {{ $profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mt-1">
              {{ $profit >= 0 ? '+' : '' }}{{ $fmt($profit) }}
            </p>
          </div>
        </div>
      </div>
    @endforeach
  @else
    <div class="glass p-8 text-center rounded-2xl">
      <i class="fa-solid fa-inbox text-4xl text-primary-300 dark:text-primary-700 mb-3 block"></i>
      <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">No investments found for this member</p>
    </div>
  @endif
</div>

@endsection
