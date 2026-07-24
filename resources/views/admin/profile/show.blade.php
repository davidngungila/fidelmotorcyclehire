@extends('layouts.admin')

@section('breadcrumb', 'My Profile')
@section('page_title', 'My Profile')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

  <div class="glass rounded-2xl p-6 lg:p-8 border border-primary-100 dark:border-dark-border">
    <div class="flex flex-col md:flex-row md:items-center gap-6">
      <div class="flex-shrink-0 mx-auto md:mx-0 relative">
        @if($user->photo)
          <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover shadow-xl shadow-primary-500/20">
        @else
          <div class="w-24 h-24 rounded-full flex items-center justify-center shadow-xl shadow-primary-500/20"
               style="background: linear-gradient(135deg, #34d399 0%, #059669 55%, #064e3b 100%);">
            <span class="text-4xl font-extrabold text-white tracking-wide">{{ $initials }}</span>
          </div>
        @endif
      </div>

      <div class="flex-1 text-center md:text-left">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight">
              {{ $user->name }}
            </h1>
            <div class="mt-2 flex flex-wrap items-center justify-center md:justify-start gap-3">
              <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-mono text-sm font-bold bg-primary-50 dark:bg-primary-900/40 text-primary-800 dark:text-primary-200 border border-primary-200 dark:border-primary-800/60">
                <i class="fa-solid fa-user-shield mr-2 text-primary-500 text-xs"></i>
                Admin
              </span>
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold border border-indigo-100 dark:border-indigo-800/50">
                <i class="fa-solid fa-envelope"></i>
                {{ $user->email }}
              </span>
            </div>
          </div>
          <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
            <i class="fa-solid fa-pen text-[11px]"></i> Edit Profile
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div class="glass rounded-2xl overflow-hidden border border-primary-100 dark:border-dark-border">
      <div class="flex items-center gap-3 px-5 py-4 border-b border-primary-100 dark:border-dark-border bg-primary-50/40 dark:bg-primary-900/20">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-sm">
          <i class="fa-solid fa-id-card text-white text-sm"></i>
        </div>
        <div>
          <h3 class="font-bold text-primary-900 dark:text-white text-sm">PERSONAL INFO</h3>
          <p class="text-[11px] text-primary-500 dark:text-primary-400">Account details</p>
        </div>
      </div>
      <div class="divide-y divide-primary-50 dark:divide-primary-800/50">
        <div class="flex items-start justify-between px-5 py-3.5 gap-3">
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Full Name</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white break-words">{{ $user->name }}</p>
          </div>
        </div>
        <div class="flex items-start justify-between px-5 py-3.5 gap-3">
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Email</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2 break-all">
              <i class="fa-solid fa-envelope text-primary-500 text-[11px] flex-shrink-0"></i>
              {{ $user->email }}
            </p>
          </div>
        </div>
        <div class="flex items-start justify-between px-5 py-3.5 gap-3">
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Phone</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2">
              <i class="fa-solid fa-phone text-primary-500 text-[11px]"></i>
              {{ $user->phone ?? '—' }}
            </p>
          </div>
        </div>
        <div class="flex items-start justify-between px-5 py-3.5 gap-3">
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Address</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white break-words">{{ $user->address ?? '—' }}</p>
          </div>
        </div>
        <div class="flex items-start justify-between px-5 py-3.5 gap-3">
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Occupation</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $user->occupation ?? '—' }}</p>
          </div>
        </div>
        <div class="flex items-start justify-between px-5 py-3.5 gap-3">
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Employer</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white break-words">{{ $user->employer ?? '—' }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="glass rounded-2xl overflow-hidden border border-primary-100 dark:border-dark-border">
      <div class="flex items-center gap-3 px-5 py-4 border-b border-primary-100 dark:border-dark-border bg-primary-50/40 dark:bg-primary-900/20">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-sm">
          <i class="fa-solid fa-shield-halved text-white text-sm"></i>
        </div>
        <div>
          <h3 class="font-bold text-primary-900 dark:text-white text-sm">ACCOUNT INFO</h3>
          <p class="text-[11px] text-primary-500 dark:text-primary-400">System details</p>
        </div>
      </div>
      <div class="divide-y divide-primary-50 dark:divide-primary-800/50">
        <div class="flex items-start justify-between px-5 py-3.5 gap-3">
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Role</p>
            <span class="badge badge-purple inline-flex items-center gap-1.5 py-1.5 mt-0.5">
              <i class="fa-solid fa-user-shield text-[10px]"></i>
              Admin
            </span>
          </div>
        </div>
        <div class="flex items-start justify-between px-5 py-3.5 gap-3">
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Registration Date</p>
            <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2">
              <i class="fa-solid fa-calendar-check text-primary-500 text-[11px]"></i>
              {{ $user->created_at ? $user->created_at->format('F j, Y') : '—' }}
            </p>
          </div>
        </div>
        <div class="flex items-start justify-between px-5 py-3.5 gap-3">
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Status</p>
            <span class="badge badge-green inline-flex items-center gap-1.5 py-1.5 mt-0.5">
              <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span>
              Active
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection
