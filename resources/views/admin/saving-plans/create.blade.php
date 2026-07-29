@extends('layouts.admin')

@section('breadcrumb', 'Saving Plans \u203A New')
@section('page_title', 'Create Saving Plan')

@section('content')

<div class="glass p-8">
  <form method="POST" action="{{ route('admin.saving-plans.store') }}" class="space-y-6">
    @csrf

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Name</label>
      <input type="text" name="name" required
             placeholder="Enter plan name"
             class="form-input py-2.5 px-4">
      @error('name')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Member ID</label>
        <input type="text" name="memberid" required
               placeholder="Enter member ID"
               class="form-input py-2.5 px-4">
        @error('memberid')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Membership</label>
        <select name="membership" required class="form-input py-2.5 px-4">
          <option value="">Select membership type</option>
          <option value="individual">Individual</option>
          <option value="corporate">Corporate</option>
          <option value="group">Group</option>
        </select>
        @error('membership')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Monthly Goal</label>
        <input type="number" name="monthly_goal" step="0.01" min="0" required
               placeholder="Enter monthly goal"
               class="form-input py-2.5 px-4">
        @error('monthly_goal')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Goal</label>
        <input type="number" name="goal" step="0.01" min="0" required
               placeholder="Enter total goal"
               class="form-input py-2.5 px-4">
        @error('goal')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="flex items-center gap-3 pt-4">
      <a href="{{ route('admin.saving-plans.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-primary-600 hover:bg-primary-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Create Saving Plan
      </button>
    </div>
  </form>
</div>

@endsection
