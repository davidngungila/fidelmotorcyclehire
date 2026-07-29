@extends('layouts.admin')

@section('page_title', 'Loan Repayments')

@section('breadcrumb', 'Loan Repayments')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-primary-900 dark:text-white">Loan Repayments</h1>
            <p class="text-sm text-primary-600 dark:text-primary-400 mt-1">Track and manage loan repayment transactions</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors">
                <i class="fa-solid fa-plus text-xs"></i>
                Record Payment
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Today</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">TZS 2.5M</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-day text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">This Week</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">TZS 15M</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-week text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">This Month</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">TZS 45M</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-calendar text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Overdue</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">TZS 8M</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Repayments Table -->
    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800">
        <div class="p-4 border-b border-primary-100 dark:border-primary-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <input type="text" placeholder="Search repayments..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-primary-200 dark:border-primary-700 bg-white dark:bg-dark-bg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-white">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-primary-400 text-xs"></i>
                </div>
                <div class="flex items-center gap-2">
                    <select class="px-3 py-2 rounded-lg border border-primary-200 dark:border-primary-700 bg-white dark:bg-dark-bg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-white">
                        <option value="">All Methods</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="mobile">Mobile Money</option>
                    </select>
                    <input type="date" class="px-3 py-2 rounded-lg border border-primary-200 dark:border-primary-700 bg-white dark:bg-dark-bg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-white">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-primary-50 dark:bg-primary-900/20">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Payment ID</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Loan ID</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Member</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Amount</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Method</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Date</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-primary-100 dark:border-primary-800 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-primary-900 dark:text-white">PAY-001234</td>
                        <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">LN-0001</td>
                        <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">John Mwangi</td>
                        <td class="px-4 py-3 text-sm font-medium text-primary-900 dark:text-white">TZS 250,000</td>
                        <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">Bank Transfer</td>
                        <td class="px-4 py-3 text-sm text-primary-600 dark:text-primary-400">2024-07-28</td>
                        <td class="px-4 py-3 text-right">
                            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-medium transition-colors">
                                <i class="fa-solid fa-eye text-[10px]"></i> View
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-primary-100 dark:border-primary-800 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-primary-900 dark:text-white">PAY-001235</td>
                        <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">LN-0002</td>
                        <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">Sarah Kamau</td>
                        <td class="px-4 py-3 text-sm font-medium text-primary-900 dark:text-white">TZS 150,000</td>
                        <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">Mobile Money</td>
                        <td class="px-4 py-3 text-sm text-primary-600 dark:text-primary-400">2024-07-28</td>
                        <td class="px-4 py-3 text-right">
                            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-medium transition-colors">
                                <i class="fa-solid fa-eye text-[10px]"></i> View
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-primary-100 dark:border-primary-800 flex items-center justify-between">
            <p class="text-xs text-primary-600 dark:text-primary-400">Showing 1-2 of 156 payments</p>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 rounded-lg border border-primary-200 dark:border-primary-700 text-xs font-medium text-primary-700 dark:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">Previous</button>
                <button class="px-3 py-1.5 rounded-lg bg-primary-600 text-white text-xs font-medium">1</button>
                <button class="px-3 py-1.5 rounded-lg border border-primary-200 dark:border-primary-700 text-xs font-medium text-primary-700 dark:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">2</button>
                <button class="px-3 py-1.5 rounded-lg border border-primary-200 dark:border-primary-700 text-xs font-medium text-primary-700 dark:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection
