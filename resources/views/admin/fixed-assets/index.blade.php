@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Fixed Assets')
@section('page_title', 'Fixed Assets')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fixed Assets</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Manage fixed assets and depreciation</p>
    </div>
    <a href="{{ route('admin.fixed-assets.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
      <i class="fa-solid fa-plus"></i> New Fixed Asset
    </a>
  </div>

  <div class="glass rounded-xl p-6">
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead>
          <tr>
            <th>Asset Code</th>
            <th>Asset Name</th>
            <th>Purchase Date</th>
            <th>Purchase Cost</th>
            <th>Current Value</th>
            <th>Depreciation Method</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($fixedAssets as $asset)
            <tr>
              <td class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $asset->asset_code }}</td>
              <td>
                <div class="font-semibold text-gray-900 dark:text-white">{{ $asset->asset_name }}</div>
                @if($asset->location)
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ $asset->location }}</div>
                @endif
              </td>
              <td>{{ $asset->purchase_date->format('M d, Y') }}</td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ number_format($asset->purchase_cost, 2) }}
              </td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ number_format($asset->current_value, 2) }}
              </td>
              <td>
                <span class="badge badge-{{ $asset->depreciation_method === 'straight_line' ? 'blue' : 'purple' }}">
                  {{ str_replace('_', ' ', ucfirst($asset->depreciation_method)) }}
                </span>
              </td>
              <td>
                @if($asset->is_active)
                  <span class="badge badge-green">Active</span>
                @else
                  <span class="badge badge-red">Inactive</span>
                @endif
              </td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.fixed-assets.show', $asset->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  <a href="{{ route('admin.fixed-assets.edit', $asset->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                    <i class="fa-solid fa-edit"></i>
                  </a>
                  <button onclick="calculateDepreciation({{ $asset->id }})" class="text-gray-600 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400 transition-colors" title="Calculate Depreciation">
                    <i class="fa-solid fa-calculator"></i>
                  </button>
                  <button onclick="deleteFixedAsset({{ $asset->id }})" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-building text-3xl mb-3 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No fixed assets found</p>
                <p class="text-xs">Create your first fixed asset to get started</p>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
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

function deleteFixedAsset(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`{{ route('admin.fixed-assets.destroy', ':id') }}`.replace(':id', id), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Deleted!', 'Fixed asset has been deleted.', 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message || 'Failed to delete fixed asset', 'error');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to delete fixed asset', 'error');
      });
    }
  });
}
</script>
