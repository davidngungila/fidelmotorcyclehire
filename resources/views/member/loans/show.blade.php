@extends('layouts.member')

@section('breadcrumb', 'Loan Details')
@section('page_title', 'Loan Details')

@php
    function fmtTsh2($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }

    function statusBadgeClass2($status): string {
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

@section('page-header')
<div class="glass p-5 lg:p-6 rounded-2xl overflow-hidden relative"
     style="background: linear-gradient(135deg, rgba(6,94,71,0.08) 0%, rgba(16,185,129,0.06) 100%);">
    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-5">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-lg shadow-primary-500/20 flex-shrink-0">
                <i class="fa-solid fa-hand-holding-dollar text-white text-xl"></i>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-mono text-sm font-bold bg-white/80 dark:bg-primary-900/60 text-primary-800 dark:text-primary-200 border border-primary-200 dark:border-primary-800/60 tabular-nums">
                        <i class="fa-solid fa-hashtag mr-1.5 text-primary-500 text-[11px]"></i>
                        {{ $loanNumber }}
                    </span>
                    <span class="badge {{ statusBadgeClass2($loan['status']) }}">
                        {{ $loan['status'] }}
                    </span>
                </div>
                <h1 class="text-xl lg:text-2xl font-extrabold text-primary-900 dark:text-white leading-tight">
                    {{ $loan['loan_product'] }}
                </h1>
                <p class="text-xs mt-1 text-primary-600 dark:text-primary-400 font-medium">
                    <i class="fa-regular fa-calendar mr-1"></i>
                    {{ $loan['disbursement_date'] ? \Carbon\Carbon::parse($loan['disbursement_date'])->format('F j, Y') : '—' }}
                    <span class="mx-1.5 opacity-40">→</span>
                    {{ $loan['maturity_date'] ? \Carbon\Carbon::parse($loan['maturity_date'])->format('F j, Y') : '—' }}
                </p>
            </div>
        </div>

        <div class="flex-shrink-0 min-w-[240px]">
            <div class="glass p-4 rounded-xl">
                <div class="flex items-end justify-between mb-2">
                    <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Repayment Progress</p>
                    <p class="text-sm font-extrabold text-green-600 dark:text-green-400 tabular-nums">{{ $progress }}%</p>
                </div>
                <div class="progress-bar h-2">
                    <div class="progress-fill" style="width: {{ $progress }}%"></div>
                </div>
                <div class="flex justify-between mt-3 text-[11px] font-semibold">
                    <div>
                        <p class="text-primary-500 dark:text-primary-400">Paid</p>
                        <p class="text-primary-900 dark:text-white tabular-nums">{{ fmtTsh2($paid) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-primary-500 dark:text-primary-400">Outstanding</p>
                        <p class="text-primary-900 dark:text-white tabular-nums">{{ fmtTsh2($outstanding) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

<div class="space-y-6">

    <div x-data="{ activeTab: 'overview' }" class="space-y-6">

        <div class="flex flex-wrap items-center gap-2 p-1.5 rounded-xl bg-white/70 dark:bg-primary-900/30 border border-primary-100 dark:border-dark-border w-fit">
            @foreach(['overview' => ['icon' => 'fa-gauge-high', 'label' => 'Overview'],
                     'schedule' => ['icon' => 'fa-calendar-days', 'label' => 'Repayment Schedule'],
                     'history' => ['icon' => 'fa-clock-rotate-left', 'label' => 'Repayment History'],
                     'statement' => ['icon' => 'fa-file-invoice', 'label' => 'Statement']]
                     as $tab => $info)
                <button @click="activeTab = '{{ $tab }}'"
                        :class="activeTab === '{{ $tab }}'
                            ? 'bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-md shadow-primary-500/20'
                            : 'text-primary-700 dark:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/40'"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all">
                    <i class="fa-solid {{ $info['icon'] }}"></i>
                    {{ $info['label'] }}
                </button>
            @endforeach
        </div>

        <div x-show="activeTab === 'overview'" x-transition:enter="fade-in 0.2s ease" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="stat-card glass">
                    <div class="bg-blob" style="background:#3b82f6;"></div>
                    <div class="icon-wrap bg-blue-50 dark:bg-blue-900/30 text-blue-500 mb-3">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Principal Amount</p>
                    <p class="text-xl font-extrabold text-primary-900 dark:text-white tabular-nums">{{ fmtTsh2($totalAmount) }}</p>
                </div>
                <div class="stat-card glass">
                    <div class="bg-blob" style="background:#ef4444;"></div>
                    <div class="icon-wrap bg-red-50 dark:bg-red-900/30 text-red-500 mb-3">
                        <i class="fa-solid fa-percent"></i>
                    </div>
                    <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Interest Rate</p>
                    <p class="text-xl font-extrabold text-primary-900 dark:text-white tabular-nums">{{ $loan['interest_rate'] ?? 0 }}%</p>
                    <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-0.5">per annum</p>
                </div>
                <div class="stat-card glass">
                    <div class="bg-blob" style="background:#10b981;"></div>
                    <div class="icon-wrap bg-green-50 dark:bg-green-900/30 text-green-500 mb-3">
                        <i class="fa-solid fa-calendar-repeat"></i>
                    </div>
                    <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Monthly Installment</p>
                    <p class="text-xl font-extrabold text-primary-900 dark:text-white tabular-nums">{{ fmtTsh2($loan['installment'] ?? 0) }}</p>
                </div>
                <div class="stat-card glass">
                    <div class="bg-blob" style="background:#f59e0b;"></div>
                    <div class="icon-wrap bg-amber-50 dark:bg-amber-900/30 text-amber-500 mb-3">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400 mb-1">Term</p>
                    <p class="text-xl font-extrabold text-primary-900 dark:text-white tabular-nums">
                        @php
                            $d1 = new \DateTime($loan['disbursement_date'] ?? date('Y-m-d'));
                            $d2 = new \DateTime($loan['maturity_date'] ?? date('Y-m-d'));
                            $months = $d1->diff($d2)->m + ($d1->diff($d2)->y * 12);
                        @endphp
                        {{ $months }} Months
                    </p>
                    <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-0.5">
                        @if(!empty($loan['maturity_date']))
                            {{ max(0, \Carbon\Carbon::parse($loan['maturity_date'])->diffInDays(\Carbon\Carbon::now())) }} days remaining
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div class="glass p-5 rounded-2xl">
                    <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-primary-500 text-xs"></i>
                        Disbursement & Maturity Details
                    </h3>
                    <dl class="space-y-3">
                        @foreach([
                            ['Disbursement Date', $loan['disbursement_date'] ? \Carbon\Carbon::parse($loan['disbursement_date'])->format('l, F j, Y') : '—'],
                            ['Maturity Date', $loan['maturity_date'] ? \Carbon\Carbon::parse($loan['maturity_date'])->format('l, F j, Y') : '—'],
                            ['Loan Product', $loan['loan_product'] ?? '—'],
                            ['Member Number', $loan['member_number'] ?? auth()->user()->member_number],
                        ] as [$label, $val])
                            <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border last:border-0 last:pb-0">
                                <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">{{ $label }}</dt>
                                <dd class="text-sm font-bold text-primary-900 dark:text-white tabular-nums">{{ $val }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="glass p-5 rounded-2xl">
                    <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-sack-dollar text-primary-500 text-xs"></i>
                        Financial Summary
                    </h3>
                    <dl class="space-y-3">
                        @php
                            $totalInterest = $loan['interest_rate'] ? round($totalAmount * ((float)$loan['interest_rate'] / 100) * ($months / 12), 2) : 0;
                            $totalPayable = $totalAmount + $totalInterest;
                        @endphp
                        @foreach([
                            ['Total Principal', fmtTsh2($totalAmount)],
                            ['Estimated Interest', fmtTsh2($totalInterest)],
                            ['Total Payable', fmtTsh2($totalPayable)],
                            ['Amount Paid to Date', fmtTsh2($paid)],
                            ['Outstanding Balance', fmtTsh2($outstanding)],
                        ] as [$label, $val])
                            <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border last:border-0 last:pb-0">
                                <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">{{ $label }}</dt>
                                <dd class="text-sm font-bold text-primary-900 dark:text-white tabular-nums">{{ $val }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'schedule'" x-transition:enter="fade-in 0.2s ease" class="glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-primary-100 dark:border-dark-border flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-primary-900 dark:text-white text-sm">Repayment Schedule</h3>
                    <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-0.5">Expected installment due dates and breakdown</p>
                </div>
                <span class="text-[11px] font-semibold text-primary-600 dark:text-primary-400">
                    {{ count($repaymentSchedule) }} installments
                </span>
            </div>
            <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                <table class="data-table">
                    <thead class="sticky top-0 z-10">
                        <tr>
                            <th>#</th>
                            <th>Due Date</th>
                            <th class="text-right">Installment</th>
                            <th class="text-right">Principal</th>
                            <th class="text-right">Interest</th>
                            <th class="text-right">Remaining</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repaymentSchedule as $idx => $s)
                            <tr class="hover:bg-primary-50/50 dark:hover:bg-primary-900/20 transition-colors">
                                <td class="text-xs font-bold text-primary-600 dark:text-primary-400 tabular-nums">{{ $s['installment_no'] }}</td>
                                <td class="text-xs font-semibold text-primary-800 dark:text-primary-200 whitespace-nowrap tabular-nums">
                                    {{ \Carbon\Carbon::parse($s['due_date'])->format('M j, Y') }}
                                </td>
                                <td class="text-right text-xs font-bold text-primary-900 dark:text-white tabular-nums">{{ fmtTsh2($s['installment']) }}</td>
                                <td class="text-right text-xs font-semibold text-green-700 dark:text-green-400 tabular-nums">{{ fmtTsh2($s['principal']) }}</td>
                                <td class="text-right text-xs font-semibold text-amber-700 dark:text-amber-400 tabular-nums">{{ fmtTsh2($s['interest']) }}</td>
                                <td class="text-right text-xs font-bold text-primary-700 dark:text-primary-300 tabular-nums">{{ fmtTsh2($s['remaining']) }}</td>
                                <td>
                                    <span class="badge {{ $s['status'] === 'Paid' ? 'badge-green' : ($s['status'] === 'Overdue' ? 'badge-red' : 'badge-yellow') }}">
                                        {{ $s['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-primary-400 dark:text-primary-500 text-sm">
                                    No schedule available for this loan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'history'" x-transition:enter="fade-in 0.2s ease" class="glass rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-primary-100 dark:border-dark-border flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-primary-900 dark:text-white text-sm">Repayment History</h3>
                    <p class="text-[11px] text-primary-500 dark:text-primary-400 mt-0.5">Payments already received on your account</p>
                </div>
                <span class="text-[11px] font-semibold text-green-600 dark:text-green-400">
                    {{ count($repaymentHistory) }} payments
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Method</th>
                            <th class="text-right">Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($repaymentHistory as $p)
                            <tr>
                                <td class="text-xs font-bold text-primary-600 dark:text-primary-400 tabular-nums">{{ $p['payment_no'] }}</td>
                                <td class="text-xs font-semibold text-primary-800 dark:text-primary-200 whitespace-nowrap tabular-nums">
                                    {{ \Carbon\Carbon::parse($p['date'])->format('M j, Y') }}
                                </td>
                                <td class="font-mono text-[11px] font-bold text-primary-700 dark:text-primary-300">{{ $p['reference'] }}</td>
                                <td class="text-xs text-primary-700 dark:text-primary-300">{{ $p['method'] }}</td>
                                <td class="text-right text-xs font-bold text-green-700 dark:text-green-400 tabular-nums">{{ fmtTsh2($p['amount']) }}</td>
                                <td>
                                    <span class="badge badge-green">
                                        <i class="fa-solid fa-check mr-1 text-[10px]"></i>
                                        {{ $p['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-primary-400 dark:text-primary-500 text-sm">
                                    <i class="fa-solid fa-inbox text-3xl mb-2 block opacity-40"></i>
                                    No repayment payments recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="activeTab === 'statement'" x-transition:enter="fade-in 0.2s ease" class="glass p-6 rounded-2xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 dark:bg-primary-900/40 text-primary-500 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-primary-900 dark:text-white text-base mb-1">Loan Account Statement</h3>
                        <p class="text-xs text-primary-600 dark:text-primary-400 max-w-md">
                            Download your full repayment statement for this loan. Includes disbursement details, all installments, and running balances.
                        </p>
                    </div>
                </div>
                <form method="GET" action="{{ route('member.statements.download', 'loan') }}" class="flex-shrink-0 w-full md:w-auto">
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <div>
                            <label class="form-label text-primary-600 dark:text-primary-400">From</label>
                            <input type="date" name="from" value="{{ date('Y-m-01', strtotime('-6 months')) }}" class="form-input text-xs">
                        </div>
                        <div>
                            <label class="form-label text-primary-600 dark:text-primary-400">To</label>
                            <input type="date" name="to" value="{{ date('Y-m-d') }}" class="form-input text-xs">
                        </div>
                    </div>
                    <input type="hidden" name="loan_number" value="{{ $loanNumber }}">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-br from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30 transition-all">
                        <i class="fa-solid fa-download text-xs"></i>
                        Download Statement (CSV)
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>

@endsection
