@extends('layouts.admin')

@section('page_title', 'Savings Product Details')

@section('breadcrumb', 'Savings & Deposits › Products › Details')

@php
    function fmtTsh($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }
@endphp

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-primary-900 dark:text-white">{{ $product->name }}</h1>
            <p class="text-sm text-primary-600 dark:text-primary-400 mt-1">Product Code: <span class="font-mono font-semibold">{{ $product->code }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back
            </a>
            <a href="{{ route('admin.products.edit', $encryptedId) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors">
                <i class="fa-solid fa-pen text-xs"></i> Edit
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold {{ $product->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
            {{ ucfirst($product->status) }}
        </span>
        @if($product->auto_interest_credit)
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                <i class="fa-solid fa-robot mr-1 text-xs"></i> Auto Credit
            </span>
        @endif
        @if($product->requires_notice)
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                <i class="fa-solid fa-bell mr-1 text-xs"></i> Notice Required
            </span>
        @endif
    </div>

    <!-- Product Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Basic Information -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-info-circle text-primary-500 text-xs"></i>
                Basic Information
            </h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Product Name</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ $product->name }}</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Product Code</dt>
                    <dd class="text-sm font-mono font-bold text-primary-900 dark:text-white">{{ $product->code }}</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Status</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ ucfirst($product->status) }}</dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Description</dt>
                    <dd class="text-sm text-primary-700 dark:text-primary-300 max-w-[200px] truncate" title="{{ $product->description ?? '—' }}">
                        {{ $product->description ?? '—' }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Interest Details -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-percent text-primary-500 text-xs"></i>
                Interest Details
            </h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Interest Rate</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ number_format($product->interest_rate, 2) }}% p.a.</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Interest Frequency</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ ucfirst($product->interest_frequency) }}</dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Auto Credit</dt>
                    <dd class="text-sm font-bold {{ $product->auto_interest_credit ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $product->auto_interest_credit ? 'Yes' : 'No' }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Balance Requirements -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-scale-balanced text-primary-500 text-xs"></i>
                Balance Requirements
            </h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Minimum Balance</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ fmtTsh($product->min_balance) }}</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Minimum Deposit</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ fmtTsh($product->min_deposit) }}</dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Maximum Deposit</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ $product->max_deposit ? fmtTsh($product->max_deposit) : 'Unlimited' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Withdrawal Rules -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-from-bracket text-primary-500 text-xs"></i>
                Withdrawal Rules
            </h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Min Withdrawal Period</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ $product->min_withdrawal_period_days }} days</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Premature Fee</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ number_format($product->premature_withdrawal_fee, 2) }}%</dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Notice Required</dt>
                    <dd class="text-sm font-bold {{ $product->requires_notice ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $product->requires_notice ? 'Yes' : 'No' }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Notice Period -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock text-primary-500 text-xs"></i>
                Notice Period
            </h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Notice Period</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ $product->notice_period_days }} days</dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Requires Notice</dt>
                    <dd class="text-sm font-bold {{ $product->requires_notice ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $product->requires_notice ? 'Yes' : 'No' }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Quick Actions -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bolt text-primary-500 text-xs"></i>
                Quick Actions
            </h3>
            <div class="space-y-3">
                <a href="{{ route('admin.products.edit', $encryptedId) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors">
                    <i class="fa-solid fa-pen text-xs"></i> Edit Product
                </a>
                <form method="POST" action="{{ route('admin.products.destroy', $encryptedId) }}" id="deleteForm" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button" onclick="confirmDelete()" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
                    <i class="fa-solid fa-trash text-xs"></i> Delete Product
                </button>
            </div>
        </div>
    </div>

    <!-- Full Description -->
    @if($product->description)
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-align-left text-primary-500 text-xs"></i>
                Product Description
            </h3>
            <p class="text-sm text-primary-700 dark:text-primary-300 leading-relaxed">{{ $product->description }}</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
  function confirmDelete() {
    Swal.fire({
      title: 'Are you sure?',
      text: 'Do you want to delete this savings product?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('deleteForm').submit();
      }
    });
  }
</script>
@endpush
@endsection
