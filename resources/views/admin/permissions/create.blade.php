@extends('layouts.admin')

@section('breadcrumb', 'System › Permissions › Create')
@section('page_title', 'Create New Permission')

@section('content')

<div class="max-w-2xl">
  <div class="glass p-6">
    <form method="POST" action="{{ route('admin.permissions.store') }}">
      @csrf

      <div class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">
            Permission Name <span class="text-red-500">*</span>
          </label>
          <input type="text" name="name" required
                 class="form-input py-2.5 text-sm"
                 placeholder="e.g., users.create, reports.view"
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
                 placeholder="e.g., Create Users, View Reports"
                 value="{{ old('display_name') }}">
          @error('display_name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">
            Module
          </label>
          <select name="module" class="form-input py-2.5 text-sm">
            <option value="general" {{ old('module') === 'general' ? 'selected' : '' }}>General</option>
            <option value="members" {{ old('module') === 'members' ? 'selected' : '' }}>Members</option>
            <option value="loans" {{ old('module') === 'loans' ? 'selected' : '' }}>Loans</option>
            <option value="savings" {{ old('module') === 'savings' ? 'selected' : '' }}>Savings</option>
            <option value="deposits" {{ old('module') === 'deposits' ? 'selected' : '' }}>Deposits</option>
            <option value="swf" {{ old('module') === 'swf' ? 'selected' : '' }}>SWF</option>
            <option value="investments" {{ old('module') === 'investments' ? 'selected' : '' }}>Investments</option>
            <option value="reports" {{ old('module') === 'reports' ? 'selected' : '' }}>Reports</option>
            <option value="users" {{ old('module') === 'users' ? 'selected' : '' }}>Users</option>
            <option value="settings" {{ old('module') === 'settings' ? 'selected' : '' }}>Settings</option>
            <option value="roles" {{ old('module') === 'roles' ? 'selected' : '' }}>Roles</option>
            <option value="permissions" {{ old('module') === 'permissions' ? 'selected' : '' }}>Permissions</option>
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
                    placeholder="Describe what this permission allows">{{ old('description') }}</textarea>
          @error('description')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50">
        <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
          <i class="fa-solid fa-plus mr-1.5 text-[11px]"></i> Create Permission
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
