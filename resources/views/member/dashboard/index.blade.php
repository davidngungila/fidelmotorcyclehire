@extends('layouts.member')

@section('breadcrumb', 'Dashboard')
@section('page_title', 'Dashboard')

@php
    $memberStatus = $member['status'] ?? 'Active';
    $statusActive = strtolower($memberStatus) === 'active';
    $memberNum = $member['member_number'] ?? $user->member_number ?? 'N/A';

    function fmtTsh($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }

    $loanDelta = count($loans) > 0 ? -12.4 : 0;
    $savDelta = 8.6;
    $depDelta = 2.1;
    $invDelta = 15.3;
    $swfDelta = 4.2;
@endphp

@section('content')

<div class="space-y-6">

    <div class="glass p-6 lg:p-8 rounded-2xl" style="background: linear-gradient(135deg, rgba(16,185,129,0.12) 0%, rgba(52,211,153,0.08) 100%); backdrop-filter: blur(16px); border: 1px solid rgba(52,211,153,0.3);">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-lg shadow-primary-500/20 flex-shrink-0">
                    <i class="fa-solid fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight">
                        Welcome back, {{ auth()->user()->name }}!
                    </h2>
                    <p class="mt-1.5 text-sm text-primary-700 dark:text-primary-300">
                        Here's a snapshot of your accounts as of {{ now()->format('F j, Y') }}.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/70 dark:bg-dark-card/70 border border-primary-200 dark:border-dark-border text-xs font-semibold text-primary-700 dark:text-primary-300">
                    <i class="fa-solid fa-clock-rotate-left text-primary-500"></i>
                    Last login: Today, {{ now()->format('g:i A') }}
                </span>
            </div>
        </div>
    </div>

    <div class="glass p-5 rounded-2xl">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <p class="text-[11px] uppercase tracking-wider font-bold text-primary-500 dark:text-primary-400 mb-1">Member Number</p>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-mono text-sm font-bold bg-primary-50 dark:bg-primary-900/40 text-primary-800 dark:text-primary-200 border border-primary-200 dark:border-primary-800/60">
                        <i class="fa-solid fa-id-card mr-2 text-primary-500 text-xs"></i>
                        {{ $memberNum }}
                    </span>
                </div>
                <div class="hidden sm:block w-px h-10 bg-primary-100 dark:bg-primary-800/50"></div>
                <div>
                    <p class="text-[11px] uppercase tracking-wider font-bold text-primary-500 dark:text-primary-400 mb-1">Membership Status</p>
                    <span class="badge {{ $statusActive ? 'badge-green' : 'badge-gray' }} inline-flex items-center gap-1.5 py-1.5">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusActive ? 'bg-primary-500 animate-pulse' : 'bg-gray-400' }}"></span>
                        {{ $memberStatus }}
                    </span>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-xs">
                @if(!empty($member['branch']))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 border border-primary-100 dark:border-primary-800/50 font-semibold">
                        <i class="fa-solid fa-location-dot text-primary-500"></i>
                        {{ $member['branch'] }} Branch
                    </span>
                @endif
                @if(!empty($loans) && count($loans) > 0)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 border border-amber-100 dark:border-amber-800/50 font-semibold">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        {{ count($loans) }} Active Loan{{ count($loans) > 1 ? 's' : '' }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        <div class="stat-card glass">
            <div class="bg-blob" style="background: #ef4444;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-red-50 dark:bg-red-900/30 text-red-500">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-500 bg-red-50 dark:bg-red-900/30 px-2 py-0.5 rounded-full">
                    <i class="fa-solid fa-arrow-down"></i>
                    {{ number_format(abs($loanDelta), 1) }}%
                </span>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Current Loan Balance</p>
            <p class="text-xl lg:text-2xl font-extrabold text-primary-900 dark:text-white leading-tight">
                {{ fmtTsh($loanBalance) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                {{ count($loans) }} active account{{ count($loans) !== 1 ? 's' : '' }}
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background: #10b981;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-green-50 dark:bg-green-900/30 text-green-500">
                    <i class="fa-solid fa-piggy-bank"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-green-500 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                    <i class="fa-solid fa-arrow-up"></i>
                    {{ number_format($savDelta, 1) }}%
                </span>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Savings Balance</p>
            <p class="text-xl lg:text-2xl font-extrabold text-primary-900 dark:text-white leading-tight">
                {{ fmtTsh($savingsBalance) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                Interest earned: {{ fmtTsh($savings['interest_earned'] ?? 0) }}
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background: #3b82f6;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-blue-50 dark:bg-blue-900/30 text-blue-500">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-green-500 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                    <i class="fa-solid fa-arrow-up"></i>
                    {{ number_format($depDelta, 1) }}%
                </span>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Deposit Balance</p>
            <p class="text-xl lg:text-2xl font-extrabold text-primary-900 dark:text-white leading-tight">
                {{ fmtTsh($depositBalance) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                {{ count($deposits) }} fixed deposit{{ count($deposits) !== 1 ? 's' : '' }}
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background: #8b5cf6;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-purple-50 dark:bg-purple-900/30 text-purple-500">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-green-500 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                    <i class="fa-solid fa-arrow-up"></i>
                    {{ number_format($invDelta, 1) }}%
                </span>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Investment Balance</p>
            <p class="text-xl lg:text-2xl font-extrabold text-primary-900 dark:text-white leading-tight">
                {{ fmtTsh($investmentBalance) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                {{ count($investments) }} investment{{ count($investments) !== 1 ? 's' : '' }}
            </p>
        </div>

        <div class="stat-card glass sm:col-span-2 lg:col-span-1">
            <div class="bg-blob" style="background: #f59e0b;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-amber-50 dark:bg-amber-900/30 text-amber-500">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-green-500 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                    <i class="fa-solid fa-arrow-up"></i>
                    {{ number_format($swfDelta, 1) }}%
                </span>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">SWF Balance</p>
            <p class="text-xl lg:text-2xl font-extrabold text-primary-900 dark:text-white leading-tight">
                {{ fmtTsh($swfBalance) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                Social Welfare Fund
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

        <div class="glass rounded-2xl overflow-hidden xl:col-span-3">
            <div class="flex items-center justify-between px-5 py-4 border-b border-primary-100 dark:border-dark-border">
                <div>
                    <h3 class="font-bold text-primary-900 dark:text-white text-sm">Recent Transactions</h3>
                    <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-0.5">Last 5 activities across your accounts</p>
                </div>
                <a href="{{ route('member.statements.index') }}" class="text-[11px] font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 inline-flex items-center gap-1">
                    View Statement
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table" x-data>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTransactions as $idx => $txn)
                            @php
                                $typeLower = strtolower($txn['type'] ?? '');
                                $isCredit = str_contains($typeLower, 'deposit') || str_contains($typeLower, 'interest') || str_contains($typeLower, 'maturity') || str_contains($typeLower, 'contribution') || str_contains($typeLower, 'disbursement') || str_contains($typeLower, 'placement');
                                $amountClass = $isCredit ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                                $sign = ($isCredit && !str_contains($typeLower, 'disbursement') && !str_contains($typeLower, 'placement')) ? '+' : '';
                                if (str_contains($typeLower, 'disbursement') || str_contains($typeLower, 'repayment')) {
                                    $sign = str_contains($typeLower, 'repayment') ? '-' : '+';
                                }
                                if (($txn['amount'] ?? 0) < 0) {
                                    $sign = '-';
                                }
                                $striped = $idx % 2 === 1;
                            @endphp
                            <tr class="{{ $striped ? 'bg-primary-50/60 dark:bg-primary-900/10' : '' }} hover:!bg-primary-100/50 dark:hover:!bg-primary-900/20 transition-colors">
                                <td class="whitespace-nowrap text-xs font-semibold text-primary-800 dark:text-primary-200">
                                    {{ \Carbon\Carbon::parse($txn['date'])->format('M j, Y') }}
                                </td>
                                <td class="whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold border
                                        {{ str_contains($typeLower, 'loan')
                                            ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-800/50'
                                            : (str_contains($typeLower, 'deposit') || str_contains($typeLower, 'saving') || str_contains($typeLower, 'maturity')
                                                ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border-green-100 dark:border-green-800/50'
                                                : (str_contains($typeLower, 'swf')
                                                    ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-800/50'
                                                    : 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-blue-100 dark:border-blue-800/50')) }}">
                                        {{ $txn['type'] }}
                                    </span>
                                </td>
                                <td class="text-xs text-primary-700 dark:text-primary-300 max-w-[220px] truncate" title="{{ $txn['description'] }}">
                                    {{ $txn['description'] }}
                                </td>
                                <td class="text-right whitespace-nowrap font-bold text-xs {{ $amountClass }} tabular-nums">
                                    {{ $sign }}{{ fmtTsh(abs($txn['amount'] ?? 0)) }}
                                </td>
                                <td class="text-right whitespace-nowrap text-xs font-semibold text-primary-800 dark:text-primary-200 tabular-nums">
                                    {{ fmtTsh($txn['balance_after'] ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-primary-400 dark:text-primary-500 text-sm">
                                    <i class="fa-solid fa-inbox text-3xl mb-2 block opacity-40"></i>
                                    No recent transactions to display.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass rounded-2xl overflow-hidden xl:col-span-2 flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-primary-100 dark:border-dark-border">
                <div>
                    <h3 class="font-bold text-primary-900 dark:text-white text-sm">Savings Growth</h3>
                    <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-0.5">Last 6 months performance</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-[11px] font-bold border border-green-100 dark:border-green-800/50">
                    <i class="fa-solid fa-chart-line"></i>
                    +{{ number_format($savDelta, 1) }}%
                </span>
            </div>
            <div class="p-4 flex-1 min-h-[280px]">
                <canvas id="savingsGrowthChart" x-init x-data x-ref="chart"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    queueMicrotask(() => initSavingsChart());
});
function initSavingsChart() {
    const canvas = document.getElementById('savingsGrowthChart');
    if (!canvas || typeof Chart === 'undefined') return;
    const ctx = canvas.getContext('2d');
    const labels = @json($savingsGrowth['labels'] ?? []);
    const values = @json($savingsGrowth['values'] ?? []);

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(26,51,40,0.6)' : 'rgba(209,250,229,0.6)';
    const textColor = isDark ? '#6ee7b7' : '#047857';
    const lineColor = '#10b981';
    const fillStart = 'rgba(16,185,129,0.28)';
    const fillEnd = 'rgba(16,185,129,0.00)';

    const grad = ctx.createLinearGradient(0, 0, 0, 300);
    grad.addColorStop(0, fillStart);
    grad.addColorStop(1, fillEnd);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Savings Balance (TSh)',
                data: values,
                borderColor: lineColor,
                backgroundColor: grad,
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: lineColor,
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: lineColor,
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#0d1f16' : '#ffffff',
                    borderColor: isDark ? '#1a3328' : '#d1fae5',
                    borderWidth: 1,
                    titleColor: isDark ? '#6ee7b7' : '#065f46',
                    bodyColor: isDark ? '#d1fae5' : '#064e3b',
                    padding: 10,
                    cornerRadius: 10,
                    titleFont: { weight: 'bold', size: 12 },
                    callbacks: {
                        label: (ctx) => ' TSh ' + Number(ctx.parsed.y).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: textColor,
                        font: { size: 11, weight: 600 }
                    },
                    border: { display: false }
                },
                y: {
                    grid: { color: gridColor, drawBorder: false },
                    ticks: {
                        color: textColor,
                        font: { size: 11, weight: 600 },
                        callback: (v) => {
                            if (v >= 1000000) return (v / 1000000).toFixed(1) + 'M';
                            if (v >= 1000) return (v / 1000).toFixed(0) + 'k';
                            return v;
                        },
                        maxTicksLimit: 6
                    },
                    border: { display: false }
                }
            }
        }
    });
}
</script>
@endpush
