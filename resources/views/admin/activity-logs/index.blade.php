@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Activity Logs')
@section('page_title', 'Activity & Audit Logs')

@section('content')

<div x-data="activityLogsList()" class="space-y-6">

  <div class="glass p-5 lg:p-6">
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="space-y-4">
      <div class="flex items-center justify-between mb-1">
        <div class="flex items-center gap-3">
          <h3 class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">
            <i class="fa-solid fa-filter mr-2 text-primary-500"></i> Filter Logs
          </h3>
        </div>
        @if($filterUser || $filterAction || $filterDateFrom || $filterDateTo)
          <a href="{{ route('admin.activity-logs.index') }}"
             class="text-xs font-bold text-red-600 dark:text-red-400 hover:text-red-500 transition-colors">
            <i class="fa-solid fa-times-circle mr-1"></i> Clear All
          </a>
        @endif
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
        <div class="lg:col-span-1">
          <label class="form-label uppercase tracking-wider text-[10px]" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">User</label>
          <select name="user" class="form-input py-2 text-xs">
            <option value="">All Users</option>
            @foreach($users as $u)
              <option value="{{ $u->id }}" {{ (string)$filterUser === (string)$u->id ? 'selected' : '' }}>
                {{ $u->name }} ({{ $u->email }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="lg:col-span-1">
          <label class="form-label uppercase tracking-wider text-[10px]" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Action / Keyword</label>
          <input type="text" name="action" value="{{ $filterAction }}"
                 placeholder="e.g. created, updated, deleted..."
                 class="form-input py-2 text-xs">
        </div>

        <div>
          <label class="form-label uppercase tracking-wider text-[10px]" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Date From</label>
          <input type="date" name="date_from" value="{{ $filterDateFrom }}"
                 class="form-input py-2 text-xs">
        </div>

        <div>
          <label class="form-label uppercase tracking-wider text-[10px]" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Date To</label>
          <input type="date" name="date_to" value="{{ $filterDateTo }}"
                 class="form-input py-2 text-xs">
        </div>

        <div class="flex items-end gap-2">
          <button type="submit"
                  class="flex-1 py-2 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all active:scale-95">
            <i class="fa-solid fa-magnifying-glass mr-1 text-[11px]"></i> Apply
          </button>
        </div>
      </div>
    </form>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3 flex-wrap">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-list-check mr-1.5"></i> {{ $activityLogs->total() }} Activity Records
        </span>
        @if($filterUser || $filterAction || $filterDateFrom || $filterDateTo)
          <span class="badge badge-blue text-[10px]">Filters Active</span>
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

    @if($activityLogs->count() > 0)
      <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
        <table class="data-table">
          <thead>
            <tr>
              <th class="w-12">#</th>
              <th>Date & Time</th>
              <th>User</th>
              <th>Action / Description</th>
              <th>IP Address</th>
              <th>Subject</th>
              <th>Properties</th>
              <th>User Agent</th>
            </tr>
          </thead>
          <tbody>
            @foreach($activityLogs as $index => $log)
              @php
                $rowNum = ($activityLogs->currentPage() - 1) * $activityLogs->perPage() + $index + 1;
                $logUser = $log->user;
                $userName = $logUser ? $logUser->name : 'System';
                $userEmail = $logUser ? $logUser->email : '-';
                $userRole = $logUser ? ($logUser->role ?? ($logUser->roles->first()->name ?? 'member')) : 'system';
                $subjectLabel = '-';
                if ($log->subject_type && $log->subject_id) {
                  $subjectLabel = ucfirst($log->subject_type) . ' #' . $log->subject_id;
                } elseif ($log->subject_type) {
                  $subjectLabel = ucfirst($log->subject_type);
                }
                $hasProperties = is_array($log->properties) && count($log->properties) > 0;
                $propertiesJson = $hasProperties ? json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '';
              @endphp
              <tr class="group align-top">
                <td class="pt-3 text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>

                <td class="pt-3 whitespace-nowrap">
                  <span class="block text-xs font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $log->created_at ? $log->created_at->format('d M Y') : '-' }}</span>
                  <span class="block text-[11px] text-primary-500 dark:text-primary-500">{{ $log->created_at ? $log->created_at->format('H:i:s') : '-' }}</span>
                </td>

                <td class="pt-3">
                  <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-[11px] font-bold flex-shrink-0 shadow-sm">
                      {{ strtoupper(substr($userName, 0, 1) ?? 'S') }}
                    </div>
                    <div class="min-w-0">
                      <p class="text-xs font-semibold truncate max-w-[140px]" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $userName }}</p>
                      <p class="text-[10px] truncate max-w-[140px] text-primary-500 dark:text-primary-500">{{ $userEmail }}</p>
                      @if($userRole === 'admin')
                        <span class="role-tag role-admin mt-1 !text-[9px]">Admin</span>
                      @elseif($userRole === 'system')
                        <span class="badge badge-gray mt-1 !text-[9px]">System</span>
                      @else
                        <span class="role-tag role-member mt-1 !text-[9px]">{{ ucfirst($userRole) }}</span>
                      @endif
                    </div>
                  </div>
                </td>

                <td class="pt-3 max-w-xs">
                  <div class="text-xs" :class="darkMode ? 'text-primary-200' : 'text-primary-800'">{{ $log->description }}</div>
                </td>

                <td class="pt-3">
                  <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 font-mono text-[11px] text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-globe text-[9px] text-primary-400 mr-0.5"></i>
                    {{ $log->ip_address ?? '-' }}
                  </span>
                </td>

                <td class="pt-3">
                  @if($subjectLabel !== '-')
                    <span class="badge badge-blue text-[10px] whitespace-nowrap">{{ $subjectLabel }}</span>
                  @else
                    <span class="text-[11px] text-primary-400 dark:text-primary-600 italic">-</span>
                  @endif
                </td>

                <td class="pt-3" x-data="{ open{{ $log->id }}: false }">
                  @if($hasProperties)
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-primary-50 dark:bg-primary-900/40 border border-primary-100 dark:border-primary-800/50">
                      <button @click="open{{ $log->id }} = !open{{ $log->id }}"
                              class="text-[10px] font-bold text-primary-700 dark:text-primary-300 hover:text-primary-600 transition-colors inline-flex items-center gap-1">
                        <i class="fa-solid fa-brackets-curly text-[9px]"></i>
                        {{ count($log->properties) }} keys
                        <i :class="open{{ $log->id }} ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-[9px] ml-0.5"></i>
                      </button>
                    </div>
                    <div x-show="open{{ $log->id }}" x-collapse class="mt-2">
                      <div class="p-3 rounded-xl bg-gray-900 border border-gray-800 overflow-x-auto max-w-sm">
                        <pre class="text-[10px] leading-relaxed text-green-300 whitespace-pre-wrap break-all">{{ $propertiesJson }}</pre>
                      </div>
                    </div>
                  @else
                    <span class="text-[11px] text-primary-400 dark:text-primary-600 italic">-</span>
                  @endif
                </td>

                <td class="pt-3">
                  @if($log->user_agent)
                    <span class="text-[10px] text-primary-600 dark:text-primary-400 block max-w-[180px] truncate" title="{{ $log->user_agent }}">
                      {{ \Illuminate\Support\Str::limit($log->user_agent, 50, '...') }}
                    </span>
                  @else
                    <span class="text-[11px] text-primary-400 dark:text-primary-600 italic">-</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if($activityLogs->hasPages())
        <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <p class="text-xs text-primary-600 dark:text-primary-400">
            Showing <span class="font-bold text-primary-900 dark:text-white">{{ $activityLogs->firstItem() ?? 0 }}</span> to
            <span class="font-bold text-primary-900 dark:text-white">{{ $activityLogs->lastItem() ?? 0 }}</span> of
            <span class="font-bold text-primary-900 dark:text-white">{{ $activityLogs->total() }}</span> records
          </p>

          <nav class="flex items-center justify-center gap-1" role="navigation" aria-label="Pagination Navigation">
            @if($activityLogs->onFirstPage())
              <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-300 dark:text-primary-700 bg-primary-50 dark:bg-primary-900/20 cursor-not-allowed">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
              </span>
            @else
              <a href="{{ $activityLogs->appends(request()->query())->previousPageUrl() }}"
                 class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 transition-colors">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
              </a>
            @endif

            @php
              $start = max($activityLogs->currentPage() - 2, 1);
              $end = min($start + 4, $activityLogs->lastPage());
              if ($end - $start < 4) {
                  $start = max($end - 4, 1);
              }
            @endphp

            @for($i = $start; $i <= $end; $i++)
              @if($i == $activityLogs->currentPage())
                <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-primary-600 shadow-sm">
                  {{ $i }}
                </span>
              @else
                <a href="{{ $activityLogs->appends(request()->query())->url($i) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-primary-700 dark:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
                  {{ $i }}
                </a>
              @endif
            @endfor

            @if($activityLogs->hasMorePages())
              <a href="{{ $activityLogs->appends(request()->query())->nextPageUrl() }}"
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
    @else
      <div class="text-center py-20">
        <div class="w-20 h-20 mx-auto rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-400 mb-5">
          <i class="fa-solid fa-clipboard-list text-3xl opacity-50"></i>
        </div>
        <h3 class="text-lg font-bold mb-2" :class="darkMode ? 'text-white' : 'text-primary-900'">No Activity Logs Found</h3>
        <p class="text-sm max-w-md mx-auto mb-6" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
          @if($filterUser || $filterAction || $filterDateFrom || $filterDateTo)
            Try adjusting your filters to see more results or clear them to view all activity.
          @else
            Activity will appear here once users start performing actions in the system.
          @endif
        </p>
        @if($filterUser || $filterAction || $filterDateFrom || $filterDateTo)
          <a href="{{ route('admin.activity-logs.index') }}"
             class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all active:scale-95">
            <i class="fa-solid fa-times-circle"></i> Clear Filters
          </a>
        @endif
      </div>
    @endif
  </div>
</div>

@endsection

@push('scripts')
<script>
  function activityLogsList() {
    return {
      changePerPage(value) {
        const params = new URLSearchParams(window.location.search);
        params.set('per_page', value);
        params.delete('page');
        window.location.href = window.location.pathname + '?' + params.toString();
      }
    }
  }
</script>
@endpush
