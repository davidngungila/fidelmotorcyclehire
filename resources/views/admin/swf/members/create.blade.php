@extends('layouts.admin')

@section('breadcrumb', 'SWF \u203A Register Member')
@section('page_title', 'Register SWF Member')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.swf.index') }}" class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to SWF</span>
    </a>
  </div>

  <div class="glass overflow-hidden">
    <div class="p-6 lg:p-8">
      
      <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 text-white flex items-center justify-center text-2xl shadow-md">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div>
          <h2 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Register New SWF Member</h2>
          <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Add a member to the Social Welfare Fund</p>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.swf.members.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Select Member</label>
            <select name="user_id" class="form-input" required>
              <option value="">-- Select a Member --</option>
              @foreach($members as $member)
                <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
              @endforeach
            </select>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Only members without SWF membership are shown</p>
          </div>

          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Join Date</label>
            <input type="date" name="join_date" value="{{ now()->format('Y-m-d') }}" class="form-input" required>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">The date the member joins the SWF fund</p>
          </div>
        </div>

        <div class="pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end gap-3">
          <a href="{{ route('admin.swf.index') }}" class="px-6 py-3 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold transition-all hover:bg-gray-300 dark:hover:bg-gray-600">
            Cancel
          </a>
          <button type="submit" class="px-8 py-3 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
            <i class="fa-solid fa-user-plus mr-2"></i>Register Member
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
