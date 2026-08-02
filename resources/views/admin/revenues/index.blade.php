@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Revenues')
@section('page_title', 'Revenues')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Revenues</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Track and manage business revenues</p>
    </div>
    <a href="{{ route('admin.revenues.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
      <i class="fa-solid fa-plus"></i> New Revenue
    </a>
  </div>

  <div class="glass rounded-xl p-6">
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead>
          <tr>
            <th>Revenue Number</th>
            <th>Date</th>
            <th>Description</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($revenues as $revenue)
            <tr>
              <td class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $revenue->entry_number }}</td>
              <td>{{ $revenue->entry_date->format('M d, Y') }}</td>
              <td>
                <div class="font-semibold text-gray-900 dark:text-white">{{ $revenue->description }}</div>
              </td>
              <td class="text-sm text-gray-600 dark:text-gray-400">{{ $revenue->category }}</td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ number_format($revenue->total_credit, 2) }}
              </td>
              <td>
                @if($revenue->status === 'posted')
                  <span class="badge badge-green">Posted</span>
                @elseif($revenue->status === 'draft')
                  <span class="badge badge-yellow">Draft</span>
                @else
                  <span class="badge badge-red">Voided</span>
                @endif
              </td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.revenues.show', $revenue->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  @if($revenue->status === 'draft')
                    <button onclick="deleteRevenue({{ $revenue->id }})" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-coins text-3xl mb-3 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No revenues found</p>
                <p class="text-xs">Create your first revenue to get started</p>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function deleteRevenue(id) {
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
      fetch(`{{ route('admin.revenues.destroy', ':id') }}`.replace(':id', id), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Deleted!', 'Revenue has been deleted.', 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message || 'Failed to delete revenue', 'error');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to delete revenue', 'error');
      });
    }
  });
}
</script>
