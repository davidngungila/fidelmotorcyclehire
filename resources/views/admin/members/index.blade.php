@extends('layouts.admin')

@section('breadcrumb', 'Members \u203A List')
@section('page_title', 'Members Directory')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
@endphp

@section('content')

<div x-data="membersList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1 max-w-2xl">
      <form method="GET" action="{{ route('admin.members.index') }}" class="flex-1" x-ref="searchForm">
        <div class="relative">
          <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
          <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
                 placeholder="Search by member number, name, phone, email..."
                 class="form-input pl-9 py-2.5 text-sm"
                 x-model="searchQuery"
                 @input.debounce.400ms="submitSearch"/>
          @if($searchQuery)
            <a href="{{ route('admin.members.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
              <i class="fa-solid fa-xmark text-xs"></i>
            </a>
          @endif
        </div>
      </form>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('admin.members.download-template') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
        <i class="fa-solid fa-file-download text-[13px]"></i> Download Template
      </a>
      <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')"
             class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
        <i class="fa-solid fa-file-import text-[13px]"></i> Import Excel
      </button>
      <a href="{{ route('admin.users.create') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
        <i class="fa-solid fa-user-plus text-[13px]"></i> New Member
      </a>
    </div>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-list-check mr-1.5"></i> {{ $members->total() }} Members Found
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
              Member Number
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'member_number', $sortDirection) }}"></i>
            </th>
            <th class="cursor-pointer select-none" @click="sortBy('name')">
              Full Name
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'name', $sortDirection) }}"></i>
            </th>
            <th class="cursor-pointer select-none" @click="sortBy('gender')">
              Gender
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'gender', $sortDirection) }}"></i>
            </th>
            <th>Phone</th>
            <th>Email</th>
            <th class="cursor-pointer select-none" @click="sortBy('branch')">
              Branch
              <i class="fa-solid ml-1.5 text-[10px] {{ $memberService->getSortDirectionIcon($sortColumn, 'branch', $sortDirection) }}"></i>
            </th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($members as $index => $member)
            @php
              $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? null);
              $memberName = $member['name'] ?? ($member['Name'] ?? 'Unknown');
              $memberGender = $member['gender'] ?? ($member['Gender'] ?? '-');
              $memberPhone = $member['phone'] ?? ($member['Phone'] ?? '-');
              $memberEmail = $member['email'] ?? ($member['Email'] ?? '-');
              $memberBranch = $member['branch'] ?? ($member['Branch'] ?? '-');
              $statusBadge = $dashboardService->memberStatusBadge($member['status'] ?? null);
              $rowNum = ($members->currentPage() - 1) * $members->perPage() + $index + 1;
            @endphp
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>
              <td>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-primary-50 dark:bg-primary-900/40 font-mono text-xs font-bold text-primary-700 dark:text-primary-300">
                  <i class="fa-solid fa-id-card text-[10px] opacity-60"></i>
                  {{ $memberNo ?? 'FTN-' . str_pad((string)$rowNum, 5, '0', STR_PAD_LEFT) }}
                </span>
              </td>
              <td>
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr($memberName, 0, 1) ?? 'M') }}
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-primary-900 dark:text-white truncate max-w-[200px]">{{ $memberName }}</p>
                    @if($memberEmail && $memberEmail !== '-')
                      <p class="text-[11px] text-primary-500 dark:text-primary-400 truncate max-w-[200px]">{{ $memberEmail }}</p>
                    @endif
                  </div>
                </div>
              </td>
              <td>
                <span class="text-xs text-primary-700 dark:text-primary-300">
                  @if(strtolower((string)$memberGender) === 'male' || strtolower((string)$memberGender) === 'm')
                    <i class="fa-solid fa-mars text-blue-500 mr-1"></i> Male
                  @elseif(strtolower((string)$memberGender) === 'female' || strtolower((string)$memberGender) === 'f')
                    <i class="fa-solid fa-venus text-pink-500 mr-1"></i> Female
                  @else
                    {{ $memberGender }}
                  @endif
                </span>
              </td>
              <td>
                @if($memberPhone && $memberPhone !== '-')
                  <span class="text-xs font-mono text-primary-700 dark:text-primary-300">{{ $memberPhone }}</span>
                @else
                  <span class="text-xs text-primary-300 dark:text-primary-600 italic">-</span>
                @endif
              </td>
              <td>
                @if($memberEmail && $memberEmail !== '-')
                  <span class="text-xs text-primary-700 dark:text-primary-300 max-w-[180px] truncate block">{{ $memberEmail }}</span>
                @else
                  <span class="text-xs text-primary-300 dark:text-primary-600 italic">-</span>
                @endif
              </td>
              <td>
                <span class="inline-flex items-center gap-1 text-xs text-primary-700 dark:text-primary-300">
                  <i class="fa-solid fa-location-dot text-[10px] text-primary-400"></i>
                  {{ $memberBranch }}
                </span>
              </td>
              <td>
                <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
              </td>
              <td class="text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.members.show', $memberNo ?? 'FTN-' . str_pad((string)$rowNum, 5, '0', STR_PAD_LEFT)) }}"
                     class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors">
                    <i class="fa-solid fa-eye text-[10px]"></i> View Profile
                  </a>
                  <a href="{{ route('admin.members.show', ['memberNumber' => $memberNo ?? 'FTN-' . str_pad((string)$rowNum, 5, '0', STR_PAD_LEFT), '#tab-loans']) }}"
                     class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-orange-50 hover:bg-orange-100 dark:bg-orange-900/30 dark:hover:bg-orange-900/50 text-orange-700 dark:text-orange-300 text-[11px] font-bold transition-colors border border-orange-200 dark:border-orange-800/40">
                    <i class="fa-solid fa-hand-holding-dollar text-[10px]"></i> Loans
                    <span class="badge badge-orange !text-[9px] !py-0.5 !px-1.5 ml-0.5">View</span>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center py-16 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-user-slash text-4xl mb-4 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No members found</p>
                <p class="text-xs">
                  @if($searchQuery)
                    Try adjusting your search terms or
                  @endif
                  <a href="{{ route('admin.users.create') }}" class="text-primary-600 dark:text-primary-400 underline hover:no-underline">create a new member</a>
                </p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($members->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $members->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $members->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $members->total() }}</span> members
        </p>

        <nav class="flex items-center justify-center gap-1" role="navigation" aria-label="Pagination Navigation">
          @if($members->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-300 dark:text-primary-700 bg-primary-50 dark:bg-primary-900/20 cursor-not-allowed">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </span>
          @else
            <a href="{{ $members->appends(request()->query())->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 transition-colors">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </a>
          @endif

          @php
            $start = max($members->currentPage() - 2, 1);
            $end = min($start + 4, $members->lastPage());
            if ($end - $start < 4) {
                $start = max($end - 4, 1);
            }
          @endphp

          @for($i = $start; $i <= $end; $i++)
            @if($i == $members->currentPage())
              <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-primary-600 shadow-sm">
                {{ $i }}
              </span>
            @else
              <a href="{{ $members->appends(request()->query())->url($i) }}"
                 class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-primary-700 dark:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
                {{ $i }}
              </a>
            @endif
          @endfor

          @if($members->hasMorePages())
            <a href="{{ $members->appends(request()->query())->nextPageUrl() }}"
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

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
  <div class="bg-white dark:bg-dark-bg rounded-2xl shadow-2xl w-full max-w-lg">
    <div class="p-6 border-b border-gray-200 dark:border-dark-border">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Import Members from Excel</h3>
        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
          <i class="fa-solid fa-xmark text-xl"></i>
        </button>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.members.import') }}" enctype="multipart/form-data" class="p-6 space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Excel File</label>
        <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-primary-500 dark:hover:border-primary-400 transition-colors">
          <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                 class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                 onchange="if(this.files[0]) { this.parentElement.querySelector('.file-name').textContent = this.files[0].name; this.parentElement.querySelector('.upload-text').classList.add('hidden'); this.parentElement.querySelector('.file-name').classList.remove('hidden'); }">
          <div class="upload-text">
            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
            <p class="text-sm text-gray-600 dark:text-gray-400">Drag and drop your Excel file here, or click to browse</p>
            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">.xlsx, .xls, .csv (Max 10MB)</p>
          </div>
          <p class="file-name hidden text-sm font-medium text-primary-600 dark:text-primary-400"></p>
        </div>
      </div>
      <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/40 rounded-lg p-4">
        <p class="text-xs text-blue-800 dark:text-blue-300 font-semibold mb-2">
          <i class="fa-solid fa-info-circle mr-1"></i> Required Excel Columns:
        </p>
        <p class="text-xs text-blue-700 dark:text-blue-400">member_number, full_name, gender, phone, email, status, registration_date, date_of_birth, national_id, occupation, employer, residential_address, member_type, marital_status, bank_name, bank_branch, account_name, account_number, bank_account_status, mobile_money_provider, mobile_money_number, emergency_contact_name, emergency_contact_phone, emergency_contact_relationship, registration_fee, notes</p>
      </div>
      <div class="flex items-center justify-end gap-3 pt-4">
        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
          Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors flex items-center gap-2">
          <i class="fa-solid fa-file-import"></i> Import Members
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function membersList() {
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
