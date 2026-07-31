@extends('layouts.admin')

@section('breadcrumb', 'Members › Investments')
@section('page_title', 'Investments Management')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
@endphp

@section('content')

<div x-data="investmentsList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <form method="GET" action="{{ route('admin.investments.index') }}" class="flex-1 max-w-2xl" x-ref="searchForm">
      <div class="relative">
        <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
        <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
               placeholder="Search by member #, name, product..."
               class="form-input pl-9 py-2.5 text-sm"
               x-model="searchQuery"
               @input.debounce.400ms="submitSearch"/>
        @if($searchQuery)
          <a href="{{ route('admin.investments.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
            <i class="fa-solid fa-xmark text-xs"></i>
          </a>
        @endif
      </div>
    </form>
    <a href="{{ route('admin.investments.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
      <i class="fa-solid fa-plus text-[13px]"></i> New Investment
    </a>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3 flex-wrap">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-chart-line mr-1.5"></i> {{ $investments->total() }} Investments
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
            <th class="cursor-pointer select-none" @click="sortBy('product')">
              Product
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'product', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('amount_invested')">
              Amount Invested
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'amount_invested', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('current_value')">
              Current Value
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'current_value', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('profit')">
              Profit
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'profit', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('return_percentage')">
              Return %
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'return_percentage', $sortDirection) }}"></i>
            </th>
            <th>Start Date</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($investments as $index => $item)
            @php
              $inv = $item->investment;
              $memberNo = $item->member_no;
              $memberName = $item->member_name;
              $product = $item->product;
              $amountInvested = $item->amount_invested;
              $currentValue = $item->current_value;
              $profit = $item->profit;
              $returnPct = $item->return_pct;
              $startDate = $item->start_date;
              $status = $item->status;
              $profitClass = $item->profit_class;
              $profitIcon = $item->profit_icon;
              $rowNum = ($investments->currentPage() - 1) * $investments->perPage() + $index + 1;
            @endphp
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>
              <td>
                <div class="flex items-center gap-2.5">
                  <div class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr($memberName, 0, 1) ?? 'M') }}
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-primary-900 dark:text-white truncate max-w-[180px]">{{ $memberName }}</p>
                    <span class="font-mono text-[11px] text-primary-500 dark:text-primary-400">{{ $memberNo }}</span>
                  </div>
                </div>
              </td>
              <td>
                <span class="text-sm font-semibold text-primary-900 dark:text-white">{{ $product }}</span>
              </td>
              <td class="text-right font-bold text-primary-900 dark:text-white text-xs">{{ $fmt($amountInvested) }}</td>
              <td class="text-right font-bold text-primary-900 dark:text-white text-xs">{{ $fmt($currentValue) }}</td>
              <td class="text-right">
                <span class="font-bold text-xs {{ $profitClass }} inline-flex items-center gap-1">
                  <i class="fa-solid {{ $profitIcon }} text-[10px]"></i>
                  {{ $profit >= 0 ? '+' : '' }}{{ $fmt($profit) }}
                </span>
              </td>
              <td class="text-right">
                <span class="badge {{ $returnPct >= 0 ? 'badge-green' : 'badge-red' }}">
                  {{ $returnPct >= 0 ? '+' : '' }}{{ number_format($returnPct, 2) }}%
                </span>
              </td>
              <td>
                @if($startDate !== '-')
                  <span class="font-mono text-[11px] text-primary-700 dark:text-primary-300">{{ $startDate }}</span>
                @else
                  <span class="text-[11px] italic text-primary-300 dark:text-primary-600">-</span>
                @endif
              </td>
              <td><span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span></td>
              <td class="text-right whitespace-nowrap">
                <a href="{{ route('admin.investments.show', encryptId($memberNo)) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-100 hover:bg-teal-200 dark:bg-teal-900/40 dark:hover:bg-teal-900/60 text-teal-700 dark:text-teal-300 text-[11px] font-bold transition-colors">
                  <i class="fa-solid fa-eye text-[10px]"></i> View
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center py-16 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-file-circle-exclamation text-4xl mb-4 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No records found</p>
                <p class="text-xs">
                  @if($searchQuery)
                    Try adjusting your search terms
                  @else
                    No investments available
                  @endif
                </p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($investments->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $investments->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $investments->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $investments->total() }}</span> investments
        </p>

        <nav class="flex items-center justify-center gap-1" role="navigation" aria-label="Pagination Navigation">
          @if($investments->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-300 dark:text-primary-700 bg-primary-50 dark:bg-primary-900/20 cursor-not-allowed">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </span>
          @else
            <a href="{{ $investments->appends(request()->query())->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 transition-colors">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </a>
          @endif

          @php
            $start = max($investments->currentPage() - 2, 1);
            $end = min($start + 4, $investments->lastPage());
            if ($end - $start < 4) {
                $start = max($end - 4, 1);
            }
          @endphp

          @for($i = $start; $i <= $end; $i++)
            @if($i == $investments->currentPage())
              <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-primary-600 shadow-sm">{{ $i }}</span>
            @else
              <a href="{{ $investments->appends(request()->query())->url($i) }}"
                 class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-primary-700 dark:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
                {{ $i }}
              </a>
            @endif
          @endfor

          @if($investments->hasMorePages())
            <a href="{{ $investments->appends(request()->query())->nextPageUrl() }}"
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
  function investmentsList() {
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
