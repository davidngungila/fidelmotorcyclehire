@extends('layouts.admin')

@section('breadcrumb', 'Members › Deposits')
@section('page_title', 'Fixed Deposits Management')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
@endphp

@section('content')

<div x-data="depositsList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1 max-w-3xl">
      <form method="GET" action="{{ route('admin.deposits.index') }}" class="flex-1" x-ref="searchForm">
        <div class="relative">
          <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
          <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
                 placeholder="Search by certificate #, member #, name, product..."
                 class="form-input pl-9 py-2.5 text-sm"
                 x-model="searchQuery"
                 @input.debounce.400ms="submitSearch"/>
          @if($searchQuery)
            <a href="{{ route('admin.deposits.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
              <i class="fa-solid fa-xmark text-xs"></i>
            </a>
          @endif
        </div>
      </form>
      <form method="GET" action="{{ route('admin.deposits.index') }}" x-ref="filterForm">
        <input type="hidden" name="q" value="{{ $searchQuery }}"/>
        <select name="status" class="form-input py-2.5 px-3 text-sm w-auto" @change="submitFilter()">
          <option value="">All Statuses</option>
          <option value="active" {{ ($statusFilter ?? '') === 'active' ? 'selected' : '' }}>Active</option>
          <option value="matured" {{ ($statusFilter ?? '') === 'matured' ? 'selected' : '' }}>Matured</option>
          <option value="withdrawn" {{ ($statusFilter ?? '') === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
        </select>
      </form>
    </div>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3 flex-wrap">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-money-bill-trend-up mr-1.5"></i> {{ $deposits->total() }} Certificates
        </span>
        @if($searchQuery)
          <span class="badge badge-blue text-[10px]">Search: {{ $searchQuery }}</span>
        @endif
        @if($statusFilter)
          <span class="badge badge-yellow text-[10px]">Status: {{ ucfirst($statusFilter) }}</span>
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
            <th class="cursor-pointer select-none" @click="sortBy('certificate_number')">
              Certificate #
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'certificate_number', $sortDirection) }}"></i>
            </th>
            <th class="cursor-pointer select-none" @click="sortBy('member_number')">
              Member
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'member_number', $sortDirection) }}"></i>
            </th>
            <th class="cursor-pointer select-none" @click="sortBy('product')">
              Product
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'product', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('amount')">
              Amount
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'amount', $sortDirection) }}"></i>
            </th>
            <th class="text-right">Interest %</th>
            <th>Start</th>
            <th>Maturity</th>
            <th>Status</th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('current_value')">
              Current Value
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'current_value', $sortDirection) }}"></i>
            </th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($deposits as $index => $d)
            @php
              $certNo = $d['certificate_number'] ?? ($d['CertificateNumber'] ?? '-');
              $memberNo = $d['member_number'] ?? '-';
              $memberName = $d['member_name'] ?? 'Unknown';
              $product = $d['product'] ?? ($d['Product'] ?? '-');
              $amount = (float)($d['amount'] ?? ($d['Amount'] ?? 0));
              $interest = (float)($d['interest'] ?? ($d['Interest'] ?? 0));
              $interestRate = $amount > 0 ? ($interest / $amount) * 100 : 0;
              $startDate = $d['start_date'] ?? ($d['StartDate'] ?? '-');
              $maturityDate = $d['maturity_date'] ?? ($d['MaturityDate'] ?? '-');
              $currentValue = (float)($d['current_value'] ?? ($d['CurrentValue'] ?? 0));
              $statusBadge = $dashboardService->depositStatusBadge($d['status'] ?? ($d['Status'] ?? null));
              $rowNum = ($deposits->currentPage() - 1) * $deposits->perPage() + $index + 1;
            @endphp
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>
              <td>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 dark:bg-purple-900/40 font-mono text-xs font-bold text-purple-700 dark:text-purple-300">
                  <i class="fa-solid fa-certificate text-[10px] opacity-60"></i>
                  {{ $certNo }}
                </span>
              </td>
              <td>
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 text-white flex items-center justify-center text-[11px] font-bold flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr($memberName, 0, 1) ?? 'M') }}
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-primary-900 dark:text-white truncate max-w-[150px]">{{ $memberName }}</p>
                    <span class="font-mono text-[11px] text-primary-500 dark:text-primary-400">{{ $memberNo }}</span>
                  </div>
                </div>
              </td>
              <td class="text-sm font-semibold text-primary-900 dark:text-white">{{ $product }}</td>
              <td class="text-right font-bold text-primary-900 dark:text-white text-xs">{{ $fmt($amount) }}</td>
              <td class="text-right">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 text-xs font-bold">
                  <i class="fa-solid fa-percent text-[9px]"></i>{{ number_format($interestRate, 2) }}%
                </span>
              </td>
              <td class="font-mono text-xs text-primary-700 dark:text-primary-300">{{ $startDate }}</td>
              <td class="font-mono text-xs text-primary-700 dark:text-primary-300">{{ $maturityDate }}</td>
              <td><span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span></td>
              <td class="text-right font-black text-sm text-primary-900 dark:text-white">{{ $fmt($currentValue) }}</td>
              <td class="text-right whitespace-nowrap">
                <a href="{{ route('admin.deposits.show', encryptId($certNo)) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/40 dark:hover:bg-purple-900/60 text-purple-700 dark:text-purple-300 text-[11px] font-bold transition-colors">
                  <i class="fa-solid fa-eye text-[10px]"></i> View
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11" class="text-center py-16 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-file-circle-exclamation text-4xl mb-4 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No records found</p>
                <p class="text-xs">
                  @if($searchQuery || $statusFilter)
                    Try adjusting your search or filters
                  @else
                    No deposit certificates available
                  @endif
                </p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($deposits->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $deposits->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $deposits->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $deposits->total() }}</span> certificates
        </p>

        <nav class="flex items-center justify-center gap-1" role="navigation" aria-label="Pagination Navigation">
          @if($deposits->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-300 dark:text-primary-700 bg-primary-50 dark:bg-primary-900/20 cursor-not-allowed">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </span>
          @else
            <a href="{{ $deposits->appends(request()->query())->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 transition-colors">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </a>
          @endif

          @php
            $start = max($deposits->currentPage() - 2, 1);
            $end = min($start + 4, $deposits->lastPage());
            if ($end - $start < 4) {
                $start = max($end - 4, 1);
            }
          @endphp

          @for($i = $start; $i <= $end; $i++)
            @if($i == $deposits->currentPage())
              <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-primary-600 shadow-sm">{{ $i }}</span>
            @else
              <a href="{{ $deposits->appends(request()->query())->url($i) }}"
                 class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-primary-700 dark:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
                {{ $i }}
              </a>
            @endif
          @endfor

          @if($deposits->hasMorePages())
            <a href="{{ $deposits->appends(request()->query())->nextPageUrl() }}"
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
  function depositsList() {
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
      },
      submitFilter() {
        this.$refs.filterForm.submit();
      }
    };
  }
</script>
@endpush
