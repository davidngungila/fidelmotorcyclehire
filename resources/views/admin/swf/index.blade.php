@extends('layouts.admin')

@section('breadcrumb', 'Members › SWF')
@section('page_title', 'SWF Management')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
@endphp

@section('content')

<div x-data="swfList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <form method="GET" action="{{ route('admin.swf.index') }}" class="flex-1 max-w-2xl" x-ref="searchForm">
      <div class="relative">
        <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
        <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
               placeholder="Search by member #, name, branch..."
               class="form-input pl-9 py-2.5 text-sm"
               x-model="searchQuery"
               @input.debounce.400ms="submitSearch"/>
        @if($searchQuery)
          <a href="{{ route('admin.swf.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
            <i class="fa-solid fa-xmark text-xs"></i>
          </a>
        @endif
      </div>
    </form>
    <a href="{{ route('admin.swf.members.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
      <i class="fa-solid fa-user-plus"></i> Register Member
    </a>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3 flex-wrap">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-shield-heart mr-1.5"></i> {{ $swf->total() }} SWF Accounts
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
            <th class="cursor-pointer select-none" @click="sortBy('member_number')">
              Member
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'member_number', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('total_contribution')">
              Total Contribution
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'total_contribution', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('benefits_paid')">
              Benefits Paid
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'benefits_paid', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('current_balance')">
              Current Balance
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'current_balance', $sortDirection) }}"></i>
            </th>
            <th>Enrollment Date</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($swf as $index => $item)
            @php
              $memberNo = $item->membership_number;
              $memberName = $item->user->name ?? 'Unknown';
              $memberBranch = $item->user->branch ?? '-';
              $memberStatus = $dashboardService->memberStatusBadge($item->user->status ?? null);
              $totalContribution = $item->total_contributions ?? 0;
              $benefitsPaid = $item->total_benefits_received ?? 0;
              $currentBalance = $item->total_contributions - $item->total_benefits_received;
              $enrollmentDate = $item->join_date ? $item->join_date->format('Y-m-d') : '-';
              $rowNum = ($swf->currentPage() - 1) * $swf->perPage() + $index + 1;
            @endphp
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>
              <td>
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">
                    {{ substr($memberName, 0, 1) }}
                  </div>
                  <div>
                    <p class="text-xs font-bold text-primary-900 dark:text-white">{{ $memberName }}</p>
                    <p class="text-[10px] text-primary-500 dark:text-primary-400">{{ $memberNo }}</p>
                  </div>
                </div>
              </td>
              <td class="text-right whitespace-nowrap text-xs font-bold text-primary-800 dark:text-primary-200 tabular-nums">
                {{ $fmt($totalContribution) }}
              </td>
              <td class="text-right whitespace-nowrap text-xs font-bold text-amber-600 dark:text-amber-400 tabular-nums">
                {{ $fmt($benefitsPaid) }}
              </td>
              <td class="text-right whitespace-nowrap text-xs font-bold {{ $currentBalance > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} tabular-nums">
                {{ $fmt($currentBalance) }}
              </td>
              <td><span class="text-xs">{{ $enrollmentDate }}</span></td>
              <td><span class="badge {{ $memberStatus['class'] }}">{{ $memberStatus['label'] }}</span></td>
              <td class="text-right whitespace-nowrap">
                <a href="{{ route('admin.swf.members.show', encryptId($item->id)) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/40 dark:hover:bg-purple-900/60 text-purple-700 dark:text-purple-300 text-[11px] font-bold transition-colors">
                  <i class="fa-solid fa-eye text-[10px]"></i> View
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-16 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-file-circle-exclamation text-4xl mb-4 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No records found</p>
                <p class="text-xs">
                  @if($searchQuery)
                    Try adjusting your search terms
                  @else
                    No SWF accounts available
                  @endif
                </p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($swf->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $swf->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $swf->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $swf->total() }}</span> accounts
        </p>

        <nav class="flex items-center justify-center gap-1" role="navigation" aria-label="Pagination Navigation">
          @if($swf->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-300 dark:text-primary-700 bg-primary-50 dark:bg-primary-900/20 cursor-not-allowed">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </span>
          @else
            <a href="{{ $swf->appends(request()->query())->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 transition-colors">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </a>
          @endif

          @php
            $start = max($swf->currentPage() - 2, 1);
            $end = min($start + 4, $swf->lastPage());
            if ($end - $start < 4) {
                $start = max($end - 4, 1);
            }
          @endphp

          @for($i = $start; $i <= $end; $i++)
            @if($i == $swf->currentPage())
              <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-primary-600 shadow-sm">{{ $i }}</span>
            @else
              <a href="{{ $swf->appends(request()->query())->url($i) }}"
                 class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-primary-700 dark:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
                {{ $i }}
              </a>
            @endif
          @endfor

          @if($swf->hasMorePages())
            <a href="{{ $swf->appends(request()->query())->nextPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 transition-colors">
              <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
          @else
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-300 dark:text-primary-700 bg-primary-50 dark:bg-primary-900/20 cursor-not-allowed">
              <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </span>
          @endif
        </nav>
      </div>
    @endif
  </div>
</div>

@endsection

@push('scripts')
<script>
  function swfList() {
    return {
      searchQuery: @json($searchQuery ?? ''),
      sortBy(column) {
        const params = new URLSearchParams(window.location.search);
        if (params.get('sort') === column) {
          params.set('sort_direction', params.get('sort_direction') === 'asc' ? 'desc' : 'asc');
        } else {
          params.set('sort', column);
          params.set('sort_direction', 'asc');
        }
        window.location.href = window.location.pathname + '?' + params.toString();
      },
      changePerPage(value) {
        const params = new URLSearchParams(window.location.search);
        params.set('per_page', value);
        params.delete('page');
        window.location.href = window.location.pathname + '?' + params.toString();
      },
      submitSearch() {
        this.$refs.searchForm.submit();
      }
    };
  }
</script>
@endpush
