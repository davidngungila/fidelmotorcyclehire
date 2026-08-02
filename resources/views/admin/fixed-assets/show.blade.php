@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Fixed Assets \u203A View Fixed Asset')
@section('page_title', 'View Fixed Asset')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $fixedAsset->asset_name }}</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $fixedAsset->asset_code }}</p>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="calculateDepreciation({{ $fixedAsset->id }})" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-100 hover:bg-green-200 dark:bg-green-900/40 dark:hover:bg-green-900/60 text-green-700 dark:text-green-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-calculator"></i> Calculate Depreciation
      </button>
      <a href="{{ route('admin.fixed-assets.edit', $fixedAsset->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <a href="{{ route('admin.fixed-assets.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Asset Details</h3>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Asset Code:</span>
            <div class="font-mono font-bold text-gray-900 dark:text-white">{{ $fixedAsset->asset_code }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Asset Name:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $fixedAsset->asset_name }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Purchase Date:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $fixedAsset->purchase_date->format('M d, Y') }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Depreciation Method:</span>
            <div>
              <span class="badge badge-{{ $fixedAsset->depreciation_method === 'straight_line' ? 'blue' : 'purple' }}">
                {{ str_replace('_', ' ', ucfirst($fixedAsset->depreciation_method)) }}
              </span>
            </div>
          </div>
          @if($fixedAsset->location)
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Location:</span>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $fixedAsset->location }}</div>
            </div>
          @endif
          @if($fixedAsset->serial_number)
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Serial Number:</span>
              <div class="font-mono font-semibold text-gray-900 dark:text-white">{{ $fixedAsset->serial_number }}</div>
            </div>
          @endif
          @if($fixedAsset->description)
            <div class="col-span-2">
              <span class="text-sm text-gray-600 dark:text-gray-400">Description:</span>
              <div class="text-sm text-gray-900 dark:text-white mt-1">{{ $fixedAsset->description }}</div>
            </div>
          @endif
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
            <div>
              @if($fixedAsset->is_active)
                <span class="badge badge-green">Active</span>
              @else
                <span class="badge badge-red">Inactive</span>
              @endif
            </div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Created At:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $fixedAsset->created_at->format('M d, Y H:i') }}</div>
          </div>
        </div>
        @if($fixedAsset->notes)
          <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <span class="text-sm text-gray-600 dark:text-gray-400">Notes:</span>
            <div class="text-sm text-gray-900 dark:text-white mt-1">{{ $fixedAsset->notes }}</div>
          </div>
        @endif
      </div>

      @if($fixedAsset->account)
        <div class="glass rounded-xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Linked Chart of Account</h3>
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
              <i class="fa-solid fa-book text-primary-700 dark:text-primary-300"></i>
            </div>
            <div>
              <div class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $fixedAsset->account->account_code }}</div>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $fixedAsset->account->account_name }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ $fixedAsset->account->account_type }} - {{ $fixedAsset->account->account_subtype }}</div>
            </div>
          </div>
        </div>
      @endif

      @if($fixedAsset->responsiblePerson)
        <div class="glass rounded-xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Responsible Person</h3>
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
              <span class="text-primary-700 dark:text-primary-300 font-semibold">{{ strtoupper(substr($fixedAsset->responsiblePerson->name, 0, 1)) }}</span>
            </div>
            <div>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $fixedAsset->responsiblePerson->name }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ $fixedAsset->responsiblePerson->email }}</div>
            </div>
          </div>
        </div>
      @endif
    </div>

    <div class="space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Summary</h3>
        <div class="space-y-3">
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Purchase Cost:</span>
            <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($fixedAsset->purchase_cost, 2) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Salvage Value:</span>
            <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($fixedAsset->salvage_value, 2) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Useful Life:</span>
            <span class="font-semibold text-gray-900 dark:text-white">{{ $fixedAsset->useful_life_years }} years</span>
          </div>
          <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">Accumulated Depreciation:</span>
              <span class="font-mono font-bold text-red-600 dark:text-red-400">{{ number_format($fixedAsset->accumulated_depreciation, 2) }}</span>
            </div>
          </div>
          <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">Current Value:</span>
              <span class="font-mono font-bold text-2xl text-primary-700 dark:text-primary-300">{{ number_format($fixedAsset->current_value, 2) }}</span>
            </div>
          </div>
          <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">Net Book Value:</span>
              <span class="font-mono font-bold {{ $fixedAsset->current_value >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ number_format($fixedAsset->current_value - $fixedAsset->accumulated_depreciation, 2) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="space-y-2">
          <a href="{{ route('admin.journal-entries.create') }}" class="block w-full text-center px-4 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
            <i class="fa-solid fa-plus mr-2"></i> New Journal Entry
          </a>
          <a href="{{ route('admin.ledger.show', $fixedAsset->account_id) }}" class="block w-full text-center px-4 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
            <i class="fa-solid fa-book mr-2"></i> View Ledger
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function calculateDepreciation(id) {
  Swal.fire({
    title: 'Calculate Depreciation?',
    text: 'This will update the accumulated depreciation and current value.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#10b981',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, calculate it!'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`{{ route('admin.fixed-assets.calculate-depreciation', ':id') }}`.replace(':id', id), {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Calculated!', 'Depreciation has been calculated.', 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message || 'Failed to calculate depreciation', 'error');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to calculate depreciation', 'error');
      });
    }
  });
}
</script>
