@extends('layouts.admin')

@section('page_title', 'Loan Product Details')

@section('breadcrumb', 'Loans › Loan Products › Details')

@php
    function fmtTsh($val): string {
        return 'TSh ' . number_format((float)$val, 0, '.', ',');
    }
@endphp

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl lg:text-2xl font-bold text-primary-900 dark:text-white">{{ $loanProduct->name }}</h1>
            <p class="text-sm text-primary-600 dark:text-primary-400 mt-1">Product Code: <span class="font-mono font-semibold">{{ $loanProduct->code }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.loan-products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back
            </a>
            <a href="{{ route('admin.loan-products.edit', $encryptedId) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors">
                <i class="fa-solid fa-pen text-xs"></i> Edit
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold {{ $loanProduct->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
            {{ ucfirst($loanProduct->status) }}
        </span>
        @if($loanProduct->requires_collateral)
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                <i class="fa-solid fa-shield-halved mr-1 text-xs"></i> Collateral Required
            </span>
        @endif
        @if($loanProduct->requires_guarantor)
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                <i class="fa-solid fa-user-shield mr-1 text-xs"></i> Guarantor Required
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
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ $loanProduct->name }}</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Product Code</dt>
                    <dd class="text-sm font-mono font-bold text-primary-900 dark:text-white">{{ $loanProduct->code }}</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Status</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ ucfirst($loanProduct->status) }}</dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Interest Type</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ ucfirst($loanProduct->interest_type) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Interest & Fees -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-percent text-primary-500 text-xs"></i>
                Interest & Fees
            </h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Interest Rate</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ number_format($loanProduct->interest_rate, 2) }}%</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Processing Fee</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ fmtTsh($loanProduct->processing_fee) }}</dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Late Fee</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ fmtTsh($loanProduct->late_fee) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Loan Amount -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-money-bill-wave text-primary-500 text-xs"></i>
                Loan Amount
            </h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Minimum Amount</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ fmtTsh($loanProduct->min_amount) }}</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Maximum Amount</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ fmtTsh($loanProduct->max_amount) }}</dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Range</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ fmtTsh($loanProduct->min_amount) }} - {{ fmtTsh($loanProduct->max_amount) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Loan Term -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-primary-500 text-xs"></i>
                Loan Term
            </h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Min Term</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ $loanProduct->min_term_months }} months</dd>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Max Term</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ $loanProduct->max_term_months }} months</dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Repayment Frequency</dt>
                    <dd class="text-sm font-bold text-primary-900 dark:text-white">{{ ucfirst($loanProduct->repayment_frequency) }}</dd>
                </div>
            </dl>
        </div>

        <!-- Requirements -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-check text-primary-500 text-xs"></i>
                Requirements
            </h3>
            <dl class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-primary-100 dark:border-dark-border">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Collateral</dt>
                    <dd class="text-sm font-bold {{ $loanProduct->requires_collateral ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $loanProduct->requires_collateral ? 'Required' : 'Not Required' }}
                    </dd>
                </div>
                <div class="flex items-center justify-between py-2">
                    <dt class="text-xs font-semibold text-primary-500 dark:text-primary-400">Guarantor</dt>
                    <dd class="text-sm font-bold {{ $loanProduct->requires_guarantor ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $loanProduct->requires_guarantor ? 'Required' : 'Not Required' }}
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
                <a href="{{ route('admin.loan-products.edit', $encryptedId) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-colors">
                    <i class="fa-solid fa-pen text-xs"></i> Edit Product
                </a>
                <form method="POST" action="{{ route('admin.loan-products.destroy', $encryptedId) }}" id="deleteForm" class="hidden">
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
    @if($loanProduct->description)
        <div class="glass p-6 rounded-2xl">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                <i class="fa-solid fa-align-left text-primary-500 text-xs"></i>
                Product Description
            </h3>
            <p class="text-sm text-primary-700 dark:text-primary-300 leading-relaxed">{{ $loanProduct->description }}</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
  function confirmDelete() {
    Swal.fire({
      title: 'Are you sure?',
      text: 'Do you want to delete this loan product?',
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
