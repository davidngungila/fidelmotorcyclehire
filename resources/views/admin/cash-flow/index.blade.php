@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Cash Flow Statement')
@section('page_title', 'Cash Flow Statement')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cash Flow Statement</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">
        For period {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
      </p>
    </div>
    <div class="flex items-center gap-2">
      <form action="{{ route('admin.cash-flow.index') }}" method="GET" class="flex items-center gap-2">
        <input type="date" name="start_date" value="{{ $startDate }}"
          class="form-input py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
        <input type="date" name="end_date" value="{{ $endDate }}"
          class="form-input py-2 px-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
        <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          Generate
        </button>
      </form>
    </div>
  </div>

  <div class="glass rounded-xl p-6">
    <div class="space-y-6">
      <!-- Operating Activities -->
      <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Cash Flow from Operating Activities</h3>
        
        <div class="space-y-2 pl-4">
          <div class="flex justify-between items-center">
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Cash Inflows from Operations</span>
            <span class="font-mono text-sm text-green-600 dark:text-green-400">{{ number_format($totalOperatingInflows, 2) }}</span>
          </div>
          @foreach($operatingInflows as $inflow)
            <div class="flex justify-between items-center pl-4">
              <span class="text-sm text-gray-600 dark:text-gray-400">{{ $inflow->account_name }}</span>
              <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($inflow->current_balance, 2) }}</span>
            </div>
          @endforeach
          
          <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Cash Outflows from Operations</span>
            <span class="font-mono text-sm text-red-600 dark:text-red-400">{{ number_format($totalOperatingOutflows, 2) }}</span>
          </div>
          @foreach($operatingOutflows as $outflow)
            <div class="flex justify-between items-center pl-4">
              <span class="text-sm text-gray-600 dark:text-gray-400">{{ $outflow->account_name }}</span>
              <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($outflow->current_balance, 2) }}</span>
            </div>
          @endforeach
          
          <div class="flex justify-between items-center pt-4 border-t-2 border-gray-300 dark:border-gray-600">
            <span class="font-bold text-gray-900 dark:text-white">Net Cash from Operating Activities</span>
            <span class="font-mono font-bold {{ $netOperatingCashFlow >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
              {{ number_format($netOperatingCashFlow, 2) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Investing Activities -->
      <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Cash Flow from Investing Activities</h3>
        
        <div class="space-y-2 pl-4">
          @foreach($investingInflows as $inflow)
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">{{ $inflow->account_name }}</span>
              <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($inflow->current_balance, 2) }}</span>
            </div>
          @endforeach
          
          <div class="flex justify-between items-center pt-4 border-t-2 border-gray-300 dark:border-gray-600">
            <span class="font-bold text-gray-900 dark:text-white">Net Cash from Investing Activities</span>
            <span class="font-mono font-bold {{ $totalInvestingInflows >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
              {{ number_format($totalInvestingInflows, 2) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Financing Activities -->
      <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Cash Flow from Financing Activities</h3>
        
        <div class="space-y-2 pl-4">
          @foreach($financingInflows as $inflow)
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">{{ $inflow->account_name }}</span>
              <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($inflow->current_balance, 2) }}</span>
            </div>
          @endforeach
          
          @foreach($equityAccounts as $equity)
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">{{ $equity->account_name }}</span>
              <span class="font-mono text-sm text-gray-900 dark:text-white">{{ number_format($equity->current_balance, 2) }}</span>
            </div>
          @endforeach
          
          <div class="flex justify-between items-center pt-4 border-t-2 border-gray-300 dark:border-gray-600">
            <span class="font-bold text-gray-900 dark:text-white">Net Cash from Financing Activities</span>
            <span class="font-mono font-bold {{ $netFinancingCashFlow >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
              {{ number_format($netFinancingCashFlow, 2) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Net Cash Flow -->
      <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
        <div class="flex justify-between items-center">
          <span class="text-lg font-bold text-gray-900 dark:text-white">Net Change in Cash</span>
          <span class="font-mono text-2xl font-bold {{ $netCashFlow >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
            {{ number_format($netCashFlow, 2) }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
