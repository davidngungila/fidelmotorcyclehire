@extends('layouts.admin')

@section('breadcrumb', 'Members \u203A Member Types \u203A Create')
@section('page_title', 'Create Member Type')

@section('content')
<div class="space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.member-types.index') }}"
       class="p-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
      <p class="text-sm text-primary-600 dark:text-primary-400">
        Create a new member type with specific benefits and privileges
      </p>
    </div>
  </div>

  <div class="glass p-6 lg:p-8">
    <form method="POST" action="{{ route('admin.member-types.store') }}" class="space-y-6">
      @csrf

      <div class="flex items-center gap-4 pb-6 border-b border-primary-100 dark:border-primary-900/50">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 text-white flex items-center justify-center text-2xl shadow-md">
          <i class="fa-solid fa-user-plus"></i>
        </div>
        <div>
          <h2 class="font-bold text-lg text-primary-900 dark:text-white">Member Type Information</h2>
          <p class="text-xs mt-0.5 text-primary-600 dark:text-primary-400">Fill in the details to create a new member type</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="md:col-span-2">
          <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Type Name *</label>
          <input type="text" name="name" value="{{ old('name') }}" required
                 placeholder="e.g. Founder, Ordinary, Scholar"
                 class="form-input @error('name') !border-red-400 @enderror">
          @error('name')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Code *</label>
          <input type="text" name="code" value="{{ old('code') }}" required
                 placeholder="e.g. FOUNDER, ORDINARY, SCHOLAR"
                 class="form-input font-mono @error('code') !border-red-400 @enderror">
          @error('code')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Priority *</label>
          <input type="number" name="priority" value="{{ old('priority', 50) }}" required min="0"
                 placeholder="Higher numbers appear first"
                 class="form-input @error('priority') !border-red-400 @enderror">
          @error('priority')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Description</label>
          <textarea name="description" rows="3"
                    placeholder="Describe this member type and its benefits..."
                    class="form-input @error('description') !border-red-400 @enderror">{{ old('description') }}</textarea>
          @error('description')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="pt-5 mt-5 border-t border-primary-100 dark:border-primary-900/50">
        <h3 class="text-xs font-bold uppercase tracking-wider mb-4 text-primary-700 dark:text-primary-300">
          <i class="fa-solid fa-money-bill mr-1.5"></i> Financial Requirements
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <div>
            <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Registration Fee (TSh) *</label>
            <input type="number" name="registration_fee" value="{{ old('registration_fee', 0) }}" required min="0" step="0.01"
                   class="form-input @error('registration_fee') !border-red-400 @enderror">
            @error('registration_fee')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Monthly Contribution (TSh) *</label>
            <input type="number" name="monthly_contribution" value="{{ old('monthly_contribution', 0) }}" required min="0" step="0.01"
                   class="form-input @error('monthly_contribution') !border-red-400 @enderror">
            @error('monthly_contribution')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Minimum Savings (TSh) *</label>
            <input type="number" name="min_savings" value="{{ old('min_savings', 0) }}" required min="0" step="0.01"
                   class="form-input @error('min_savings') !border-red-400 @enderror">
            @error('min_savings')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <div class="pt-5 mt-5 border-t border-primary-100 dark:border-primary-900/50">
        <h3 class="text-xs font-bold uppercase tracking-wider mb-4 text-primary-700 dark:text-primary-300">
          <i class="fa-solid fa-percent mr-1.5"></i> Loan Benefits
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Loan Multiplier *</label>
            <input type="number" name="max_loan_multiplier" value="{{ old('max_loan_multiplier', 1) }}" required min="1"
                   placeholder="e.g. 3 for 3x savings"
                   class="form-input @error('max_loan_multiplier') !border-red-400 @enderror">
            @error('max_loan_multiplier')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Interest Rate Discount (%) *</label>
            <input type="number" name="interest_rate_discount" value="{{ old('interest_rate_discount', 0) }}" required min="0" max="100" step="0.01"
                   placeholder="e.g. 0.5 for 0.5% discount"
                   class="form-input @error('interest_rate_discount') !border-red-400 @enderror">
            @error('interest_rate_discount')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400"><i class="fa-solid fa-circle-exclamation mr-1 text-[10px]"></i>{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <div class="pt-5 mt-5 border-t border-primary-100 dark:border-primary-900/50">
        <h3 class="text-xs font-bold uppercase tracking-wider mb-4 text-primary-700 dark:text-primary-300">
          <i class="fa-solid fa-shield-halved mr-1.5"></i> Privileges
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="form-label uppercase tracking-wider mb-2 text-primary-700 dark:text-primary-300">Voting Rights</label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/30 has-[:checked]:text-green-700 dark:has-[:checked]:text-green-300">
                <input type="radio" name="can_vote" value="1" class="hidden" {{ old('can_vote', 0) ? 'checked' : '' }}>
                <i class="fa-solid fa-check text-[12px]"></i> Yes
              </label>
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/30 has-[:checked]:text-red-700 dark:has-[:checked]:text-red-300">
                <input type="radio" name="can_vote" value="0" class="hidden" {{ old('can_vote', 0) === 0 ? 'checked' : '' }}>
                <i class="fa-solid fa-xmark text-[12px]"></i> No
              </label>
            </div>
          </div>

          <div>
            <label class="form-label uppercase tracking-wider mb-2 text-primary-700 dark:text-primary-300">Can Hold Office</label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/30 has-[:checked]:text-green-700 dark:has-[:checked]:text-green-300">
                <input type="radio" name="can_hold_office" value="1" class="hidden" {{ old('can_hold_office', 0) ? 'checked' : '' }}>
                <i class="fa-solid fa-check text-[12px]"></i> Yes
              </label>
              <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                           border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                           has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/30 has-[:checked]:text-red-700 dark:has-[:checked]:text-red-300">
                <input type="radio" name="can_hold_office" value="0" class="hidden" {{ old('can_hold_office', 0) === 0 ? 'checked' : '' }}>
                <i class="fa-solid fa-xmark text-[12px]"></i> No
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="pt-5 mt-5 border-t border-primary-100 dark:border-primary-900/50">
        <h3 class="text-xs font-bold uppercase tracking-wider mb-2 text-primary-700 dark:text-primary-300">Status</h3>
        <div class="grid grid-cols-2 gap-3">
          <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                       border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                       has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/30 has-[:checked]:text-green-700 dark:has-[:checked]:text-green-300">
            <input type="radio" name="status" value="active" class="hidden" {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
            <i class="fa-solid fa-circle-check text-[12px]"></i> Active
          </label>
          <label class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl border-2 cursor-pointer transition-all text-xs font-semibold
                       border-primary-200 dark:border-primary-900/60 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300
                       has-[:checked]:border-gray-500 has-[:checked]:bg-gray-50 dark:has-[:checked]:bg-gray-900/30 has-[:checked]:text-gray-700 dark:has-[:checked]:text-gray-300">
            <input type="radio" name="status" value="inactive" class="hidden" {{ old('status') === 'inactive' ? 'checked' : '' }}>
            <i class="fa-solid fa-circle-xmark text-[12px]"></i> Inactive
          </label>
        </div>
      </div>

      <div class="pt-6 mt-6 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row items-center justify-end gap-3">
        <a href="{{ route('admin.member-types.index') }}"
           class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-colors text-center">
          Cancel
        </a>
        <button type="submit"
                class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
          <i class="fa-solid fa-user-plus mr-1.5 text-[13px]"></i> Create Member Type
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
