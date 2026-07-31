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
        @forelse($displayNotifications as $notification)
            @php
                $isUnread = $notification['is_unread'] ?? false;
                $category = $notification['category'] ?? 'general';
                $priority = $notification['priority'] ?? 'normal';
                $categoryConfig = match($category) {
                    'announcement' => ['icon' => 'fa-bullhorn', 'color' => 'blue', 'label' => 'Announcement'],
                    'loan' => ['icon' => 'fa-hand-holding-dollar', 'color' => 'orange', 'label' => 'Loan'],
                    'general' => ['icon' => 'fa-circle-info', 'color' => 'green', 'label' => 'General'],
                    default => ['icon' => 'fa-circle-info', 'color' => 'blue', 'label' => 'Info'],
                };
                $priorityConfig = match($priority) {
                    'urgent' => ['border' => 'red', 'bg' => 'red'],
                    'high' => ['border' => 'orange', 'bg' => 'orange'],
                    'normal' => ['border' => 'primary', 'bg' => 'primary'],
                    'low' => ['border' => 'gray', 'bg' => 'gray'],
                    default => ['border' => 'primary', 'bg' => 'primary'],
                };
                $icon = $categoryConfig['icon'];
                $color = $categoryConfig['color'];
                $label = $categoryConfig['label'];
                $borderColor = $priorityConfig['border'];
                $bgColor = $priorityConfig['bg'];
            @endphp
            <div class="glass p-5 rounded-xl border-l-4 {{ $isUnread ? 'border-l-' . $borderColor . '-500 bg-' . $bgColor . '-50/50 dark:bg-' . $bgColor . '-900/20' : 'border-l-transparent' }} hover:shadow-md transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg bg-{{ $color }}-50 dark:bg-{{ $color }}-900/30 text-{{ $color }}-500 dark:text-{{ $color }}-400 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 mb-1">
                            <div>
                                <span class="badge badge-{{ $color }} text-[10px] mb-1">{{ $label }}</span>
                                @if($priority === 'urgent')
                                    <span class="badge badge-red text-[10px] mb-1 ml-1">Urgent</span>
                                @elseif($priority === 'high')
                                    <span class="badge badge-orange text-[10px] mb-1 ml-1">High</span>
                                @endif
                                <h3 class="font-bold text-primary-900 dark:text-white text-sm">
                                    {{ $notification['title'] ?? 'Notification' }}
                                </h3>
                            </div>
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-{{ $borderColor }}-500 flex-shrink-0 mt-2"></span>
                            @endif
                        </div>
                        <p class="text-sm text-primary-700 dark:text-primary-300 mb-2">
                            {{ $notification['message'] ?? '' }}
                        </p>
                        <div class="flex items-center gap-3 text-[11px] text-primary-500 dark:text-primary-400">
                            <span class="flex items-center gap-1">
                                <i class="fa-solid fa-clock text-[10px]"></i>
                                {{ \Carbon\Carbon::parse($notification['date'] ?? now())->diffForHumans() }}
                            </span>
                            @if($notification['date'] ?? null)
                                <span>
                                    {{ \Carbon\Carbon::parse($notification['date'])->format('M j, Y g:i A') }}
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
