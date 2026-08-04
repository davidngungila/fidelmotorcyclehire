@extends('layouts.admin')

@section('breadcrumb', 'System › Settings › Test WhatsApp')
@section('page_title', 'Test WhatsApp Message')

@section('content')
<div class="space-y-6">
  <div class="glass p-6 rounded-2xl">
    <div class="flex items-center gap-3 mb-6">
      <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-500 flex items-center justify-center">
        <i class="fa-brands fa-whatsapp text-xl"></i>
      </div>
      <div>
        <h2 class="text-xl font-bold text-primary-900 dark:text-white">Test WhatsApp Message</h2>
        <p class="text-sm text-primary-500 dark:text-primary-400">Send a test WhatsApp message to verify configuration</p>
      </div>
    </div>

    @if(session('success'))
      <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
          <i class="fa-solid fa-circle-check text-green-600 dark:text-green-400 mt-0.5"></i>
          <div>
            <p class="text-sm font-semibold text-green-800 dark:text-green-200">{{ session('success') }}</p>
          </div>
        </div>
      </div>
    @endif

    @if(session('error'))
      <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
          <i class="fa-solid fa-circle-xmark text-red-600 dark:text-red-400 mt-0.5"></i>
          <div>
            <p class="text-sm font-semibold text-red-800 dark:text-red-200">{{ session('error') }}</p>
          </div>
        </div>
      </div>
    @endif

    <form x-data="{ loading: false }" @submit="loading = true" action="{{ route('admin.settings.test-whatsapp') }}" method="POST" class="space-y-6">
      @csrf

      <div>
        <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Phone Number</label>
        <input type="text" name="phone" required placeholder="+255123456789"
               class="w-full px-4 py-2.5 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Include country code (e.g., +255 for Tanzania)</p>
      </div>

      <div>
        <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Message</label>
        <textarea name="message" rows="4" required placeholder="Enter your test message here"
                  class="w-full px-4 py-2.5 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"></textarea>
      </div>

      <div class="flex items-center gap-3">
        <button type="submit" :disabled="loading"
                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold transition-all shadow-lg shadow-green-500/20 disabled:opacity-50 disabled:cursor-not-allowed">
          <i class="fa-brands fa-whatsapp mr-2"></i>
          <span x-show="!loading">Send Test Message</span>
          <span x-show="loading">Sending...</span>
        </button>
        <a href="{{ route('admin.settings.index') }}" class="px-6 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          Back to Settings
        </a>
      </div>
    </form>
  </div>

  <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
    <div class="flex items-start gap-3">
      <i class="fa-solid fa-circle-info text-blue-600 dark:text-blue-400 mt-0.5"></i>
      <div>
        <p class="text-sm font-semibold text-blue-800 dark:text-blue-200">Configuration Status</p>
        <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
          @if($whatsappSettings->session_api_key && $whatsappSettings->is_active)
            <span class="inline-flex items-center gap-1">
              <i class="fa-solid fa-check-circle"></i> WhatsApp is configured and active
            </span>
          @else
            <span class="inline-flex items-center gap-1">
              <i class="fa-solid fa-exclamation-triangle"></i> WhatsApp is not configured or inactive. Please configure WhatsApp settings first.
            </span>
          @endif
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
