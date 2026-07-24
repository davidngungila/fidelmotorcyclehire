@extends('layouts.admin')

@section('breadcrumb', 'System › Roles')
@section('page_title', 'Roles Management')

@section('content')

<div x-data="rolesList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <form method="GET" action="{{ route('admin.roles.index') }}" class="flex-1 max-w-2xl" x-ref="searchForm">
      <div class="relative">
        <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
        <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
               placeholder="Search by name, display name..."
               class="form-input pl-9 py-2.5 text-sm"
               x-model="searchQuery"
               @input.debounce.400ms="submitSearch"/>
        @if($searchQuery)
          <a href="{{ route('admin.roles.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
            <i class="fa-solid fa-xmark text-xs"></i>
          </a>
        @endif
      </div>
    </form>

    <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
      <i class="fa-solid fa-plus text-[11px]"></i>
      <span>New Role</span>
    </a>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-list-check mr-1.5"></i> {{ $roles->total() }} Roles Found
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
            <th>Role Name</th>
            <th>Display Name</th>
            <th>Permissions</th>
            <th>Description</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($roles as $index => $role)
            @php
              $rowNum = ($roles->currentPage() - 1) * $roles->perPage() + $index + 1;
            @endphp
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>
              <td>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 text-xs font-mono font-semibold">
                  <i class="fa-solid fa-shield-halved text-[10px]"></i>
                  {{ $role->name }}
                </span>
              </td>
              <td class="font-semibold text-primary-900 dark:text-white">{{ $role->display_name ?? $role->name }}</td>
              <td>
                <span class="badge badge-blue text-[10px]">{{ $role->permissions->count() }} permissions</span>
              </td>
              <td class="text-sm text-primary-600 dark:text-primary-400 max-w-[200px] truncate">{{ $role->description ?? '-' }}</td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.roles.edit', $role->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors">
                    <i class="fa-solid fa-pen text-[10px]"></i> Edit
                  </a>
                  @if(!in_array(strtolower($role->name), ['admin', 'member']))
                    <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" x-data="{ confirm: false }" @submit.prevent="if(confirm) $el.submit()">
                      @csrf
                      @method('DELETE')
                      <button type="button" @click="confirm = true" x-show="!confirm" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-[11px] font-bold transition-colors">
                        <i class="fa-solid fa-trash text-[10px]"></i>
                      </button>
                      <button type="submit" x-show="confirm" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-500 hover:bg-red-600 text-white text-[11px] font-bold transition-colors">
                        <i class="fa-solid fa-check text-[10px]"></i> Confirm
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-8 text-primary-500 dark:text-primary-400 text-xs">
                <i class="fa-solid fa-user-shield text-2xl mb-2 block opacity-40"></i>
                No roles found
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($roles->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $roles->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $roles->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $roles->total() }}</span> roles
        </p>
        {{ $roles->links() }}
      </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
  function rolesList() {
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
