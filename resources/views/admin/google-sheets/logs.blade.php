@extends('layouts.admin')

@section('breadcrumb', 'Google Sheets \u203A Sync Logs')
@section('page_title', 'Google Sheets Sync Logs')

@section('content')

<div x-data="syncLogsPage()" class="space-y-6">
  <div class="glass p-6 lg:p-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Sync Logs</h1>
        <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
          View sync history and operation logs from Google Sheets integration
        </p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="loadLogs()" :disabled="loading"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-colors disabled:opacity-60">
          <i :class="loading ? 'fa-solid fa-circle-notch fa-spin' : 'fa-solid fa-rotate'" class="text-sm"></i>
          Refresh
        </button>
      </div>
    </div>

    <div class="flex flex-col md:flex-row gap-4 mb-6">
      <div class="flex-1">
        <input type="text" x-model="search" @keyup.enter="loadLogs()" placeholder="Search logs..."
               class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800/50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
               :class="darkMode ? 'text-white placeholder:text-primary-500' : 'text-primary-900 placeholder:text-primary-400'">
      </div>
      <select x-model="typeFilter" @change="loadLogs()"
              class="px-4 py-2.5 rounded-xl bg-white dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800/50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
              :class="darkMode ? 'text-white' : 'text-primary-900'">
        <option value="">All Types</option>
        <option value="all_sync">All Sync</option>
        <option value="active_sync">Active Sync</option>
        <option value="customers_sync">Customers Sync</option>
        <option value="transactions_sync">Transactions Sync</option>
        <option value="balances_sync">Balances Sync</option>
        <option value="manual_sync">Manual Sync</option>
      </select>
      <select x-model="statusFilter" @change="loadLogs()"
              class="px-4 py-2.5 rounded-xl bg-white dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800/50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
              :class="darkMode ? 'text-white' : 'text-primary-900'">
        <option value="">All Status</option>
        <option value="completed">Completed</option>
        <option value="failed">Failed</option>
        <option value="running">Running</option>
      </select>
    </div>

    <div x-show="loading" class="flex items-center justify-center py-12">
      <div class="w-10 h-10 border-4 border-primary-200 dark:border-primary-800 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <div x-show="!loading && logs.length === 0" class="text-center py-12">
      <i class="fa-solid fa-clock-rotate-left text-4xl text-primary-300 dark:text-primary-700 mb-4"></i>
      <p class="text-sm" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">No sync logs found</p>
    </div>

    <div x-show="!loading && logs.length > 0" class="space-y-3">
      <template x-for="log in logs" :key="log.id">
        <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800/50">
          <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3 flex-1">
              <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                   :class="log.status === 'completed' ? 'bg-green-500' : log.status === 'failed' ? 'bg-red-500' : 'bg-yellow-500'">
                <i class="fa-solid text-white text-sm"
                   :class="log.status === 'completed' ? 'fa-check' : log.status === 'failed' ? 'fa-xmark' : 'fa-clock'"></i>
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                  <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'" x-text="formatSyncType(log.sync_type)"></p>
                  <span class="badge" :class="log.status === 'completed' ? 'badge-green' : log.status === 'failed' ? 'badge-red' : 'badge-yellow'" x-text="log.status"></span>
                </div>
                <p class="text-xs mb-2" :class="darkMode ? 'text-primary-400' : 'text-primary-600'" x-text="formatDate(log.created_at)"></p>
                <div class="flex flex-wrap items-center gap-4 text-xs">
                  <span :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
                    <i class="fa-solid fa-database mr-1"></i> Records synced: <span class="font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'" x-text="log.records_synced"></span>
                  </span>
                  <span x-show="log.records_failed > 0" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Failed: <span class="font-bold text-red-500" x-text="log.records_failed"></span>
                  </span>
                  <span x-show="log.completed_at" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
                    <i class="fa-solid fa-clock mr-1"></i> Duration: <span class="font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'" x-text="formatDuration(log.started_at, log.completed_at)"></span>
                  </span>
                </div>
                <div x-show="log.error" class="mt-2 p-2 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50">
                  <p class="text-xs text-red-600 dark:text-red-400 font-mono" x-text="log.error"></p>
                </div>
              </div>
            </div>
            <div class="flex-shrink-0">
              <button @click="viewDetails(log)" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-50 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors border border-primary-100 dark:border-primary-800/50">
                <i class="fa-solid fa-info-circle text-[10px]"></i> Details
              </button>
            </div>
          </div>
        </div>
      </template>
    </div>

    <div x-show="pagination.total > 0" class="flex items-center justify-between mt-6">
      <p class="text-sm" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
        Showing <span x-text="(pagination.current_page - 1) * pagination.per_page + 1"></span> to <span x-text="Math.min(pagination.current_page * pagination.per_page, pagination.total)"></span> of <span x-text="pagination.total"></span> logs
      </p>
      <div class="flex items-center gap-2">
        <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
                class="px-3 py-1.5 rounded-lg bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          Previous
        </button>
        <span class="text-sm font-bold px-3" :class="darkMode ? 'text-white' : 'text-primary-900'" x-text="pagination.current_page"></span>
        <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page"
                class="px-3 py-1.5 rounded-lg bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          Next
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function syncLogsPage() {
    return {
      loading: false,
      search: '',
      typeFilter: '',
      statusFilter: '',
      logs: [],
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 50,
        total: 0
      },
      darkMode: document.documentElement.classList.contains('dark'),
      async loadLogs(page = 1) {
        this.loading = true;
        try {
          const params = new URLSearchParams({
            page: page,
            type: this.typeFilter,
            status: this.statusFilter,
            limit: 50
          });
          
          const response = await fetch('{{ route('admin.google-sheets.logs') }}?' + params.toString(), {
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });
          
          const data = await response.json();
          if (data.success) {
            this.logs = data.data;
            this.pagination = {
              current_page: 1,
              last_page: 1,
              per_page: data.data.length,
              total: data.data.length
            };
          }
        } catch (error) {
          console.error('Failed to load logs:', error);
        } finally {
          this.loading = false;
        }
      },
      changePage(page) {
        if (page >= 1 && page <= this.pagination.last_page) {
          this.loadLogs(page);
        }
      },
      viewDetails(log) {
        alert('Sync ID: ' + log.id + '\nType: ' + log.sync_type + '\nStatus: ' + log.status + '\nRecords Synced: ' + log.records_synced + '\nRecords Failed: ' + log.records_failed + '\nStarted: ' + log.started_at + '\nCompleted: ' + (log.completed_at || 'N/A') + (log.error ? '\nError: ' + log.error : ''));
      },
      formatSyncType(type) {
        return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
      },
      formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-GB', { 
          day: '2-digit', 
          month: 'short', 
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      },
      formatDuration(start, end) {
        if (!start || !end) return '-';
        const startDate = new Date(start);
        const endDate = new Date(end);
        const diff = Math.floor((endDate - startDate) / 1000);
        const minutes = Math.floor(diff / 60);
        const seconds = diff % 60;
        return minutes > 0 ? `${minutes}m ${seconds}s` : `${seconds}s`;
      },
      init() {
        this.loadLogs();
      }
    }
  }
</script>
@endpush
