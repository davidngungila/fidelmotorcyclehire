@extends('layouts.admin')

@section('breadcrumb', 'SWF \u203A Members \u203A Details')
@section('page_title', 'SWF Member Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
@endphp

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.swf.index') }}" class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to SWF</span>
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Member Info -->
    <div class="lg:col-span-1">
      <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4 mb-6">
          <div class="w-16 h-16 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-2xl font-bold">
            {{ substr($swfMember->user->name, 0, 1) }}
          </div>
          <div>
            <h2 class="text-xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $swfMember->user->name }}</h2>
            <p class="text-sm" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">{{ $swfMember->membership_number }}</p>
          </div>
        </div>

        <div class="space-y-4">
          <div class="flex justify-between items-center py-2 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Email</span>
            <span class="text-xs font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $swfMember->user->email }}</span>
          </div>
          <div class="flex justify-between items-center py-2 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Join Date</span>
            <span class="text-xs font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $swfMember->join_date ? $swfMember->join_date->format('M j, Y') : '-' }}</span>
          </div>
          <div class="flex justify-between items-center py-2 border-b border-primary-100 dark:border-primary-900/50">
            <span class="text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Status</span>
            <span class="badge {{ $swfMember->is_active ? 'badge-green' : 'badge-red' }}">{{ $swfMember->is_active ? 'Active' : 'Inactive' }}</span>
          </div>
        </div>

        <div class="mt-6 space-y-3">
          <a href="{{ route('admin.swf.contributions.create', encryptId($swfMember->id)) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 text-white text-sm font-bold transition-all">
            <i class="fa-solid fa-plus"></i> Add Contribution
          </a>
        </div>
      </div>
    </div>

    <!-- Contributions & Benefits -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass p-5 rounded-2xl">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center">
              <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
              <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Total Contributions</p>
              <p class="text-lg font-bold text-primary-900 dark:text-white">{{ $fmt($swfMember->total_contributions) }}</p>
            </div>
          </div>
        </div>
        <div class="glass p-5 rounded-2xl">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
              <i class="fa-solid fa-gift"></i>
            </div>
            <div>
              <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Benefits Received</p>
              <p class="text-lg font-bold text-primary-900 dark:text-white">{{ $fmt($swfMember->total_benefits_received) }}</p>
            </div>
          </div>
        </div>
        <div class="glass p-5 rounded-2xl">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center">
              <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
              <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Current Balance</p>
              <p class="text-lg font-bold {{ $swfMember->total_contributions - $swfMember->total_benefits_received > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $fmt($swfMember->total_contributions - $swfMember->total_benefits_received) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Contributions History -->
      <div class="glass p-6 rounded-2xl">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">Contribution History</h3>
          <span class="text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">{{ $swfMember->contributions->count() }} contributions</span>
        </div>
        
        @if($swfMember->contributions->count() > 0)
          <div class="overflow-x-auto">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>Payment Method</th>
                  <th>Reference</th>
                </tr>
              </thead>
              <tbody>
                @foreach($swfMember->contributions as $contribution)
                  <tr>
                    <td>{{ $contribution->contribution_date->format('M j, Y') }}</td>
                    <td class="font-bold text-green-600 dark:text-green-400">{{ $fmt($contribution->amount) }}</td>
                    <td>{{ ucfirst($contribution->payment_method) }}</td>
                    <td>{{ $contribution->reference_number ?? '-' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-sm text-primary-500 dark:text-primary-400 text-center py-8">No contributions recorded yet.</p>
        @endif
      </div>

      <!-- Benefits Received -->
      <div class="glass p-6 rounded-2xl">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">Benefits Received</h3>
          <span class="text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">{{ $swfMember->benefits->count() }} benefits</span>
        </div>
        
        @if($swfMember->benefits->count() > 0)
          <div class="space-y-3">
            @foreach($swfMember->benefits as $benefit)
              <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-900/50">
                <div class="flex items-center justify-between">
                  <div>
                    <h4 class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $benefit->name }}</h4>
                    <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">{{ $benefit->pivot->received_date ? \Carbon\Carbon::parse($benefit->pivot->received_date)->format('M j, Y') : '-' }}</p>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-amber-600 dark:text-amber-400">{{ $fmt($benefit->pivot->amount) }}</p>
                    <span class="badge badge-green text-[10px]">{{ ucfirst($benefit->pivot->status) }}</span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-sm text-primary-500 dark:text-primary-400 text-center py-8">No benefits received yet.</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
