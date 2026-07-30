@extends('layouts.admin')

@section('breadcrumb', 'Members \u203A Member Types')
@section('page_title', 'Member Types Management')

@php
  function fmtTsh($val): string {
      return 'TSh ' . number_format((float)$val, 0, '.', ',');
  }
@endphp

@section('content')
<div class="space-y-6">
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="glass p-5 rounded-2xl">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 font-semibold mb-1">Total Types</p>
          <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ $memberTypes->total() }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
          <i class="fa-solid fa-layer-group text-primary-600 dark:text-primary-400 text-lg"></i>
        </div>
      </div>
    </div>
    <div class="glass p-5 rounded-2xl">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 font-semibold mb-1">Active</p>
          <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $activeCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
          <i class="fa-solid fa-circle-check text-green-600 dark:text-green-400 text-lg"></i>
        </div>
      </div>
    </div>
    <div class="glass p-5 rounded-2xl">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-primary-600 dark:text-primary-400 font-semibold mb-1">Inactive</p>
          <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $inactiveCount }}</p>
        </div>
        <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/40 flex items-center justify-center">
          <i class="fa-solid fa-circle-xmark text-red-600 dark:text-red-400 text-lg"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1 max-w-2xl">
      <form method="GET" action="{{ route('admin.member-types.index') }}" class="flex-1">
        <div class="relative">
          <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
          <input type="text" name="q" value="{{ $searchQuery }}"
                 placeholder="Search by name, code..."
                 class="form-input pl-9 py-2.5 text-sm">
          @if($searchQuery)
            <a href="{{ route('admin.member-types.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
              <i class="fa-solid fa-xmark text-xs"></i>
            </a>
          @endif
        </div>
      </form>
    </div>

    <div class="flex items-center gap-3">
      <select name="status" onchange="window.location.href='{{ route('admin.member-types.index') }}?status='+this.value" class="form-input py-2 px-3 text-sm">
        <option value="">All Status</option>
        <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
      <a href="{{ route('admin.member-types.create') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
        <i class="fa-solid fa-plus text-[13px]"></i> New Type
      </a>
    </div>
  </div>

  <!-- Table -->
  <div class="glass p-5 rounded-2xl">
    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th>Name</th>
            <th>Code</th>
            <th>Registration Fee</th>
            <th>Monthly Contribution</th>
            <th>Loan Multiplier</th>
            <th>Voting Rights</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($memberTypes as $index => $type)
            @php
              $encryptedId = encryptId($type->id);
              $rowNum = ($memberTypes->currentPage() - 1) * $memberTypes->perPage() + $index + 1;
            @endphp
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>
              <td>
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/40 flex items-center justify-center">
                    <i class="fa-solid fa-user-tag text-purple-600 dark:text-purple-400 text-xs"></i>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-primary-900 dark:text-white">{{ $type->name }}</p>
                    @if($type->description)
                      <p class="text-[11px] text-primary-500 dark:text-primary-400 truncate max-w-[150px]">{{ $type->description }}</p>
                    @endif
                  </div>
                </div>
              </td>
              <td>
                <span class="inline-flex items-center px-2 py-1 rounded-lg bg-primary-100 dark:bg-primary-900/40 font-mono text-xs font-bold text-primary-700 dark:text-primary-300">
                  {{ $type->code }}
                </span>
              </td>
              <td>
                <span class="text-sm text-primary-700 dark:text-primary-300">{{ fmtTsh($type->registration_fee) }}</span>
              </td>
              <td>
                <span class="text-sm text-primary-700 dark:text-primary-300">{{ fmtTsh($type->monthly_contribution) }}</span>
              </td>
              <td>
                <span class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-bold">
                  {{ $type->max_loan_multiplier }}x
                </span>
              </td>
              <td>
                @if($type->can_vote)
                  <span class="inline-flex items-center px-2 py-1 rounded-lg bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 text-xs font-bold">
                    <i class="fa-solid fa-check mr-1 text-[10px]"></i> Yes
                  </span>
                @else
                  <span class="inline-flex items-center px-2 py-1 rounded-lg bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 text-xs font-bold">
                    <i class="fa-solid fa-xmark mr-1 text-[10px]"></i> No
                  </span>
                @endif
              </td>
              <td>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $type->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                  {{ ucfirst($type->status) }}
                </span>
              </td>
              <td class="text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.member-types.show', $encryptedId) }}"
                     class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-100 hover:bg-green-200 dark:bg-green-900/40 dark:hover:bg-green-900/60 text-green-700 dark:text-green-300 text-[11px] font-bold transition-colors">
                    <i class="fa-solid fa-eye text-[10px]"></i> View
                  </a>
                  <a href="{{ route('admin.member-types.edit', $encryptedId) }}"
                     class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/40 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 text-[11px] font-bold transition-colors">
                    <i class="fa-solid fa-pen text-[10px]"></i> Edit
                  </a>
                  <form method="POST" action="{{ route('admin.member-types.destroy', $encryptedId) }}" id="deleteForm-{{ $type->id }}" class="hidden">
                    @csrf
                    @method('DELETE')
                  </form>
                  <button type="button" onclick="confirmDelete({{ $type->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-100 hover:bg-red-200 dark:bg-red-900/40 dark:hover:bg-red-900/60 text-red-700 dark:text-red-300 text-[11px] font-bold transition-colors">
                    <i class="fa-solid fa-trash text-[10px]"></i> Delete
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center py-16 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-user-tag text-4xl mb-4 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No member types found</p>
                <p class="text-xs">
                  <a href="{{ route('admin.member-types.create') }}" class="text-primary-600 dark:text-primary-400 underline hover:no-underline">Create a new member type</a>
                </p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($memberTypes->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $memberTypes->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $memberTypes->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $memberTypes->total() }}</span> types
        </p>
        {{ $memberTypes->links() }}
      </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  function confirmDelete(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: 'Do you want to delete this member type?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#6b7280',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('deleteForm-' + id).submit();
      }
    });
  }
</script>
@endpush
@endsection
