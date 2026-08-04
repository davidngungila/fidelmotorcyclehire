@extends('layouts.admin')

@section('breadcrumb', 'Communication › WhatsApp')
@section('page_title', 'WhatsApp Communication')

@section('content')
<div class="space-y-6">

  <!-- Status Messages -->
  @if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
      <div class="flex items-start gap-3">
        <i class="fa-solid fa-circle-check text-green-600 dark:text-green-400 mt-0.5"></i>
        <p class="text-sm font-semibold text-green-800 dark:text-green-200">{{ session('success') }}</p>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
      <div class="flex items-start gap-3">
        <i class="fa-solid fa-circle-xmark text-red-600 dark:text-red-400 mt-0.5"></i>
        <p class="text-sm font-semibold text-red-800 dark:text-red-200">{{ session('error') }}</p>
      </div>
    </div>
  @endif

  <!-- Personal Access Token Section -->
  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
        <i class="fa-solid fa-key"></i>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-primary-900 dark:text-white">Personal Access Token</h3>
        <p class="text-xs text-primary-500 dark:text-primary-400">Required for account-level operations</p>
      </div>
    </div>

    @if($settings && $settings->personal_access_token)
      <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3 mb-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-check-circle text-green-600 dark:text-green-400"></i>
            <span class="text-sm font-medium text-green-800 dark:text-green-200">Token configured</span>
          </div>
          <form action="{{ route('admin.communication.whatsapp.personal-token') }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">Update Token</button>
          </form>
        </div>
      </div>
    @endif

    <form action="{{ route('admin.communication.whatsapp.personal-token') }}" method="POST">
      @csrf
      @if($settings && $settings->personal_access_token)
        @method('PUT')
      @endif
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Personal Access Token</label>
          <input type="password" name="personal_access_token" value="{{ $settings->personal_access_token ?? '' }}" 
                 placeholder="Enter your Personal Access Token from Wasender dashboard"
                 class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Get your token from Settings > Personal Access Token in Wasender dashboard</p>
        </div>
        <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold transition-all">
          {{ $settings && $settings->personal_access_token ? 'Update Token' : 'Save Token' }}
        </button>
      </div>
    </form>
  </div>

  <!-- Sessions Section -->
  @if($settings && $settings->personal_access_token)
  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center">
        <i class="fa-brands fa-whatsapp"></i>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-primary-900 dark:text-white">WhatsApp Sessions</h3>
        <p class="text-xs text-primary-500 dark:text-primary-400">Manage your WhatsApp sessions</p>
      </div>
    </div>

    <!-- Create Session Form -->
    <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg p-4 mb-4">
      <h4 class="text-sm font-semibold text-primary-900 dark:text-white mb-3">Create New Session</h4>
      <form action="{{ route('admin.communication.whatsapp.session') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session Name</label>
            <input type="text" name="name" required placeholder="e.g., Business WhatsApp"
                   class="w-full px-3 py-2 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
            <input type="text" name="phone_number" required placeholder="+1234567890"
                   class="w-full px-3 py-2 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
          </div>
        </div>
        <button type="submit" class="mt-3 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition-all">
          <i class="fa-solid fa-plus mr-2"></i>Create Session
        </button>
      </form>
    </div>

    <!-- Sessions List -->
    @if($sessions)
      <div class="space-y-3">
        @foreach($sessions as $session)
          <div class="border border-primary-200 dark:border-primary-800 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full {{ $session['status'] === 'connected' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-gray-100 dark:bg-gray-700' }} flex items-center justify-center">
                  <i class="fa-brands fa-whatsapp {{ $session['status'] === 'connected' ? 'text-green-600 dark:text-green-400' : 'text-gray-500' }}"></i>
                </div>
                <div>
                  <p class="text-sm font-semibold text-primary-900 dark:text-white">{{ $session['name'] }}</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ $session['phone_number'] }}</p>
                </div>
              </div>
              <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $session['status'] === 'connected' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                {{ ucfirst($session['status']) }}
              </span>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No sessions found. Create a new session to get started.</p>
    @endif
  </div>
  @endif

  <!-- Session API Key Section -->
  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
        <i class="fa-solid fa-link"></i>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-primary-900 dark:text-white">Session API Key</h3>
        <p class="text-xs text-primary-500 dark:text-primary-400">Required for sending messages</p>
      </div>
    </div>

    @if($sessionDetails)
      <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-5 mb-4">
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
            <i class="fa-brands fa-whatsapp text-2xl text-green-600 dark:text-green-400"></i>
          </div>
          <div class="flex-1">
            <h4 class="text-lg font-bold text-green-800 dark:text-green-200 mb-3">Session Information</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-xs text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">Session Name</p>
                <p class="text-sm font-semibold text-green-900 dark:text-green-100">{{ $sessionDetails['name'] ?? $settings->session_name ?? 'N/A' }}</p>
              </div>
              <div>
                <p class="text-xs text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">Phone Number</p>
                <p class="text-sm font-semibold text-green-900 dark:text-green-100">{{ $sessionDetails['phone_number'] ?? $settings->phone_number ?? 'N/A' }}</p>
              </div>
              <div>
                <p class="text-xs text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">Status</p>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ ($sessionDetails['status'] ?? $settings->session_status) === 'connected' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                  <i class="fa-solid fa-circle text-[8px] mr-1.5 {{ ($sessionDetails['status'] ?? $settings->session_status) === 'connected' ? 'animate-pulse' : '' }}"></i>
                  {{ ucfirst($sessionDetails['status'] ?? $settings->session_status ?? 'Unknown') }}
                </span>
              </div>
              <div>
                <p class="text-xs text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">Last Active</p>
                <p class="text-sm font-semibold text-green-900 dark:text-green-100">{{ $sessionDetails['last_active'] ?? 'N/A' }}</p>
              </div>
              <div class="md:col-span-2">
                <p class="text-xs text-green-600 dark:text-green-400 uppercase tracking-wider mb-1">WhatsApp Account</p>
                <p class="text-sm font-semibold text-green-900 dark:text-green-100">{{ $sessionDetails['account_name'] ?? 'N/A' }}</p>
              </div>
            </div>

            @if(($sessionDetails['status'] ?? $settings->session_status) === 'connected')
            <div class="mt-4 pt-4 border-t border-green-200 dark:border-green-800">
              <p class="text-xs text-green-700 dark:text-green-300 mb-2">The WhatsApp session is connected and ready to use.</p>
              <div class="flex gap-2">
                <form action="{{ route('admin.communication.whatsapp.disconnect-session') }}" method="POST" class="inline">
                  @csrf
                  <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400 text-xs font-semibold transition-all">
                    <i class="fa-solid fa-power-off mr-1"></i>Disconnect
                  </button>
                </form>
                <form action="{{ route('admin.communication.whatsapp.restart-session') }}" method="POST" class="inline">
                  @csrf
                  <button type="submit" class="px-3 py-1.5 rounded-lg bg-yellow-100 hover:bg-yellow-200 text-yellow-700 dark:bg-yellow-900/30 dark:hover:bg-yellow-900/50 dark:text-yellow-400 text-xs font-semibold transition-all">
                    <i class="fa-solid fa-rotate mr-1"></i>Restart
                  </button>
                </form>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    @elseif($settings && $settings->session_api_key)
      <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 mb-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-check-circle text-blue-600 dark:text-blue-400"></i>
            <span class="text-sm font-medium text-blue-800 dark:text-blue-200">Session API Key configured</span>
          </div>
          <div class="flex items-center gap-3">
            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $settings->session_name ?? 'N/A' }}</span>
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $settings->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
              {{ $settings->is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>
      </div>
    @endif

    <form action="{{ route('admin.communication.whatsapp.session-api-key') }}" method="POST">
      @csrf
      @if($settings && $settings->session_api_key)
        @method('PUT')
      @endif
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Session API Key</label>
          <input type="password" name="session_api_key" value="{{ $settings->session_api_key ?? '' }}" 
                 placeholder="Enter your Session API Key from Wasender session screen"
                 class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
          <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Get your API key after connecting your WhatsApp session from Session Management screen</p>
        </div>
        <div class="flex items-center gap-3">
          <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-all">
            {{ $settings && $settings->session_api_key ? 'Update API Key' : 'Save API Key' }}
          </button>
          @if($settings && $settings->session_api_key)
            <form action="{{ route('admin.communication.whatsapp.toggle-status') }}" method="POST">
              @csrf
              <button type="submit" class="px-4 py-2 rounded-lg {{ $settings->is_active ? 'bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900/30 dark:hover:bg-red-900/50 dark:text-red-400' : 'bg-green-100 hover:bg-green-200 text-green-700 dark:bg-green-900/30 dark:hover:bg-green-900/50 dark:text-green-400' }} text-sm font-semibold transition-all">
              {{ $settings->is_active ? 'Deactivate' : 'Activate' }}
            </button>
            </form>
          @endif
        </div>
      </div>
    </form>
  </div>

  <!-- Send Message Section -->
  @if($settings && $settings->session_api_key && $settings->is_active)
  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-lg bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 flex items-center justify-center">
        <i class="fa-solid fa-paper-plane"></i>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-primary-900 dark:text-white">Send Messages</h3>
        <p class="text-xs text-primary-500 dark:text-primary-400">Send single or bulk messages via WhatsApp or SMS</p>
      </div>
    </div>

    <!-- Channel Type Tabs -->
    <div x-data="{ channelType: 'whatsapp', messageType: 'single' }" class="space-y-6">
      <div class="flex gap-2 border-b border-primary-200 dark:border-primary-800">
        <button @click="channelType = 'whatsapp'" 
                :class="channelType === 'whatsapp' ? 'border-b-2 border-green-500 text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'"
                class="px-4 py-2 text-sm font-medium transition-colors">
          <i class="fa-brands fa-whatsapp mr-2"></i>WhatsApp
        </button>
        <button @click="channelType = 'sms'" 
                :class="channelType === 'sms' ? 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400'"
                class="px-4 py-2 text-sm font-medium transition-colors">
          <i class="fa-solid fa-comment-sms mr-2"></i>SMS
        </button>
      </div>

      <!-- WhatsApp Messages -->
      <div x-show="channelType === 'whatsapp'" x-transition>
        <!-- Message Type Tabs -->
        <div class="flex gap-2 border-b border-primary-200 dark:border-primary-800 mb-4">
          <button @click="messageType = 'single'" 
                  :class="messageType === 'single' ? 'border-b-2 border-primary-500 text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400'"
                  class="px-4 py-2 text-sm font-medium transition-colors">
            Single Message
          </button>
          <button @click="messageType = 'bulk'" 
                  :class="messageType === 'bulk' ? 'border-b-2 border-primary-500 text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400'"
                  class="px-4 py-2 text-sm font-medium transition-colors">
            Bulk Message
          </button>
        </div>

        <!-- Single WhatsApp Message Form -->
        <div x-show="messageType === 'single'" x-transition>
          <form action="{{ route('admin.communication.whatsapp.send-single') }}" method="POST">
            @csrf
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                <input type="text" name="phone_number" required placeholder="+255123456789"
                       class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Include country code (e.g., +255 for Tanzania)</p>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Message</label>
                <textarea name="message" rows="4" required placeholder="Enter your message here"
                          class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"></textarea>
              </div>
              <button type="submit" class="px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold transition-all shadow-lg shadow-green-500/20">
                <i class="fa-brands fa-whatsapp mr-2"></i>Send WhatsApp Message
              </button>
            </div>
          </form>
        </div>

        <!-- Bulk WhatsApp Message Form -->
        <div x-show="messageType === 'bulk'" x-transition>
          <form action="{{ route('admin.communication.whatsapp.send-bulk') }}" method="POST">
            @csrf
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone Numbers</label>
                <textarea name="phone_numbers" rows="6" required placeholder="+2551234567890&#10;+2551234567891&#10;+2551234567892"
                          class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none font-mono text-sm"></textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Enter one phone number per line (include country code)</p>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Message</label>
                <textarea name="message" rows="4" required placeholder="Enter your message here"
                          class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"></textarea>
              </div>
              <button type="submit" class="px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-semibold transition-all shadow-lg shadow-green-500/20">
                <i class="fa-brands fa-whatsapp mr-2"></i>Send Bulk WhatsApp
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- SMS Messages -->
      <div x-show="channelType === 'sms'" x-transition>
        <!-- Message Type Tabs -->
        <div class="flex gap-2 border-b border-primary-200 dark:border-primary-800 mb-4">
          <button @click="messageType = 'single'" 
                  :class="messageType === 'single' ? 'border-b-2 border-primary-500 text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400'"
                  class="px-4 py-2 text-sm font-medium transition-colors">
            Single SMS
          </button>
          <button @click="messageType = 'bulk'" 
                  :class="messageType === 'bulk' ? 'border-b-2 border-primary-500 text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400'"
                  class="px-4 py-2 text-sm font-medium transition-colors">
            Bulk SMS
          </button>
        </div>

        <!-- Single SMS Form -->
        <div x-show="messageType === 'single'" x-transition>
          <form action="{{ route('admin.communication.whatsapp.send-single-sms') }}" method="POST">
            @csrf
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                <input type="text" name="phone_number" required placeholder="255123456789"
                       class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Include country code (e.g., 255 for Tanzania, without +)</p>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Message</label>
                <textarea name="message" rows="4" required placeholder="Enter your SMS message here"
                          class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"></textarea>
              </div>
              <button type="submit" class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold transition-all shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-comment-sms mr-2"></i>Send SMS
              </button>
            </div>
          </form>
        </div>

        <!-- Bulk SMS Form -->
        <div x-show="messageType === 'bulk'" x-transition>
          <form action="{{ route('admin.communication.whatsapp.send-bulk-sms') }}" method="POST">
            @csrf
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone Numbers</label>
                <textarea name="phone_numbers" rows="6" required placeholder="2551234567890&#10;2551234567891&#10;2551234567892"
                          class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none font-mono text-sm"></textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Enter one phone number per line (include country code, without +)</p>
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Message</label>
                <textarea name="message" rows="4" required placeholder="Enter your SMS message here"
                          class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"></textarea>
              </div>
              <button type="submit" class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold transition-all shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-comment-sms mr-2"></i>Send Bulk SMS
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @else
  <div class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl p-6 text-center">
    <i class="fa-brands fa-whatsapp text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
    <p class="text-sm text-gray-600 dark:text-gray-400">Configure your Session API Key and activate it to send messages</p>
  </div>
  @endif

  <!-- Message History Section -->
  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center">
        <i class="fa-solid fa-history"></i>
      </div>
      <div>
        <h3 class="text-lg font-semibold text-primary-900 dark:text-white">Message History</h3>
        <p class="text-xs text-primary-500 dark:text-primary-400">View sent and failed messages</p>
      </div>
    </div>

    @if($messageHistory->count() > 0)
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-primary-50 dark:bg-primary-900/20">
              <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Phone Number</th>
              <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Message</th>
              <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Type</th>
              <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Status</th>
              <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Sent At</th>
            </tr>
          </thead>
          <tbody>
            @foreach($messageHistory as $history)
              <tr class="border-b border-primary-100 dark:border-primary-800 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors">
                <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $history->phone_number }}</td>
                <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300 max-w-xs truncate">{{ Str::limit($history->message, 50) }}</td>
                <td class="py-3 px-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                    {{ ucfirst($history->message_type) }}
                  </span>
                </td>
                <td class="py-3 px-4">
                  @if($history->status === 'sent')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Sent</span>
                  @elseif($history->status === 'failed')
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Failed</span>
                  @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">Pending</span>
                  @endif
                </td>
                <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $history->sent_at ? $history->sent_at->format('M d, Y H:i') : 'N/A' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if($messageHistory->hasPages())
        <div class="flex items-center justify-between mt-5 pt-5 border-t border-primary-100 dark:border-primary-800">
          <span class="text-xs text-primary-600 dark:text-primary-400">Showing {{ $messageHistory->firstItem() }} to {{ $messageHistory->lastItem() }} of {{ $messageHistory->total() }} results</span>
          {{ $messageHistory->links() }}
        </div>
      @endif
    @else
      <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No message history found.</p>
    @endif
  </div>

</div>
@endsection
