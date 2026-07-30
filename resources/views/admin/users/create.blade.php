@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Users \u203A Create')
@section('page_title', 'Create New Member')

@section('content')
<div class="space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.users.index') }}"
       class="p-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
      <p class="text-sm" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
        Register a new member with complete profile information
      </p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Form -->
    <div class="lg:col-span-2">
      <div class="glass p-6 lg:p-8">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
          @csrf

          <!-- Tabs -->
          <div x-data="{ activeTab: 'basic' }" class="space-y-6">
            <!-- Tab Navigation -->
            <div class="flex flex-wrap gap-1 border-b border-primary-100 dark:border-primary-900/50 pb-4">
              <button type="button" @click="activeTab = 'basic'" :class="activeTab === 'basic' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-primary-400 dark:text-primary-600 border-b-2 border-transparent hover:text-primary-600 dark:hover:text-primary-400'" class="px-4 py-2 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-user mr-1.5"></i> Basic Info
              </button>
              <button type="button" @click="activeTab = 'contact'" :class="activeTab === 'contact' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-primary-400 dark:text-primary-600 border-b-2 border-transparent hover:text-primary-600 dark:hover:text-primary-400'" class="px-4 py-2 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-address-book mr-1.5"></i> Contact
              </button>
              <button type="button" @click="activeTab = 'membership'" :class="activeTab === 'membership' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-primary-400 dark:text-primary-600 border-b-2 border-transparent hover:text-primary-600 dark:hover:text-primary-400'" class="px-4 py-2 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-id-card mr-1.5"></i> Membership
              </button>
              <button type="button" @click="activeTab = 'account'" :class="activeTab === 'account' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-primary-400 dark:text-primary-600 border-b-2 border-transparent hover:text-primary-600 dark:hover:text-primary-400'" class="px-4 py-2 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-shield-halved mr-1.5"></i> Account
              </button>
              <button type="button" @click="activeTab = 'kin'" :class="activeTab === 'kin' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-primary-400 dark:text-primary-600 border-b-2 border-transparent hover:text-primary-600 dark:hover:text-primary-400'" class="px-4 py-2 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-users mr-1.5"></i> Next of Kin
              </button>
              <button type="button" @click="activeTab = 'banking'" :class="activeTab === 'banking' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-primary-400 dark:text-primary-600 border-b-2 border-transparent hover:text-primary-600 dark:hover:text-primary-400'" class="px-4 py-2 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-building-columns mr-1.5"></i> Banking
              </button>
              <button type="button" @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-primary-400 dark:text-primary-600 border-b-2 border-transparent hover:text-primary-600 dark:hover:text-primary-400'" class="px-4 py-2 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-file-lines mr-1.5"></i> Documents
              </button>
              <button type="button" @click="activeTab = 'additional'" :class="activeTab === 'additional' ? 'text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400' : 'text-primary-400 dark:text-primary-600 border-b-2 border-transparent hover:text-primary-600 dark:hover:text-primary-400'" class="px-4 py-2 text-xs font-semibold transition-colors">
                <i class="fa-solid fa-ellipsis mr-1.5"></i> Additional
              </button>
            </div>

            <!-- Tab 1: Basic Information -->
            <div x-show="activeTab === 'basic'" x-transition class="space-y-5">
              <h3 class="text-sm font-bold uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
                <i class="fa-solid fa-user mr-2"></i> Basic Information
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-3">
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Member Type *</label>
                  <select name="member_type_id" required class="form-input @error('member_type_id') !border-red-400 @enderror">
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
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">First Name *</label>
                  <input type="text" name="first_name" value="{{ old('first_name') }}" required
                         placeholder="e.g. John"
                         class="form-input @error('first_name') !border-red-400 @enderror">
                  @error('first_name')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Middle Name</label>
                  <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                         placeholder="e.g. Michael"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Last Name *</label>
                  <input type="text" name="last_name" value="{{ old('last_name') }}" required
                         placeholder="e.g. Mwangi"
                         class="form-input @error('last_name') !border-red-400 @enderror">
                  @error('last_name')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Gender *</label>
                  <select name="gender" required class="form-input @error('gender') !border-red-400 @enderror">
                    <option value="">Select gender...</option>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                  </select>
                  @error('gender')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Date of Birth</label>
                  <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">National ID (NIDA)</label>
                  <input type="text" name="national_id" value="{{ old('national_id') }}"
                         placeholder="e.g. 1990123456789012"
                         class="form-input font-mono">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Passport/Driving License</label>
                  <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                         placeholder="Optional ID document"
                         class="form-input font-mono">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Member Number</label>
                  <input type="text" name="member_number" value="{{ old('member_number') }}"
                         placeholder="Auto-generated if empty"
                         class="form-input font-mono bg-primary-50 dark:bg-primary-900/30" readonly>
                  <p class="mt-1 text-[10px] text-primary-500 dark:text-primary-400">Auto-generated: MB{{ date('ymd') }}0001</p>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Registration Date</label>
                  <input type="date" name="registration_date" value="{{ old('registration_date', date('Y-m-d')) }}"
                         class="form-input">
                </div>

                <div class="md:col-span-3">
                  <label class="form-label uppercase tracking-wider mb-2 text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Status</label>
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
                      <input type="radio" name="status" value="suspended" class="hidden" {{ old('status') === 'suspended' ? 'checked' : '' }}>
                      <i class="fa-solid fa-ban text-[12px]"></i> Suspended
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tab 2: Contact Information -->
            <div x-show="activeTab === 'contact'" x-transition class="space-y-5">
              <h3 class="text-sm font-bold uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
                <i class="fa-solid fa-address-book mr-2"></i> Contact Information
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Phone Number *</label>
                  <input type="tel" name="phone" value="{{ old('phone') }}" required
                         placeholder="e.g. +255 712 345 678"
                         class="form-input @error('phone') !border-red-400 @enderror">
                  @error('phone')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Alternative Phone</label>
                  <input type="tel" name="alternative_phone" value="{{ old('alternative_phone') }}"
                         placeholder="e.g. +255 765 432 109"
                         class="form-input">
                </div>

                <div class="md:col-span-2">
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Email Address</label>
                  <input type="email" name="email" value="{{ old('email') }}"
                         placeholder="e.g. john@example.com"
                         class="form-input @error('email') !border-red-400 @enderror">
                  @error('email')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Region</label>
                  <select name="region" class="form-input">
                    <option value="">Select region...</option>
                    <option value="Dar es Salaam" {{ old('region') === 'Dar es Salaam' ? 'selected' : '' }}>Dar es Salaam</option>
                    <option value="Arusha" {{ old('region') === 'Arusha' ? 'selected' : '' }}>Arusha</option>
                    <option value="Mwanza" {{ old('region') === 'Mwanza' ? 'selected' : '' }}>Mwanza</option>
                    <option value="Dodoma" {{ old('region') === 'Dodoma' ? 'selected' : '' }}>Dodoma</option>
                    <option value="Tanga" {{ old('region') === 'Tanga' ? 'selected' : '' }}>Tanga</option>
                    <option value="Morogoro" {{ old('region') === 'Morogoro' ? 'selected' : '' }}>Morogoro</option>
                    <option value="Other" {{ old('region') === 'Other' ? 'selected' : '' }}>Other</option>
                  </select>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">District</label>
                  <input type="text" name="district" value="{{ old('district') }}"
                         placeholder="e.g. Ilala"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Ward</label>
                  <input type="text" name="ward" value="{{ old('ward') }}"
                         placeholder="e.g. Makumbusho"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Street/Village</label>
                  <input type="text" name="street" value="{{ old('street') }}"
                         placeholder="e.g. Msimbazi Street"
                         class="form-input">
                </div>

                <div class="md:col-span-2">
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Physical Address</label>
                  <textarea name="address" rows="2"
                            placeholder="Full physical address..."
                            class="form-input">{{ old('address') }}</textarea>
                </div>
              </div>
            </div>

            <!-- Tab 3: Membership Details -->
            <div x-show="activeTab === 'membership'" x-transition class="space-y-5">
              <h3 class="text-sm font-bold uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
                <i class="fa-solid fa-id-card mr-2"></i> Membership Details
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Branch *</label>
                  <select name="branch" required class="form-input @error('branch') !border-red-400 @enderror">
                    <option value="">Select branch...</option>
                    <option value="Head Office" {{ old('branch') === 'Head Office' ? 'selected' : '' }}>Head Office</option>
                    <option value="Arusha Branch" {{ old('branch') === 'Arusha Branch' ? 'selected' : '' }}>Arusha Branch</option>
                    <option value="Mwanza Branch" {{ old('branch') === 'Mwanza Branch' ? 'selected' : '' }}>Mwanza Branch</option>
                    <option value="Dodoma Branch" {{ old('branch') === 'Dodoma Branch' ? 'selected' : '' }}>Dodoma Branch</option>
                  </select>
                  @error('branch')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Membership Category</label>
                  <select name="membership_category" class="form-input">
                    <option value="">Select category...</option>
                    <option value="Individual" {{ old('membership_category') === 'Individual' ? 'selected' : '' }}>Individual</option>
                    <option value="Corporate" {{ old('membership_category') === 'Corporate' ? 'selected' : '' }}>Corporate</option>
                    <option value="Group" {{ old('membership_category') === 'Group' ? 'selected' : '' }}>Group</option>
                  </select>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Occupation</label>
                  <input type="text" name="occupation" value="{{ old('occupation') }}"
                         placeholder="e.g. Teacher, Business Owner"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Employer/Business</label>
                  <input type="text" name="employer" value="{{ old('employer') }}"
                         placeholder="e.g. Ministry of Education"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Monthly Income (TSh)</label>
                  <input type="number" name="monthly_income" value="{{ old('monthly_income') }}" min="0" step="0.01"
                         placeholder="e.g. 500000"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Introduced By (Referrer)</label>
                  <input type="text" name="introduced_by" value="{{ old('introduced_by') }}"
                         placeholder="Member number or name"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Joining Fee (TSh)</label>
                  <input type="number" name="joining_fee" value="{{ old('joining_fee', 0) }}" min="0" step="0.01"
                         placeholder="e.g. 5000"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Shares Purchased</label>
                  <input type="number" name="shares_purchased" value="{{ old('shares_purchased', 0) }}" min="0"
                         placeholder="Number of shares"
                         class="form-input">
                </div>

                <div class="md:col-span-2">
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Initial Savings Deposit (TSh)</label>
                  <input type="number" name="initial_savings" value="{{ old('initial_savings', 0) }}" min="0" step="0.01"
                         placeholder="e.g. 10000"
                         class="form-input">
                </div>
              </div>
            </div>

            <!-- Tab 4: Account Information -->
            <div x-show="activeTab === 'account'" x-transition class="space-y-5">
              <h3 class="text-sm font-bold uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
                <i class="fa-solid fa-shield-halved mr-2"></i> Account Information
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Username</label>
                  <input type="text" name="username" value="{{ old('username') }}"
                         placeholder="Auto-generated from member number"
                         class="form-input bg-primary-50 dark:bg-primary-900/30" readonly>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Password *</label>
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
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Confirm Password *</label>
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

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Role *</label>
                  <select name="role" required class="form-input @error('role') !border-red-400 @enderror">
                    <option value="">Select a role...</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin - Full system access</option>
                    <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Member - Self-service portal</option>
                  </select>
                  @error('role')
                    <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
                  @enderror
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider mb-2 text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Email Verified</label>
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
                  <label class="form-label uppercase tracking-wider mb-2 text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Phone Verified</label>
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
            </div>

            <!-- Tab 5: Next of Kin -->
            <div x-show="activeTab === 'kin'" x-transition class="space-y-5">
              <h3 class="text-sm font-bold uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
                <i class="fa-solid fa-users mr-2"></i> Next of Kin
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Full Name</label>
                  <input type="text" name="kin_name" value="{{ old('kin_name') }}"
                         placeholder="e.g. Mary Mwangi"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Relationship</label>
                  <select name="kin_relationship" class="form-input">
                    <option value="">Select relationship...</option>
                    <option value="Spouse" {{ old('kin_relationship') === 'Spouse' ? 'selected' : '' }}>Spouse</option>
                    <option value="Parent" {{ old('kin_relationship') === 'Parent' ? 'selected' : '' }}>Parent</option>
                    <option value="Child" {{ old('kin_relationship') === 'Child' ? 'selected' : '' }}>Child</option>
                    <option value="Sibling" {{ old('kin_relationship') === 'Sibling' ? 'selected' : '' }}>Sibling</option>
                    <option value="Friend" {{ old('kin_relationship') === 'Friend' ? 'selected' : '' }}>Friend</option>
                    <option value="Other" {{ old('kin_relationship') === 'Other' ? 'selected' : '' }}>Other</option>
                  </select>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Phone Number</label>
                  <input type="tel" name="kin_phone" value="{{ old('kin_phone') }}"
                         placeholder="e.g. +255 712 345 678"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Address</label>
                  <input type="text" name="kin_address" value="{{ old('kin_address') }}"
                         placeholder="e.g. P.O. Box 1234, Dar es Salaam"
                         class="form-input">
                </div>
              </div>
            </div>

            <!-- Tab 6: Banking & Mobile Money -->
            <div x-show="activeTab === 'banking'" x-transition class="space-y-5">
              <h3 class="text-sm font-bold uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
                <i class="fa-solid fa-building-columns mr-2"></i> Banking & Mobile Money
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Bank Name</label>
                  <select name="bank_name" class="form-input">
                    <option value="">Select bank...</option>
                    <option value="CRDB" {{ old('bank_name') === 'CRDB' ? 'selected' : '' }}>CRDB Bank</option>
                    <option value="NMB" {{ old('bank_name') === 'NMB' ? 'selected' : '' }}>NMB Bank</option>
                    <option value="NBC" {{ old('bank_name') === 'NBC' ? 'selected' : '' }}>NBC Bank</option>
                    <option value="TPB" {{ old('bank_name') === 'TPB' ? 'selected' : '' }}>TPB Bank</option>
                    <option value="Other" {{ old('bank_name') === 'Other' ? 'selected' : '' }}>Other</option>
                  </select>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Bank Account Number</label>
                  <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                         placeholder="e.g. 0151234567890"
                         class="form-input font-mono">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Account Name</label>
                  <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}"
                         placeholder="e.g. John Michael Mwangi"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Mobile Money Network</label>
                  <select name="mobile_money_network" class="form-input">
                    <option value="">Select network...</option>
                    <option value="M-Pesa" {{ old('mobile_money_network') === 'M-Pesa' ? 'selected' : '' }}>M-Pesa</option>
                    <option value="Tigo Pesa" {{ old('mobile_money_network') === 'Tigo Pesa' ? 'selected' : '' }}>Tigo Pesa</option>
                    <option value="Airtel Money" {{ old('mobile_money_network') === 'Airtel Money' ? 'selected' : '' }}>Airtel Money</option>
                    <option value="Halopesa" {{ old('mobile_money_network') === 'Halopesa' ? 'selected' : '' }}>Halopesa</option>
                  </select>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Mobile Wallet Number</label>
                  <input type="tel" name="mobile_wallet_number" value="{{ old('mobile_wallet_number') }}"
                         placeholder="e.g. +255 712 345 678"
                         class="form-input font-mono">
                </div>
              </div>
            </div>

            <!-- Tab 7: Documents -->
            <div x-show="activeTab === 'documents'" x-transition class="space-y-5">
              <h3 class="text-sm font-bold uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
                <i class="fa-solid fa-file-lines mr-2"></i> Documents
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Passport Photo</label>
                  <input type="file" name="passport_photo" accept="image/*"
                         class="form-input">
                  <p class="mt-1 text-[10px] text-primary-500 dark:text-primary-400">JPG, PNG (Max 2MB)</p>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">National ID Copy</label>
                  <input type="file" name="national_id_copy" accept="image/*,.pdf"
                         class="form-input">
                  <p class="mt-1 text-[10px] text-primary-500 dark:text-primary-400">JPG, PNG, PDF (Max 5MB)</p>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Signature</label>
                  <input type="file" name="signature" accept="image/*"
                         class="form-input">
                  <p class="mt-1 text-[10px] text-primary-500 dark:text-primary-400">JPG, PNG (Max 1MB)</p>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Other Attachments</label>
                  <input type="file" name="other_attachments" accept=".pdf,.doc,.docx"
                         class="form-input">
                  <p class="mt-1 text-[10px] text-primary-500 dark:text-primary-400">PDF, DOC, DOCX (Max 10MB)</p>
                </div>
              </div>
            </div>

            <!-- Tab 8: Additional Information -->
            <div x-show="activeTab === 'additional'" x-transition class="space-y-5">
              <h3 class="text-sm font-bold uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">
                <i class="fa-solid fa-ellipsis mr-2"></i> Additional Information
              </h3>

              <div class="grid grid-cols-1 gap-5">
                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Notes</label>
                  <textarea name="notes" rows="4"
                            placeholder="Additional notes or comments..."
                            class="form-input">{{ old('notes') }}</textarea>
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Tags</label>
                  <input type="text" name="tags" value="{{ old('tags') }}"
                         placeholder="e.g. VIP, Long-term, Active"
                         class="form-input">
                </div>

                <div>
                  <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Custom Fields</label>
                  <textarea name="custom_fields" rows="3"
                            placeholder="JSON format for custom fields..."
                            class="form-input font-mono text-xs">{{ old('custom_fields') }}</textarea>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 mt-6 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row items-center justify-end gap-3">
              <a href="{{ route('admin.users.index') }}"
                 class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-colors text-center">
                Cancel
              </a>
              <button type="submit"
                      class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
                <i class="fa-solid fa-user-plus mr-1.5 text-[13px]"></i> Save Member
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Sidebar Summary -->
    <div class="space-y-6">
      <div class="glass p-6 rounded-2xl">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2" :class="darkMode ? 'text-white' : 'text-primary-900'">
          <i class="fa-solid fa-clipboard-list text-primary-500 text-xs"></i>
          Summary
        </h3>

        <div class="space-y-4">
          <div>
            <p class="text-xs mb-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Member Number</p>
            <p class="text-sm font-mono font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">MB{{ date('ymd') }}0001</p>
          </div>

          <div>
            <p class="text-xs mb-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Registration Date</p>
            <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ date('M d, Y') }}</p>
          </div>

          <div>
            <p class="text-xs mb-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Member Type</p>
            <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">Selected in form</p>
          </div>

          <div>
            <p class="text-xs mb-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Branch</p>
            <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">Selected in form</p>
          </div>

          <div>
            <p class="text-xs mb-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Status</p>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
              Active
            </span>
          </div>
        </div>

        <div class="mt-6 pt-4 border-t border-primary-100 dark:border-primary-900/50">
          <h4 class="text-xs font-bold uppercase tracking-wider mb-3" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Auto-generated Values</h4>
          <div class="space-y-2 text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
            <p><span class="font-semibold">Username:</span> From member number</p>
            <p><span class="font-semibold">Status:</span> Active (default)</p>
            <p><span class="font-semibold">Reg Date:</span> Current date</p>
          </div>
        </div>
      </div>

      <div class="glass p-6 rounded-2xl">
        <h3 class="font-bold text-sm mb-4 flex items-center gap-2" :class="darkMode ? 'text-white' : 'text-primary-900'">
          <i class="fa-solid fa-circle-info text-primary-500 text-xs"></i>
          Quick Tips
        </h3>
        <ul class="space-y-2 text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
          <li class="flex items-start gap-2">
            <i class="fa-solid fa-check text-green-500 mt-0.5 text-[10px]"></i>
            <span>All fields marked with * are required</span>
          </li>
          <li class="flex items-start gap-2">
            <i class="fa-solid fa-check text-green-500 mt-0.5 text-[10px]"></i>
            <span>Member number auto-generates if left blank</span>
          </li>
          <li class="flex items-start gap-2">
            <i class="fa-solid fa-check text-green-500 mt-0.5 text-[10px]"></i>
            <span>Use tabs to navigate different sections</span>
          </li>
          <li class="flex items-start gap-2">
            <i class="fa-solid fa-check text-green-500 mt-0.5 text-[10px]"></i>
            <span>Review summary before submitting</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

@endsection
