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
                    Deposits ({{ count($deposits) }})
                </button>
                <button @click="activeMiniTab = 'withdrawals'"
                        :class="activeMiniTab === 'withdrawals' ? 'bg-white dark:bg-primary-800 text-red-600 dark:text-red-400 shadow' : 'text-primary-600 dark:text-primary-400 hover:text-primary-800'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all">
                    <i class="fa-solid fa-arrow-up text-red-500"></i>
                    Withdrawals ({{ count($withdrawals) }})
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
                        @forelse($deposits as $d)
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
                        @forelse($withdrawals as $w)
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
        </div>
    </div>

    <div class="glass rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-primary-100 dark:border-dark-border flex items-center justify-between">
            <div>
                <h3 class="font-bold text-primary-900 dark:text-white text-sm">Full Running Ledger</h3>
                <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-0.5">Complete chronological transaction history</p>
            </div>
            <span class="text-[11px] font-semibold text-primary-600 dark:text-primary-400">
                {{ $ledgerPaginated->total() }} entries
            </span>
        </div>
        <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
            <table class="data-table">
                <thead class="sticky top-0 z-10">
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th class="text-right">Debit</th>
                        <th class="text-right">Credit</th>
                        <th class="text-right">Running Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ledgerPaginated as $idx => $txn)
                        @php
                            $typeLower = strtolower($txn['type'] ?? '');
                            $striped = $idx % 2 === 1;
                            $typeBadge = match(true) {
                                str_contains($typeLower, 'deposit') => 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border-green-100 dark:border-green-800/50',
                                str_contains($typeLower, 'withdraw') => 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 border-red-100 dark:border-red-800/50',
                                str_contains($typeLower, 'interest') => 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-blue-100 dark:border-blue-800/50',
                                default => 'bg-gray-50 dark:bg-gray-800/30 text-gray-700 dark:text-gray-300 border-gray-100 dark:border-gray-700',
                            };
                        @endphp
                        <tr class="{{ $striped ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }} hover:!bg-primary-100/50 dark:hover:!bg-primary-900/20 transition-colors">
                            <td class="whitespace-nowrap text-xs font-semibold text-primary-800 dark:text-primary-200 tabular-nums">
                                {{ \Carbon\Carbon::parse($txn['date'])->format('M j, Y') }}
                            </td>
                            <td class="whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $typeBadge }}">
                                    {{ $txn['type'] }}
                                </span>
                            </td>
                            <td class="text-xs text-primary-700 dark:text-primary-300 max-w-[260px] truncate" title="{{ $txn['description'] ?? '' }}">
                                {{ $txn['description'] ?? '—' }}
                            </td>
                            <td class="text-right whitespace-nowrap text-xs font-bold text-red-600 dark:text-red-400 tabular-nums">
                                {{ $txn['debit'] > 0 ? '-' . fmtTshSav($txn['debit']) : '—' }}
                            </td>
                            <td class="text-right whitespace-nowrap text-xs font-bold text-green-600 dark:text-green-400 tabular-nums">
                                {{ $txn['credit'] > 0 ? '+' . fmtTshSav($txn['credit']) : '—' }}
                            </td>
                            <td class="text-right whitespace-nowrap text-xs font-bold text-primary-800 dark:text-primary-200 tabular-nums">
                                {{ fmtTshSav($txn['balance_after']) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-primary-400 dark:text-primary-500 text-sm">
                                <i class="fa-solid fa-book text-3xl mb-2 block opacity-40"></i>
                                No ledger entries to display.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ledgerPaginated->hasPages())
            <div class="px-5 py-4 border-t border-primary-100 dark:border-dark-border">
                {{ $ledgerPaginated->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
