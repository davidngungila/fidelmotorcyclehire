@extends('layouts.admin')

@section('breadcrumb', 'Notifications')
@section('page_title', 'Admin Notifications')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-primary-900 dark:text-white">Notifications</h2>
            <p class="text-sm text-primary-600 dark:text-primary-400">Stay updated with system activities and announcements</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.notifications.create') }}"
                    class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 inline-flex items-center gap-2">
                <i class="fa-solid fa-plus text-[12px]"></i>
                <span>Create</span>
            </a>
            <span class="badge badge-blue text-xs">Total: {{ count($notifications) }}</span>
            @if($unreadCount > 0)
                <span class="badge badge-green text-xs">{{ $unreadCount }} Unread</span>
            @endif
        </div>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass p-4 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 flex items-center justify-center">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ $announcementCount }}</p>
                    <p class="text-xs text-primary-600 dark:text-primary-400">Announcements</p>
                </div>
            </div>
        </div>
        <div class="glass p-4 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/30 text-purple-500 dark:text-purple-400 flex items-center justify-center">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ $systemCount }}</p>
                    <p class="text-xs text-primary-600 dark:text-primary-400">System Alerts</p>
                </div>
            </div>
        </div>
        <div class="glass p-4 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-500 dark:text-green-400 flex items-center justify-center">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-primary-900 dark:text-white">{{ count($notifications) - $unreadCount }}</p>
                    <p class="text-xs text-primary-600 dark:text-primary-400">Read</p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-3">
        @forelse($notifications as $notification)
            @php
                $isUnread = !$notification->is_read;
                $category = $notification->category ?? 'general';
                $priority = $notification->priority ?? 'normal';
                $categoryConfig = match($category) {
                    'announcement' => ['icon' => 'fa-bullhorn', 'color' => 'blue', 'label' => 'Announcement'],
                    'system' => ['icon' => 'fa-gear', 'color' => 'purple', 'label' => 'System'],
                    'alert' => ['icon' => 'fa-triangle-exclamation', 'color' => 'red', 'label' => 'Alert'],
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
                                    {{ $notification->title }}
                                </h3>
                            </div>
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-{{ $borderColor }}-500 flex-shrink-0 mt-2"></span>
                            @endif
                        </div>
                        <p class="text-sm text-primary-700 dark:text-primary-300 mb-2">
                            {{ $notification->message }}
                        </p>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 text-[11px] text-primary-500 dark:text-primary-400">
                                <span class="flex items-center gap-1">
                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                <span>
                                    {{ $notification->created_at->format('M j, Y g:i A') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($isUnread)
                                    <button onclick="markAsRead({{ $notification->id }})"
                                            class="text-[11px] text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
                                        Mark as read
                                    </button>
                                @endif
                                <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
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
                    You're all caught up! We'll notify you here about important system updates and announcements.
                </p>
            </div>
        @endforelse
    </div>

</div>

@endsection

@push('scripts')
<script>
function markAsRead(id) {
    fetch('{{ route('admin.notifications.mark-read', ':id') }}'.replace(':id', id), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endpush
