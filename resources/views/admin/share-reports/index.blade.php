@extends('layouts.admin')

@section('breadcrumb', 'System › Share Reports')
@section('page_title', 'Share Reports Dashboard')

@section('content')

<div class="space-y-6">

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Total Products</p>
          <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ $totalProducts }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
          <i class="fa-solid fa-box text-blue-600 dark:text-blue-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Total Purchases</p>
          <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ $totalPurchases }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
          <i class="fa-solid fa-cart-shopping text-green-600 dark:text-green-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Total Certificates</p>
          <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ $totalCertificates }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
          <i class="fa-solid fa-certificate text-purple-600 dark:text-purple-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Total Transfers</p>
          <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ $totalTransfers }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
          <i class="fa-solid fa-arrow-right-arrow-left text-amber-600 dark:text-amber-400 text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Total Dividends</p>
          <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ $totalDividends }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center">
          <i class="fa-solid fa-money-bill-trend-up text-teal-600 dark:text-teal-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Total Transactions</p>
          <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ $totalTransactions }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
          <i class="fa-solid fa-exchange-alt text-indigo-600 dark:text-indigo-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Total Investment</p>
          <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ number_format($totalInvestment, 2) }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
          <i class="fa-solid fa-dollar-sign text-green-600 dark:text-green-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Dividends Paid</p>
          <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ number_format($totalDividendsPaid, 2) }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
          <i class="fa-solid fa-hand-holding-dollar text-rose-600 dark:text-rose-400 text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <h3 class="text-lg font-semibold text-primary-900 dark:text-white mb-4">Quick Links</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <a href="{{ route('admin.share-products.index') }}" class="flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
        <i class="fa-solid fa-box text-primary-600 dark:text-primary-400"></i>
        <span class="text-sm text-primary-900 dark:text-white">Share Products</span>
      </a>
      <a href="{{ route('admin.share-purchases.index') }}" class="flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
        <i class="fa-solid fa-cart-shopping text-primary-600 dark:text-primary-400"></i>
        <span class="text-sm text-primary-900 dark:text-white">Share Purchases</span>
      </a>
      <a href="{{ route('admin.share-certificates.index') }}" class="flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
        <i class="fa-solid fa-certificate text-primary-600 dark:text-primary-400"></i>
        <span class="text-sm text-primary-900 dark:text-white">Share Certificates</span>
      </a>
      <a href="{{ route('admin.share-transfers.index') }}" class="flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
        <i class="fa-solid fa-arrow-right-arrow-left text-primary-600 dark:text-primary-400"></i>
        <span class="text-sm text-primary-900 dark:text-white">Share Transfers</span>
      </a>
      <a href="{{ route('admin.share-dividends.index') }}" class="flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
        <i class="fa-solid fa-money-bill-trend-up text-primary-600 dark:text-primary-400"></i>
        <span class="text-sm text-primary-900 dark:text-white">Share Dividends</span>
      </a>
      <a href="{{ route('admin.share-transactions.index') }}" class="flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
        <i class="fa-solid fa-exchange-alt text-primary-600 dark:text-primary-400"></i>
        <span class="text-sm text-primary-900 dark:text-white">Share Transactions</span>
      </a>
      <a href="{{ route('admin.share-settings.index') }}" class="flex items-center gap-3 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors">
        <i class="fa-solid fa-gear text-primary-600 dark:text-primary-400"></i>
        <span class="text-sm text-primary-900 dark:text-white">Share Settings</span>
      </a>
    </div>
  </div>

</div>

@endsection
