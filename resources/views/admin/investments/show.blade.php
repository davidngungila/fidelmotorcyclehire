@extends('layouts.admin')

@section('breadcrumb', 'Members › Investments › View')
@section('page_title', 'Investment Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
@endphp

@section('content')

<div class="space-y-6">
  @if($member)
    <div class="glass p-6">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-400 to-teal-600 text-white flex items-center justify-center text-2xl font-bold shadow-lg">
            {{ strtoupper(substr($member['full_name'] ?? 'M', 0, 1)) }}
          </div>
          <div>
            <h2 class="text-xl font-bold text-primary-900 dark:text-white">{{ $member['full_name'] ?? 'Unknown' }}</h2>
            <div class="flex items-center gap-3 mt-1">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-teal-50 dark:bg-teal-900/40 font-mono text-xs font-bold text-teal-700 dark:text-teal-300">
                <i class="fa-solid fa-id-card text-[10px]"></i>
                {{ $memberNumber }}
              </span>
              <span class="badge {{ $dashboardService->memberStatusBadge($member['status'] ?? null)['class'] }}">
                {{ $dashboardService->memberStatusBadge($member['status'] ?? null)['label'] }}
              </span>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <a href="tel:{{ $member['phone'] ?? '' }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-100 hover:bg-green-200 dark:bg-green-900/40 dark:hover:bg-green-900/60 text-green-700 dark:text-green-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-phone text-[10px]"></i> Call
          </a>
          <a href="mailto:{{ $member['email'] ?? '' }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/40 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-envelope text-[10px]"></i> Email
          </a>
          <a href="{{ route('admin.members.show', $memberNumber) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
          </a>
        </div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Gender</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['gender'] ?? '-' }}</p>
        </div>
        <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Branch</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['branch'] ?? '-' }}</p>
        </div>
        <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Phone</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['phone'] ?? '-' }}</p>
        </div>
        <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Email</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white truncate">{{ $member['email'] ?? '-' }}</p>
        </div>
        <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Occupation</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['occupation'] ?? '-' }}</p>
        </div>
        <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Employer</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white truncate">{{ $member['employer'] ?? '-' }}</p>
        </div>
      </div>
    </div>
  @endif

  @if(isset($investments) && is_array($investments) && count($investments) > 0)
    @foreach($investments as $investment)
      <div class="glass p-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
          <div>
            <h3 class="text-lg font-bold text-primary-900 dark:text-white flex items-center gap-2">
              <i class="fa-solid fa-chart-line text-teal-500"></i>
              {{ $investment['product'] ?? 'Investment' }}
            </h3>
            <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">
              Started: {{ $investment['start_date'] ?? '-' }}
            </p>
          </div>
          <div class="flex items-center gap-3">
            @php
              $status = $dashboardService->depositStatusBadge($investment['status'] ?? null);
            @endphp
            <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
          <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Amount Invested</p>
            <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt($investment['amount_invested'] ?? 0) }}</p>
          </div>
          <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Units</p>
            <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmtInt($investment['units'] ?? 0) }}</p>
          </div>
          <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Current Value</p>
            <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt($investment['current_value'] ?? 0) }}</p>
          </div>
          <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Profit/Loss</p>
            @php
              $profit = $investment['profit'] ?? 0;
              $profitClass = $profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
              $profitIcon = $profit >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down';
            @endphp
            <p class="text-lg font-black {{ $profitClass }} flex items-center gap-1">
              <i class="fa-solid {{ $profitIcon }} text-sm"></i>
              {{ $profit >= 0 ? '+' : '' }}{{ $fmt($profit) }}
            </p>
          </div>
        </div>

        <div class="flex items-center gap-4">
          <div class="flex-1">
            <div class="flex items-center justify-between mb-1">
              <span class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase">Return</span>
              @php
                $returnPct = $investment['return_percentage'] ?? 0;
              @endphp
              <span class="badge {{ $returnPct >= 0 ? 'badge-green' : 'badge-red' }}">
                {{ $returnPct >= 0 ? '+' : '' }}{{ number_format($returnPct, 2) }}%
              </span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill {{ $returnPct >= 0 ? 'bg-teal-500' : 'bg-red-500' }}" style="width: {{ min(abs($returnPct), 100) }}%"></div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  @else
    <div class="glass p-8 text-center">
      <i class="fa-solid fa-inbox text-4xl text-primary-300 dark:text-primary-700 mb-3 block"></i>
      <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">No investments found for this member</p>
    </div>
  @endif
</div>

@endsection
