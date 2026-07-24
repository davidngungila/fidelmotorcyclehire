@extends('layouts.admin')

@section('breadcrumb', 'System › Permissions › Edit')
@section('page_title', 'Edit Permission')

@section('content')

<div class="max-w-2xl">
  <div class="glass p-6">
    <form method="POST" action="{{ route('admin.permissions.update', $permission->id) }}">
      @csrf
      @method('PUT')

      <div class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">
            Permission Name <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" required
                 class="form-input py-2.5 text-sm"
                 placeholder="e.g., users.create, reports.view"
                 value="{{ old('name', $permission->name) }}">
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
                 placeholder="e.g., Create Users, View Reports"
                 value="{{ old('display_name', $permission->display_name) }}">
          @error('display_name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">
            Module
          </label>
          <select name="module" class="form-input py-2.5 text-sm">
            <option value="general" {{ old('module', $permission->module) === 'general' ? 'selected' : '' }}>General</option>
            <option value="members" {{ old('module', $permission->module) === 'members' ? 'selected' : '' }}>Members</option>
            <option value="loans" {{ old('module', $permission->module) === 'loans' ? 'selected' : '' }}>Loans</option>
            <option value="savings" {{ old('module', $permission->module) === 'savings' ? 'selected' : '' }}>Savings</option>
            <option value="deposits" {{ old('module', $permission->module) === 'deposits' ? 'selected' : '' }}>Deposits</option>
            <option value="swf" {{ old('module', $permission->module) === 'swf' ? 'selected' : '' }}>SWF</option>
            <option value="investments" {{ old('module', $permission->module) === 'investments' ? 'selected' : '' }}>Investments</option>
            <option value="reports" {{ old('module', $permission->module) === 'reports' ? 'selected' : '' }}>Reports</option>
            <option value="users" {{ old('module', $permission->module) === 'users' ? 'selected' : '' }}>Users</option>
            <option value="settings" {{ old('module', $permission->module) === 'settings' ? 'selected' : '' }}>Settings</option>
            <option value="roles" {{ old('module', $permission->module) === 'roles' ? 'selected' : '' }}>Roles</option>
            <option value="permissions" {{ old('module', $permission->module) === 'permissions' ? 'selected' : '' }}>Permissions</option>
          </select>
          @error('module')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">
            Description
          </label>
          <textarea name="description" rows="3"
                    class="form-input py-2.5 text-sm resize-none"
                    placeholder="Describe what this permission allows">{{ old('description', $permission->description) }}</textarea>
          @error('description')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-900/30">
          <p class="text-xs font-semibold text-primary-900 dark:text-white mb-2">Assigned Roles ({{ $permission->roles->count() }})</p>
          @forelse($permission->roles as $role)
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-[11px] font-semibold mr-2 mb-2">
              <i class="fa-solid fa-shield-halved text-[9px]"></i> {{ $role->display_name ?? $role->name }}
            </span>
          @empty
            <p class="text-xs text-primary-500 dark:text-primary-400">No roles assigned</p>
          @endforelse
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50">
        <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
          <i class="fa-solid fa-save mr-1.5 text-[11px]"></i> Update Permission
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
