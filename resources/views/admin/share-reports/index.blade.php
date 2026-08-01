@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Reports')
@section('page_title', 'Share Reports Dashboard')

@section('content')

<div class="space-y-6">

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="glass p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-400 mb-1">Total Products</p>
          <p class="text-2xl font-bold text-white">{{ $totalProducts }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-primary-600/20 flex items-center justify-center">
          <i class="fa-solid fa-box text-primary-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-400 mb-1">Total Purchases</p>
          <p class="text-2xl font-bold text-white">{{ $totalPurchases }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-primary-600/20 flex items-center justify-center">
          <i class="fa-solid fa-cart-shopping text-primary-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-400 mb-1">Total Certificates</p>
          <p class="text-2xl font-bold text-white">{{ $totalCertificates }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-primary-600/20 flex items-center justify-center">
          <i class="fa-solid fa-certificate text-primary-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-400 mb-1">Total Transfers</p>
          <p class="text-2xl font-bold text-white">{{ $totalTransfers }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-primary-600/20 flex items-center justify-center">
          <i class="fa-solid fa-arrow-right-arrow-left text-primary-400 text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="glass p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-400 mb-1">Total Dividends</p>
          <p class="text-2xl font-bold text-white">{{ $totalDividends }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-primary-600/20 flex items-center justify-center">
          <i class="fa-solid fa-money-bill-trend-up text-primary-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-400 mb-1">Total Transactions</p>
          <p class="text-2xl font-bold text-white">{{ $totalTransactions }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-primary-600/20 flex items-center justify-center">
          <i class="fa-solid fa-exchange-alt text-primary-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-400 mb-1">Total Investment</p>
          <p class="text-2xl font-bold text-white">{{ number_format($totalInvestment, 2) }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center">
          <i class="fa-solid fa-dollar-sign text-green-400 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-400 mb-1">Dividends Paid</p>
          <p class="text-2xl font-bold text-white">{{ number_format($totalDividendsPaid, 2) }}</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-amber-600/20 flex items-center justify-center">
          <i class="fa-solid fa-hand-holding-dollar text-amber-400 text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="glass p-6">
    <h3 class="text-lg font-semibold text-white mb-4">Quick Links</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <a href="{{ route('admin.share-products.index') }}" class="flex items-center gap-3 p-4 bg-primary-800/30 rounded-lg hover:bg-primary-800/50 transition-colors">
        <i class="fa-solid fa-box text-primary-400"></i>
        <span class="text-sm text-white">Share Products</span>
      </a>
      <a href="{{ route('admin.share-purchases.index') }}" class="flex items-center gap-3 p-4 bg-primary-800/30 rounded-lg hover:bg-primary-800/50 transition-colors">
        <i class="fa-solid fa-cart-shopping text-primary-400"></i>
        <span class="text-sm text-white">Share Purchases</span>
      </a>
      <a href="{{ route('admin.share-certificates.index') }}" class="flex items-center gap-3 p-4 bg-primary-800/30 rounded-lg hover:bg-primary-800/50 transition-colors">
        <i class="fa-solid fa-certificate text-primary-400"></i>
        <span class="text-sm text-white">Share Certificates</span>
      </a>
      <a href="{{ route('admin.share-transfers.index') }}" class="flex items-center gap-3 p-4 bg-primary-800/30 rounded-lg hover:bg-primary-800/50 transition-colors">
        <i class="fa-solid fa-arrow-right-arrow-left text-primary-400"></i>
        <span class="text-sm text-white">Share Transfers</span>
      </a>
      <a href="{{ route('admin.share-dividends.index') }}" class="flex items-center gap-3 p-4 bg-primary-800/30 rounded-lg hover:bg-primary-800/50 transition-colors">
        <i class="fa-solid fa-money-bill-trend-up text-primary-400"></i>
        <span class="text-sm text-white">Share Dividends</span>
      </a>
      <a href="{{ route('admin.share-transactions.index') }}" class="flex items-center gap-3 p-4 bg-primary-800/30 rounded-lg hover:bg-primary-800/50 transition-colors">
        <i class="fa-solid fa-exchange-alt text-primary-400"></i>
        <span class="text-sm text-white">Share Transactions</span>
      </a>
      <a href="{{ route('admin.share-settings.index') }}" class="flex items-center gap-3 p-4 bg-primary-800/30 rounded-lg hover:bg-primary-800/50 transition-colors">
        <i class="fa-solid fa-gear text-primary-400"></i>
        <span class="text-sm text-white">Share Settings</span>
      </a>
    </div>
  </div>

</div>

@endsection
