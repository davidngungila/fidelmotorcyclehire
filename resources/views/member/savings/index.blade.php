@extends('layouts.member')

@section('breadcrumb', 'My Savings')
@section('page_title', 'My Savings')

@php
    function fmtTshSav($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }
@endphp

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="stat-card glass">
            <div class="bg-blob" style="background:#10b981;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-green-50 dark:bg-green-900/30 text-green-500">
                    <i class="fa-solid fa-piggy-bank"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Savings Balance</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                {{ fmtTshSav($balance) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                Deposited: {{ fmtTshSav($totalDeposited) }}
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background:#3b82f6;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-blue-50 dark:bg-blue-900/30 text-blue-500">
                    <i class="fa-solid fa-percent"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Interest Earned</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-blue-600 dark:text-blue-400 leading-tight tabular-nums">
                {{ fmtTshSav($interestEarned) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                Passive income earned
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background:#8b5cf6;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-purple-50 dark:bg-purple-900/30 text-purple-500">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Running Balance</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                {{ fmtTshSav($runningBalance) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                Balance + Interest
            </p>
        </div>
    </div>

    <div x-data="{ activeMiniTab: 'deposits' }" class="glass p-5 rounded-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-1">Recent Transactions</h3>
                <p class="text-[11px] text-primary-500 dark:text-primary-400">Deposits and withdrawals activity</p>
            </div>

            <div class="flex items-center gap-1 p-1 rounded-xl bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-dark-border w-fit">
                <button @click="activeMiniTab = 'deposits'"
                        :class="activeMiniTab === 'deposits' ? 'bg-white dark:bg-primary-800 text-green-600 dark:text-green-400 shadow' : 'text-primary-600 dark:text-primary-400 hover:text-primary-800'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all">
                    <i class="fa-solid fa-arrow-down text-green-500"></i>
                    Deposits ({{ $depositsPaginated->total() }})
                </button>
                <button @click="activeMiniTab = 'withdrawals'"
                        :class="activeMiniTab === 'withdrawals' ? 'bg-white dark:bg-primary-800 text-red-600 dark:text-red-400 shadow' : 'text-primary-600 dark:text-primary-400 hover:text-primary-800'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all">
                    <i class="fa-solid fa-arrow-up text-red-500"></i>
                    Withdrawals ({{ $withdrawalsPaginated->total() }})
                </button>
            </div>
        </div>

        <div x-show="activeMiniTab === 'deposits'" x-transition:enter="fade-in 0.2s ease">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Balance After</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($depositsPaginated as $d)
                            <tr>
                                <td class="text-xs font-semibold text-primary-800 dark:text-primary-200 whitespace-nowrap tabular-nums">
                                    {{ \Carbon\Carbon::parse($d['date'])->format('M j, Y') }}
                                </td>
                                <td class="text-xs text-primary-700 dark:text-primary-300">
                                    {{ $d['description'] ?? 'Deposit' }}
                                </td>
                                <td class="text-right text-xs font-bold text-green-700 dark:text-green-400 tabular-nums">
                                    +{{ fmtTshSav(abs($d['amount'] ?? 0)) }}
                                </td>
                                <td class="text-right text-xs font-semibold text-primary-700 dark:text-primary-300 tabular-nums">
                                    {{ fmtTshSav($d['balance_after'] ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-primary-400 dark:text-primary-500 text-sm">
                                    <i class="fa-solid fa-inbox text-2xl mb-2 block opacity-40"></i>
                                    No deposits recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($depositsPaginated->hasPages())
                <div class="mt-4">
                    {{ $depositsPaginated->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <div x-show="activeMiniTab === 'withdrawals'" x-transition:enter="fade-in 0.2s ease" style="display: none;">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Balance After</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawalsPaginated as $w)
                            <tr>
                                <td class="text-xs font-semibold text-primary-800 dark:text-primary-200 whitespace-nowrap tabular-nums">
                                    {{ \Carbon\Carbon::parse($w['date'])->format('M j, Y') }}
                                </td>
                                <td class="text-xs text-primary-700 dark:text-primary-300">
                                    {{ $w['description'] ?? 'Withdrawal' }}
                                </td>
                                <td class="text-right text-xs font-bold text-red-700 dark:text-red-400 tabular-nums">
                                    -{{ fmtTshSav(abs($w['amount'] ?? 0)) }}
                                </td>
                                <td class="text-right text-xs font-semibold text-primary-700 dark:text-primary-300 tabular-nums">
                                    {{ fmtTshSav($w['balance_after'] ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-primary-400 dark:text-primary-500 text-sm">
                                    <i class="fa-solid fa-face-smile text-2xl mb-2 block opacity-40"></i>
                                    No withdrawals yet. Great job saving!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($withdrawalsPaginated->hasPages())
                <div class="mt-4">
                    {{ $withdrawalsPaginated->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

@endsection
