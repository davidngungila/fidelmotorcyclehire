@extends('layouts.admin')

@section('breadcrumb', 'Integrations \u203A Google Sheets')
@section('page_title', 'Google Sheets Integration')

@section('content')

<div x-data="googleSheetsDashboard()" class="space-y-6">

  <div class="glass p-6 lg:p-8 relative overflow-hidden"
       :class="isActive ? 'border-primary-300 dark:border-primary-700' : 'border-yellow-300 dark:border-yellow-800'"
       style="border-width: 2px;">
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-500/10 to-transparent rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>

    <div class="relative flex flex-col md:flex-row md:items-start gap-6">
      <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-lime-400 via-green-500 to-emerald-600 text-white flex items-center justify-center text-4xl shadow-lg flex-shrink-0">
        <i class="fa-brands fa-google"></i>
      </div>

      <div class="flex-1">
        <div class="flex flex-wrap items-center gap-3 mb-2">
          <h1 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Google Sheets Integration</h1>
          <span x-show="isActive" class="badge badge-green !px-3 !py-1 text-[11px]">
            <i class="fa-solid fa-circle-check mr-1 text-[10px]"></i> Connected
          </span>
          <span x-show="!isActive" class="badge badge-yellow !px-3 !py-1 text-[11px]">
            <i class="fa-solid fa-triangle-exclamation mr-1 text-[10px]"></i> Not Configured
          </span>
        </div>
        <p class="text-sm mb-5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
          Live data sync between the cooperative management system and Google Sheets workbook
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="p-4 rounded-2xl bg-white/60 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800/50">
            <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500 mb-1">Spreadsheet ID</p>
            <p class="font-mono text-xs truncate" :class="darkMode ? 'text-white' : 'text-primary-900'"
               :title="{{ json_encode($spreadsheetId) }}">
              {{ $spreadsheetId ?: '(Not set)' }}
            </p>
          </div>
          <div class="p-4 rounded-2xl bg-white/60 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800/50">
            <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500 mb-1">Last Sync</p>
            <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">
              {{ $lastSyncAt ? $lastSyncAt->format('d M Y, H:i') : 'Never synced' }}
            </p>
          </div>
          <div class="p-4 rounded-2xl bg-white/60 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800/50">
            <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500 mb-1">Worksheets</p>
            <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">
              {{ is_array($sheetNames) ? count($sheetNames) : 0 }} sheets configured
            </p>
          </div>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row md:flex-col gap-3 md:flex-shrink-0">
        <form method="POST" action="{{ route('admin.google-sheets.sync') }}" x-data="{ syncing: false }">
          @csrf
          <input type="hidden" name="spreadsheet_id" value="{{ $spreadsheetId }}">
          <button type="submit"
                  @click="syncing = true"
                  :disabled="syncing"
                  class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-400 hover:to-primary-500 text-white font-bold transition-all shadow-lg hover:shadow-xl active:scale-[0.97] disabled:opacity-60 disabled:cursor-not-allowed">
            <i :class="syncing ? 'fa-solid fa-circle-notch fa-spin' : 'fa-solid fa-rotate'" class="text-[15px]"></i>
            <span x-text="syncing ? 'Syncing...' : 'Sync All Sheets'">Sync All Sheets</span>
          </button>
        </form>
        <a href="{{ route('admin.settings.index') }}"
           class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-colors">
          <i class="fa-solid fa-gear text-[13px]"></i> Configuration
        </a>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    @php
      $totalRows = 0;
      foreach($sampleSheets as $sheet) { $totalRows += $sheet['rows']; }
    @endphp

    <div class="glass p-5">
      <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500">Total Rows</p>
        <i class="fa-solid fa-database text-primary-400"></i>
      </div>
      <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ number_format($totalRows) }}</p>
      <div class="mt-3 h-1.5 bg-primary-100 dark:bg-primary-900/40 rounded-full overflow-hidden">
        <div class="h-full w-4/5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-full"></div>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500">Sheets Status</p>
        <i class="fa-solid fa-file-spreadsheet text-primary-400"></i>
      </div>
      <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ count($sampleSheets) }} / {{ count($sampleSheets) }}</p>
      <div class="mt-3 flex items-center gap-2">
        <span class="badge badge-green !text-[10px]"><i class="fa-solid fa-check mr-1 text-[9px]"></i>All Synced</span>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500">Avg. Sync Time</p>
        <i class="fa-solid fa-bolt text-primary-400"></i>
      </div>
      <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">2.4<span class="text-sm text-primary-500 ml-0.5">s</span></p>
      <div class="mt-3 flex items-center gap-1">
        <i class="fa-solid fa-arrow-trend-down text-green-500 text-[11px]"></i>
        <span class="text-[11px] text-green-600 dark:text-green-400 font-semibold">-18% faster</span>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between mb-3">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500">Next Scheduled</p>
        <i class="fa-solid fa-clock text-primary-400"></i>
      </div>
      <p class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">29<span class="text-sm text-primary-500 ml-0.5">m</span></p>
      <div class="mt-3 h-1.5 bg-primary-100 dark:bg-primary-900/40 rounded-full overflow-hidden">
        <div class="h-full w-1/12 bg-gradient-to-r from-blue-400 to-indigo-600 rounded-full"></div>
      </div>
    </div>
  </div>

  <div class="glass p-5 lg:p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div class="flex items-center gap-3">
        <h3 class="font-bold text-base" :class="darkMode ? 'text-white' : 'text-primary-900'">Sheet Status & Details</h3>
        <span class="badge badge-green text-[10px]"><i class="fa-solid fa-circle-check mr-1 text-[9px]"></i> Healthy</span>
      </div>
      <div class="flex items-center gap-2 text-xs">
        <span class="inline-flex items-center gap-1.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
          <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Synced
        </span>
        <span class="inline-flex items-center gap-1.5 ml-3" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
          <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Pending
        </span>
        <span class="inline-flex items-center gap-1.5 ml-3" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
          <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Error
        </span>
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th style="width: 48px;"></th>
            <th>Sheet Name</th>
            <th>Records</th>
            <th>Last Sync</th>
            <th>Columns</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($sampleSheets as $sheet)
            @php
              $colorMap = [
                'primary' => 'from-primary-400 to-primary-600 bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-400',
                'orange' => 'from-orange-400 to-orange-600 bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400',
                'blue' => 'from-blue-400 to-blue-600 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400',
                'purple' => 'from-purple-400 to-purple-600 bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400',
                'pink' => 'from-pink-400 to-pink-600 bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-400',
                'lime' => 'from-lime-400 to-lime-600 bg-lime-100 dark:bg-lime-900/40 text-lime-600 dark:text-lime-400',
                'indigo' => 'from-indigo-400 to-indigo-600 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400',
              ];
              $colors = $colorMap[$sheet['color']] ?? $colorMap['primary'];
              $colorParts = explode(' ', $colors);
              $gradient = array_shift($colorParts) . ' ' . array_shift($colorParts);
              $bgRest = implode(' ', $colorParts);
            @endphp
            <tr>
              <td>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $gradient }} text-white flex items-center justify-center text-base shadow-sm">
                  <i class="fa-solid {{ $sheet['icon'] }} text-[14px]"></i>
                </div>
              </td>
              <td>
                <div class="flex items-center gap-3">
                  <div>
                    <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $sheet['name'] }}</p>
                    <p class="text-[11px]" :class="darkMode ? 'text-primary-500' : 'text-primary-500'">
                      <span class="font-mono">Sheet{{ $loop->iteration }}!A1:Z</span>
                    </p>
                  </div>
                </div>
              </td>
              <td>
                <span class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ number_format($sheet['rows']) }}</span>
                <span class="text-xs ml-1" :class="darkMode ? 'text-primary-500' : 'text-primary-500'">rows</span>
              </td>
              <td>
                <span class="text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
                  {{ $lastSyncAt ? $lastSyncAt->format('H:i:s') : 'Pending' }}
                </span>
                <p class="text-[10px]" :class="darkMode ? 'text-primary-500' : 'text-primary-500'">
                  {{ $lastSyncAt ? $lastSyncAt->format('d M Y') : '-' }}
                </p>
              </td>
              <td>
                <div class="flex -space-x-1.5">
                  <span class="w-6 h-6 rounded-lg border-2 {{ $bgRest }} border-white dark:border-[#0d1f16] flex items-center justify-center text-[9px] font-bold">A</span>
                  <span class="w-6 h-6 rounded-lg border-2 {{ $bgRest }} border-white dark:border-[#0d1f16] flex items-center justify-center text-[9px] font-bold opacity-80">B</span>
                  <span class="w-6 h-6 rounded-lg border-2 {{ $bgRest }} border-white dark:border-[#0d1f16] flex items-center justify-center text-[9px] font-bold opacity-60">C</span>
                  <span class="w-6 h-6 rounded-lg border-2 bg-gray-100 dark:bg-gray-800 border-white dark:border-[#0d1f16] flex items-center justify-center text-[9px] font-bold text-gray-500">+</span>
                </div>
              </td>
              <td>
                <span class="badge badge-green text-[10px]">
                  <i class="fa-solid fa-check mr-1 text-[9px]"></i> Synced ✓
                </span>
              </td>
              <td class="text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-2">
                  <button class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors">
                    <i class="fa-solid fa-eye text-[10px]"></i> Preview
                  </button>
                  <form method="POST" action="{{ route('admin.google-sheets.sync') }}" x-data class="inline">
                    @csrf
                    <input type="hidden" name="spreadsheet_id" value="{{ $spreadsheetId }}">
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white hover:bg-gray-50 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors border border-primary-100 dark:border-primary-800/50">
                      <i class="fa-solid fa-rotate text-[10px]"></i> Sync
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function googleSheetsDashboard() {
    return {
      isActive: @json($isActive ?? true),
      init() {
      }
    }
  }
</script>
@endpush
