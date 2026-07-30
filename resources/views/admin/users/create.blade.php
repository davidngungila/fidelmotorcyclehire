@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Users \u203A Create')
@section('page_title', 'Create New Member')

@php
  $generatedMemberNumber = 'MB' . date('ymd') . str_pad('1', 4, '0', STR_PAD_LEFT);
@endphp

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-4">
    <a href="{{ route('admin.users.index') }}"
       class="p-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
      <p class="text-sm text-primary-600 dark:text-primary-400">
        Add a new member with comprehensive information
      </p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Main Form -->
    <div class="lg:col-span-3">
      <div class="glass p-6 lg:p-8">
        <form method="POST" action="{{ route('admin.users.store') }}" id="memberForm" class="space-y-6">
          @csrf

          <!-- Tabs Navigation -->
          <div class="flex flex-wrap gap-2 pb-6 border-b border-primary-100 dark:border-primary-900/50 mb-6">
            <button type="button" onclick="showTab('basic')" id="tab-basic" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold bg-primary-600 text-white">
              <i class="fa-solid fa-user mr-1.5"></i> Basic Info
            </button>
            <button type="button" onclick="showTab('contact')" id="tab-contact" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
              <i class="fa-solid fa-phone mr-1.5"></i> Contact
            </button>
            <button type="button" onclick="showTab('membership')" id="tab-membership" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
              <i class="fa-solid fa-id-card mr-1.5"></i> Membership
            </button>
            <button type="button" onclick="showTab('account')" id="tab-account" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
              <i class="fa-solid fa-lock mr-1.5"></i> Account
            </button>
            <button type="button" onclick="showTab('kin')" id="tab-kin" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
              <i class="fa-solid fa-users mr-1.5"></i> Next of Kin
            </button>
            <button type="button" onclick="showTab('banking')" id="tab-banking" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
              <i class="fa-solid fa-building-columns mr-1.5"></i> Banking
            </button>
            <button type="button" onclick="showTab('documents')" id="tab-documents" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
              <i class="fa-solid fa-file mr-1.5"></i> Documents
            </button>
            <button type="button" onclick="showTab('additional')" id="tab-additional" class="tab-btn px-4 py-2 rounded-lg text-sm font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
              <i class="fa-solid fa-ellipsis mr-1.5"></i> Additional
            </button>
          </div>

          <!-- Tab 1: Basic Information -->
          <div id="content-basic" class="tab-content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Member Type *</label>
                <select name="member_type_id" class="form-select @error('member_type_id') !border-red-400 @enderror">
                  <option value="">Select Member Type</option>
                  @foreach($memberTypes as $type)
                    <option value="{{ $type->id }}" {{ old('member_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                  @endforeach
                </select>
                @error('member_type_id')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                       placeholder="e.g. John"
                       class="form-input @error('first_name') !border-red-400 @enderror">
                @error('first_name')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                       placeholder="e.g. Michael"
                       class="form-input @error('middle_name') !border-red-400 @enderror">
                @error('middle_name')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                       placeholder="e.g. Doe"
                       class="form-input @error('last_name') !border-red-400 @enderror">
                @error('last_name')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Gender *</label>
                <select name="gender" class="form-select @error('gender') !border-red-400 @enderror">
                  <option value="">Select Gender</option>
                  <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                  <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                  <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                       class="form-input @error('date_of_birth') !border-red-400 @enderror">
                @error('date_of_birth')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">National ID (NIDA)</label>
                <input type="text" name="national_id" value="{{ old('national_id') }}"
                       placeholder="e.g. 1234567890123"
                       class="form-input @error('national_id') !border-red-400 @enderror">
                @error('national_id')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Passport/Driving License</label>
                <input type="text" name="passport_license" value="{{ old('passport_license') }}"
                       placeholder="Optional"
                       class="form-input @error('passport_license') !border-red-400 @enderror">
                @error('passport_license')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Member Number (Auto-generated)</label>
                <input type="text" name="member_number" value="{{ old('member_number') }}" readonly
                       placeholder="{{ $generatedMemberNumber }}"
                       class="form-input bg-primary-50 dark:bg-primary-900/30 font-mono">
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Registration Date</label>
                <input type="date" name="registration_date" value="{{ old('registration_date', now()->format('Y-m-d')) }}"
                       class="form-input @error('registration_date') !border-red-400 @enderror">
                @error('registration_date')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Status</label>
                <select name="status" class="form-select @error('status') !border-red-400 @enderror">
                  <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                  <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                @error('status')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end">
              <button type="button" onclick="saveTab('basic')" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
                <i class="fa-solid fa-save mr-1.5"></i> Save & Next
              </button>
            </div>
          </div>

          <!-- Tab 2: Contact Information -->
          <div id="content-contact" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Phone Number *</label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                       placeholder="e.g. +255 123 456 789"
                       class="form-input @error('phone') !border-red-400 @enderror">
                @error('phone')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Alternative Phone</label>
                <input type="tel" name="alternative_phone" value="{{ old('alternative_phone') }}"
                       placeholder="Optional"
                       class="form-input @error('alternative_phone') !border-red-400 @enderror">
                @error('alternative_phone')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       placeholder="e.g. john@example.com"
                       class="form-input @error('email') !border-red-400 @enderror">
                @error('email')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Region</label>
                <input type="text" name="region" value="{{ old('region') }}"
                       placeholder="e.g. Dar es Salaam"
                       class="form-input @error('region') !border-red-400 @enderror">
                @error('region')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">District</label>
                <input type="text" name="district" value="{{ old('district') }}"
                       placeholder="e.g. Ilala"
                       class="form-input @error('district') !border-red-400 @enderror">
                @error('district')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Ward</label>
                <input type="text" name="ward" value="{{ old('ward') }}"
                       placeholder="e.g. Kariakoo"
                       class="form-input @error('ward') !border-red-400 @enderror">
                @error('ward')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Street/Village</label>
                <input type="text" name="street_village" value="{{ old('street_village') }}"
                       placeholder="e.g. Msimbazi Street"
                       class="form-input @error('street_village') !border-red-400 @enderror">
                @error('street_village')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Physical Address</label>
                <textarea name="physical_address" rows="2"
                          placeholder="Full physical address"
                          class="form-input @error('physical_address') !border-red-400 @enderror">{{ old('physical_address') }}</textarea>
                @error('physical_address')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-between">
              <button type="button" onclick="showTab('basic')" class="px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-all">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Previous
              </button>
              <button type="button" onclick="saveTab('contact')" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
                <i class="fa-solid fa-save mr-1.5"></i> Save & Next
              </button>
            </div>
          </div>

          <!-- Tab 3: Membership Details -->
          <div id="content-membership" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Branch *</label>
                <input type="text" name="branch" value="{{ old('branch') }}" required
                       placeholder="e.g. Head Office"
                       class="form-input @error('branch') !border-red-400 @enderror">
                @error('branch')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Membership Category</label>
                <input type="text" name="membership_category" value="{{ old('membership_category') }}"
                       placeholder="e.g. Individual"
                       class="form-input @error('membership_category') !border-red-400 @enderror">
                @error('membership_category')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Occupation</label>
                <input type="text" name="occupation" value="{{ old('occupation') }}"
                       placeholder="e.g. Teacher"
                       class="form-input @error('occupation') !border-red-400 @enderror">
                @error('occupation')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Employer/Business</label>
                <input type="text" name="employer_business" value="{{ old('employer_business') }}"
                       placeholder="e.g. Ministry of Education"
                       class="form-input @error('employer_business') !border-red-400 @enderror">
                @error('employer_business')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Monthly Income (TSh)</label>
                <input type="number" name="monthly_income" value="{{ old('monthly_income') }}" min="0" step="0.01"
                       placeholder="e.g. 500000"
                       class="form-input @error('monthly_income') !border-red-400 @enderror">
                @error('monthly_income')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Introduced By (Referrer)</label>
                <input type="text" name="introduced_by" value="{{ old('introduced_by') }}"
                       placeholder="Member number or name"
                       class="form-input @error('introduced_by') !border-red-400 @enderror">
                @error('introduced_by')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Joining Fee (TSh)</label>
                <input type="number" name="joining_fee" value="{{ old('joining_fee') }}" min="0" step="0.01"
                       placeholder="e.g. 10000"
                       class="form-input @error('joining_fee') !border-red-400 @enderror">
                @error('joining_fee')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Shares Purchased (TSh)</label>
                <input type="number" name="shares_purchased" value="{{ old('shares_purchased') }}" min="0" step="0.01"
                       placeholder="e.g. 50000"
                       class="form-input @error('shares_purchased') !border-red-400 @enderror">
                @error('shares_purchased')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Initial Savings Deposit (TSh)</label>
                <input type="number" name="initial_savings" value="{{ old('initial_savings') }}" min="0" step="0.01"
                       placeholder="e.g. 100000"
                       class="form-input @error('initial_savings') !border-red-400 @enderror">
                @error('initial_savings')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-between">
              <button type="button" onclick="showTab('contact')" class="px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-all">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Previous
              </button>
              <button type="button" onclick="saveTab('membership')" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
                <i class="fa-solid fa-save mr-1.5"></i> Save & Next
              </button>
            </div>
          </div>

          <!-- Tab 4: Account Information -->
          <div id="content-account" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Username</label>
                <input type="text" name="username" value="{{ old('username') }}"
                       placeholder="Auto-generated from member number"
                       class="form-input @error('username') !border-red-400 @enderror">
                @error('username')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Role *</label>
                <select name="role" class="form-select @error('role') !border-red-400 @enderror">
                  <option value="member" {{ old('role', 'member') === 'member' ? 'selected' : '' }}>Member</option>
                  <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Password *</label>
                <input type="password" name="password" required
                       placeholder="Min 8 characters"
                       class="form-input @error('password') !border-red-400 @enderror">
                @error('password')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Confirm Password *</label>
                <input type="password" name="password_confirmation" required
                       placeholder="Re-enter password"
                       class="form-input">
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Email Verified</label>
                <div class="grid grid-cols-2 gap-3">
                  <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                               border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                               has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/30 has-[:checked]:text-green-700 dark:has-[:checked]:text-green-300">
                    <input type="radio" name="email_verified" value="1" class="hidden" {{ old('email_verified', 0) ? 'checked' : '' }}>
                    <i class="fa-solid fa-check text-[12px]"></i> Yes
                  </label>
                  <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                               border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                               has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/30 has-[:checked]:text-red-700 dark:has-[:checked]:text-red-300">
                    <input type="radio" name="email_verified" value="0" class="hidden" {{ old('email_verified', 0) === 0 ? 'checked' : '' }}>
                    <i class="fa-solid fa-xmark text-[12px]"></i> No
                  </label>
                </div>
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Phone Verified</label>
                <div class="grid grid-cols-2 gap-3">
                  <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                               border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                               has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/30 has-[:checked]:text-green-700 dark:has-[:checked]:text-green-300">
                    <input type="radio" name="phone_verified" value="1" class="hidden" {{ old('phone_verified', 0) ? 'checked' : '' }}>
                    <i class="fa-solid fa-check text-[12px]"></i> Yes
                  </label>
                  <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                               border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                               has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/30 has-[:checked]:text-red-700 dark:has-[:checked]:text-red-300">
                    <input type="radio" name="phone_verified" value="0" class="hidden" {{ old('phone_verified', 0) === 0 ? 'checked' : '' }}>
                    <i class="fa-solid fa-xmark text-[12px]"></i> No
                  </label>
                </div>
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-between">
              <button type="button" onclick="showTab('membership')" class="px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-all">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Previous
              </button>
              <button type="button" onclick="saveTab('account')" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
                <i class="fa-solid fa-save mr-1.5"></i> Save & Next
              </button>
            </div>
          </div>

          <!-- Tab 5: Next of Kin -->
          <div id="content-kin" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Full Name *</label>
                <input type="text" name="next_of_kin_full_name" value="{{ old('next_of_kin_full_name') }}"
                       placeholder="e.g. Jane Doe"
                       class="form-input @error('next_of_kin_full_name') !border-red-400 @enderror">
                @error('next_of_kin_full_name')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Relationship *</label>
                <input type="text" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship') }}"
                       placeholder="e.g. Spouse, Parent, Sibling"
                       class="form-input @error('next_of_kin_relationship') !border-red-400 @enderror">
                @error('next_of_kin_relationship')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Phone Number *</label>
                <input type="tel" name="next_of_kin_phone" value="{{ old('next_of_kin_phone') }}"
                       placeholder="e.g. +255 123 456 789"
                       class="form-input @error('next_of_kin_phone') !border-red-400 @enderror">
                @error('next_of_kin_phone')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Address *</label>
                <textarea name="next_of_kin_address" rows="2"
                          placeholder="Full address of next of kin"
                          class="form-input @error('next_of_kin_address') !border-red-400 @enderror">{{ old('next_of_kin_address') }}</textarea>
                @error('next_of_kin_address')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-between">
              <button type="button" onclick="showTab('account')" class="px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-all">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Previous
              </button>
              <button type="button" onclick="saveTab('kin')" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
                <i class="fa-solid fa-save mr-1.5"></i> Save & Next
              </button>
            </div>
          </div>

          <!-- Tab 6: Banking & Mobile Money -->
          <div id="content-banking" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Bank Name</label>
                <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                       placeholder="e.g. CRDB Bank"
                       class="form-input @error('bank_name') !border-red-400 @enderror">
                @error('bank_name')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Bank Account Number</label>
                <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                       placeholder="e.g. 01512345678900"
                       class="form-input @error('bank_account_number') !border-red-400 @enderror">
                @error('bank_account_number')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Account Name</label>
                <input type="text" name="account_name" value="{{ old('account_name') }}"
                       placeholder="e.g. John Doe"
                       class="form-input @error('account_name') !border-red-400 @enderror">
                @error('account_name')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Mobile Money Network</label>
                <select name="mobile_money_network" class="form-select @error('mobile_money_network') !border-red-400 @enderror">
                  <option value="">Select Network</option>
                  <option value="m-pesa" {{ old('mobile_money_network') === 'm-pesa' ? 'selected' : '' }}>M-Pesa</option>
                  <option value="tigopesa" {{ old('mobile_money_network') === 'tigopesa' ? 'selected' : '' }}>Tigo Pesa</option>
                  <option value="airtel" {{ old('mobile_money_network') === 'airtel' ? 'selected' : '' }}>Airtel Money</option>
                  <option value="halopesa" {{ old('mobile_money_network') === 'halopesa' ? 'selected' : '' }}>Halopesa</option>
                </select>
                @error('mobile_money_network')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Mobile Wallet Number</label>
                <input type="tel" name="mobile_wallet_number" value="{{ old('mobile_wallet_number') }}"
                       placeholder="e.g. +255 123 456 789"
                       class="form-input @error('mobile_wallet_number') !border-red-400 @enderror">
                @error('mobile_wallet_number')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-between">
              <button type="button" onclick="showTab('kin')" class="px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-all">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Previous
              </button>
              <button type="button" onclick="saveTab('banking')" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
                <i class="fa-solid fa-save mr-1.5"></i> Save & Next
              </button>
            </div>
          </div>

          <!-- Tab 7: Documents -->
          <div id="content-documents" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Passport Photo</label>
                <input type="file" name="passport_photo"
                       class="form-input @error('passport_photo') !border-red-400 @enderror">
                @error('passport_photo')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">National ID Copy</label>
                <input type="file" name="national_id_copy"
                       class="form-input @error('national_id_copy') !border-red-400 @enderror">
                @error('national_id_copy')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Signature</label>
                <input type="file" name="signature"
                       class="form-input @error('signature') !border-red-400 @enderror">
                @error('signature')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Other Attachments</label>
                <input type="file" name="other_attachments"
                       class="form-input @error('other_attachments') !border-red-400 @enderror">
                @error('other_attachments')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-between">
              <button type="button" onclick="showTab('banking')" class="px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-all">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Previous
              </button>
              <button type="button" onclick="saveTab('documents')" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
                <i class="fa-solid fa-save mr-1.5"></i> Save & Next
              </button>
            </div>
          </div>

          <!-- Tab 8: Additional Information -->
          <div id="content-additional" class="tab-content hidden">
            <div class="grid grid-cols-1 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Notes</label>
                <textarea name="notes" rows="4"
                          placeholder="Any additional notes about this member"
                          class="form-input @error('notes') !border-red-400 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Tags</label>
                <input type="text" name="tags" value="{{ old('tags') }}"
                       placeholder="Comma-separated tags (e.g. vip, new, priority)"
                       class="form-input @error('tags') !border-red-400 @enderror">
                @error('tags')
                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-between">
              <button type="button" onclick="showTab('documents')" class="px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-all">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Previous
              </button>
              <button type="button" onclick="saveTab('additional')" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
                <i class="fa-solid fa-save mr-1.5"></i> Save & Next
              </button>
            </div>
          </div>

          <!-- Final Actions -->
          <div class="mt-8 pt-6 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-colors text-center">
              Cancel
            </a>
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
              <i class="fa-solid fa-user-plus mr-1.5 text-[13px]"></i> Save Member
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Sidebar Summary -->
    <div class="space-y-6">
      <div class="glass p-6 rounded-2xl">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
          <i class="fa-solid fa-clipboard-list text-primary-500 text-xs"></i>
          Summary
        </h3>
        <div class="space-y-4">
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Member Number</p>
            <p class="text-sm font-semibold text-primary-900 dark:text-white font-mono">{{ $generatedMemberNumber }}</p>
          </div>
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Registration Date</p>
            <p class="text-sm font-semibold text-primary-900 dark:text-white">{{ now()->format('M d, Y') }}</p>
          </div>
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Status</p>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
              Active
            </span>
          </div>
        </div>
      </div>

      <div class="glass p-6 rounded-2xl">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
          <i class="fa-solid fa-info-circle text-primary-500 text-xs"></i>
          Auto-generated Values
        </h3>
        <div class="space-y-3 text-xs text-primary-700 dark:text-primary-300">
          <div class="flex justify-between">
            <span>Member Number:</span>
            <span class="font-mono">{{ $generatedMemberNumber }}</span>
          </div>
          <div class="flex justify-between">
            <span>Username:</span>
            <span class="font-mono">{{ $generatedMemberNumber }}</span>
          </div>
          <div class="flex justify-between">
            <span>Status:</span>
            <span class="font-semibold">Active</span>
          </div>
          <div class="flex justify-between">
            <span>Registration Date:</span>
            <span>{{ now()->format('M d, Y') }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Update tab button styles
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.classList.remove('bg-primary-600', 'text-white');
      btn.classList.add('bg-primary-100', 'dark:bg-primary-900/40', 'text-primary-700', 'dark:text-primary-300');
    });
    document.getElementById('tab-' + tabName).classList.remove('bg-primary-100', 'dark:bg-primary-900/40', 'text-primary-700', 'dark:text-primary-300');
    document.getElementById('tab-' + tabName).classList.add('bg-primary-600', 'text-white');
  }

  function saveTab(tabName) {
    // Show success message
    Swal.fire({
      icon: 'success',
      title: 'Saved Successfully',
      text: 'Tab data has been saved successfully!',
      timer: 1500,
      showConfirmButton: false
    });

    // Move to next tab
    const tabs = ['basic', 'contact', 'membership', 'account', 'kin', 'banking', 'documents', 'additional'];
    const currentIndex = tabs.indexOf(tabName);
    if (currentIndex < tabs.length - 1) {
      showTab(tabs[currentIndex + 1]);
    }
  }
</script>
@endpush
@endsection
