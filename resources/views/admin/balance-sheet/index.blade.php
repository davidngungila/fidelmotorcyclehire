@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Balance Sheet')
@section('page_title', 'Balance Sheet')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Balance Sheet</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">As of {{ \Carbon\Carbon::parse($asOfDate)->format('F d, Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
      <form action="{{ route('admin.balance-sheet.index') }}" method="GET" class="flex items-center gap-2">
        <input type="date" name="as_of_date" value="{{ $asOfDate }}"
          class="form-input py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
        <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          Generate
        </button>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Assets Section -->
    <div class="glass rounded-xl p-6">
      <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Assets</h3>
      
      <div class="space-y-4">
        <!-- Current Assets -->
        <div>
          <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Current Assets</h4>
          <div class="space-y-2 pl-4">
            @foreach($currentAssets as $asset)
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $asset->account_name }}</span>
                <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($asset->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
              <span class="font-semibold text-gray-900 dark:text-white">Total Current Assets</span>
              <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($totalCurrentAssets, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Fixed Assets -->
        <div>
          <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Fixed Assets</h4>
          <div class="space-y-2 pl-4">
            @foreach($fixedAssets as $asset)
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $asset->account_name }}</span>
                <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($asset->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
              <span class="font-semibold text-gray-900 dark:text-white">Total Fixed Assets</span>
              <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($totalFixedAssets, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Total Assets -->
        <div class="flex justify-between items-center pt-4 border-t-2 border-gray-300 dark:border-gray-600">
          <span class="text-lg font-bold text-gray-900 dark:text-white">Total Assets</span>
          <span class="font-mono text-lg font-bold text-primary-700 dark:text-primary-300">{{ number_format($totalAssets, 2) }}</span>
        </div>
      </div>
    </div>

    <!-- Liabilities & Equity Section -->
    <div class="glass rounded-xl p-6">
      <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Liabilities & Equity</h3>
      
      <div class="space-y-4">
        <!-- Current Liabilities -->
        <div>
          <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Current Liabilities</h4>
          <div class="space-y-2 pl-4">
            @foreach($currentLiabilities as $liability)
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $liability->account_name }}</span>
                <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($liability->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
              <span class="font-semibold text-gray-900 dark:text-white">Total Current Liabilities</span>
              <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($totalCurrentLiabilities, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Long Term Liabilities -->
        <div>
          <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Long Term Liabilities</h4>
          <div class="space-y-2 pl-4">
            @foreach($longTermLiabilities as $liability)
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $liability->account_name }}</span>
                <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($liability->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
              <span class="font-semibold text-gray-900 dark:text-white">Total Long Term Liabilities</span>
              <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($totalLongTermLiabilities, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Total Liabilities -->
        <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
          <span class="font-semibold text-gray-900 dark:text-white">Total Liabilities</span>
          <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($totalLiabilities, 2) }}</span>
        </div>

        <!-- Equity -->
        <div>
          <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Equity</h4>
          <div class="space-y-2 pl-4">
            @foreach($equityAccounts as $equity)
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $equity->account_name }}</span>
                <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($equity->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
              <span class="font-semibold text-gray-900 dark:text-white">Total Equity</span>
              <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($totalEquity, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Total Liabilities & Equity -->
        <div class="flex justify-between items-center pt-4 border-t-2 border-gray-300 dark:border-gray-600">
          <span class="text-lg font-bold text-gray-900 dark:text-white">Total Liabilities & Equity</span>
          <span class="font-mono text-lg font-bold text-primary-700 dark:text-primary-300">{{ number_format($totalLiabilitiesAndEquity, 2) }}</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Balance Check -->
  <div class="glass rounded-xl p-6">
    <div class="flex items-center justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Balance Check</h3>
        @if(abs($totalAssets - $totalLiabilitiesAndEquity) < 0.01)
          <p class="text-green-600 dark:text-green-400 font-semibold mt-1">
            <i class="fa-solid fa-check-circle mr-2"></i>Balance sheet is balanced
          </p>
        @else
          <p class="text-red-600 dark:text-red-400 font-semibold mt-1">
            <i class="fa-solid fa-exclamation-circle mr-2"></i>Balance sheet is not balanced
          </p>
        @endif
      </div>
      <div class="text-right">
        <div class="text-sm text-gray-600 dark:text-gray-400">Difference:</div>
        <div class="font-mono font-bold text-xl {{ abs($totalAssets - $totalLiabilitiesAndEquity) < 0.01 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
          {{ number_format(abs($totalAssets - $totalLiabilitiesAndEquity), 2) }}
        </div>
      </div>
    </div>
  </div>
</div>
