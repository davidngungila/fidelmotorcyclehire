@extends('layouts.admin')

@section('breadcrumb', 'Members › Loans')
@section('page_title', 'Loans Management')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
@endphp

@section('content')

<div x-data="{
  searchQuery: @json($searchQuery ?? ''),
  showImportModal: null,
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
  },
  closeImportModal() {
    this.showImportModal = null;
  }
}" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1 max-w-3xl">
      <form method="GET" action="{{ route('admin.loans.index') }}" class="flex-1" x-ref="searchForm">
        <div class="relative">
          <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
          <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
                 placeholder="Search by loan #, member #, name, product..."
                 class="form-input pl-9 py-2.5 text-sm"
                 x-model="searchQuery"
                 @input.debounce.400ms="submitSearch"/>
          @if($searchQuery)
            <a href="{{ route('admin.loans.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
              <i class="fa-solid fa-xmark text-xs"></i>
            </a>
          @endif
        </div>
      </form>
      <form method="GET" action="{{ route('admin.loans.index') }}" x-ref="filterForm">
        <input type="hidden" name="q" value="{{ $searchQuery }}"/>
        <div class="flex items-center gap-2">
          <select name="status" class="form-input py-2.5 px-3 text-sm w-auto" @change="submitFilter()">
            <option value="">All Statuses</option>
            <option value="active" {{ ($statusFilter ?? '') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="settled" {{ ($statusFilter ?? '') === 'settled' ? 'selected' : '' }}>Settled</option>
            <option value="defaulted" {{ ($statusFilter ?? '') === 'defaulted' ? 'selected' : '' }}>Defaulted</option>
            <option value="pending" {{ ($statusFilter ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
          </select>
        </div>
      </form>
    </div>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3 flex-wrap">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-hand-holding-dollar mr-1.5"></i> {{ $loans->total() }} Loans Found
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
        <div class="flex items-center gap-2">
          <button @click="showImportModal = 'loan-payments'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-[11px] font-bold transition-colors border border-blue-200 dark:border-blue-800/40">
            <i class="fa-solid fa-file-import text-[10px]"></i> Import Loan Payments
          </button>
          <button @click="showImportModal = 'loans-information'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-50 hover:bg-green-100 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 text-[11px] font-bold transition-colors border border-green-200 dark:border-green-800/40">
            <i class="fa-solid fa-file-import text-[10px]"></i> Import Loans Information
          </button>
        </div>
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th class="cursor-pointer select-none" @click="sortBy('loan_number')">
              Loan Number
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'loan_number', $sortDirection) }}"></i>
            </th>
            <th class="cursor-pointer select-none" @click="sortBy('member_number')">
              Member
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'member_number', $sortDirection) }}"></i>
            </th>
            <th class="cursor-pointer select-none" @click="sortBy('loan_product')">
              Product
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'loan_product', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('loan_amount')">
              Amount
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'loan_amount', $sortDirection) }}"></i>
            </th>
            <th class="text-right cursor-pointer select-none" @click="sortBy('outstanding_balance')">
              Outstanding
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'outstanding_balance', $sortDirection) }}"></i>
            </th>
            <th class="text-right">Paid</th>
            <th>Status</th>
            <th>Progress</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($loans as $index => $loan)
            @php
              $loanNo = $loan['loan_number'] ?? ($loan['LoanNumber'] ?? '-');
              $memberNo = $loan['member_number'] ?? '-';
              $memberName = $loan['member_name'] ?? 'Unknown';
              $product = $loan['loan_product'] ?? ($loan['LoanProduct'] ?? '-');
              $amount = $loan['loan_amount'] ?? ($loan['LoanAmount'] ?? 0);
              $outstanding = $loan['outstanding_balance'] ?? ($loan['OutstandingBalance'] ?? 0);
              $paid = $loan['paid_amount'] ?? ($loan['PaidAmount'] ?? 0);
              $status = $dashboardService->loanStatusBadge($loan['status'] ?? ($loan['Status'] ?? null));
              $progress = $amount > 0 ? min(($paid / $amount) * 100, 100) : 0;
              $rowNum = ($loans->currentPage() - 1) * $loans->perPage() + $index + 1;
            @endphp
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>
              <td>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-orange-50 dark:bg-orange-900/40 font-mono text-xs font-bold text-orange-700 dark:text-orange-300">
                  <i class="fa-solid fa-file-invoice text-[10px] opacity-60"></i>
                  {{ $loanNo }}
                </span>
              </td>
              <td>
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-[11px] font-bold flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr($memberName, 0, 1) ?? 'M') }}
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-primary-900 dark:text-white truncate max-w-[160px]">{{ $memberName }}</p>
                    <span class="font-mono text-[11px] text-primary-500 dark:text-primary-400">{{ $memberNo }}</span>
                  </div>
                </div>
              </td>
              <td>
                <span class="text-sm font-semibold text-primary-900 dark:text-white">{{ $product }}</span>
              </td>
              <td class="text-right font-bold text-primary-900 dark:text-white text-xs">{{ $fmt($amount) }}</td>
              <td class="text-right font-bold text-orange-600 dark:text-orange-400 text-xs">{{ $fmt($outstanding) }}</td>
              <td class="text-right font-bold text-green-600 dark:text-green-400 text-xs">{{ $fmt($paid) }}</td>
              <td><span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span></td>
              <td class="min-w-[140px]">
                <div class="flex items-center gap-2">
                  <div class="flex-1 progress-bar">
                    <div class="progress-fill" style="width: {{ $progress }}%"></div>
                  </div>
                  <span class="text-[10px] font-bold text-primary-600 dark:text-primary-400 whitespace-nowrap">{{ number_format($progress, 0) }}%</span>
                </div>
              </td>
              <td class="text-right whitespace-nowrap">
                <a href="{{ route('admin.loans.show', encryptId($loanNo)) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors">
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
                  @if($searchQuery || $statusFilter)
                    Try adjusting your search or filters
                  @else
                    No loan records available
                  @endif
                </p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($loans->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $loans->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $loans->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $loans->total() }}</span> loans
        </p>

        <nav class="flex items-center justify-center gap-1" role="navigation" aria-label="Pagination Navigation">
          @if($loans->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-300 dark:text-primary-700 bg-primary-50 dark:bg-primary-900/20 cursor-not-allowed">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </span>
          @else
            <a href="{{ $loans->appends(request()->query())->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 transition-colors">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </a>
          @endif

          @php
            $start = max($loans->currentPage() - 2, 1);
            $end = min($start + 4, $loans->lastPage());
            if ($end - $start < 4) {
                $start = max($end - 4, 1);
            }
          @endphp

          @for($i = $start; $i <= $end; $i++)
            @if($i == $loans->currentPage())
              <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-primary-600 shadow-sm">
                {{ $i }}
              </span>
            @else
              <a href="{{ $loans->appends(request()->query())->url($i) }}"
                 class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-primary-700 dark:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
                {{ $i }}
              </a>
            @endif
          @endfor

          @if($loans->hasMorePages())
            <a href="{{ $loans->appends(request()->query())->nextPageUrl() }}"
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

<!-- Import Loan Payments Modal -->
<div x-show="showImportModal === 'loan-payments'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
  <div x-show="showImportModal === 'loan-payments'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg mx-4" style="display: none;">
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-primary-900 dark:text-white">Import Loan Payments</h3>
        <button @click="closeImportModal()" class="text-primary-400 hover:text-primary-600 dark:text-primary-500 dark:hover:text-primary-300">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.loans.import-loan-payments') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
          <label class="block text-sm font-semibold text-primary-700 dark:text-primary-300 mb-2">Select Excel File</label>
          <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="form-input w-full">
          <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">Supported formats: .xlsx, .xls, .csv</p>
        </div>
        <div class="flex items-center gap-3 justify-end">
          <button type="button" @click="closeImportModal()" class="px-4 py-2 rounded-lg text-sm font-bold text-primary-700 dark:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
            <i class="fa-solid fa-upload mr-1.5"></i> Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Import Loans Information Modal -->
<div x-show="showImportModal === 'loans-information'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
  <div x-show="showImportModal === 'loans-information'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg mx-4" style="display: none;">
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-primary-900 dark:text-white">Import Loans Information</h3>
        <button @click="closeImportModal()" class="text-primary-400 hover:text-primary-600 dark:text-primary-500 dark:hover:text-primary-300">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.loans.import-loans-information') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
          <label class="block text-sm font-semibold text-primary-700 dark:text-primary-300 mb-2">Select Excel File</label>
          <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="form-input w-full">
          <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">Supported formats: .xlsx, .xls, .csv</p>
        </div>
        <div class="flex items-center gap-3 justify-end">
          <button type="button" @click="closeImportModal()" class="px-4 py-2 rounded-lg text-sm font-bold text-primary-700 dark:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
            Cancel
          </button>
          <button type="submit" class="px-4 py-2 rounded-lg text-sm font-bold text-white bg-green-600 hover:bg-green-700 transition-colors">
            <i class="fa-solid fa-upload mr-1.5"></i> Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
