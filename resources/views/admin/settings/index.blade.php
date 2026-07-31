@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Settings')
@section('page_title', 'System Settings')

@section('content')

<div x-data="settingsTabs()" class="space-y-6">

  <div class="flex items-center justify-between">
    <p class="text-sm" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
      Configure application behavior, notifications, integrations and security
    </p>
  </div>

  <div class="glass overflow-hidden">

    <div class="flex flex-col sm:flex-row border-b border-primary-100 dark:border-primary-900/50">
      <template x-for="(tab, idx) in tabs" :key="tab.key">
        <button @click="activeTab = tab.key"
                class="flex items-center gap-2.5 px-5 py-3.5 text-xs font-bold uppercase tracking-wider whitespace-nowrap transition-all relative
                       border-b-2 sm:border-b-0 sm:border-r-0 border-transparent"
                :class="activeTab === tab.key
                    ? (darkMode ? 'text-primary-300 border-primary-500 bg-primary-900/30' : 'text-primary-700 border-primary-500 bg-primary-50')
                    : (darkMode ? 'text-primary-500 hover:text-primary-300 hover:bg-primary-900/20' : 'text-primary-500 hover:text-primary-700 hover:bg-primary-50/50')">
          <i :class="tab.icon" class="text-[12px]"></i>
          <span x-text="tab.label"></span>
        </button>
      </template>
    </div>

    <div class="p-6 lg:p-8">

      <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
          @csrf
          @method('PUT')
          <input type="hidden" name="tab" value="general">

          <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-xl shadow-md">
              <i class="fa-solid fa-gear"></i>
            </div>
            <div>
              <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">General Settings</h3>
              <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Core application configuration</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Application Name</label>
              <input type="text" name="app_name" value="{{ $generalSettings['app_name'] }}"
                     class="form-input" placeholder="e.g. FEEDTAN DIGITAL">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Support Email</label>
              <input type="email" name="support_email" value="{{ $generalSettings['support_email'] }}"
                     class="form-input" placeholder="support@example.com">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Default Currency</label>
              <input type="text" name="currency" value="{{ $generalSettings['currency'] }}"
                     class="form-input font-mono" placeholder="TSh">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Timezone</label>
              <select name="timezone" class="form-input">
                <option value="Africa/Dar_es_Salaam" {{ $generalSettings['timezone'] === 'Africa/Dar_es_Salaam' ? 'selected' : '' }}>Africa/Dar es Salaam (GMT+3)</option>
                <option value="Africa/Nairobi" {{ $generalSettings['timezone'] === 'Africa/Nairobi' ? 'selected' : '' }}>Africa/Nairobi (GMT+3)</option>
                <option value="Africa/Johannesburg" {{ $generalSettings['timezone'] === 'Africa/Johannesburg' ? 'selected' : '' }}>Africa/Johannesburg (GMT+2)</option>
                <option value="UTC" {{ $generalSettings['timezone'] === 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
              </select>
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Default Branch</label>
              <select name="default_branch" class="form-input">
                <option value="Dar es Salaam" {{ $generalSettings['default_branch'] === 'Dar es Salaam' ? 'selected' : '' }}>Dar es Salaam</option>
                <option value="Arusha" {{ $generalSettings['default_branch'] === 'Arusha' ? 'selected' : '' }}>Arusha</option>
                <option value="Mwanza" {{ $generalSettings['default_branch'] === 'Mwanza' ? 'selected' : '' }}>Mwanza</option>
                <option value="Dodoma" {{ $generalSettings['default_branch'] === 'Dodoma' ? 'selected' : '' }}>Dodoma</option>
                <option value="Mbeya" {{ $generalSettings['default_branch'] === 'Mbeya' ? 'selected' : '' }}>Mbeya</option>
                <option value="Tanga" {{ $generalSettings['default_branch'] === 'Tanga' ? 'selected' : '' }}>Tanga</option>
              </select>
            </div>

            <div class="md:col-span-2">
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Date Format</label>
              <select name="date_format" class="form-input">
                <option value="d/m/Y" {{ $generalSettings['date_format'] === 'd/m/Y' ? 'selected' : '' }}>24/07/2026 (d/m/Y)</option>
                <option value="m/d/Y" {{ $generalSettings['date_format'] === 'm/d/Y' ? 'selected' : '' }}>07/24/2026 (m/d/Y)</option>
                <option value="Y-m-d" {{ $generalSettings['date_format'] === 'Y-m-d' ? 'selected' : '' }}>2026-07-24 (Y-m-d)</option>
                <option value="d M Y" {{ $generalSettings['date_format'] === 'd M Y' ? 'selected' : '' }}>24 Jul 2026 (d M Y)</option>
              </select>
            </div>
          </div>

          <div class="pt-6 mt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
              <i class="fa-solid fa-floppy-disk mr-1.5 text-[13px]"></i> Save General Settings
            </button>
          </div>
        </form>
      </div>

      <div x-show="activeTab === 'notifications'" style="display:none"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
          @csrf
          @method('PUT')
          <input type="hidden" name="tab" value="notifications">

          <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center text-xl shadow-md">
              <i class="fa-solid fa-bell"></i>
            </div>
            <div>
              <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">Notifications</h3>
              <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Configure how users receive alerts</p>
            </div>
          </div>

          <div class="space-y-3">
            @php
              $notifItems = [
                ['key' => 'email_notifications', 'label' => 'Email Notifications', 'desc' => 'Send alerts via email', 'icon' => 'fa-envelope'],
                ['key' => 'sms_notifications', 'label' => 'SMS Notifications', 'desc' => 'Send transaction alerts via SMS', 'icon' => 'fa-sms'],
                ['key' => 'push_notifications', 'label' => 'Push Notifications', 'desc' => 'Browser push notifications', 'icon' => 'fa-bell-on'],
                ['key' => 'loan_alerts', 'label' => 'Loan Alerts', 'desc' => 'Updates on loan applications and repayments', 'icon' => 'fa-hand-holding-dollar'],
                ['key' => 'savings_alerts', 'label' => 'Savings Alerts', 'desc' => 'Deposits, withdrawals and interest updates', 'icon' => 'fa-piggy-bank'],
                ['key' => 'investment_alerts', 'label' => 'Investment Alerts', 'desc' => 'Portfolio performance and maturity alerts', 'icon' => 'fa-chart-line'],
                ['key' => 'weekly_report', 'label' => 'Weekly Report Summary', 'desc' => 'Automated weekly digest to admins', 'icon' => 'fa-calendar-week'],
                ['key' => 'monthly_report', 'label' => 'Monthly Report Summary', 'desc' => 'Automated monthly financial digest', 'icon' => 'fa-calendar-days'],
              ];
            @endphp

            @foreach($notifItems as $item)
              <div class="flex items-center justify-between p-4 rounded-xl border border-primary-100 dark:border-primary-900/50 bg-primary-50/50 dark:bg-primary-900/20">
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 rounded-xl bg-white dark:bg-primary-900/50 border border-primary-100 dark:border-primary-800/50 flex items-center justify-center text-primary-500">
                    <i class="fa-solid {{ $item['icon'] }} text-sm"></i>
                  </div>
                  <div>
                    <h4 class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">{{ $item['label'] }}</h4>
                    <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">{{ $item['desc'] }}</p>
                  </div>
                </div>
                <label x-data="{ on: {{ $notificationSettings[$item['key']] ? 'true' : 'false' }} }"
                       class="relative inline-flex items-center cursor-pointer">
                  <input type="hidden" :value="on ? 1 : 0" name="{{ $item['key'] }}">
                  <input type="checkbox" class="sr-only peer" :checked="on" @change="on = !on; $el.previousElementSibling.value = on ? 1 : 0">
                  <div class="w-12 h-7 rounded-full transition-colors peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-200 dark:peer-focus:ring-primary-800
                              bg-gray-200 dark:bg-gray-700 peer-checked:bg-primary-600">
                  </div>
                  <div class="absolute left-0.5 top-0.5 w-6 h-6 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5 flex items-center justify-center">
                    <i :class="on ? 'fa-solid fa-check text-primary-600' : 'fa-solid fa-xmark text-gray-400'" class="text-[10px]"></i>
                  </div>
                </label>
              </div>
            @endforeach
          </div>

          <div class="pt-6 mt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
              <i class="fa-solid fa-floppy-disk mr-1.5 text-[13px]"></i> Save Notification Settings
            </button>
          </div>
        </form>
      </div>

      <div x-show="activeTab === 'google_sheets'" style="display:none"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
          @csrf
          @method('PUT')
          <input type="hidden" name="tab" value="google_sheets">

          <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-lime-400 to-green-600 text-white flex items-center justify-center text-xl shadow-md">
              <i class="fa-brands fa-google"></i>
            </div>
            <div>
              <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">Google Sheets Integration</h3>
              <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Connect and configure Google Sheets data source</p>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-5">
            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Spreadsheet ID</label>
              <div class="relative">
                <i class="fa-brands fa-google absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
                <input type="text" name="spreadsheet_id" value="{{ $googleSheetsSettings['spreadsheet_id'] }}"
                       placeholder="1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms"
                       class="form-input pl-9 font-mono text-xs">
              </div>
              <p class="mt-1.5 text-[11px]" :class="darkMode ? 'text-primary-500' : 'text-primary-500'">
                <i class="fa-solid fa-circle-info mr-1 text-[10px]"></i>
                Find this in your Google Sheets URL between /d/ and /edit
              </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider mb-2" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Auto Sync</label>
                <label x-data="{ on: {{ $googleSheetsSettings['auto_sync'] ? 'true' : 'false' }} }"
                       class="relative inline-flex items-center cursor-pointer">
                  <input type="hidden" :value="on ? 1 : 0" name="auto_sync">
                  <input type="checkbox" class="sr-only peer" :checked="on" @change="on = !on; $el.previousElementSibling.value = on ? 1 : 0">
                  <div class="w-12 h-7 rounded-full transition-colors bg-gray-200 dark:bg-gray-700 peer-checked:bg-primary-600"></div>
                  <div class="absolute left-0.5 top-0.5 w-6 h-6 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5 flex items-center justify-center">
                    <i :class="on ? 'fa-solid fa-check text-primary-600' : 'fa-solid fa-xmark text-gray-400'" class="text-[10px]"></i>
                  </div>
                </label>
              </div>

              <div>
                <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Sync Interval (minutes)</label>
                <input type="number" name="sync_interval" value="{{ $googleSheetsSettings['sync_interval'] }}"
                       min="5" max="1440" class="form-input" placeholder="30">
              </div>
            </div>

            <div class="p-5 rounded-2xl border-2 border-dashed border-primary-200 dark:border-primary-900/50 bg-primary-50/30 dark:bg-primary-900/10">
              <div class="flex flex-col items-center text-center gap-3 py-4">
                <div class="w-16 h-16 rounded-2xl bg-white dark:bg-primary-900/40 border border-primary-100 dark:border-primary-800 flex items-center justify-center text-primary-400">
                  <i class="fa-solid fa-key text-2xl"></i>
                </div>
                <div>
                  <h4 class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">Service Account Credentials</h4>
                  <p class="text-xs mt-1 max-w-sm" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Upload your Google Cloud service account JSON key file to enable read/write access to your spreadsheet.</p>
                </div>
                <label class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold cursor-pointer transition-all active:scale-95">
                  <i class="fa-solid fa-cloud-arrow-up text-[12px]"></i>
                  Upload JSON Key
                  <input type="file" accept=".json" class="hidden">
                </label>
              </div>
            </div>
          </div>

          <div class="pt-6 mt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end gap-3">
            <a href="{{ route('admin.google-sheets.index') }}"
               class="px-5 py-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-colors">
              <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-[12px]"></i> Open Sync Dashboard
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
              <i class="fa-solid fa-floppy-disk mr-1.5 text-[13px]"></i> Save Sheets Config
            </button>
          </div>
        </form>
      </div>

      <div x-show="activeTab === 'security'" style="display:none"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
          @csrf
          @method('PUT')
          <input type="hidden" name="tab" value="security">

          <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-400 to-rose-600 text-white flex items-center justify-center text-xl shadow-md">
              <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
              <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">Security Settings</h3>
              <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Authentication, password and session hardening</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Password Expiry (days)</label>
              <input type="number" name="password_expiry_days" value="{{ $securitySettings['password_expiry_days'] }}"
                     min="0" max="365" class="form-input" placeholder="90">
              <p class="mt-1 text-[11px]" :class="darkMode ? 'text-primary-500' : 'text-primary-500'">Set to 0 to disable expiry</p>
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Session Timeout (minutes)</label>
              <input type="number" name="session_timeout_minutes" value="{{ $securitySettings['session_timeout_minutes'] }}"
                     min="5" max="1440" class="form-input" placeholder="60">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Max Login Attempts</label>
              <input type="number" name="login_attempts" value="{{ $securitySettings['login_attempts'] }}"
                     min="1" max="20" class="form-input" placeholder="5">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Lockout Duration (minutes)</label>
              <input type="number" name="lockout_minutes" value="{{ $securitySettings['lockout_minutes'] }}"
                     min="1" max="1440" class="form-input" placeholder="30">
            </div>
          </div>

          <div class="space-y-3 mt-4">
            <div class="flex items-center justify-between p-4 rounded-xl border border-primary-100 dark:border-primary-900/50 bg-primary-50/50 dark:bg-primary-900/20">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white dark:bg-primary-900/50 border border-primary-100 dark:border-primary-800/50 flex items-center justify-center text-primary-500">
                  <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <div>
                  <h4 class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">Require Strong Passwords</h4>
                  <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Min 8 chars, mixed case, numbers, and symbols</p>
                </div>
              </div>
              <label x-data="{ on: {{ $securitySettings['require_strong_password'] ? 'true' : 'false' }} }"
                     class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" :value="on ? 1 : 0" name="require_strong_password">
                <input type="checkbox" class="sr-only peer" :checked="on" @change="on = !on; $el.previousElementSibling.value = on ? 1 : 0">
                <div class="w-12 h-7 rounded-full transition-colors bg-gray-200 dark:bg-gray-700 peer-checked:bg-primary-600"></div>
                <div class="absolute left-0.5 top-0.5 w-6 h-6 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5 flex items-center justify-center">
                  <i :class="on ? 'fa-solid fa-check text-primary-600' : 'fa-solid fa-xmark text-gray-400'" class="text-[10px]"></i>
                </div>
              </label>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl border border-primary-100 dark:border-primary-900/50 bg-primary-50/50 dark:bg-primary-900/20">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white dark:bg-primary-900/50 border border-primary-100 dark:border-primary-800/50 flex items-center justify-center text-primary-500">
                  <i class="fa-solid fa-mobile-screen text-sm"></i>
                </div>
                <div>
                  <h4 class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">Two-Factor Authentication (2FA)</h4>
                  <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Require OTP for admin logins via authenticator app</p>
                </div>
              </div>
              <label x-data="{ on: {{ $securitySettings['two_factor_enabled'] ? 'true' : 'false' }} }"
                     class="relative inline-flex items-center cursor-pointer">
                <input type="hidden" :value="on ? 1 : 0" name="two_factor_enabled">
                <input type="checkbox" class="sr-only peer" :checked="on" @change="on = !on; $el.previousElementSibling.value = on ? 1 : 0">
                <div class="w-12 h-7 rounded-full transition-colors bg-gray-200 dark:bg-gray-700 peer-checked:bg-primary-600"></div>
                <div class="absolute left-0.5 top-0.5 w-6 h-6 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5 flex items-center justify-center">
                  <i :class="on ? 'fa-solid fa-check text-primary-600' : 'fa-solid fa-xmark text-gray-400'" class="text-[10px]"></i>
                </div>
              </label>
            </div>
          </div>

          <div class="pt-6 mt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
              <i class="fa-solid fa-shield-halved mr-1.5 text-[13px]"></i> Save Security Settings
            </button>
          </div>
        </form>
      </div>

      <div x-show="activeTab === 'email'" style="display:none"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
          @csrf
          @method('PUT')
          <input type="hidden" name="tab" value="email">

          <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-400 to-purple-600 text-white flex items-center justify-center text-xl shadow-md">
              <i class="fa-solid fa-envelope"></i>
            </div>
            <div>
              <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">Email Configuration</h3>
              <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">SMTP settings for password reset and notifications</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Mail Driver</label>
              <select name="mail_driver" class="form-input">
                <option value="smtp" {{ $emailSettings->mail_driver === 'smtp' ? 'selected' : '' }}>SMTP</option>
                <option value="mailgun" {{ $emailSettings->mail_driver === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                <option value="ses" {{ $emailSettings->mail_driver === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                <option value="sendmail" {{ $emailSettings->mail_driver === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
              </select>
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Mail Host</label>
              <input type="text" name="mail_host" value="{{ $emailSettings->mail_host ?? 'smtp.mailtrap.io' }}"
                     class="form-input" placeholder="smtp.mailtrap.io">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Mail Port</label>
              <input type="number" name="mail_port" value="{{ $emailSettings->mail_port ?? 2525 }}"
                     class="form-input" placeholder="2525">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Encryption</label>
              <select name="mail_encryption" class="form-input">
                <option value="tls" {{ $emailSettings->mail_encryption === 'tls' ? 'selected' : '' }}>TLS</option>
                <option value="ssl" {{ $emailSettings->mail_encryption === 'ssl' ? 'selected' : '' }}>SSL</option>
                <option value="null" {{ $emailSettings->mail_encryption === 'null' || !$emailSettings->mail_encryption ? 'selected' : '' }}>None</option>
              </select>
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Username</label>
              <input type="text" name="mail_username" value="{{ $emailSettings->mail_username ?? '' }}"
                     class="form-input" placeholder="SMTP username">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Password</label>
              <input type="password" name="mail_password" value="{{ $emailSettings->mail_password ?? '' }}"
                     class="form-input" placeholder="SMTP password">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">From Address</label>
              <input type="email" name="mail_from_address" value="{{ $emailSettings->mail_from_address ?? 'noreply@example.com' }}"
                     class="form-input" placeholder="noreply@example.com">
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">From Name</label>
              <input type="text" name="mail_from_name" value="{{ $emailSettings->mail_from_name ?? 'Member Portal' }}"
                     class="form-input" placeholder="Member Portal">
            </div>
          </div>

          <div class="flex items-center justify-between p-4 rounded-xl border border-primary-100 dark:border-primary-900/50 bg-primary-50/50 dark:bg-primary-900/20 mt-4">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-white dark:bg-primary-900/50 border border-primary-100 dark:border-primary-800/50 flex items-center justify-center text-primary-500">
                <i class="fa-solid fa-power-off text-sm"></i>
              </div>
              <div>
                <h4 class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'">Enable Email Service</h4>
                <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Activate email sending for password reset and notifications</p>
              </div>
            </div>
            <label x-data="{ on: {{ $emailSettings->is_active ? 'true' : 'false' }} }"
                   class="relative inline-flex items-center cursor-pointer">
              <input type="hidden" :value="on ? 1 : 0" name="is_active">
              <input type="checkbox" class="sr-only peer" :checked="on" @change="on = !on; $el.previousElementSibling.value = on ? 1 : 0">
              <div class="w-12 h-7 rounded-full transition-colors bg-gray-200 dark:bg-gray-700 peer-checked:bg-primary-600"></div>
              <div class="absolute left-0.5 top-0.5 w-6 h-6 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5 flex items-center justify-center">
                <i :class="on ? 'fa-solid fa-check text-primary-600' : 'fa-solid fa-xmark text-gray-400'" class="text-[10px]"></i>
              </div>
            </label>
          </div>

          <div class="pt-6 mt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
              <i class="fa-solid fa-envelope mr-1.5 text-[13px]"></i> Save Email Settings
            </button>
          </div>
        </form>
      </div>

      <div x-show="activeTab === 'sms'" x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6" x-data="{ on: {{ $smsSettings->is_active ?? false }}}">
          @csrf
          @method('PUT')
          <input type="hidden" name="tab" value="sms">

          <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 text-white flex items-center justify-center text-xl shadow-md">
              <i class="fa-solid fa-comment-sms"></i>
            </div>
            <div>
              <h3 class="font-bold text-lg" :class="darkMode ? 'text-white' : 'text-primary-900'">SMS Configuration</h3>
              <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Configure SMS gateway for notifications</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">API Token</label>
              <input type="text" name="sms_api_token" value="{{ $smsSettings->api_token ?? '' }}"
                     class="form-input" placeholder="Enter your Messaging Service API token">
              <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'>Get your API token from Messaging Service dashboard</p>
            </div>

            <div>
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Sender ID</label>
              <input type="text" name="sms_sender_id" value="{{ $smsSettings->sender_id ?? 'FEEDTAN' }}"
                     class="form-input" placeholder="e.g. FEEDTAN">
              <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Your registered sender name</p>
            </div>
          </div>

          <div class="flex items-center justify-between p-4 rounded-xl bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-900/50">
            <div>
              <p class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-primary-900'">Enable SMS Notifications</p>
              <p class="text-xs mt-0.5" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Allow sending SMS alerts to members</p>
            </div>
            <div class="relative">
              <input type="hidden" name="sms_is_active" :value="on ? 1 : 0">
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" class="sr-only peer" :checked="on" @change="on = !on; $el.previousElementSibling.value = on ? 1 : 0">
                <div class="w-12 h-7 rounded-full transition-colors bg-gray-200 dark:bg-gray-700 peer-checked:bg-green-600"></div>
                <div class="absolute left-0.5 top-0.5 w-6 h-6 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5 flex items-center justify-center">
                  <i :class="on ? 'fa-solid fa-check text-green-600' : 'fa-solid fa-xmark text-gray-400'" class="text-[10px]"></i>
                </div>
              </label>
            </div>
          </div>

          <div class="pt-6 mt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-green-600 hover:bg-green-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
              <i class="fa-solid fa-comment-sms mr-1.5 text-[13px]"></i> Save SMS Settings
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function settingsTabs() {
    return {
      activeTab: 'general',
      tabs: [
        { key: 'general', label: 'General', icon: 'fa-solid fa-gear' },
        { key: 'notifications', label: 'Notifications', icon: 'fa-solid fa-bell' },
        { key: 'google_sheets', label: 'Google Sheets', icon: 'fa-brands fa-google' },
        { key: 'security', label: 'Security', icon: 'fa-solid fa-shield-halved' },
        { key: 'email', label: 'Email', icon: 'fa-solid fa-envelope' },
        { key: 'sms', label: 'SMS', icon: 'fa-solid fa-comment-sms' },
      ]
    }
  }
</script>
@endpush
