@extends('layouts.admin')

@section('breadcrumb', 'System › Permissions')
@section('page_title', 'Permissions Management')

@section('content')

<div x-data="permissionsList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <form method="GET" action="{{ route('admin.permissions.index') }}" class="flex-1 max-w-2xl" x-ref="searchForm">
      <div class="relative">
        <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
        <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
               placeholder="Search by name, display name, module..."
               class="form-input pl-9 py-2.5 text-sm"
               x-model="searchQuery"
               @input.debounce.400ms="submitSearch"/>
        @if($searchQuery)
          <a href="{{ route('admin.permissions.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
            <i class="fa-solid fa-xmark text-xs"></i>
          </a>
        @endif
      </div>
    </form>

    <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
      <i class="fa-solid fa-plus text-[11px]"></i>
      <span>New Permission</span>
    </a>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-list-check mr-1.5"></i> {{ $permissions->total() }} Permissions Found
        </span>
        @if($searchQuery)
          <span class="badge badge-blue text-[10px]">Search: {{ $searchQuery }}</span>
        @endif
      </div>
      <div class="flex items-center gap-3">
        <label class="flex items-center gap-2 text-xs text-primary-600 dark:text-primary-400">
          Per page:
          <select name="per_page" class="form-input py-1.5 px-2 w-20 text-xs" @change="changePerPage($el.value)">
            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
          </select>
        </label>
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th>Permission Name</th>
            <th>Display Name</th>
            <th>Module</th>
            <th>Roles</th>
            <th>Description</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($permissions as $index => $permission)
            @php
              $rowNum = ($permissions->currentPage() - 1) * $permissions->perPage() + $index + 1;
            @endphp
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>
              <td>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 text-xs font-mono font-semibold">
                  <i class="fa-solid fa-key text-[10px]"></i>
                  {{ $permission->name }}
                </span>
              </td>
              <td class="font-semibold text-primary-900 dark:text-white">{{ $permission->display_name ?? $permission->name }}</td>
              <td>
                <span class="badge badge-purple text-[10px]">{{ $permission->module ?? 'general' }}</span>
              </td>
              <td>
                <span class="badge badge-blue text-[10px]">{{ $permission->roles->count() }} roles</span>
              </td>
              <td class="text-sm text-primary-600 dark:text-primary-400 max-w-[200px] truncate">{{ $permission->description ?? '-' }}</td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors">
                    <i class="fa-solid fa-pen text-[10px]"></i> Edit
                  </a>
                  <form method="POST" action="{{ route('admin.permissions.destroy', $permission->id) }}" x-data="{ confirm: false }" @submit.prevent="if(confirm) $el.submit()">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="confirm = true" x-show="!confirm" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-[11px] font-bold transition-colors">
                      <i class="fa-solid fa-trash text-[10px]"></i>
                    </button>
                    <button type="submit" x-show="confirm" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-[11px] font-bold transition-colors">
                      <i class="fa-solid fa-check text-[10px]"></i> Confirm
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-8 text-primary-500 dark:text-primary-400 text-xs">
                <i class="fa-solid fa-key text-2xl mb-2 block opacity-40"></i>
                No permissions found
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($permissions->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $permissions->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $permissions->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $permissions->total() }}</span> permissions
        </p>
        {{ $permissions->links() }}
      </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  function permissionsList() {
    return {
      searchQuery: '{{ $searchQuery ?? '' }}',
      submitSearch() {
        this.$refs.searchForm.submit();
      },
      changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        window.location.href = url.toString();
      }
    };
  }
</script>
@endpush
