@extends('layouts.admin')

@section('page_title', 'Loan Products')

@section('breadcrumb', 'Loan Products')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-primary-900 dark:text-white">Loan Products</h1>
            <p class="text-sm text-primary-600 dark:text-primary-400 mt-1">Define and manage loan product profiles</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.loan-products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors">
                <i class="fa-solid fa-plus text-xs"></i>
                Create Product
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Total Products</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">{{ $loanProducts->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-box text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Active</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">{{ $loanProducts->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-check text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Inactive</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">{{ $loanProducts->where('status', 'inactive')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-xmark text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-primary-100 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-primary-600 dark:text-primary-400 font-medium">Avg Interest Rate</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white mt-1">{{ $loanProducts->avg('interest_rate') ? number_format($loanProducts->avg('interest_rate'), 1) : 0 }}%</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-percent text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800">
        <div class="p-4 border-b border-primary-100 dark:border-primary-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <form method="GET" action="{{ route('admin.loan-products.index') }}" class="relative flex-1 max-w-md">
                    <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Search products..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-primary-200 dark:border-primary-700 bg-white dark:bg-dark-bg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-white">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-primary-400 text-xs"></i>
                </form>
                <form method="GET" action="{{ route('admin.loan-products.index') }}">
                    <input type="hidden" name="q" value="{{ $searchQuery }}">
                    <select name="status" class="px-3 py-2 rounded-lg border border-primary-200 dark:border-primary-700 bg-white dark:bg-dark-bg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-white">
                        <option value="">All Status</option>
                        <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-primary-50 dark:bg-primary-900/20">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Name</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Code</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Interest Rate</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Amount Range</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Term Range</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Status</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-primary-600 dark:text-primary-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loanProducts as $product)
                        <tr class="border-b border-primary-100 dark:border-primary-800 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-primary-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300 font-mono">{{ $product->code }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-primary-900 dark:text-white">{{ number_format($product->interest_rate, 2) }}%</td>
                            <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">{{ number_format($product->min_amount, 0) }} - {{ number_format($product->max_amount, 0) }} TSh</td>
                            <td class="px-4 py-3 text-sm text-primary-700 dark:text-primary-300">{{ $product->min_term_months }} - {{ $product->max_term_months }} months</td>
                            <td class="px-4 py-3">
                                @if($product->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.loan-products.show', $product->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-medium transition-colors">
                                        <i class="fa-solid fa-eye text-[10px]"></i> View
                                    </a>
                                    <a href="{{ route('admin.loan-products.edit', $product->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/40 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 text-xs font-medium transition-colors">
                                        <i class="fa-solid fa-pen text-[10px]"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.loan-products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure you want to delete this loan product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-100 hover:bg-red-200 dark:bg-red-900/40 dark:hover:bg-red-900/60 text-red-700 dark:text-red-300 text-xs font-medium transition-colors">
                                            <i class="fa-solid fa-trash text-[10px]"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-primary-600 dark:text-primary-400">
                                <i class="fa-solid fa-box-open text-3xl mb-3 block opacity-30"></i>
                                <p class="text-sm font-semibold mb-1">No loan products found</p>
                                <p class="text-xs">Create your first loan product to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loanProducts->hasPages())
        <div class="p-4 border-t border-primary-100 dark:border-primary-800 flex items-center justify-between">
            <p class="text-xs text-primary-600 dark:text-primary-400">Showing {{ $loanProducts->firstItem() }}-{{ $loanProducts->lastItem() }} of {{ $loanProducts->total() }} products</p>
            {{ $loanProducts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
