@extends('layouts.admin')

@section('breadcrumb', 'Communication / SMS')
@section('page_title', 'SMS')

@section('content')
<div class="space-y-6">
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center">
                <i class="fa-solid fa-message text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary-900 dark:text-white">SMS Messaging</h2>
                <p class="text-sm text-primary-500 dark:text-primary-400">Send SMS messages to members</p>
            </div>
        </div>

        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">SMS Integration Coming Soon</p>
                    <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">SMS messaging functionality will be available in a future update.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
