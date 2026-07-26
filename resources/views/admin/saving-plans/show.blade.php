@extends('layouts.admin')

@section('breadcrumb', 'Members › Saving Plans › Details')
@section('page_title', 'Saving Plans Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
@endphp

@section('content')

<div class="space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.saving-plans.index') }}" 
       class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to Saving Plans</span>
    </a>
  </div>

  <div class="glass p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">
          {{ $member['name'] ?? $member['Name'] ?? 'Unknown Member' }}
        </h2>
        <p class="text-sm text-gray-500">
          <i class="fa-solid fa-id-card mr-1.5"></i> {{ $memberId }}
        </p>
      </div>
      <div class="flex items-center gap-3">
        <a href="{{ route('admin.members.show', encryptId($memberId)) }}" 
           class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
          <i class="fa-solid fa-user mr-1.5"></i> View Member Profile
        </a>
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th>Plan Name</th>
            <th>Membership</th>
            <th class="text-right">Monthly Goal</th>
            <th class="text-right">Total Goal</th>
            <th class="text-right">Progress</th>
          </tr>
        </thead>
        <tbody>
          @forelse($plans as $index => $plan)
            <tr class="group">
              <td class="text-xs text-gray-500">{{ $index + 1 }}</td>
              <td class="text-sm font-medium">{{ $plan['name'] ?? '-' }}</td>
              <td>
                <span class="badge @if(strtolower($plan['membership'] ?? '') === 'active') badge-green @else badge-yellow @endif text-[10px]">
                  {{ $plan['membership'] ?? '-' }}
                </span>
              </td>
              <td class="text-sm font-semibold text-right">{{ $fmt($plan['monthly_goal'] ?? 0) }}</td>
              <td class="text-sm font-semibold text-right">{{ $fmt($plan['goal'] ?? 0) }}</td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-green-500 rounded-full" style="width: 0%"></div>
                  </div>
                  <span class="text-xs text-gray-500">0%</span>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-8 text-gray-500">
                <i class="fa-solid fa-inbox text-3xl mb-2"></i>
                <p>No saving plans found for this member</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(!empty($plans))
      <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-500">
            Total Plans: {{ count($plans) }}
          </div>
          <div class="text-sm font-semibold">
            Total Goal: 
            <span class="text-primary-600">
              {{ $fmt(collect($plans)->sum('goal')) }}
            </span>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>

@endsection
