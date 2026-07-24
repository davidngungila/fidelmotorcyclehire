@extends('layouts.member')

@section('breadcrumb', 'Notifications')
@section('page_title', 'My Notifications')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-primary-900 dark:text-white">Notifications</h2>
            <p class="text-sm text-primary-600 dark:text-primary-400">Stay updated with your account activities</p>
        </div>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('member.notifications.mark-all-read') }}" class="inline-flex">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white bg-gradient-to-br from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30 transition-all">
                    <i class="fa-solid fa-check-double text-xs"></i>
                    Mark All Read
                </button>
            </form>
        @endif
    </div>

    @if($unreadCount > 0)
        <div class="glass p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <i class="fa-solid fa-bell text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-blue-900 dark:text-blue-100">
                        You have {{ $unreadCount }} unread notification{{ $unreadCount !== 1 ? 's' : '' }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-3">
        @forelse($notifications as $notification)
            @php
                $isUnread = !($notification['read_at'] ?? false);
                $type = $notification['type'] ?? 'info';
                $typeConfig = match($type) {
                    'loan' => ['icon' => 'fa-hand-holding-dollar', 'color' => 'orange', 'label' => 'Loan'],
                    'savings' => ['icon' => 'fa-piggy-bank', 'color' => 'green', 'label' => 'Savings'],
                    'deposit' => ['icon' => 'fa-certificate', 'color' => 'blue', 'label' => 'Deposit'],
                    'swf' => ['icon' => 'fa-shield-heart', 'color' => 'purple', 'label' => 'SWF'],
                    'investment' => ['icon' => 'fa-chart-line', 'color' => 'teal', 'label' => 'Investment'],
                    'system' => ['icon' => 'fa-gear', 'color' => 'gray', 'label' => 'System'],
                    default => ['icon' => 'fa-circle-info', 'color' => 'blue', 'label' => 'Info'],
                };
                $icon = $typeConfig['icon'];
                $color = $typeConfig['color'];
                $label = $typeConfig['label'];
            @endphp
            <div class="glass p-5 rounded-xl border-l-4 {{ $isUnread ? 'border-l-primary-500 bg-primary-50/50 dark:bg-primary-900/20' : 'border-l-transparent' }} hover:shadow-md transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-{{ $color }}-50 dark:bg-{{ $color }}-900/30 text-{{ $color }}-500 dark:text-{{ $color }}-400 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 mb-1">
                            <div>
                                <span class="badge badge-{{ $color }} text-[10px] mb-1">{{ $label }}</span>
                                <h3 class="font-bold text-primary-900 dark:text-white text-sm">
                                    {{ $notification['title'] ?? 'Notification' }}
                                </h3>
                            </div>
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-primary-500 flex-shrink-0 mt-2"></span>
                            @endif
                        </div>
                        <p class="text-sm text-primary-700 dark:text-primary-300 mb-2">
                            {{ $notification['message'] ?? '' }}
                        </p>
                        <div class="flex items-center gap-3 text-[11px] text-primary-500 dark:text-primary-400">
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-clock text-[10px]"></i>
                                {{ \Carbon\Carbon::parse($notification['created_at'] ?? now())->diffForHumans() }}
                            </span>
                            @if($notification['created_at'] ?? null)
                                <span>
                                    {{ \Carbon\Carbon::parse($notification['created_at'])->format('M j, Y g:i A') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass p-10 rounded-2xl text-center">
                <div class="w-16 h-16 rounded-2xl bg-primary-50 dark:bg-primary-900/20 text-primary-400 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-bell-slash text-2xl"></i>
                </div>
                <h3 class="font-bold text-primary-900 dark:text-white text-lg mb-2">No notifications</h3>
                <p class="text-sm text-primary-600 dark:text-primary-400 max-w-md mx-auto">
                    You're all caught up! We'll notify you here about important account updates and announcements.
                </p>
            </div>
        @endforelse
    </div>

</div>

@endsection
