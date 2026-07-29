@extends('layouts.admin')

@section('page_title', 'Loan Applications')

@section('breadcrumb', 'Loan Applications')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-primary-900 dark:text-white">Loan Applications</h1>
            <p class="text-sm text-primary-600 dark:text-primary-400 mt-1">Manage and review loan applications from members</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.loans.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors">
                <i class="fa-solid fa-plus text-xs"></i>
                New Application
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Pending</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">{{ $loans->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-clock text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Approved</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">{{ $loans->where('status', 'approved')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Rejected</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">{{ $loans->where('status', 'rejected')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Total Amount</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">{{ number_format($loans->sum('principal_amount'), 0) }} TSh</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-money-bill text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800">
        <div class="p-4 border-b border-primary-100 dark:border-primary-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <input type="text" placeholder="Search applications..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-primary-200 dark:border-primary-700 bg-white dark:bg-dark-bg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-white">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-primary-400 text-xs"></i>
                </div>
                <div class="flex items-center gap-2">
                    <select class="px-3 py-2 rounded-lg border border-primary-200 dark:border-primary-700 bg-white dark:bg-dark-bg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-white">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-primary-50 dark:bg-primary-900/20">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Loan Number</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Member</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Purpose</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Amount</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Date</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr class="border-b border-primary-100 dark:border-primary-800 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-primary-900 dark:text-white">{{ $loan->loan_number }}</td>
                            <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">{{ $loan->user->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">{{ ucfirst($loan->purpose) }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-primary-900 dark:text-white">{{ number_format($loan->principal_amount, 0) }} TSh</td>
                            <td class="px-4 py-3">
                                @php
                                    $status = $dashboardService->loanStatusBadge($loan->status);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-primary-600 dark:text-primary-400">{{ $loan->application_date ? $loan->application_date->format('Y-m-d') : '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.loans.show', encrypt($loan->loan_number)) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-medium transition-colors">
                                    <i class="fa-solid fa-eye text-[10px]"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-primary-600 dark:text-primary-400">
                                No loan applications found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loans->hasPages())
        <div class="p-4 border-t border-primary-100 dark:border-primary-800 flex items-center justify-between">
            <p class="text-xs text-primary-600 dark:text-primary-400">Showing {{ $loans->firstItem() }}-{{ $loans->lastItem() }} of {{ $loans->total() }} applications</p>
            {{ $loans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
