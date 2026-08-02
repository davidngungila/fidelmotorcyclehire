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

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Loan Completion Certificates -->
    <div class="glass rounded-xl p-6 loan-certificates">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Loan Completion Certificates</h3>
          <p class="text-sm text-gray-600 dark:text-gray-400">{{ count($loanCertificates) }} certificate{{ count($loanCertificates) !== 1 ? 's' : '' }}</p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
          <i class="fa-solid fa-certificate text-green-600 dark:text-green-400"></i>
        </div>
      </div>

      @forelse($loanCertificates as $certificate)
        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg mb-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
          <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-green-50 dark:bg-green-900/40 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800/60">
                  {{ $certificate->certificate_number }}
                </span>
                @if($certificate->is_active)
                  <span class="badge badge-green text-[10px]">Active</span>
                @else
                  <span class="badge badge-red text-[10px]">Inactive</span>
                @endif
              </div>
              <h4 class="font-semibold text-gray-900 dark:text-white text-sm truncate">
                Loan #{{ $certificate->loan->loan_number }}
              </h4>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Completed: {{ $certificate->completion_date->format('M d, Y') }}
              </p>
            </div>
            <div class="flex items-center gap-2 ml-4">
              <a href="{{ route('member.certificates.loan-show', $certificate->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                <i class="fa-solid fa-eye"></i>
              </a>
              <a href="{{ route('member.certificates.loan-print', $certificate->id) }}" target="_blank" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">
                <i class="fa-solid fa-print"></i>
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
          <i class="fa-solid fa-certificate text-3xl mb-3 block opacity-30"></i>
          <p class="text-sm font-semibold mb-1">No loan completion certificates</p>
          <p class="text-xs">Certificates will appear here when you complete a loan</p>
        </div>
      @endforelse
    </div>

    <!-- Share Certificates -->
    <div class="glass rounded-xl p-6 share-certificates">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Share Certificates</h3>
          <p class="text-sm text-gray-600 dark:text-gray-400">{{ count($shareCertificates) }} certificate{{ count($shareCertificates) !== 1 ? 's' : '' }}</p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
          <i class="fa-solid fa-file-contract text-blue-600 dark:text-blue-400"></i>
        </div>
      </div>

      @forelse($shareCertificates as $certificate)
        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg mb-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
          <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center px-2 py-0.5 rounded font-mono text-[10px] font-bold bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                  {{ $certificate->certificate_number }}
                </span>
                @if($certificate->is_active)
                  <span class="badge badge-green text-[10px]">Active</span>
                @else
                  <span class="badge badge-red text-[10px]">Inactive</span>
                @endif
              </div>
              <h4 class="font-semibold text-gray-900 dark:text-white text-sm truncate">
                {{ $certificate->sharePurchase->shareProduct->name ?? 'Unknown Product' }}
              </h4>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ $certificate->number_of_shares }} shares
              </p>
            </div>
            <div class="flex items-center gap-2 ml-4">
              <a href="{{ route('member.certificates.share-show', $certificate->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                <i class="fa-solid fa-eye"></i>
              </a>
              <a href="{{ route('member.certificates.share-print', $certificate->id) }}" target="_blank" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">
                <i class="fa-solid fa-print"></i>
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
          <i class="fa-solid fa-file-contract text-3xl mb-3 block opacity-30"></i>
          <p class="text-sm font-semibold mb-1">No share certificates</p>
          <p class="text-xs">Certificates will appear here when you purchase shares</p>
        </div>
      @endforelse
    </div>
  </div>
</div>
