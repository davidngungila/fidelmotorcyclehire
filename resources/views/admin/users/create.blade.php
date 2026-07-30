@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Users \u203A Create')
@section('page_title', 'Create New User')

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-4">
    <a href="{{ route('admin.users.index') }}"
       class="p-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
      <p class="text-sm" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
        Add a new system user with role-based access
      </p>
    </div>
  </div>

  <div class="glass p-6 lg:p-8">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
      @csrf

      <div class="flex items-center gap-4 pb-6 border-b border-primary-100 dark:border-primary-900/50">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-2xl shadow-md">
          <i class="fa-solid fa-user-plus"></i>
        </div>
        <div>
          <h2 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">User Information</h2>
          <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Fill in the details to create a new user account</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Full Name *</label>
          <input type="text" name="name" value="{{ old('name') }}" required
                 placeholder="e.g. John Mwangi"
                 class="form-input @error('name') !border-red-400 @enderror">
          @error('name')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Email Address *</label>
          <input type="email" name="email" value="{{ old('email') }}" required
                 placeholder="e.g. john@example.com"
                 class="form-input @error('email') !border-red-400 @enderror">
          @error('email')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Password *</label>
          <div x-data="{ show: false }" class="relative">
            <input :type="show ? 'text' : 'password'" name="password" required
                   placeholder="Min. 8 characters"
                   class="form-input pr-10 @error('password') !border-red-400 @enderror">
            <button type="button" @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600 dark:hover:text-primary-300">
              <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
            </button>
          </div>
          @error('password')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Confirm Password *</label>
          <div x-data="{ show: false }" class="relative">
            <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                   placeholder="Re-enter password"
                   class="form-input pr-10">
            <button type="button" @click="show = !show"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600 dark:hover:text-primary-300">
              <i :class="show ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'" class="text-sm"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="pt-5 mt-5 border-t border-primary-100 dark:border-primary-900/50">
        <h3 class="text-xs font-bold uppercase tracking-wider mb-4" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
          <i class="fa-solid fa-shield-halved mr-1.5"></i> Access & Role Configuration
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Role *</label>
            <select name="role" required class="form-input @error('role') !border-red-400 @enderror">
              <option value="">Select a role...</option>
              <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin - Full system access</option>
              <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager - Operational oversight</option>
              <option value="teller" {{ old('role') === 'teller' ? 'selected' : '' }}>Teller - Transactions only</option>
              <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Member - Self-service portal</option>
              <option value="auditor" {{ old('role') === 'auditor' ? 'selected' : '' }}>Auditor - Read-only access</option>
            </select>
            @error('role')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Member Type</label>
            <select name="member_type_id" class="form-input @error('member_type_id') !border-red-400 @enderror">
              <option value="">Select member type...</option>
              @foreach(\App\Models\MemberType::active()->orderBy('priority', 'desc')->get() as $type)
                <option value="{{ $type->id }}" {{ old('member_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }} - {{ $type->code }}</option>
              @endforeach
            </select>
            @error('member_type_id')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Member Number</label>
            <input type="text" name="member_number" value="{{ old('member_number') }}"
                   placeholder="e.g. FTN-00123 (optional)"
                   class="form-input font-mono @error('member_number') !border-red-400 @enderror">
            @error('member_number')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider mb-2" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Account Status</label>
            <div class="grid grid-cols-3 gap-3">
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/30 has-[:checked]:text-green-700 dark:has-[:checked]:text-green-300">
                <input type="radio" name="status" value="active" class="hidden" {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                <i class="fa-solid fa-circle-check text-[12px]"></i> Active
              </label>
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50 dark:has-[:checked]:bg-yellow-900/30 has-[:checked]:text-yellow-700 dark:has-[:checked]:text-yellow-300">
                <input type="radio" name="status" value="pending" class="hidden" {{ old('status') === 'pending' ? 'checked' : '' }}>
                <i class="fa-solid fa-clock text-[12px]"></i> Pending
              </label>
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-gray-500 has-[:checked]:bg-gray-50 dark:has-[:checked]:bg-gray-900/30 has-[:checked]:text-gray-700 dark:has-[:checked]:text-gray-300">
                <input type="radio" name="status" value="inactive" class="hidden" {{ old('status') === 'inactive' ? 'checked' : '' }}>
                <i class="fa-solid fa-circle-xmark text-[12px]"></i> Inactive
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="pt-6 mt-6 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row items-center justify-end gap-3">
        <a href="{{ route('admin.users.index') }}"
           class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-colors text-center">
          Cancel
        </a>
        <button type="submit"
                class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
          <i class="fa-solid fa-user-plus mr-1.5 text-[13px]"></i> Create User
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
