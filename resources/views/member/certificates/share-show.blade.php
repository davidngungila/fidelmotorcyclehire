@extends('layouts.member')

@section('breadcrumb', 'My Certificates \u203A Share Certificate')
@section('page_title', 'Share Certificate')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Certificate #{{ $certificate->certificate_number }}</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Issued on {{ $certificate->issue_date->format('F d, Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('member.certificates.share-print', $certificate->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold transition-all">
        <i class="fa-solid fa-print"></i> Print Certificate
      </a>
      <a href="{{ route('member.certificates.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Certificate Details</h3>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Certificate Number:</span>
            <div class="font-mono font-bold text-gray-900 dark:text-white">{{ $certificate->certificate_number }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Issue Date:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $certificate->issue_date->format('F d, Y') }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Number of Shares:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $certificate->number_of_shares }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Share Value:</span>
            <div class="font-mono font-bold text-gray-900 dark:text-white">TSh {{ number_format($certificate->share_value, 2) }}</div>
          </div>
          <div class="col-span-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
            <div>
              @if($certificate->is_active)
                <span class="badge badge-green">Active</span>
              @else
                <span class="badge badge-red">Inactive</span>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Share Purchase Information</h3>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Share Product:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $certificate->sharePurchase->shareProduct->name ?? 'N/A' }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Purchase Date:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $certificate->sharePurchase->purchase_date ? $certificate->sharePurchase->purchase_date->format('F d, Y') : 'N/A' }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Price per Share:</span>
            <div class="font-mono font-bold text-gray-900 dark:text-white">TSh {{ number_format($certificate->sharePurchase->price_per_share, 2) }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Amount:</span>
            <div class="font-mono font-bold text-green-600 dark:text-green-400">TSh {{ number_format($certificate->sharePurchase->total_amount, 2) }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="space-y-2">
          <a href="{{ route('member.certificates.share-print', $certificate->id) }}" target="_blank" class="block w-full text-center px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold transition-all">
            <i class="fa-solid fa-print mr-2"></i> Print Certificate
          </a>
          <a href="{{ route('member.investments.index') }}" class="block w-full text-center px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
            <i class="fa-solid fa-chart-line mr-2"></i> View Investments
          </a>
        </div>
      </div>

      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Certificate Info</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          This certificate confirms your ownership of shares in the organization. You can use this document as proof of share ownership for any official purposes.
        </p>
      </div>
    </div>
  </div>
</div>
