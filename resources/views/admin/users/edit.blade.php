@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Users \u203A Edit')
@section('page_title', 'Edit User: ' . $user->name)

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-4">
    <a href="{{ route('admin.users.index') }}"
       class="p-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div class="flex items-center gap-3 flex-1">
      <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-base font-bold shadow-md">
        {{ strtoupper(substr($user->name, 0, 1) ?? 'U') }}
      </div>
      <div class="flex-1 min-w-0">
        <h2 class="font-bold text-lg truncate" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $user->name }}</h2>
        <p class="text-xs mt-0.5 truncate" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">{{ $user->email }}</p>
      </div>
      @php
        $userRole = $user->role ?? ($user->roles->first()->name ?? 'member');
      @endphp
      @if($userRole === 'admin')
        <span class="role-tag role-admin">Admin</span>
      @elseif($userRole === 'manager')
        <span class="role-tag role-manager">Manager</span>
      @elseif($userRole === 'teller')
        <span class="role-tag role-teller">Teller</span>
      @elseif($userRole === 'auditor')
        <span class="role-tag role-auditor">Auditor</span>
      @else
        <span class="role-tag role-member">Member</span>
      @endif
    </div>
  </div>

  <div class="glass p-6 lg:p-8">
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="flex items-center gap-4 pb-6 border-b border-primary-100 dark:border-primary-900/50">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center text-2xl shadow-md">
          <i class="fa-solid fa-pen-to-square"></i>
        </div>
        <div>
          <h2 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">Edit User Information</h2>
          <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Update user account details and permissions</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @if($user->memberProfile)
          <div class="md:col-span-2 lg:col-span-3">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Member Type *</label>
            <select name="member_type_id" required class="form-input @error('member_type_id') !border-red-400 @enderror">
              <option value="">Select member type...</option>
              @foreach($memberTypes as $type)
                <option value="{{ $type->id }}" {{ old('member_type_id', $user->member_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }} - {{ $type->code }}</option>
              @endforeach
            </select>
            @error('member_type_id')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">First Name *</label>
            <input type="text" name="first_name" value="{{ old('first_name', $user->memberProfile->first_name) }}" required
                   placeholder="e.g. John"
                   class="form-input @error('first_name') !border-red-400 @enderror">
            @error('first_name')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Middle Name</label>
            <input type="text" name="middle_name" value="{{ old('middle_name', $user->memberProfile->middle_name) }}"
                   placeholder="e.g. Michael"
                   class="form-input @error('middle_name') !border-red-400 @enderror">
            @error('middle_name')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Last Name *</label>
            <input type="text" name="last_name" value="{{ old('last_name', $user->memberProfile->last_name) }}" required
                   placeholder="e.g. Doe"
                   class="form-input @error('last_name') !border-red-400 @enderror">
            @error('last_name')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>
        @else
          <div class="md:col-span-2 lg:col-span-3">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   placeholder="e.g. John Mwangi"
                   class="form-input @error('name') !border-red-400 @enderror">
            @error('name')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>
        @endif

        <div class="md:col-span-2 lg:col-span-3">
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Email Address *</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                 placeholder="e.g. john@example.com"
                 class="form-input @error('email') !border-red-400 @enderror">
          @error('email')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
          @enderror
        </div>

        @if($user->memberProfile)
          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Phone</label>
            <input type="text" name="phone_number" value="{{ old('phone_number', $user->memberProfile->phone_number) }}"
                   placeholder="+255 123 456 789"
                   class="form-input @error('phone_number') !border-red-400 @enderror">
            @error('phone_number')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Date of Birth</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->memberProfile->date_of_birth ? $user->memberProfile->date_of_birth->format('Y-m-d') : '') }}"
                   class="form-input @error('date_of_birth') !border-red-400 @enderror">
            @error('date_of_birth')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Gender</label>
            <select name="gender" class="form-input @error('gender') !border-red-400 @enderror">
              <option value="">Select gender</option>
              <option value="male" {{ old('gender', $user->memberProfile->gender) === 'male' ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender', $user->memberProfile->gender) === 'female' ? 'selected' : '' }}>Female</option>
              <option value="other" {{ old('gender', $user->memberProfile->gender) === 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">National ID (NIDA)</label>
            <input type="text" name="national_id" value="{{ old('national_id', $user->memberProfile->national_id) }}"
                   placeholder="e.g. 1234567890123"
                   class="form-input font-mono @error('national_id') !border-red-400 @enderror">
            @error('national_id')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Passport/Driving License</label>
            <input type="text" name="passport_driving_license" value="{{ old('passport_driving_license', $user->memberProfile->passport_driving_license) }}"
                   placeholder="Optional"
                   class="form-input @error('passport_driving_license') !border-red-400 @enderror">
            @error('passport_driving_license')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Member Number</label>
            <input type="text" name="member_number" value="{{ old('member_number', $user->member_number) }}"
                   readonly
                   class="form-input font-mono bg-primary-50 dark:bg-primary-900/30">
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Registration Date</label>
            <input type="date" name="registration_date" value="{{ old('registration_date', $user->memberProfile->registration_date ? $user->memberProfile->registration_date->format('Y-m-d') : '') }}"
                   class="form-input @error('registration_date') !border-red-400 @enderror">
            @error('registration_date')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>
        @else
          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                   placeholder="+255 123 456 789"
                   class="form-input @error('phone') !border-red-400 @enderror">
            @error('phone')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Date of Birth</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}"
                   class="form-input @error('date_of_birth') !border-red-400 @enderror">
            @error('date_of_birth')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Gender</label>
            <select name="gender" class="form-input @error('gender') !border-red-400 @enderror">
              <option value="">Select gender</option>
              <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
              <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">National ID</label>
            <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}"
                   placeholder="National ID number"
                   class="form-input @error('national_id') !border-red-400 @enderror">
            @error('national_id')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>
        @endif

        <div class="md:col-span-2 lg:col-span-3">
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Bio / About Me</label>
          <textarea name="bio" rows="3"
                    class="form-input @error('bio') !border-red-400 @enderror"
                    placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
          @error('bio')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">New Password <span class="font-normal normal-case opacity-70">(leave blank to keep current)</span></label>
          <div x-data="{ show: false }" class="relative">
            <input :type="show ? 'text' : 'password'" name="password"
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
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Confirm New Password</label>
          <div x-data="{ show: false }" class="relative">
            <input :type="show ? 'text' : 'password'" name="password_confirmation"
                   placeholder="Re-enter new password"
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Role *</label>
            <select name="role" required class="form-input @error('role') !border-red-400 @enderror">
              <option value="">Select a role...</option>
              <option value="admin" {{ old('role', $userRole) === 'admin' ? 'selected' : '' }}>Admin - Full system access</option>
              <option value="manager" {{ old('role', $userRole) === 'manager' ? 'selected' : '' }}>Manager - Operational oversight</option>
              <option value="teller" {{ old('role', $userRole) === 'teller' ? 'selected' : '' }}>Teller - Transactions only</option>
              <option value="member" {{ old('role', $userRole) === 'member' ? 'selected' : '' }}>Member - Self-service portal</option>
              <option value="auditor" {{ old('role', $userRole) === 'auditor' ? 'selected' : '' }}>Auditor - Read-only access</option>
            </select>
            @error('role')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Member Number</label>
            <input type="text" name="member_number" value="{{ old('member_number', $user->member_number) }}"
                   placeholder="e.g. FTN-00123 (optional)"
                   class="form-input font-mono @error('member_number') !border-red-400 @enderror">
            @error('member_number')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Marital Status</label>
            <select name="marital_status" class="form-input @error('marital_status') !border-red-400 @enderror">
              <option value="">Select status</option>
              <option value="single" {{ old('marital_status', $user->marital_status) === 'single' ? 'selected' : '' }}>Single</option>
              <option value="married" {{ old('marital_status', $user->marital_status) === 'married' ? 'selected' : '' }}>Married</option>
              <option value="divorced" {{ old('marital_status', $user->marital_status) === 'divorced' ? 'selected' : '' }}>Divorced</option>
              <option value="widowed" {{ old('marital_status', $user->marital_status) === 'widowed' ? 'selected' : '' }}>Widowed</option>
            </select>
            @error('marital_status')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div class="md:col-span-2 lg:col-span-3">
            <label class="form-label uppercase tracking-wider mb-2" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Account Status</label>
            <div class="grid grid-cols-3 gap-3">
              @php
                $currentStatus = old('status', $user->status ?? 'active');
              @endphp
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/30 has-[:checked]:text-green-700 dark:has-[:checked]:text-green-300">
                <input type="radio" name="status" value="active" class="hidden" {{ $currentStatus === 'active' ? 'checked' : '' }}>
                <i class="fa-solid fa-circle-check text-[12px]"></i> Active
              </label>
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50 dark:has-[:checked]:bg-yellow-900/30 has-[:checked]:text-yellow-700 dark:has-[:checked]:text-yellow-300">
                <input type="radio" name="status" value="pending" class="hidden" {{ $currentStatus === 'pending' ? 'checked' : '' }}>
                <i class="fa-solid fa-clock text-[12px]"></i> Pending
              </label>
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-gray-500 has-[:checked]:bg-gray-50 dark:has-[:checked]:bg-gray-900/30 has-[:checked]:text-gray-700 dark:has-[:checked]:text-gray-300">
                <input type="radio" name="status" value="inactive" class="hidden" {{ $currentStatus === 'inactive' ? 'checked' : '' }}>
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
          <i class="fa-solid fa-floppy-disk mr-1.5 text-[13px]"></i> Save Changes
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
