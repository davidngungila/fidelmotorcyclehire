@extends('layouts.member')

@section('breadcrumb', 'My Saving Plan')
@section('page_title', 'My Saving Plan')

@php
    function fmtTsh($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }
@endphp

@section('content')

<div class="space-y-6">

    <!-- Plan Overview Card -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-primary-900 dark:text-white">{{ $savingPlan->name }}</h2>
                <p class="text-sm text-primary-500 dark:text-primary-400 mt-1">Membership: {{ $savingPlan->membership ?? $savingPlan->memberid }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-primary-500 dark:text-primary-400">Target Date</p>
                <p class="text-lg font-bold text-primary-900 dark:text-white">Dec 2026</p>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Overall Progress -->
            <div class="bg-primary-50 dark:bg-primary-900/30 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-primary-700 dark:text-primary-300">Overall Progress</span>
                    <span class="text-2xl font-bold text-primary-900 dark:text-white">{{ number_format($progress, 1) }}%</span>
                </div>
                <div class="w-full bg-primary-200 dark:bg-primary-700 rounded-full h-3 mb-2">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(100, $progress) }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-primary-600 dark:text-primary-400">
                    <span>{{ fmtTsh($currentSavings) }}</span>
                    <span>{{ fmtTsh($goalAmount) }}</span>
                </div>
            </div>

            <!-- Monthly Goal Progress -->
            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-300">Monthly Goal</span>
                    <span class="text-2xl font-bold text-blue-900 dark:text-white">{{ number_format($monthlyProgress, 1) }}%</span>
                </div>
                <div class="w-full bg-blue-200 dark:bg-blue-700 rounded-full h-3 mb-2">
                    <div class="bg-gradient-to-r from-blue-500 to-cyan-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(100, $monthlyProgress) }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-blue-600 dark:text-blue-400">
                    <span>{{ fmtTsh($currentSavings) }}</span>
                    <span>{{ fmtTsh($monthlyGoal) }}</span>
                </div>
            </div>

            <!-- Remaining Amount -->
            <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-purple-700 dark:text-purple-300">Remaining</span>
                    <span class="text-2xl font-bold text-purple-900 dark:text-white">{{ fmtTsh($remaining) }}</span>
                </div>
                <div class="w-full bg-purple-200 dark:bg-purple-700 rounded-full h-3 mb-2">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 h-3 rounded-full transition-all duration-500" style="width: {{ min(100, ($remaining / $goalAmount) * 100) }}%"></div>
                </div>
                <p class="text-xs text-purple-600 dark:text-purple-400 mt-2">{{ number_format(($remaining / $goalAmount) * 100, 1) }}% to goal</p>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4 text-center">
                <p class="text-xs text-green-600 dark:text-green-400 mb-1">Current Savings</p>
                <p class="text-xl font-bold text-green-700 dark:text-green-300">{{ fmtTsh($currentSavings) }}</p>
            </div>
            <div class="bg-orange-50 dark:bg-orange-900/30 rounded-lg p-4 text-center">
                <p class="text-xs text-orange-600 dark:text-orange-400 mb-1">Goal Amount</p>
                <p class="text-xl font-bold text-orange-700 dark:text-orange-300">{{ fmtTsh($goalAmount) }}</p>
            </div>
            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 text-center">
                <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">Monthly Target</p>
                <p class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ fmtTsh($monthlyGoal) }}</p>
            </div>
            <div class="bg-pink-50 dark:bg-pink-900/30 rounded-lg p-4 text-center">
                <p class="text-xs text-pink-600 dark:text-pink-400 mb-1">Total Deposits</p>
                <p class="text-xl font-bold text-pink-700 dark:text-pink-300">{{ $recentTransactions->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Monthly Contributions Chart -->
    <div class="glass p-6 rounded-2xl">
        <h3 class="font-bold text-primary-900 dark:text-white mb-4">Monthly Contributions</h3>
        @if($monthlyContributions->count() > 0)
            <div class="space-y-3">
                @foreach($monthlyContributions as $contribution)
                    @php
                        $monthProgress = $monthlyGoal > 0 ? ($contribution->total / $monthlyGoal) * 100 : 0;
                        $monthDate = \Carbon\Carbon::parse($contribution->month . '-01');
                    @endphp
                    <div class="flex items-center gap-4">
                        <div class="w-24 text-sm font-semibold text-primary-700 dark:text-primary-300">
                            {{ $monthDate->format('M Y') }}
                        </div>
                        <div class="flex-1">
                            <div class="w-full bg-primary-200 dark:bg-primary-700 rounded-full h-4">
                                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full transition-all duration-500" style="width: {{ min(100, $monthProgress) }}%"></div>
                            </div>
                        </div>
                        <div class="w-32 text-right">
                            <p class="text-sm font-bold text-primary-900 dark:text-white">{{ fmtTsh($contribution->total) }}</p>
                            <p class="text-xs text-primary-500 dark:text-primary-400">{{ number_format($monthProgress, 1) }}%</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-chart-bar text-3xl mb-2 block opacity-40"></i>
                <p class="text-sm">No contribution data available yet</p>
            </div>
        @endif
    </div>

    <!-- Recent Transactions -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-primary-900 dark:text-white">Recent Deposits</h3>
            <a href="{{ route('member.savings.index') }}" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-500">
                View All <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
            </a>
        </div>
        @if($recentTransactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                            <tr>
                                <td class="text-xs font-semibold text-primary-800 dark:text-primary-200 whitespace-nowrap">
                                    {{ $transaction->date->format('M j, Y') }}
                                </td>
                                <td>
                                    <span class="badge badge-green text-[10px]">{{ ucfirst($transaction->transaction_type) }}</span>
                                </td>
                                <td class="text-xs text-primary-700 dark:text-primary-300">
                                    {{ $transaction->reference_no ?? '—' }}
                                </td>
                                <td class="text-right text-xs font-bold text-green-700 dark:text-green-400">
                                    +{{ fmtTsh($transaction->amount) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-inbox text-3xl mb-2 block opacity-40"></i>
                <p class="text-sm">No deposits recorded yet</p>
            </div>
        @endif
    </div>

</div>

@endsection
