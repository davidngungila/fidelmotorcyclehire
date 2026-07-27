@extends('layouts.member')

@section('breadcrumb', 'My Saving Plan')
@section('page_title', 'My Saving Plan')

@php
    function fmtTshDep($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }

    function depStatusClass($status): string {
        $s = strtolower(trim($status ?? ''));
        return match($s) {
            'active' => 'badge-green',
            'matured', 'completed' => 'badge-blue',
            'defaulted' => 'badge-red',
            'pending' => 'badge-yellow',
            default => 'badge-gray',
        };
    }
@endphp

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card glass">
            <div class="bg-blob" style="background:#3b82f6;"></div>
            <div class="icon-wrap bg-blue-50 dark:bg-blue-900/30 text-blue-500 mb-3">
                <i class="fa-solid fa-money-bill-trend-up"></i>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Total Invested</p>
            <p class="text-xl font-extrabold text-primary-900 dark:text-white tabular-nums">{{ fmtTshDep($totalInvested) }}</p>
        </div>
        <div class="stat-card glass">
            <div class="bg-blob" style="background:#10b981;"></div>
            <div class="icon-wrap bg-green-50 dark:bg-green-900/30 text-green-500 mb-3">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Current Value</p>
            <p class="text-xl font-extrabold text-primary-900 dark:text-white tabular-nums">{{ fmtTshDep($totalValue) }}</p>
        </div>
        <div class="stat-card glass">
            <div class="bg-blob" style="background:#8b5cf6;"></div>
            <div class="icon-wrap bg-purple-50 dark:bg-purple-900/30 text-purple-500 mb-3">
                <i class="fa-solid fa-percent"></i>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Interest Accrued</p>
            <p class="text-xl font-extrabold text-purple-600 dark:text-purple-400 tabular-nums">{{ fmtTshDep($totalInterest) }}</p>
        </div>
        <div class="stat-card glass">
            <div class="bg-blob" style="background:#f59e0b;"></div>
            <div class="icon-wrap bg-amber-50 dark:bg-amber-900/30 text-amber-500 mb-3">
                <i class="fa-solid fa-hourglass-end"></i>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Maturing Soon</p>
            <p class="text-xl font-extrabold text-primary-900 dark:text-white tabular-nums">{{ $maturingSoon }}</p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-0.5">within 60 days</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($processedDeposits as $dep)
            <div class="glass p-5 rounded-2xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex-1 min-w-0">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-mono text-[11px] font-bold bg-primary-50 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 border border-primary-200 dark:border-primary-800/60 mb-2 tabular-nums">
                            {{ $dep['certificate_number'] }}
                        </span>
                        <h3 class="font-bold text-primary-900 dark:text-white text-base leading-tight">
                            {{ $dep['product'] }}
                        </h3>
                    </div>
                    <span class="badge {{ depStatusClass($dep['status']) }} flex-shrink-0">
                        {{ $dep['status'] }}
                    </span>
                </div>

                <div class="mb-4">
                    <div class="flex items-end justify-between mb-2">
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Current Value</p>
                            <p class="text-2xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                                {{ fmtTshDep($dep['current_value_float']) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Progress</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400 tabular-nums">
                                {{ $dep['progress_percent'] }}%
                            </p>
                        </div>
                    </div>

                    <div class="progress-bar h-2">
                        <div class="progress-fill" style="width: {{ $dep['progress_percent'] }}%"></div>
                    </div>

                    <div class="flex justify-between mt-2 text-[11px]">
                        <span class="font-semibold text-primary-500 dark:text-primary-400">
                            Days: {{ $dep['days_elapsed'] }}
                        </span>
                        <span class="font-bold {{ $dep['days_remaining'] > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-green-600 dark:text-green-400' }}">
                            @if($dep['days_remaining'] > 0)
                                <i class="fa-solid fa-clock mr-1"></i>
                                {{ $dep['days_remaining'] }} days left
                            @else
                                <i class="fa-solid fa-flag-checkered mr-1"></i>
                                Matured
                            @endif
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4 pt-3 border-t border-primary-100 dark:border-dark-border">
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Principal</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white tabular-nums">
                            {{ fmtTshDep($dep['amount_float']) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Interest</p>
                        <p class="text-sm font-bold text-blue-600 dark:text-blue-400 tabular-nums">
                            {{ fmtTshDep($dep['interest_float']) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Start Date</p>
                        <p class="text-xs font-semibold text-primary-700 dark:text-primary-300 tabular-nums">
                            {{ \Carbon\Carbon::parse($dep['start_date'])->format('M j, Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Maturity</p>
                        <p class="text-xs font-semibold text-primary-700 dark:text-primary-300 tabular-nums">
                            {{ \Carbon\Carbon::parse($dep['maturity_date'])->format('M j, Y') }}
                        </p>
                    </div>
                </div>

                <div class="mt-auto pt-3">
                    <a href="{{ route('member.deposits.show', encryptId($dep['certificate_number'])) }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-br from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30 transition-all">
                        <i class="fa-solid fa-file-lines text-xs"></i>
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full glass p-10 rounded-2xl text-center">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-money-bill-trend-up text-2xl"></i>
                </div>
                <h3 class="font-bold text-primary-900 dark:text-white text-lg mb-2">No fixed deposits yet</h3>
                <p class="text-sm text-primary-600 dark:text-primary-400 max-w-md mx-auto">
                    Start earning higher interest with our Fixed Deposit products. Visit your nearest branch to open a deposit certificate today!
                </p>
            </div>
        @endforelse
    </div>

</div>

@endsection
