@extends('layouts.admin')

@section('breadcrumb', 'My Profile › Edit')
@section('page_title', 'Edit Profile')

@section('content')

<div class="space-y-6">

  <div class="glass rounded-2xl p-6 lg:p-8 border border-primary-100 dark:border-dark-border">
    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" x-data="{ showPassword: false }">
      @csrf
      @method('PUT')

      <div class="space-y-6">

        <!-- Profile Photo Section -->
        <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-primary-100 dark:border-dark-border">
          <div class="relative flex-shrink-0">
            @if($user->photo)
              <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover shadow-xl">
            @else
              <div class="w-24 h-24 rounded-full flex items-center justify-center shadow-xl"
                   style="background: linear-gradient(135deg, #34d399 0%, #059669 55%, #064e3b 100%);">
                <span class="text-4xl font-extrabold text-white tracking-wide">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
              </div>
            @endif
            <label class="absolute bottom-0 right-0 w-8 h-8 bg-primary-600 hover:bg-primary-500 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg transition-colors">
              <i class="fa-solid fa-camera text-xs"></i>
              <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif" class="hidden" @change="$el.form.submit()">
            </label>
          </div>
          <div class="text-center sm:text-left">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm">Profile Photo</h3>
            <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">JPG, PNG or GIF. Max 2MB</p>
            @if($user->photo)
              <button type="button" onclick="document.getElementById('removePhoto').click()" class="mt-2 text-xs text-red-500 hover:text-red-600 font-semibold">
                Remove Photo
              </button>
              <input type="checkbox" name="remove_photo" value="1" id="removePhoto" class="hidden">
            @endif
          </div>
        </div>

        <!-- Personal Information -->
        <div>
          <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
            <i class="fa-solid fa-user text-primary-500"></i> Personal Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Full Name <span class="text-red-500">*</span></label>
              <input type="text" name="name" required value="{{ old('name', $user->name) }}"
                     class="form-input py-2.5 text-sm"
                     placeholder="Enter your full name">
              @error('name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Email <span class="text-red-500">*</span></label>
              <input type="email" name="email" required value="{{ old('email', $user->email) }}"
                     class="form-input py-2.5 text-sm"
                     placeholder="your@email.com">
              @error('email')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Phone</label>
              <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                     class="form-input py-2.5 text-sm"
                     placeholder="+255 123 456 789">
              @error('phone')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Date of Birth</label>
              <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}"
                     class="form-input py-2.5 text-sm">
              @error('date_of_birth')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Gender</label>
              <select name="gender" class="form-input py-2.5 text-sm">
                <option value="">Select gender</option>
                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
              </select>
              @error('gender')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Marital Status</label>
              <select name="marital_status" class="form-input py-2.5 text-sm">
                <option value="">Select status</option>
                <option value="single" {{ old('marital_status', $user->marital_status) === 'single' ? 'selected' : '' }}>Single</option>
                <option value="married" {{ old('marital_status', $user->marital_status) === 'married' ? 'selected' : '' }}>Married</option>
                <option value="divorced" {{ old('marital_status', $user->marital_status) === 'divorced' ? 'selected' : '' }}>Divorced</option>
                <option value="widowed" {{ old('marital_status', $user->marital_status) === 'widowed' ? 'selected' : '' }}>Widowed</option>
              </select>
              @error('marital_status')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div class="md:col-span-2 lg:col-span-3">
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Address</label>
              <input type="text" name="address" value="{{ old('address', $user->address) }}"
                     class="form-input py-2.5 text-sm"
                     placeholder="Your full address">
              @error('address')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Occupation</label>
              <input type="text" name="occupation" value="{{ old('occupation', $user->occupation) }}"
                     class="form-input py-2.5 text-sm"
                     placeholder="Your occupation">
              @error('occupation')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Employer</label>
              <input type="text" name="employer" value="{{ old('employer', $user->employer) }}"
                     class="form-input py-2.5 text-sm"
                     placeholder="Your employer">
              @error('employer')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">National ID</label>
              <input type="text" name="national_id" value="{{ old('national_id', $user->national_id) }}"
                     class="form-input py-2.5 text-sm"
                     placeholder="National ID number">
              @error('national_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div class="md:col-span-2 lg:col-span-3">
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Bio / About Me</label>
              <textarea name="bio" rows="3"
                        class="form-input py-2.5 text-sm"
                        placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
              @error('bio')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        <!-- Password Change -->
        <div>
          <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
            <i class="fa-solid fa-lock text-primary-500"></i> Change Password
          </h3>
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Current Password</label>
              <input type="password" name="current_password"
                     class="form-input py-2.5 text-sm"
                     placeholder="Enter current password">
              @error('current_password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
              @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">New Password</label>
                <input type="password" name="new_password"
                       class="form-input py-2.5 text-sm"
                       placeholder="Min 8 characters">
                @error('new_password')
                  <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-xs font-semibold text-primary-700 dark:text-primary-300 mb-1.5">Confirm New Password</label>
                <input type="password" name="new_password_confirmation"
                       class="form-input py-2.5 text-sm"
                       placeholder="Confirm new password">
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="flex items-center justify-end gap-3 pt-6 border-t border-primary-100 dark:border-dark-border mt-6">
        <a href="{{ route('admin.profile.show') }}" class="px-5 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
          <i class="fa-solid fa-save mr-1.5 text-[11px]"></i> Save Changes
        </button>
      </div>
    </form>
  </div>

</div>

@endsection
