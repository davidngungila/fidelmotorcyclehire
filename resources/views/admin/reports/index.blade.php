@extends('layouts.admin')

@section('breadcrumb', 'Reports')
@section('page_title', 'Reports Dashboard')

@section('content')

<div x-data="reportsDashboard()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
        Generate comprehensive reports across all cooperative modules
      </p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

    @foreach($reports as $report)
      <div class="glass p-5 group hover:shadow-lg transition-all duration-300"
           x-data="{ open: false }">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $report['color'] }} flex items-center justify-center text-white text-2xl flex-shrink-0 shadow-md group-hover:scale-110 transition-transform">
            <i class="fa-solid {{ $report['icon'] }}"></i>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-base" :class="darkMode ? 'text-white' : 'text-primary-900'">
              {{ $report['title'] }}
            </h3>
            <p class="text-xs mt-1 line-clamp-2" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
              {{ $report['description'] }}
            </p>
          </div>
        </div>

        <div class="mt-5 pt-4 border-t border-primary-100 dark:border-primary-900/50">
          <button @click="open = !open"
                  class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ $report['bg_color'] }} {{ $report['text_color'] }} hover:shadow-md">
            <i class="fa-solid fa-sliders text-[11px]"></i>
            <span x-text="open ? 'Hide Filters' : 'Configure Filters'">Configure Filters</span>
            <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-[10px] ml-1"></i>
          </button>
        </div>

        <div x-show="open" x-collapse
             class="mt-4 space-y-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

          <form method="POST" action="{{ route('admin.reports.generate') }}" x-data="reportForm()">
            @csrf
            <input type="hidden" name="report_type" value="{{ $report['type'] }}">

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Date From</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] }}"
                       class="form-input text-xs py-2">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Date To</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] }}"
                       class="form-input text-xs py-2">
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-3">
              <div>
                <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Branch</label>
                <select name="branch" class="form-input text-xs py-2">
                  <option value="">All Branches</option>
                  @foreach($branches as $branch)
                    <option value="{{ $branch }}" {{ $filters['branch'] === $branch ? 'selected' : '' }}>
                      {{ $branch }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Status</label>
                <select name="status" class="form-input text-xs py-2">
                  <option value="">All Statuses</option>
                  @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ $filters['status'] === $status ? 'selected' : '' }}>
                      {{ $status }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="mt-3">
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Format</label>
              <div class="flex gap-2 mt-1">
                <label class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-all text-xs font-semibold
                             border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                             has-[:checked]:bg-primary-600 has-[:checked]:text-white has-[:checked]:border-primary-600">
                  <input type="radio" name="format" value="csv" class="hidden" {{ $filters['format'] === 'csv' ? 'checked' : '' }}>
                  <i class="fa-solid fa-file-csv text-[11px]"></i> CSV
                </label>
                <label class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition-all text-xs font-semibold
                             border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                             has-[:checked]:bg-primary-600 has-[:checked]:text-white has-[:checked]:border-primary-600">
                  <input type="radio" name="format" value="print" class="hidden" {{ $filters['format'] === 'print' ? 'checked' : '' }}>
                  <i class="fa-solid fa-print text-[11px]"></i> Print
                </label>
              </div>
            </div>

            <button type="submit"
                    class="w-full mt-5 py-2.5 rounded-xl bg-gradient-to-r {{ $report['color'] }} text-white text-xs font-bold transition-all hover:shadow-lg active:scale-[0.98]">
              <i class="fa-solid fa-file-arrow-down mr-1.5 text-[11px]"></i>
              Generate {{ $report['title'] }}
            </button>
          </form>
        </div>
      </div>
    @endforeach
  </div>

</div>

@endsection

@push('scripts')
<script>
  function reportsDashboard() {
    return {
      init() {
      }
    }
  }

  function reportForm() {
    return {}
  }
</script>
@endpush
