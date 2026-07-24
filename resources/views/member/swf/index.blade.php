@extends('layouts.member')

@section('breadcrumb', 'My SWF')
@section('page_title', 'Social Welfare Fund')

@php
    function fmtTshSwf($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }
@endphp

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="stat-card glass">
            <div class="bg-blob" style="background:#8b5cf6;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-purple-50 dark:bg-purple-900/30 text-purple-500">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Total Contribution</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                {{ fmtTshSwf($totalContribution) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                Lifetime contributions
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background:#f59e0b;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-amber-50 dark:bg-amber-900/30 text-amber-500">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Benefits Paid</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-amber-600 dark:text-amber-400 leading-tight tabular-nums">
                {{ fmtTshSwf($benefits) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                Total benefits received
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background:#10b981;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-green-50 dark:bg-green-900/30 text-green-500">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Current Balance</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                {{ fmtTshSwf($currentBalance) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                Available for benefits
            </p>
        </div>
    </div>

    <div class="glass p-5 rounded-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-1">Contribution History</h3>
                <p class="text-[11px] text-primary-500 dark:text-primary-400">Your SWF contribution records</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Running Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($processedHistory as $idx => $c)
                        @php
                            $isCredit = strcasecmp($c['type'] ?? '', 'contribution') === 0 || strcasecmp($c['type'] ?? '', 'deposit') === 0;
                            $striped = $idx % 2 === 1;
                        @endphp
                        <tr class="{{ $striped ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }} hover:!bg-primary-100/50 dark:hover:!bg-primary-900/20 transition-colors">
                            <td class="whitespace-nowrap text-xs font-semibold text-primary-800 dark:text-primary-200 tabular-nums">
                                {{ \Carbon\Carbon::parse($c['date'])->format('M j, Y') }}
                            </td>
                            <td>
                                <span class="badge {{ $isCredit ? 'badge-green' : 'badge-red' }}">
                                    {{ ucfirst($c['type'] ?? 'Contribution') }}
                                </span>
                            </td>
                            <td class="text-right whitespace-nowrap text-xs font-bold {{ $isCredit ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} tabular-nums">
                                {{ $isCredit ? '+' : '-' }}{{ fmtTshSwf(abs($c['amount'] ?? 0)) }}
                            </td>
                            <td class="text-right whitespace-nowrap text-xs font-bold text-primary-800 dark:text-primary-200 tabular-nums">
                                {{ fmtTshSwf($c['running_balance'] ?? 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-primary-400 dark:text-primary-500 text-sm">
                                <i class="fa-solid fa-inbox text-3xl mb-2 block opacity-40"></i>
                                No contribution history available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($swf['enrollment_date'] ?? null)
        <div class="glass p-5 rounded-2xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-500 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Enrollment Date</p>
                    <p class="text-sm font-bold text-primary-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($swf['enrollment_date'])->format('F j, Y') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>

@endsection
