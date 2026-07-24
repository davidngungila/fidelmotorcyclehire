@extends('layouts.admin')

@section('breadcrumb', 'Google Sheets › Sync')
@section('page_title', 'Sync Google Sheets')

@section('content')

<div class="space-y-6">
  <div class="glass p-6 rounded-2xl">
    <div class="flex items-center gap-4 mb-6">
      <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-400 to-green-600 text-white flex items-center justify-center">
        <i class="fa-solid fa-rotate text-lg"></i>
      </div>
      <div>
        <h2 class="text-lg font-bold text-primary-900 dark:text-white">Sync in Progress</h2>
        <p class="text-sm text-primary-600 dark:text-primary-400">Please wait while we sync your Google Sheets data...</p>
      </div>
    </div>

    <div class="space-y-4">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
          <i class="fa-solid fa-spinner fa-spin text-sm"></i>
        </div>
        <div class="flex-1">
          <p class="text-sm font-semibold text-primary-900 dark:text-white">Connecting to Google Sheets API...</p>
          <p class="text-xs text-primary-500 dark:text-primary-400">Establishing secure connection</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
          <i class="fa-solid fa-spinner fa-spin text-sm"></i>
        </div>
        <div class="flex-1">
          <p class="text-sm font-semibold text-primary-900 dark:text-white">Fetching spreadsheet data...</p>
          <p class="text-xs text-primary-500 dark:text-primary-400">Retrieving data from configured sheets</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
          <i class="fa-solid fa-spinner fa-spin text-sm"></i>
        </div>
        <div class="flex-1">
          <p class="text-sm font-semibold text-primary-900 dark:text-white">Processing records...</p>
          <p class="text-xs text-primary-500 dark:text-primary-400">Validating and storing data</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
          <i class="fa-solid fa-spinner fa-spin text-sm"></i>
        </div>
        <div class="flex-1">
          <p class="text-sm font-semibold text-primary-900 dark:text-white">Updating sync status...</p>
          <p class="text-xs text-primary-500 dark:text-primary-400">Recording sync metadata</p>
        </div>
      </div>
    </div>
  </div>

  <div class="glass p-6 rounded-2xl">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center">
        <i class="fa-solid fa-circle-info"></i>
      </div>
      <div>
        <h3 class="font-bold text-primary-900 dark:text-white text-sm">Sync Information</h3>
        <p class="text-[11px] text-primary-500 dark:text-primary-400">What happens during sync</p>
      </div>
    </div>
    <ul class="space-y-2 text-sm text-primary-700 dark:text-primary-300">
      <li class="flex items-start gap-2">
        <i class="fa-solid fa-check text-green-500 mt-0.5 text-xs"></i>
        <span>All sheet data is fetched from Google Sheets</span>
      </li>
      <li class="flex items-start gap-2">
        <i class="fa-solid fa-check text-green-500 mt-0.5 text-xs"></i>
        <span>Data is validated and processed for consistency</span>
      </li>
      <li class="flex items-start gap-2">
        <i class="fa-solid fa-check text-green-500 mt-0.5 text-xs"></i>
        <span>Sync timestamp is updated for tracking</span>
      </li>
      <li class="flex items-start gap-2">
        <i class="fa-solid fa-check text-green-500 mt-0.5 text-xs"></i>
        <span>Activity log records the sync operation</span>
      </li>
    </ul>
  </div>
</div>

@push('scripts')
<script>
  // Auto-redirect after sync completes (simulated)
  setTimeout(() => {
    window.location.href = '{{ route('admin.google-sheets.index') }}';
  }, 3000);
</script>
@endpush
