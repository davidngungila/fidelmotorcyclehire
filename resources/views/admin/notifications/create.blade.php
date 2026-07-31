@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Notifications \u203A Create')
@section('page_title', 'Create Notification')

@section('content')

<div class="space-y-6">

  <div class="flex items-center justify-between">
    <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to Notifications</span>
    </a>
  </div>

  <div class="glass overflow-hidden">
    <div class="p-6 lg:p-8">
      
      <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center text-2xl shadow-md">
          <i class="fa-solid fa-bell"></i>
        </div>
        <div>
          <h2 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Create New Notification</h2>
          <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Send announcements, system alerts, or important updates</p>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.notifications.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Category</label>
            <select name="category" class="form-input" required>
              <option value="">Select category</option>
              <option value="announcement">Announcement</option>
              <option value="system">System</option>
              <option value="alert">Alert</option>
            </select>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Choose the type of notification</p>
          </div>

          <div>
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Priority</label>
            <select name="priority" class="form-input" required>
              <option value="">Select priority</option>
              <option value="urgent">Urgent</option>
              <option value="high">High</option>
              <option value="normal">Normal</option>
              <option value="low">Low</option>
            </select>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Set the urgency level</p>
          </div>
        </div>

        <div>
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Title</label>
          <input type="text" name="title" class="form-input" placeholder="Enter notification title" required maxlength="255">
          <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Maximum 255 characters</p>
        </div>

        <div>
          <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Message</label>
          <textarea name="message" rows="5" class="form-input" placeholder="Enter the notification message" required></textarea>
          <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Detailed message content</p>
        </div>

        <div class="pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end gap-3">
          <a href="{{ route('admin.notifications.index') }}"
                  class="px-6 py-2.5 rounded-xl border border-primary-200 dark:border-primary-700 text-primary-700 dark:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/30 text-sm font-bold transition-all">
            Cancel
          </a>
          <button type="submit"
                  class="px-6 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
            <i class="fa-solid fa-paper-plane mr-1.5 text-[13px]"></i> Post Notification
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection
