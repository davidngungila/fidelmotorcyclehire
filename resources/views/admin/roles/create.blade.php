@extends('layouts.admin')

@section('breadcrumb', 'System › Roles › Create')
@section('page_title', 'Create New Role')

@section('content')

<div class="max-w-2xl">
  <div class="glass p-6">
    <form method="POST" action="{{ route('admin.roles.store') }}">
      @csrf

      <div class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">
            Role Name <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" required
                 class="form-input py-2.5 text-sm"
                 placeholder="e.g., manager, editor, viewer"
                 value="{{ old('name') }}">
          @error('name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">
            Display Name
          </label>
          <input type="text" name="display_name"
                 class="form-input py-2.5 text-sm"
                 placeholder="e.g., Manager, Editor, Viewer"
                 value="{{ old('display_name') }}">
          @error('display_name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">
            Description
          </label>
          <textarea name="description" rows="3"
                    class="form-input py-2.5 text-sm resize-none"
                    placeholder="Describe the role's purpose and responsibilities">{{ old('description') }}</textarea>
          @error('description')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">
            Permissions
          </label>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-y-auto p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
            @forelse($permissions as $permission)
              <label class="flex items-start gap-3 p-2 rounded-lg hover:bg-white dark:hover:bg-primary-900/50 transition-colors cursor-pointer">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                       class="mt-0.5 rounded border-primary-300 text-primary-600 focus:ring-primary-500">
                <div>
                  <p class="text-xs font-semibold text-primary-900 dark:text-white">{{ $permission->display_name ?? $permission->name }}</p>
                  <p class="text-[11px] text-primary-500 dark:text-primary-400">{{ $permission->name }}</p>
                </div>
              </label>
            @empty
              <p class="text-xs text-primary-500 dark:text-primary-400 col-span-2">No permissions available</p>
            @endforelse
          </div>
          @error('permissions')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50">
        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
          <i class="fa-solid fa-plus mr-1.5 text-[11px]"></i> Create Role
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
