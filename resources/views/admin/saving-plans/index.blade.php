@extends('layouts.admin')

@section('breadcrumb', 'Members › Saving Plans')
@section('page_title', 'Saving Plans Management')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
@endphp

@section('content')

<div x-data="savingPlansList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1 max-w-3xl">
      <form method="GET" action="{{ route('admin.saving-plans.index') }}" class="flex-1" x-ref="searchForm">
        <div class="relative">
          <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
          <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
                 placeholder="Search by plan name, member ID, membership..."
                 class="form-input pl-9 py-2.5 text-sm"
                 x-model="searchQuery"
                 @input.debounce.400ms="submitSearch"/>
          @if($searchQuery)
            <a href="{{ route('admin.saving-plans.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
              <i class="fa-solid fa-xmark text-xs"></i>
            </a>
          @endif
        </div>
      </form>
    </div>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3 flex-wrap">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-piggy-bank mr-1.5"></i> {{ $total }} Saving Plans
        </span>
        @if($searchQuery)
          <span class="badge badge-blue text-[10px]">Search: {{ $searchQuery }}</span>
        @endif
      </div>
      <div class="flex items-center gap-3">
        <label class="flex items-center gap-2 text-xs text-primary-600 dark:text-primary-400">
          Per page:
          <select name="per_page" class="form-input py-1.5 px-2 w-20 text-xs" @change="changePerPage($el.value)">
            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
          </select>
        </label>
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th class="cursor-pointer select-none" @click="sortBy('name')">
              Plan Name
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'name', $sortDirection) }}"></i>
            </th>
            <th class="cursor-pointer select-none" @click="sortBy('memberid')">
              Member ID
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'memberid', $sortDirection) }}"></i>
            </th>
            <th>Member Name</th>
            <th class="cursor-pointer select-none" @click="sortBy('membership')">
              Membership
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'membership', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('monthly_goal')">
              Monthly Goal
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'monthly_goal', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('goal')">
              Total Goal
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'goal', $sortDirection) }}"></i>
            </th>
            <th class="text-right">Progress</th>
          </tr>
        </thead>
        <tbody>
          @foreach($plans as $index => $plan)
            <tr class="group">
              <td class="text-xs text-gray-500">{{ ($currentPage - 1) * $perPage + $index + 1 }}</td>
              <td class="text-sm font-medium">{{ $plan['name'] ?? '-' }}</td>
              <td>
                <a href="{{ route('admin.saving-plans.show', encryptId($plan['memberid'] ?? '')) }}" 
                   class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                  {{ $plan['memberid'] ?? '-' }}
                </a>
              </td>
              <td class="text-sm">{{ $plan['member_name'] ?? 'Unknown' }}</td>
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
          @endforeach
        </tbody>
      </table>
    </div>

    @if($total > $perPage)
      <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
        <div class="text-xs text-gray-500">
          Showing {{ ($currentPage - 1) * $perPage + 1 }} to {{ min($currentPage * $perPage, $total) }} of {{ $total }} plans
        </div>
        <div class="flex items-center gap-2">
          @if($currentPage > 1)
            <a href="{{ route('admin.saving-plans.index', ['page' => $currentPage - 1, 'q' => $searchQuery, 'per_page' => $perPage, 'sort' => $sortColumn, 'sort_direction' => $sortDirection]) }}" 
               class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
              <i class="fa-solid fa-chevron-left"></i>
            </a>
          @endif
          <span class="text-xs font-medium text-gray-700">{{ $currentPage }}</span>
          @if($currentPage * $perPage < $total)
            <a href="{{ route('admin.saving-plans.index', ['page' => $currentPage + 1, 'q' => $searchQuery, 'per_page' => $perPage, 'sort' => $sortColumn, 'sort_direction' => $sortDirection]) }}" 
               class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
              <i class="fa-solid fa-chevron-right"></i>
            </a>
          @endif
        </div>
      </div>
    @endif
  </div>
</div>

<script>
function savingPlansList() {
  return {
    searchQuery: '{{ $searchQuery ?? '' }}',
    sortColumn: '{{ $sortColumn }}',
    sortDirection: '{{ $sortDirection }}',
    
    submitSearch() {
      this.$refs.searchForm.submit();
    },
    
    sortBy(column) {
      const newDirection = this.sortColumn === column && this.sortDirection === 'asc' ? 'desc' : 'asc';
      const url = new URL(window.location.href);
      url.searchParams.set('sort', column);
      url.searchParams.set('sort_direction', newDirection);
      window.location.href = url.toString();
    },
    
    changePerPage(perPage) {
      const url = new URL(window.location.href);
      url.searchParams.set('per_page', perPage);
      url.searchParams.set('page', '1');
      window.location.href = url.toString();
    }
  }
}
</script>

@endsection
