@extends('layouts.admin')

@section('breadcrumb', 'SWF \u203A Benefits')
@section('page_title', 'SWF Benefits')

@section('content')
<div class="space-y-6">
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
      <h2 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">SWF Benefits</h2>
      <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Manage and grant benefits to SWF members</p>
    </div>
    <a href="{{ route('admin.swf.benefits.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-sm font-bold transition-all">
      <i class="fa-solid fa-plus"></i> Create Benefit
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Available Benefits -->
    <div class="glass p-6 rounded-2xl">
      <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-500 flex items-center justify-center">
          <i class="fa-solid fa-gift"></i>
        </div>
        <div>
          <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">Available Benefits</h3>
          <p class="text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">{{ $benefits->count() }} benefits available</p>
        </div>
      </div>

      <div class="space-y-3">
        @forelse($benefits as $benefit)
          <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-900/50">
            <div class="flex items-start justify-between">
              <div>
                <h4 class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $benefit->name }}</h4>
                <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">{{ $benefit->description }}</p>
                <div class="flex items-center gap-2 mt-2">
                  <span class="text-[10px] uppercase font-bold tracking-wider text-purple-600 dark:text-purple-400">{{ $benefit->category }}</span>
                  @if($benefit->max_amount)
                    <span class="text-[10px] font-bold text-green-600 dark:text-green-400">Max: {{ number_format($benefit->max_amount, 2) }} TSh</span>
                  @endif
                </div>
              </div>
              @if($benefit->requires_approval)
                <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400">Requires Approval</span>
              @endif
            </div>
          </div>
        @empty
          <p class="text-sm text-primary-500 dark:text-primary-400 text-center py-8">No benefits available. Create one to get started.</p>
        @endforelse
      </div>
    </div>

    <!-- Grant Benefit Form -->
    <div class="glass p-6 rounded-2xl">
      <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-500 flex items-center justify-center">
          <i class="fa-solid fa-hand-holding-heart"></i>
        </div>
        <div>
          <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">Grant Benefit</h3>
          <p class="text-xs" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Grant a benefit to a SWF member</p>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.swf.benefits.grant') }}" class="space-y-4">
        @csrf

        <div>
          <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Select Member</label>
          <select name="swf_member_id" class="form-input" required>
            <option value="">-- Select SWF Member --</option>
            @foreach($swfMembers as $member)
              <option value="{{ $member->id }}">{{ $member->membership_number }} - {{ $member->user->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Select Benefit</label>
          <select name="swf_benefit_id" class="form-input" required>
            <option value="">-- Select Benefit --</option>
            @foreach($benefits as $benefit)
              <option value="{{ $benefit->id }}">{{ $benefit->name }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Amount (TSh)</label>
          <input type="number" name="amount" step="0.01" min="0" class="form-input" placeholder="e.g., 50000" required>
        </div>

        <div>
          <label class="form-label uppercase tracking-wider text-xs" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Received Date</label>
          <input type="date" name="received_date" value="{{ now()->format('Y-m-d') }}" class="form-input" required>
        </div>

        <button type="submit" class="w-full px-6 py-3 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
          <i class="fa-solid fa-check mr-2"></i>Grant Benefit
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
