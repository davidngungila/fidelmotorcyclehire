@extends('layouts.member')

@section('breadcrumb', 'My Certificates')
@section('page_title', 'My Certificates')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Certificates</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">View and download your loan completion and share certificates</p>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="glass rounded-xl p-5 border-l-4 border-green-500">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
          <i class="fa-solid fa-certificate text-green-600 dark:text-green-400 text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm text-gray-600 dark:text-gray-400">Loan Completion Certificates</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($loanCertificates) }}</p>
        </div>
      </div>
    </div>
    <div class="glass rounded-xl p-5 border-l-4 border-blue-500">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
          <i class="fa-solid fa-file-contract text-blue-600 dark:text-blue-400 text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm text-gray-600 dark:text-gray-400">Share Certificates</p>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($shareCertificates) }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Loan Completion Certificates Section -->
  <div class="glass rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center">
            <i class="fa-solid fa-certificate text-white"></i>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Loan Completion Certificates</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ count($loanCertificates) }} certificate{{ count($loanCertificates) !== 1 ? 's' : '' }} available</p>
          </div>
        </div>
      </div>
    </div>

    @forelse($loanCertificates as $certificate)
      <div class="p-5 border-b border-gray-100 dark:border-gray-800 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="flex items-start gap-4 flex-1 min-w-0">
            <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-mono text-xs font-bold bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800/60">
                  {{ $certificate->certificate_number }}
                </span>
                @if($certificate->is_active)
                  <span class="badge badge-green text-xs">Active</span>
                @else
                  <span class="badge badge-red text-xs">Inactive</span>
                @endif
              </div>
              <h4 class="font-semibold text-gray-900 dark:text-white text-base mb-1">
                Loan #{{ $certificate->loan->loan_number }}
              </h4>
              <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                <span class="flex items-center gap-1">
                  <i class="fa-solid fa-calendar-check text-green-500"></i>
                  Completed: {{ $certificate->completion_date->format('M d, Y') }}
                </span>
                <span class="flex items-center gap-1">
                  <i class="fa-solid fa-money-bill-wave text-green-500"></i>
                  TSh {{ number_format($certificate->total_paid, 2) }}
                </span>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('member.certificates.loan-show', $certificate->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-colors">
              <i class="fa-solid fa-eye"></i>
              View
            </a>
            <a href="{{ route('member.certificates.loan-print', $certificate->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold transition-colors">
              <i class="fa-solid fa-print"></i>
              Print
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center mx-auto mb-4">
          <i class="fa-solid fa-certificate text-green-400 text-2xl"></i>
        </div>
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No loan completion certificates</h4>
        <p class="text-gray-600 dark:text-gray-400 text-sm">Certificates will appear here when you complete a loan</p>
      </div>
    @endforelse
  </div>

  <!-- Share Certificates Section -->
  <div class="glass rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
            <i class="fa-solid fa-file-contract text-white"></i>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Share Certificates</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ count($shareCertificates) }} certificate{{ count($shareCertificates) !== 1 ? 's' : '' }} available</p>
          </div>
        </div>
      </div>
    </div>

    @forelse($shareCertificates as $certificate)
      <div class="p-5 border-b border-gray-100 dark:border-gray-800 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="flex items-start gap-4 flex-1 min-w-0">
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-chart-line text-blue-600 dark:text-blue-400 text-xl"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-mono text-xs font-bold bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                  {{ $certificate->certificate_number }}
                </span>
                @if($certificate->is_active)
                  <span class="badge badge-green text-xs">Active</span>
                @else
                  <span class="badge badge-red text-xs">Inactive</span>
                @endif
              </div>
              <h4 class="font-semibold text-gray-900 dark:text-white text-base mb-1">
                {{ $certificate->sharePurchase->shareProduct->name ?? 'Unknown Product' }}
              </h4>
              <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                <span class="flex items-center gap-1">
                  <i class="fa-solid fa-layer-group text-blue-500"></i>
                  {{ $certificate->number_of_shares }} shares
                </span>
                <span class="flex items-center gap-1">
                  <i class="fa-solid fa-money-bill-wave text-blue-500"></i>
                  TSh {{ number_format($certificate->sharePurchase->total_amount, 2) }}
                </span>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-2 sm:gap-3">
            <a href="{{ route('member.certificates.share-show', $certificate->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-colors">
              <i class="fa-solid fa-eye"></i>
              View
            </a>
            <a href="{{ route('member.certificates.share-print', $certificate->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold transition-colors">
              <i class="fa-solid fa-print"></i>
              Print
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center mx-auto mb-4">
          <i class="fa-solid fa-file-contract text-blue-400 text-2xl"></i>
        </div>
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No share certificates</h4>
        <p class="text-gray-600 dark:text-gray-400 text-sm">Certificates will appear here when you purchase shares</p>
      </div>
    @endforelse
  </div>
</div>
