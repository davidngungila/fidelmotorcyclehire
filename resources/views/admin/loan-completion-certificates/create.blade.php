@extends('layouts.admin')

@section('breadcrumb', 'Loans \u203A Completion Certificates \u203A Generate Certificate')
@section('page_title', 'Generate Completion Certificate')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Generate Completion Certificate</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Create certificate for loan #{{ $loan->loan_number }}</p>
    </div>
    <a href="{{ route('admin.loans.show', $loan->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
      <i class="fa-solid fa-arrow-left"></i> Back to Loan
    </a>
  </div>

  <div class="glass rounded-xl p-8">
    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
      <h3 class="font-semibold text-green-800 dark:text-green-200 mb-2">Loan Details</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
        <div>
          <span class="text-green-600 dark:text-green-400">Member:</span>
          <div class="font-semibold text-gray-900 dark:text-white">{{ $loan->user->name }}</div>
        </div>
        <div>
          <span class="text-green-600 dark:text-green-400">Loan Number:</span>
          <div class="font-mono font-semibold text-gray-900 dark:text-white">{{ $loan->loan_number }}</div>
        </div>
        <div>
          <span class="text-green-600 dark:text-green-400">Principal Amount:</span>
          <div class="font-mono font-semibold text-gray-900 dark:text-white">{{ number_format($loan->principal_amount, 2) }}</div>
        </div>
        <div>
          <span class="text-green-600 dark:text-green-400">Amount Paid:</span>
          <div class="font-mono font-semibold text-green-600 dark:text-green-400">{{ number_format($loan->amount_paid, 2) }}</div>
        </div>
      </div>
    </div>

    <form action="{{ route('admin.loan-completion-certificates.store', $loan->id) }}" method="POST" class="space-y-6">
      @csrf
      
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
        <textarea name="notes" rows="3"
          class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Add any additional notes about this certificate"></textarea>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.loans.show', $loan->id) }}" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          Generate Certificate
        </button>
      </div>
    </form>
  </div>
</div>
