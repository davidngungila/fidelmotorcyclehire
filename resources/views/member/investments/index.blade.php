@extends('layouts.member')

@section('breadcrumb', 'My Investments')
@section('page_title', 'My Investments')

@php
    function fmtTshInv($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }
@endphp

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="stat-card glass">
            <div class="bg-blob" style="background:#3b82f6;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-blue-50 dark:bg-blue-900/30 text-blue-500">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Total Invested</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                {{ fmtTshInv($totalInvested) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                {{ count($investments) }} investment{{ count($investments) !== 1 ? 's' : '' }}
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background:#10b981;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-green-50 dark:bg-green-900/30 text-green-500">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Current Value</p>
            <p class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                {{ fmtTshInv($totalCurrentValue) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                Market value today
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background:{{ $totalProfit >= 0 ? '#10b981' : '#ef4444' }};"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap {{ $totalProfit >= 0 ? 'bg-green-50 dark:bg-green-900/30 text-green-500' : 'bg-red-50 dark:bg-red-900/30 text-red-500' }}">
                    <i class="fa-solid {{ $totalProfit >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Total Profit/Loss</p>
            <p class="text-2xl lg:text-3xl font-extrabold {{ $totalProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} leading-tight tabular-nums">
                {{ $totalProfit >= 0 ? '+' : '' }}{{ fmtTshInv($totalProfit) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                {{ $overallReturn >= 0 ? '+' : '' }}{{ number_format($overallReturn, 2) }}% overall return
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($investments as $item)
            @php
                $inv = $item->investment;
                $productName = $item->product_name;
                $productCode = $item->product_code;
                $duration = $item->duration;
                $profit = $item->profit;
                $profitPct = $item->profit_pct;
                $status = $item->status;
                
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
            @endphp
            <div class="glass p-5 rounded-2xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-primary-900 dark:text-white text-base leading-tight mb-1">
                            {{ $productName }}
                        </h3>
                        <p class="text-[11px] text-primary-500 dark:text-primary-400">
                            Started: {{ $inv->investment_date ? \Carbon\Carbon::parse($inv->investment_date)->format('M j, Y') : '—' }}
                        </p>
                    </div>
                    <span class="badge {{ $statusClass }} flex-shrink-0">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Invested</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white tabular-nums">
                            {{ fmtTshInv($inv->amount ?? 0) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Duration</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white tabular-nums">
                            {{ $duration ?: '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Current Value</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white tabular-nums">
                            {{ fmtTshInv($inv->actual_return ?? $inv->expected_return ?? 0) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Profit/Loss</p>
                        @php
                            $profitClass = $profit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                        @endphp
                        <p class="text-sm font-bold {{ $profitClass }} tabular-nums">
                            {{ $profit >= 0 ? '+' : '' }}{{ fmtTshInv($profit) }}
                        </p>
                    </div>
                </div>

                <div class="mb-4 pt-3 border-t border-primary-100 dark:border-dark-border">
                    <div class="flex items-end justify-between mb-2">
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Return</p>
                        <p class="text-lg font-bold {{ $profitPct >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} tabular-nums">
                            {{ $profitPct >= 0 ? '+' : '' }}{{ number_format($profitPct, 2) }}%
                        </p>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill {{ $profitPct >= 0 ? 'bg-green-500' : 'bg-red-500' }}" style="width: {{ min(abs($profitPct), 100) }}%"></div>
                    </div>
                </div>

                <div class="mt-auto pt-3">
                    <a href="{{ route('member.investments.show', app('App\Services\EncryptedIdService')->encrypt($inv->id)) }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-br from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition-all">
                        <i class="fa-solid fa-circle-info text-xs"></i>
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full glass p-10 rounded-2xl text-center">
                <div class="w-16 h-16 rounded-2xl bg-teal-50 dark:bg-teal-900/20 text-teal-500 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-chart-pie text-2xl"></i>
                </div>
                <h3 class="font-bold text-primary-900 dark:text-white text-lg mb-2">No investments found</h3>
                <p class="text-sm text-primary-600 dark:text-primary-400 max-w-md mx-auto">
                    You don't have any active investment accounts. Contact us to explore investment opportunities that match your financial goals.
                </p>
            </div>
        @endforelse
    </div>

</div>

@endsection
