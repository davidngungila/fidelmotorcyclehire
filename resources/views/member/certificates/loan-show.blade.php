@extends('layouts.member')

@section('breadcrumb', 'My Certificates \u203A Loan Completion Certificate')
@section('page_title', 'Loan Completion Certificate')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Loan Completion Certificate</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $certificate->certificate_number }}</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('member.certificates.loan-print', $certificate->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
        <i class="fa-solid fa-print"></i> Print Certificate
      </a>
      <a href="{{ route('member.certificates.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="glass rounded-xl p-8 max-w-4xl mx-auto border-4 border-green-200 dark:border-green-800">
    <div class="text-center mb-8">
      <div class="w-20 h-20 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center mx-auto mb-4">
        <i class="fa-solid fa-certificate text-4xl text-green-600 dark:text-green-400"></i>
      </div>
      <h2 class="text-3xl font-bold text-green-700 dark:text-green-400 mb-2">Certificate of Completion</h2>
      <p class="text-gray-600 dark:text-gray-400">This certifies that the loan has been fully repaid</p>
    </div>

    <div class="space-y-6">
      <div class="grid grid-cols-2 gap-6">
        <div>
          <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Certificate Number</label>
          <div class="font-mono text-lg font-bold text-gray-900 dark:text-white">{{ $certificate->certificate_number }}</div>
        </div>
        <div>
          <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Issue Date</label>
          <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $certificate->completion_date->format('F d, Y') }}</div>
        </div>
      </div>

      <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Loan Details</h3>
        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Loan Number</label>
            <div class="font-mono text-lg font-bold text-gray-900 dark:text-white">{{ $certificate->loan->loan_number }}</div>
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Loan Amount</label>
            <div class="text-lg font-semibold text-gray-900 dark:text-white">TSh {{ number_format($certificate->loan->principal_amount, 2) }}</div>
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Purpose</label>
            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($certificate->loan->purpose) }}</div>
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Disbursement Date</label>
            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $certificate->loan->disbursement_date ? $certificate->loan->disbursement_date->format('F d, Y') : 'N/A' }}</div>
          </div>
        </div>
      </div>

      <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Member Details</h3>
        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Member Name</label>
            <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $certificate->loan->member->name ?? 'N/A' }}</div>
          </div>
          <div>
            <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Member Number</label>
            <div class="font-mono text-lg font-bold text-gray-900 dark:text-white">{{ $certificate->loan->member_number }}</div>
          </div>
        </div>
      </div>

      <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6 text-center">
          <p class="text-lg font-bold text-green-700 dark:text-green-400 mb-2">✓ Loan Successfully Completed</p>
          <p class="text-sm text-gray-600 dark:text-gray-400">This certificate serves as proof that the loan has been fully repaid according to the terms agreed upon.</p>
        </div>
      </div>

      @if($certificate->notes)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
          <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Additional Notes</label>
          <div class="text-sm text-gray-900 dark:text-white mt-2">{{ $certificate->notes }}</div>
        </div>
      @endif
    </div>
  </div>
</div>
