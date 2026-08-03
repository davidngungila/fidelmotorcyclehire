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

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Assets Section -->
    <div class="glass rounded-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
          <i class="fa-solid fa-landmark"></i> Assets
        </h3>
      </div>
      
      <div class="p-6 space-y-6">
        <!-- Current Assets -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4">
          <h4 class="text-md font-bold text-blue-700 dark:text-blue-300 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-coins text-sm"></i> Current Assets
          </h4>
          <div class="space-y-3 pl-4">
            @foreach($currentAssets as $asset)
              <div class="flex justify-between items-center py-1 border-b border-blue-100 dark:border-blue-800/30 last:border-0">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $asset->account_name }}</span>
                <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($asset->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-3 border-t-2 border-blue-200 dark:border-blue-700">
              <span class="font-bold text-blue-900 dark:text-blue-100">Total Current Assets</span>
              <span class="font-mono font-bold text-lg text-blue-700 dark:text-blue-300">{{ number_format($totalCurrentAssets, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Fixed Assets -->
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4">
          <h4 class="text-md font-bold text-purple-700 dark:text-purple-300 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-building text-sm"></i> Fixed Assets
          </h4>
          <div class="space-y-3 pl-4">
            @foreach($fixedAssets as $asset)
              <div class="flex justify-between items-center py-1 border-b border-purple-100 dark:border-purple-800/30 last:border-0">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $asset->account_name }}</span>
                <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($asset->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-3 border-t-2 border-purple-200 dark:border-purple-700">
              <span class="font-bold text-purple-900 dark:text-purple-100">Total Fixed Assets</span>
              <span class="font-mono font-bold text-lg text-purple-700 dark:text-purple-300">{{ number_format($totalFixedAssets, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Total Assets -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-4">
          <div class="flex justify-between items-center">
            <span class="text-lg font-bold text-white flex items-center gap-2">
              <i class="fa-solid fa-chart-pie"></i> Total Assets
            </span>
            <span class="font-mono text-2xl font-bold text-white">{{ number_format($totalAssets, 2) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Liabilities & Equity Section -->
    <div class="glass rounded-2xl overflow-hidden">
      <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
          <i class="fa-solid fa-scale-balanced"></i> Liabilities & Equity
        </h3>
      </div>
      
      <div class="p-6 space-y-6">
        <!-- Current Liabilities -->
        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl p-4">
          <h4 class="text-md font-bold text-orange-700 dark:text-orange-300 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-sm"></i> Current Liabilities
          </h4>
          <div class="space-y-3 pl-4">
            @foreach($currentLiabilities as $liability)
              <div class="flex justify-between items-center py-1 border-b border-orange-100 dark:border-orange-800/30 last:border-0">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $liability->account_name }}</span>
                <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($liability->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-3 border-t-2 border-orange-200 dark:border-orange-700">
              <span class="font-bold text-orange-900 dark:text-orange-100">Total Current Liabilities</span>
              <span class="font-mono font-bold text-lg text-orange-700 dark:text-orange-300">{{ number_format($totalCurrentLiabilities, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Long Term Liabilities -->
        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4">
          <h4 class="text-md font-bold text-red-700 dark:text-red-300 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-clock text-sm"></i> Long Term Liabilities
          </h4>
          <div class="space-y-3 pl-4">
            @foreach($longTermLiabilities as $liability)
              <div class="flex justify-between items-center py-1 border-b border-red-100 dark:border-red-800/30 last:border-0">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $liability->account_name }}</span>
                <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($liability->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-3 border-t-2 border-red-200 dark:border-red-700">
              <span class="font-bold text-red-900 dark:text-red-100">Total Long Term Liabilities</span>
              <span class="font-mono font-bold text-lg text-red-700 dark:text-red-300">{{ number_format($totalLongTermLiabilities, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Total Liabilities -->
        <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-4">
          <div class="flex justify-between items-center">
            <span class="font-bold text-white">Total Liabilities</span>
            <span class="font-mono font-bold text-xl text-white">{{ number_format($totalLiabilities, 2) }}</span>
          </div>
        </div>

        <!-- Equity -->
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
          <h4 class="text-md font-bold text-green-700 dark:text-green-300 mb-3 flex items-center gap-2">
            <i class="fa-solid fa-users text-sm"></i> Equity
          </h4>
          <div class="space-y-3 pl-4">
            @foreach($equityAccounts as $equity)
              <div class="flex justify-between items-center py-1 border-b border-green-100 dark:border-green-800/30 last:border-0">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $equity->account_name }}</span>
                <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($equity->current_balance, 2) }}</span>
              </div>
            @endforeach
            <div class="flex justify-between items-center pt-3 border-t-2 border-green-200 dark:border-green-700">
              <span class="font-bold text-green-900 dark:text-green-100">Total Equity</span>
              <span class="font-mono font-bold text-lg text-green-700 dark:text-green-300">{{ number_format($totalEquity, 2) }}</span>
            </div>
          </div>
        </div>

        <!-- Total Liabilities & Equity -->
        <div class="bg-gradient-to-r from-green-500 to-teal-500 rounded-xl p-4">
          <div class="flex justify-between items-center">
            <span class="text-lg font-bold text-white flex items-center gap-2">
              <i class="fa-solid fa-chart-line"></i> Total Liabilities & Equity
            </span>
            <span class="font-mono text-2xl font-bold text-white">{{ number_format($totalLiabilitiesAndEquity, 2) }}</span>
          </div>
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

@endsection
