@extends('layouts.member')

@section('breadcrumb', 'My Profile')
@section('page_title', 'My Profile')

@php
    function fmtTsh($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }

    $displayName = $fullName ?? $user->name ?? 'Member User';
    $displayInitials = $initials ?? strtoupper(substr($displayName, 0, 1));
    $displayMemberNumber = $user->member_number ?? 'FTN-00001';
    $status = $user->status ?? 'Active';
    $statusActive = strtolower($status) === 'active';

    $fullNameVal = $user->name ?? '—';
    $genderVal = $user->gender ?? '—';
    $phoneVal = $user->phone ?? '—';
    $emailVal = $user->email ?? '—';
    $addressVal = $user->address ?? '—';
    $occupationVal = $user->occupation ?? '—';
    $employerVal = $user->employer ?? '—';

    $branchVal = $user->branch ?? '—';
    $regDateVal = optional($user->created_at)->format('Y-m-d') ?? '—';
    if ($regDateVal !== '—' && is_string($regDateVal)) {
        $regDateFormatted = \Carbon\Carbon::parse($regDateVal)->format('F j, Y');
    } else {
        $regDateFormatted = '—';
    }
@endphp

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
                    <span class="text-4xl font-extrabold text-white tracking-wide">{{ $displayInitials }}</span>
                  </div>
                @endif
            </div>

            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight">
                            {{ $displayName }}
                        </h1>
                        <div class="mt-2 flex flex-wrap items-center justify-center md:justify-start gap-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-mono text-sm font-bold bg-primary-50 dark:bg-primary-900/40 text-primary-800 dark:text-primary-200 border border-primary-200 dark:border-primary-800/60">
                                <i class="fa-solid fa-id-card mr-2 text-primary-500 text-xs"></i>
                                {{ $displayMemberNumber }}
                            </span>
                            <span class="badge {{ $statusActive ? 'badge-green' : 'badge-gray' }} inline-flex items-center gap-1.5 py-1.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusActive ? 'bg-primary-500 animate-pulse' : 'bg-gray-400' }}"></span>
                                {{ $status }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold border border-indigo-100 dark:border-indigo-800/50">
                                <i class="fa-solid fa-user-shield"></i>
                                Member Account
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('member.profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
                        <i class="fa-solid fa-pen text-[11px]"></i> Edit Profile
                    </a>
                </div>
                @if(session('hint') === 'missing_member_number')
                    <div class="mt-4 inline-flex items-start gap-2 p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 text-left max-w-lg">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-200">Member number not assigned</p>
                            <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">Your account is missing a member number. Please contact the administrator to complete your profile registration.</p>
                        </div>
                    </div>
                @endif
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
                    <p class="text-[11px] text-primary-500 dark:text-primary-400">Read-only profile details</p>
                </div>
            </div>
            <div class="divide-y divide-primary-50 dark:divide-primary-800/50">
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Full Name</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white break-words">{{ $fullNameVal }}</p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Gender</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $genderVal }}</p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Phone</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-phone text-primary-500 text-[11px]"></i>
                            {{ $phoneVal }}
                        </p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Email</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2 break-all">
                            <i class="fa-solid fa-envelope text-primary-500 text-[11px] flex-shrink-0"></i>
                            {{ $emailVal }}
                        </p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Address</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white break-words">{{ $addressVal }}</p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Occupation</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $occupationVal }}</p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Employer</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white break-words">{{ $employerVal }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass rounded-2xl overflow-hidden border border-primary-100 dark:border-dark-border">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-primary-100 dark:border-dark-border bg-primary-50/40 dark:bg-primary-900/20">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-leaf text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-primary-900 dark:text-white text-sm">MEMBERSHIP</h3>
                    <p class="text-[11px] text-primary-500 dark:text-primary-400">Account details</p>
                </div>
            </div>
            <div class="divide-y divide-primary-50 dark:divide-primary-800/50">
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Branch</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-primary-500 text-[11px]"></i>
                            {{ $branchVal }} Branch
                        </p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Registration Date</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-calendar-check text-primary-500 text-[11px]"></i>
                            {{ $regDateFormatted }}
                        </p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Status</p>
                        <span class="badge {{ $statusActive ? 'badge-green' : 'badge-gray' }} inline-flex items-center gap-1.5 py-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusActive ? 'bg-primary-500 animate-pulse' : 'bg-gray-400' }}"></span>
                            {{ $status }}
                        </span>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Member Number</p>
                        <span class="inline-flex items-center mt-0.5 px-3 py-1.5 rounded-lg font-mono text-sm font-bold bg-primary-50 dark:bg-primary-900/40 text-primary-800 dark:text-primary-200 border border-primary-200 dark:border-primary-800/60">
                            <i class="fa-solid fa-id-card mr-2 text-primary-500 text-xs"></i>
                            {{ $displayMemberNumber }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-5 bg-gradient-to-br from-primary-50/70 dark:from-primary-900/20 to-transparent border-t border-primary-100 dark:border-dark-border">
                <p class="text-[11px] uppercase tracking-wider font-bold text-primary-500 dark:text-primary-400 mb-3">Account Summary</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl bg-white/60 dark:bg-dark-card/60 border border-primary-100 dark:border-dark-border">
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-1">Savings</p>
                        <p class="text-sm font-extrabold text-primary-900 dark:text-white tabular-nums">
                            {{ isset($savingsBalance) ? fmtTsh($savingsBalance) : '—' }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/60 dark:bg-dark-card/60 border border-primary-100 dark:border-dark-border">
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-1">SWF Fund</p>
                        <p class="text-sm font-extrabold text-primary-900 dark:text-white tabular-nums">
                            {{ isset($swfBalance) ? fmtTsh($swfBalance) : '—' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl p-5 border border-primary-100 dark:border-dark-border flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                <i class="fa-solid fa-circle-info text-white"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-primary-900 dark:text-white">Need help with your account?</p>
                <p class="text-xs text-primary-600 dark:text-primary-400 mt-0.5">Download your statement or contact our support team for assistance.</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <a href="{{ route('member.statements.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-lg shadow-primary-500/25 transition-all hover:shadow-primary-500/40 hover:-translate-y-0.5"
               style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                Download Statement
            </a>
            <a href="mailto:admin@feedtan.co.tz?subject={{ urlencode('Profile Help - Member ' . $displayMemberNumber) }}&body={{ urlencode("Hello FEEDTAN Admin,\n\nI need assistance with my member account.\n\nMember Name: {$displayName}\nMember Number: {$displayMemberNumber}\n\n") }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-primary-700 dark:text-primary-200 bg-white dark:bg-dark-card border border-primary-200 dark:border-dark-border transition-all hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:-translate-y-0.5">
                <i class="fa-solid fa-envelope-open-text"></i>
                Contact Admin
            </a>
        </div>
    </div>

</div>

@endsection
