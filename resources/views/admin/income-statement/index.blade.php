@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Income Statement')
@section('page_title', 'Income Statement')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Income Statement</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">
        For period {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
      </p>
    </div>
    <div class="flex items-center gap-2">
      <form action="{{ route('admin.income-statement.index') }}" method="GET" class="flex items-center gap-2">
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
      <!-- Revenue Section -->
      <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Revenue</h3>
        
        <div class="space-y-4">
          <!-- Operating Revenue -->
          <div>
            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Operating Revenue</h4>
            <div class="space-y-2 pl-4">
              @foreach($operatingRevenue as $revenue)
                <div class="flex justify-between items-center">
                  <span class="text-sm text-gray-600 dark:text-gray-400">{{ $revenue->account_name }}</span>
                  <span class="font-mono text-sm text-green-600 dark:text-green-400">{{ number_format($revenue->current_balance, 2) }}</span>
                </div>
              @endforeach
              <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                <span class="font-semibold text-gray-900 dark:text-white">Total Operating Revenue</span>
                <span class="font-mono font-bold text-green-600 dark:text-green-400">{{ number_format($totalOperatingRevenue, 2) }}</span>
              </div>
            </div>
          </div>

          <!-- Non-Operating Revenue -->
          <div>
            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Non-Operating Revenue</h4>
            <div class="space-y-2 pl-4">
              @foreach($nonOperatingRevenue as $revenue)
                <div class="flex justify-between items-center">
                  <span class="text-sm text-gray-600 dark:text-gray-400">{{ $revenue->account_name }}</span>
                  <span class="font-mono text-sm text-green-600 dark:text-green-400">{{ number_format($revenue->current_balance, 2) }}</span>
                </div>
              @endforeach
              <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                <span class="font-semibold text-gray-900 dark:text-white">Total Non-Operating Revenue</span>
                <span class="font-mono font-bold text-green-600 dark:text-green-400">{{ number_format($totalNonOperatingRevenue, 2) }}</span>
              </div>
            </div>
          </div>

          <!-- Total Revenue -->
          <div class="flex justify-between items-center pt-4 border-t-2 border-gray-300 dark:border-gray-600">
            <span class="text-lg font-bold text-gray-900 dark:text-white">Total Revenue</span>
            <span class="font-mono text-lg font-bold text-green-600 dark:text-green-400">{{ number_format($totalRevenue, 2) }}</span>
          </div>
        </div>
      </div>

      <!-- Expenses Section -->
      <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Expenses</h3>
        
        <div class="space-y-4">
          <!-- Operating Expenses -->
          <div>
            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Operating Expenses</h4>
            <div class="space-y-2 pl-4">
              @foreach($operatingExpenses as $expense)
                <div class="flex justify-between items-center">
                  <span class="text-sm text-gray-600 dark:text-gray-400">{{ $expense->account_name }}</span>
                  <span class="font-mono text-sm text-red-600 dark:text-red-400">{{ number_format($expense->current_balance, 2) }}</span>
                </div>
              @endforeach
              <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                <span class="font-semibold text-gray-900 dark:text-white">Total Operating Expenses</span>
                <span class="font-mono font-bold text-red-600 dark:text-red-400">{{ number_format($totalOperatingExpenses, 2) }}</span>
              </div>
            </div>
          </div>

          <!-- Non-Operating Expenses -->
          <div>
            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-2">Non-Operating Expenses</h4>
            <div class="space-y-2 pl-4">
              @foreach($nonOperatingExpenses as $expense)
                <div class="flex justify-between items-center">
                  <span class="text-sm text-gray-600 dark:text-gray-400">{{ $expense->account_name }}</span>
                  <span class="font-mono text-sm text-red-600 dark:text-red-400">{{ number_format($expense->current_balance, 2) }}</span>
                </div>
              @endforeach
              <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                <span class="font-semibold text-gray-900 dark:text-white">Total Non-Operating Expenses</span>
                <span class="font-mono font-bold text-red-600 dark:text-red-400">{{ number_format($totalNonOperatingExpenses, 2) }}</span>
              </div>
            </div>
          </div>

          <!-- Total Expenses -->
          <div class="flex justify-between items-center pt-4 border-t-2 border-gray-300 dark:border-gray-600">
            <span class="text-lg font-bold text-gray-900 dark:text-white">Total Expenses</span>
            <span class="font-mono text-lg font-bold text-red-600 dark:text-red-400">{{ number_format($totalExpenses, 2) }}</span>
          </div>
        </div>
      </div>

      <!-- Net Income Section -->
      <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Gross Profit</span>
            <div class="font-mono font-bold text-xl {{ $grossProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
              {{ number_format($grossProfit, 2) }}
            </div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Net Income</span>
            <div class="font-mono font-bold text-2xl {{ $netIncome >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
              {{ number_format($netIncome, 2) }}
            </div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Profit Margin</span>
            @php
              $profitMargin = $totalRevenue > 0 ? ($netIncome / $totalRevenue) * 100 : 0;
              $marginColorClass = $profitMargin >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
            @endphp
            <div class="font-mono font-bold text-xl {{ $marginColorClass }}">
              {{ number_format($profitMargin, 2) }}%
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
