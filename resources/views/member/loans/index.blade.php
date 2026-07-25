@extends('layouts.member')

@section('breadcrumb', 'My Loans')
@section('page_title', 'My Loans')

@php
    function fmtTsh($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }

    function statusBadgeClass($status): string {
        $s = strtolower(trim($status ?? ''));
        return match($s) {
            'active' => 'badge-green',
            'settled', 'completed', 'paid', 'closed' => 'badge-blue',
            'defaulted', 'default', 'overdue' => 'badge-red',
            'pending', 'processing' => 'badge-yellow',
            default => 'badge-gray',
        };
    }
@endphp

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="stat-card glass">
            <div class="bg-blob" style="background: #ef4444;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-red-50 dark:bg-red-900/30 text-red-500">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Total Outstanding</p>
            <p class="text-2xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                {{ fmtTsh($totalOutstanding) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                {{ $activeCount }} active loan{{ $activeCount !== 1 ? 's' : '' }}
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background: #10b981;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-green-50 dark:bg-green-900/30 text-green-500">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Total Repaid</p>
            <p class="text-2xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                {{ fmtTsh($totalBorrowed - $totalOutstanding) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                of {{ fmtTsh($totalBorrowed) }} borrowed
            </p>
        </div>

        <div class="stat-card glass">
            <div class="bg-blob" style="background: #3b82f6;"></div>
            <div class="flex items-start justify-between mb-3">
                <div class="icon-wrap bg-blue-50 dark:bg-blue-900/30 text-blue-500">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
            <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Total Accounts</p>
            <p class="text-2xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                {{ count($processedLoans) }}
            </p>
            <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-1">
                {{ count($processedLoans) - $activeCount }} settled / closed
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($processedLoans as $loan)
            <div class="glass p-5 rounded-2xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex-1 min-w-0">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-mono text-[11px] font-bold bg-primary-50 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 border border-primary-200 dark:border-primary-800/60 mb-2">
                            {{ $loan['loan_number'] }}
                        </span>
                        <h3 class="font-bold text-primary-900 dark:text-white text-base leading-tight">
                            {{ $loan['loan_product'] }}
                        </h3>
                    </div>
                    <span class="badge {{ statusBadgeClass($loan['status']) }} flex-shrink-0">
                        {{ $loan['status'] }}
                    </span>
                </div>

                <div class="mb-4">
                    <div class="flex items-end justify-between mb-2">
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Outstanding</p>
                            <p class="text-2xl font-extrabold text-primary-900 dark:text-white leading-tight tabular-nums">
                                {{ fmtTsh($loan['outstanding_float']) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Progress</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400 tabular-nums">
                                {{ $loan['progress_percent'] }}%
                            </p>
                        </div>
                    </div>

                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $loan['progress_percent'] }}%"></div>
                    </div>

                    <div class="flex justify-between mt-2 text-[11px] text-primary-500 dark:text-primary-400 font-semibold">
                        <span>Paid: {{ fmtTsh($loan['paid_amount_float']) }}</span>
                        <span>Principal: {{ fmtTsh($loan['total_amount']) }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4 pt-3 border-t border-primary-100 dark:border-dark-border">
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Installment</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white tabular-nums">
                            {{ fmtTsh($loan['installment'] ?? 0) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Interest</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white tabular-nums">
                            {{ $loan['interest_rate'] ?? 0 }}% p.a.
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Disbursed</p>
                        <p class="text-xs font-semibold text-primary-700 dark:text-primary-300 tabular-nums">
                            {{ $loan['disbursement_date'] ? \Carbon\Carbon::parse($loan['disbursement_date'])->format('M j, Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Maturity</p>
                        <p class="text-xs font-semibold text-primary-700 dark:text-primary-300 tabular-nums">
                            {{ $loan['maturity_date'] ? \Carbon\Carbon::parse($loan['maturity_date'])->format('M j, Y') : '—' }}
                        </p>
                    </div>
                </div>

                <div class="mt-auto pt-3">
                    <a href="{{ route('member.loans.show', encryptId($loan['loan_number'])) }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-br from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30 transition-all">
                        <i class="fa-solid fa-circle-info text-xs"></i>
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full glass p-10 rounded-2xl text-center">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-500 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-file-invoice-dollar text-2xl"></i>
                </div>
                <h3 class="font-bold text-primary-900 dark:text-white text-lg mb-2">No loans found</h3>
                <p class="text-sm text-primary-600 dark:text-primary-400 max-w-md mx-auto">
                    You don't have any active or previous loan accounts. Visit your nearest branch to apply for a loan product that suits your needs.
                </p>
            </div>
        @endforelse
    </div>

</div>

@endsection
