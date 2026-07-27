@extends('layouts.admin')

@section('breadcrumb', 'Integrations \u203A Google Sheets')
@section('page_title', 'Google Sheets Integration')

@section('content')

<div x-data="googleSheetsDashboard()" class="space-y-6">

  <div class="glass p-6 lg:p-8 relative overflow-hidden"
       style="border-width: 2px;"
       :class="lastSync && lastSync.status === 'completed' ? 'border-primary-300 dark:border-primary-700' : 'border-yellow-300 dark:border-yellow-800'">
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-500/10 to-transparent rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>

    <div class="relative flex flex-col md:flex-row md:items-start gap-6">
      <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-lime-400 via-green-500 to-emerald-600 text-white flex items-center justify-center text-4xl shadow-lg flex-shrink-0">
        <i class="fa-brands fa-google"></i>
      </div>

      <div class="flex-1">
        <div class="flex flex-wrap items-center gap-3 mb-2">
          <h1 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Google Sheets Integration</h1>
          <span x-show="lastSync && lastSync.status === 'completed'" class="badge badge-green !px-3 !py-1 text-[11px]">
            <i class="fa-solid fa-circle-check mr-1 text-[10px]"></i> Connected
          </span>
          <span x-show="!lastSync || lastSync.status !== 'completed'" class="badge badge-yellow !px-3 !py-1 text-[11px]">
            <i class="fa-solid fa-triangle-exclamation mr-1 text-[10px]"></i> Not Synced
          </span>
        </div>
        <p class="text-sm mb-5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
          Live data sync between the cooperative management system and Google Sheets workbook
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="p-4 rounded-2xl bg-white/60 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800/50">
            <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500 mb-1">Total Customers</p>
            <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">
              {{ number_format($total_customers) }}
            </p>
          </div>
          <div class="p-4 rounded-2xl bg-white/60 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800/50">
            <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500 mb-1">Last Sync</p>
            <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">
              {{ $last_sync ? $last_sync->completed_at?->format('d M Y, H:i') : 'Never synced' }}
            </p>
          </div>
          <div class="p-4 rounded-2xl bg-white/60 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800/50">
            <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500 mb-1">Total Balance</p>
            <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">
              KES {{ number_format($total_balance, 2) }}
            </p>
          </div>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row md:flex-col gap-3 md:flex-shrink-0">
        <button @click="triggerSync('all')"
                :disabled="syncing"
                class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-400 hover:to-primary-500 text-white font-bold transition-all shadow-lg hover:shadow-xl active:scale-[0.97] disabled:opacity-60 disabled:cursor-not-allowed">
          <i :class="syncing ? 'fa-solid fa-circle-notch fa-spin' : 'fa-solid fa-rotate'" class="text-[15px]"></i>
          <span x-text="syncing ? 'Syncing...' : 'Sync All Data'">Sync All Data</span>
        </button>
        <button @click="triggerSync('active')"
                :disabled="syncing"
                class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-colors">
          <i class="fa-solid fa-user-check text-[13px]"></i> Sync Active Only
        </button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    <div class="glass p-5">
      <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500">Total Customers</p>
        <i class="fa-solid fa-users text-primary-400"></i>
      </div>
      <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ number_format($total_customers) }}</p>
      <div class="mt-3 flex items-center gap-2">
        <span class="badge badge-green !text-[10px]">{{ number_format($active_customers) }} Active</span>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500">Total Balance</p>
        <i class="fa-solid fa-wallet text-primary-400"></i>
      </div>
      <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">KES {{ number_format($total_balance) }}</p>
      <div class="mt-3 flex items-center gap-1">
        <span class="text-[11px]" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Across all accounts</span>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500">Sync Status</p>
        <i class="fa-solid fa-sync text-primary-400"></i>
      </div>
      <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $sync_stats['total'] ?? 0 }}</p>
      <div class="mt-3 flex items-center gap-2">
        <span class="badge badge-green !text-[10px]">{{ $sync_stats['success_rate'] ?? 0 }}% Success</span>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500">Next Sync</p>
        <i class="fa-solid fa-clock text-primary-400"></i>
      </div>
      <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">6<span class="text-sm text-primary-500 ml-0.5">h</span></p>
      <div class="mt-3 h-1.5 bg-primary-100 dark:bg-primary-900/40 rounded-full overflow-hidden">
        <div class="h-full w-1/12 bg-gradient-to-r from-blue-400 to-indigo-600 rounded-full"></div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="glass p-5 lg:p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-base" :class="darkMode ? 'text-white' : 'text-primary-900'">Account Breakdown</h3>
      </div>
      <div class="space-y-3">
        <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-500 text-white flex items-center justify-center">
              <i class="fa-solid fa-wallet text-sm"></i>
            </div>
            <div>
              <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">Flexi</p>
              <p class="text-xs" :class="darkMode ? 'text-primary-500' : 'text-primary-600'">Flexible Savings</p>
            </div>
          </div>
          <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">KES {{ number_format($account_breakdown['flexi'] ?? 0) }}</p>
        </div>
        <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-green-500 text-white flex items-center justify-center">
              <i class="fa-solid fa-piggy-bank text-sm"></i>
            </div>
            <div>
              <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">RDA</p>
              <p class="text-xs" :class="darkMode ? 'text-primary-500' : 'text-primary-600'">Regular Deposit</p>
            </div>
          </div>
          <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">KES {{ number_format($account_breakdown['rda'] ?? 0) }}</p>
        </div>
        <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-red-500 text-white flex items-center justify-center">
              <i class="fa-solid fa-shield text-sm"></i>
            </div>
            <div>
              <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">Emergency</p>
              <p class="text-xs" :class="darkMode ? 'text-primary-500' : 'text-primary-600'">Emergency Fund</p>
            </div>
          </div>
          <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">KES {{ number_format($account_breakdown['emergency'] ?? 0) }}</p>
        </div>
        <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-purple-500 text-white flex items-center justify-center">
              <i class="fa-solid fa-briefcase text-sm"></i>
            </div>
            <div>
              <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">Business</p>
              <p class="text-xs" :class="darkMode ? 'text-primary-500' : 'text-primary-600'">Business Savings</p>
            </div>
          </div>
          <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">KES {{ number_format($account_breakdown['business'] ?? 0) }}</p>
        </div>
      </div>
    </div>

    <div class="glass p-5 lg:p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-base" :class="darkMode ? 'text-white' : 'text-primary-900'">Recent Sync Logs</h3>
      </div>
      <div class="space-y-3 max-h-80 overflow-y-auto">
        @forelse($sync_logs as $log)
          <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg @if($log->status === 'completed') bg-green-500 @elseif($log->status === 'failed') bg-red-500 @else bg-yellow-500 @endif text-white flex items-center justify-center">
                <i class="fa-solid @if($log->status === 'completed') fa-check @elseif($log->status === 'failed') fa-xmark @else fa-clock @endif text-xs"></i>
              </div>
              <div>
                <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ ucfirst($log->sync_type) }}</p>
                <p class="text-xs" :class="darkMode ? 'text-primary-500' : 'text-primary-600'">{{ $log->created_at?->format('d M Y, H:i') }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-xs font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $log->records_synced }} synced</p>
              <span class="badge @if($log->status === 'completed') badge-green @elseif($log->status === 'failed') badge-red @else badge-yellow @endif text-[10px]">{{ ucfirst($log->status) }}</span>
            </div>
          </div>
        @empty
          <p class="text-center text-sm py-8" :class="darkMode ? 'text-primary-500' : 'text-primary-600'">No sync logs available</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function googleSheetsDashboard() {
    return {
      syncing: false,
      lastSync: @json($last_sync),
      darkMode: document.documentElement.classList.contains('dark'),
      async triggerSync(type) {
        this.syncing = true;
        try {
          const response = await fetch('{{ route('admin.google-sheets.sync') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ type: type, force: false })
          });
          const data = await response.json();
          if (data.success) {
            setTimeout(() => window.location.reload(), 1500);
          } else {
            alert('Sync failed: ' + (data.error || 'Unknown error'));
          }
        } catch (error) {
          alert('Sync failed: ' + error.message);
        } finally {
          this.syncing = false;
        }
      },
      init() {
      }
    }
  }
</script>
@endpush
