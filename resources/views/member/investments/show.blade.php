@extends('layouts.member')

@section('breadcrumb', 'Investment Details')
@section('page_title', 'Investment Details')

@php
    function fmtTshInv($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }
    
    // Handle status object
    if (is_array($status)) {
        $statusClass = $status['class'] ?? 'badge-gray';
        $statusLabel = $status['label'] ?? 'Unknown';
    } elseif (is_object($status)) {
        $statusClass = $status->class ?? 'badge-gray';
        $statusLabel = $status->label ?? 'Unknown';
    } else {
        $statusLabel = ucfirst($status ?? 'Unknown');
        $statusClass = match(strtolower($status ?? '')) {
            'active' => 'badge-green',
            'matured', 'completed' => 'badge-blue',
            'pending' => 'badge-yellow',
            default => 'badge-gray',
        };
    }
    
    $profitBadgeClass = $profit >= 0 ? 'badge-green' : 'badge-red';
    $profitPrefix = $profit >= 0 ? '+' : '';
    $profitFillClass = $profit >= 0 ? 'bg-teal-500' : 'bg-red-500';
    $profitTextClass = $profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
@endphp

@section('content')

<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('member.investments.index') }}" 
       class="inline-flex items-center gap-2 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-white transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        Back to My Investments
    </a>

    <!-- Investment Header -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-primary-900 dark:text-white flex items-center gap-3">
                    <i class="fa-solid fa-chart-line text-teal-500"></i>
                    {{ $investment->investment_number }}
                </h1>
                <p class="text-sm text-primary-500 dark:text-primary-400 mt-1">
                    {{ $product_name }}
                </p>
            </div>
            <span class="badge {{ $statusClass }} flex-shrink-0">
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    <!-- Investment Details Grid -->
    <div class="glass p-6 rounded-2xl">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-primary-50 dark:bg-primary-900/30 rounded-xl p-4">
                <p class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase mb-1">Amount Invested</p>
                @php
                    $amountValue = $investment->amount ?? 0;
                @endphp
                <p class="text-lg font-black text-primary-900 dark:text-white">{{ fmtTshInv($amountValue) }}</p>
            </div>
            <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
                <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Interest Rate</p>
                @php
                    $interestRateValue = $investment->interest_rate ?? 0;
                @endphp
                <p class="text-lg font-black text-primary-900 dark:text-white">{{ number_format($interestRateValue, 2) }}%</p>
            </div>
            <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
                <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Expected Return</p>
                @php
                    $expectedReturnValue = $investment->expected_return ?? 0;
                @endphp
                <p class="text-lg font-black text-primary-900 dark:text-white">{{ fmtTshInv($expectedReturnValue) }}</p>
            </div>
            <div class="bg-teal-50 dark:bg-teal-900/30 rounded-xl p-4">
                <p class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase mb-1">Actual Return</p>
                @php
                    $actualReturnValue = $investment->actual_return ?? 0;
                @endphp
                <p class="text-lg font-black text-primary-900 dark:text-white">{{ fmtTshInv($actualReturnValue) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-primary-50 dark:bg-primary-900/30 rounded-xl p-4">
                <p class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase mb-1">Investment Date</p>
                @php
                    $investmentDate = $investment->investment_date ? $investment->investment_date->format('M d, Y') : '-';
                @endphp
                <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $investmentDate }}</p>
            </div>
            <div class="bg-primary-50 dark:bg-primary-900/30 rounded-xl p-4">
                <p class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase mb-1">Maturity Date</p>
                @php
                    $maturityDate = $investment->maturity_date ? $investment->maturity_date->format('M d, Y') : '-';
                @endphp
                <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $maturityDate }}</p>
            </div>
            <div class="bg-primary-50 dark:bg-primary-900/30 rounded-xl p-4">
                <p class="text-[10px] font-bold text-primary-600 dark:text-primary-400 uppercase mb-1">Duration</p>
                @php
                    $durationDisplay = $duration ?: '-';
                @endphp
                <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $durationDisplay }}</p>
            </div>
        </div>

        @if($investment->notes)
            <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-4 mb-6">
                <p class="text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase mb-1">Notes</p>
                <p class="text-sm text-primary-900 dark:text-white">{{ $investment->notes }}</p>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[10px] font-bold text-teal-600 dark:text-teal-400 uppercase">Profit/Loss</span>
                    <span class="badge {{ $profitBadgeClass }}">
                        {{ $profitPrefix }}{{ number_format($profitPct, 2) }}%
                    </span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill {{ $profitFillClass }}" style="width: {{ min(abs($profitPct), 100) }}%"></div>
                </div>
                <p class="text-xs font-bold {{ $profitTextClass }} mt-1">
                    {{ $profitPrefix }}{{ fmtTshInv($profit) }}
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
