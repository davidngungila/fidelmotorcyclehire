@extends('layouts.admin')

@section('breadcrumb', 'Motorcycle Inventory \u203A Details')
@section('page_title', 'Motorcycle Details')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

  <div class="glass p-6">
    <div class="flex items-start justify-between mb-6">
      <div>
        <h3 class="text-xl font-bold text-primary-900 dark:text-white">{{ $motorcycle->brand }} {{ $motorcycle->model }}</h3>
        <p class="text-sm text-primary-600 dark:text-primary-400 mt-1">Engine: {{ $motorcycle->engine_number }}</p>
      </div>
      <span class="badge {{ $motorcycle->status === 'Available' ? 'badge-green' : ($motorcycle->status === 'Assigned' ? 'badge-blue' : ($motorcycle->status === 'Sold' ? 'badge-red' : 'badge-orange')) }}">
        {{ $motorcycle->status }}
      </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="space-y-4">
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Brand</p>
          <p class="text-sm text-primary-900 dark:text-white">{{ $motorcycle->brand }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Model</p>
          <p class="text-sm text-primary-900 dark:text-white">{{ $motorcycle->model }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Engine Number</p>
          <p class="text-sm font-mono text-primary-900 dark:text-white">{{ $motorcycle->engine_number }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Chassis Number</p>
          <p class="text-sm font-mono text-primary-900 dark:text-white">{{ $motorcycle->chassis_number }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Registration Number</p>
          <p class="text-sm font-mono text-primary-900 dark:text-white">{{ $motorcycle->registration_number ?? '-' }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Colour</p>
          <p class="text-sm text-primary-900 dark:text-white">{{ $motorcycle->colour }}</p>
        </div>
      </div>

      <div class="space-y-4">
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Purchase Price</p>
          <p class="text-sm font-mono text-primary-900 dark:text-white">{{ number_format($motorcycle->purchase_price, 2) }} TZS</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Selling Price</p>
          <p class="text-sm font-mono text-primary-900 dark:text-white">{{ $motorcycle->selling_price ? number_format($motorcycle->selling_price, 2) . ' TZS' : '-' }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Status</p>
          <p class="text-sm text-primary-900 dark:text-white">{{ $motorcycle->status }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Assigned To</p>
          <p class="text-sm text-primary-900 dark:text-white">{{ $motorcycle->assignedUser?->name ?? '-' }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Purchase Date</p>
          <p class="text-sm text-primary-900 dark:text-white">{{ $motorcycle->purchase_date?->format('d M Y') ?? '-' }}</p>
        </div>
        <div>
          <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-1">Sale Date</p>
          <p class="text-sm text-primary-900 dark:text-white">{{ $motorcycle->sale_date?->format('d M Y') ?? '-' }}</p>
        </div>
      </div>
    </div>

    @if($motorcycle->notes)
      <div class="mt-6 pt-6 border-t border-primary-200 dark:border-primary-800">
        <p class="text-xs font-semibold text-primary-500 dark:text-primary-400 uppercase tracking-wider mb-2">Notes</p>
        <p class="text-sm text-primary-900 dark:text-white">{{ $motorcycle->notes }}</p>
      </div>
    @endif
  </div>

  <div class="flex items-center justify-end gap-3">
    <a href="{{ route('admin.motorcycles.index') }}"
       class="px-5 py-2.5 rounded-xl border border-primary-300 dark:border-primary-700 text-primary-700 dark:text-primary-300 text-sm font-bold hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-all">
      <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
    </a>
    <a href="{{ route('admin.motorcycles.edit', $motorcycle) }}"
       class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
      <i class="fa-solid fa-pen mr-2"></i> Edit Motorcycle
    </a>
  </div>

</div>

@endsection
