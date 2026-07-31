@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Settings \u203A Test SMS')
@section('page_title', 'Test SMS')

@section('content')

<div x-data="testSms()" class="space-y-6">

  <div class="flex items-center justify-between">
    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to Settings</span>
    </a>
  </div>

  <div class="glass overflow-hidden">
    <div class="p-6 lg:p-8">
      
      <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 text-white flex items-center justify-center text-2xl shadow-md">
          <i class="fa-solid fa-comment-sms"></i>
        </div>
        <div>
          <h2 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Send Test SMS</h2>
          <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Test your SMS configuration by sending a message</p>
        </div>
      </div>

      @if(!$smsSettings->is_active || !$smsSettings->api_token)
        <div class="mb-6 p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
          <div class="flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
            <div>
              <p class="font-bold text-sm text-yellow-800 dark:text-yellow-200">SMS Service Not Configured</p>
              <p class="text-xs mt-1 text-yellow-700 dark:text-yellow-300">Please configure your SMS settings and enable the service before testing.</p>
              <a href="{{ route('admin.settings.index') }}" class="text-xs mt-2 inline-block text-yellow-800 dark:text-yellow-200 underline hover:no-underline">Go to SMS Settings</a>
            </div>
          </div>
        </div>
      @endif

      <form @submit.prevent="sendTestSms" class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Phone Number</label>
            <input type="text" x-model="phone" 
                   class="form-input" 
                   placeholder="e.g. 255123456789"
                   :disabled="sending">
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Enter phone number without + sign (e.g., 255123456789)</p>
          </div>

          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Message</label>
            <textarea x-model="message" rows="4" 
                      class="form-input" 
                      placeholder="Enter your test message here"
                      :disabled="sending"></textarea>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Character count: <span x-text="message.length"></span></p>
          </div>
        </div>

        <div class="pt-6 border-t border-primary-100 dark:border-primary-900/50">
          <button type="submit" :disabled="sending || !phone || !message"
                  class="px-8 py-3 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold transition-all shadow-sm hover:shadow-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
            <i x-show="!sending" class="fa-solid fa-paper-plane"></i>
            <i x-show="sending" class="fa-solid fa-spinner fa-spin"></i>
            <span x-text="sending ? 'Sending...' : 'Send Test SMS'"></span>
          </button>
        </div>

      </form>

      @if(session('sms_result'))
        <div class="mt-6 p-4 rounded-xl {{ session('sms_success') ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }} border">
          <div class="flex items-start gap-3">
            <i class="fa-solid {{ session('sms_success') ? 'fa-check-circle text-green-600 dark:text-green-400' : 'fa-times-circle text-red-600 dark:text-red-400' }} mt-0.5"></i>
            <div class="flex-1">
              <p class="font-bold text-sm {{ session('sms_success') ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                {{ session('sms_success') ? 'SMS Sent Successfully' : 'SMS Sending Failed' }}
              </p>
              <p class="text-xs mt-1 {{ session('sms_success') ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                {{ session('sms_result') }}
              </p>
              @if(session('sms_data'))
                <details class="mt-2">
                  <summary class="text-xs cursor-pointer {{ session('sms_success') ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }} hover:underline">View Details</summary>
                  <pre class="mt-2 text-xs bg-white dark:bg-dark-bg p-2 rounded overflow-auto max-h-40">{{ json_encode(session('sms_data'), JSON_PRETTY_PRINT) }}</pre>
                </details>
              @endif
            </div>
          </div>
        </div>
        {{ session()->forget(['sms_result', 'sms_success', 'sms_data']) }}
      @endif

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function testSms() {
    return {
      phone: '',
      message: 'This is a test message from FEEDTAN Members Portal.',
      sending: false,
      async sendTestSms() {
        if (!this.phone || !this.message) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please enter both phone number and message.',
          });
          return;
        }

        this.sending = true;

        try {
          const response = await fetch('{{ route('admin.settings.test-sms') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
              phone: this.phone,
              message: this.message,
            }),
          });

          const data = await response.json();

          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: data.message || 'Test SMS sent successfully!',
              timer: 3000,
              showConfirmButton: false,
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message || 'Failed to send test SMS',
            });
          }
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to send test SMS. Please try again.',
          });
        } finally {
          this.sending = false;
        }
      }
    }
  }
</script>
@endpush
