@extends('layouts.admin')

@section('breadcrumb', 'SWF \u203A Benefits \u203A Create')
@section('page_title', 'Create Benefit')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.swf.benefits.index') }}" class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to Benefits</span>
    </a>
  </div>

  <div class="glass overflow-hidden">
    <div class="p-6 lg:p-8">
      
      <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 text-white flex items-center justify-center text-2xl shadow-md">
          <i class="fa-solid fa-gift"></i>
        </div>
        <div>
          <h2 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Create SWF Benefit</h2>
          <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Define a new benefit for SWF members</p>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.swf.benefits.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Benefit Name</label>
            <input type="text" name="name" class="form-input" placeholder="e.g., Emergency Assistance" required>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">The name of the benefit</p>
          </div>

          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Description</label>
            <textarea name="description" rows="3" class="form-input" placeholder="Describe what this benefit provides..."></textarea>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Detailed description of the benefit</p>
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Category</label>
            <select name="category" class="form-input" required>
              <option value="general">General</option>
              <option value="emergency">Emergency</option>
              <option value="education">Education</option>
              <option value="health">Health</option>
              <option value="funeral">Funeral</option>
              <option value="welfare">Welfare</option>
            </select>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Benefit category for organization</p>
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Maximum Amount (TSh)</label>
            <input type="number" name="max_amount" step="0.01" min="0" class="form-input" placeholder="e.g., 500000">
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Maximum amount that can be granted (leave empty for unlimited)</p>
          </div>

          <div class="md:col-span-2 flex items-center gap-3">
            <input type="hidden" name="requires_approval" value="0">
            <input type="checkbox" name="requires_approval" id="requires_approval" value="1" class="w-4 h-4 rounded border-primary-300 text-primary-600 focus:ring-primary-500" checked>
            <label for="requires_approval" class="text-sm" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Requires Admin Approval</label>
            <p class="text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">If checked, granting this benefit requires admin approval</p>
          </div>
        </div>

        <div class="pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end gap-3">
          <a href="{{ route('admin.swf.benefits.index') }}" class="px-6 py-3 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold transition-all hover:bg-gray-300 dark:hover:bg-gray-600">
            Cancel
          </a>
          <button type="submit" class="px-8 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
            <i class="fa-solid fa-plus mr-2"></i>Create Benefit
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
