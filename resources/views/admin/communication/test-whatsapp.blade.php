@extends('layouts.admin')

@section('breadcrumb', 'Communication \u203A WhatsApp \u203A Test')
@section('page_title', 'Test WhatsApp')

@section('content')

<div x-data="testWhatsApp()" class="space-y-6">

  <div class="flex items-center justify-between">
    <a href="{{ route('admin.communication.whatsapp') }}" class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to WhatsApp</span>
    </a>
  </div>

  <div class="glass overflow-hidden">
    <div class="p-6 lg:p-8">
      
      <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 text-white flex items-center justify-center text-2xl shadow-md">
          <i class="fa-brands fa-whatsapp"></i>
        </div>
        <div>
          <h2 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Send Test WhatsApp</h2>
          <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Test your WhatsApp configuration by sending a message</p>
        </div>
      </div>

      @if(!$whatsappSettings->is_active || !$whatsappSettings->api_key)
        <div class="mb-6 p-4 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
          <div class="flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
            <div>
              <p class="font-bold text-sm text-yellow-800 dark:text-yellow-200">WhatsApp Service Not Configured</p>
              <p class="text-xs mt-1 text-yellow-700 dark:text-yellow-300">Please configure your WhatsApp settings and enable the service before testing.</p>
              <a href="{{ route('admin.settings.index') }}" class="text-xs mt-2 inline-block text-yellow-800 dark:text-yellow-200 underline hover:no-underline">Go to WhatsApp Settings</a>
            </div>
          </div>
        </div>
      @endif

      <form @submit.prevent="sendTestWhatsApp" class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Select Template</label>
            <select x-model="selectedTemplate" @change="onTemplateChange" class="form-input" :disabled="sending">
              <option value="">-- Select a Template --</option>
              <template x-for="tmpl in templates" :key="tmpl.name">
                <option :value="tmpl.name" x-text="tmpl.label"></option>
              </template>
              <option value="custom">-- Custom Template --</option>
            </select>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Choose a predefined template or enter custom template name</p>
          </div>

          <div class="md:col-span-2" x-show="selectedTemplate === 'custom'">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Custom Template Name</label>
            <input type="text" x-model="template" 
                   class="form-input" 
                   placeholder="e.g. medicine_reminder"
                   :disabled="sending">
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Enter the template name as configured in your WhatsApp Business account</p>
          </div>

          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Phone Number</label>
            <input type="text" x-model="phone" 
                   class="form-input" 
                   placeholder="e.g. 255123456789"
                   :disabled="sending">
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Enter phone number with country code (255 for Tanzania)</p>
          </div>

          <!-- Dynamic Personalisation Fields -->
          <template x-if="selectedTemplate && selectedTemplate !== 'custom' && getTemplateParameters().length > 0">
            <div class="md:col-span-2 space-y-4">
              <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Template Parameters</label>
              <template x-for="param in getTemplateParameters()" :key="param">
                <div>
                  <label class="block text-sm font-medium mb-1" :class="darkMode ? 'text-primary-300' : 'text-primary-700'" x-text="formatParameterLabel(param)"></label>
                  <input type="text" x-model="personalisationData[param]" 
                         class="form-input" 
                         :placeholder="'Enter ' + param"
                         :disabled="sending">
                </div>
              </template>
            </div>
          </template>

          <!-- Custom JSON Personalisation -->
          <div class="md:col-span-2" x-show="selectedTemplate === 'custom'">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Personalisation (JSON, Optional)</label>
            <textarea x-model="personalisation" rows="4" 
                      class="form-input font-mono text-xs" 
                      placeholder='{"name": "John", "number": "12345"}'
                      :disabled="sending"></textarea>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">JSON object with personalisation data for the template</p>
          </div>

          <div class="flex items-center gap-3">
            <input type="checkbox" x-model="testMode" id="test_mode" class="w-4 h-4 rounded border-primary-300 text-primary-600 focus:ring-primary-500" :disabled="sending">
            <label for="test_mode" class="text-sm" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Test Mode (No actual message will be sent)</label>
          </div>
        </div>

        <div class="pt-6 border-t border-primary-100 dark:border-primary-900/50">
          <button type="submit" :disabled="sending || !phone || !template"
                  class="px-8 py-3 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold transition-all shadow-sm hover:shadow-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
            <i x-show="!sending" class="fa-brands fa-whatsapp"></i>
            <i x-show="sending" class="fa-solid fa-spinner fa-spin"></i>
            <span x-text="sending ? 'Sending...' : 'Send Test WhatsApp'"></span>
          </button>
        </div>

      </form>

      @if(session('whatsapp_result'))
        <div class="mt-6 p-4 rounded-xl {{ session('whatsapp_success') ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }} border">
          <div class="flex items-start gap-3">
            <i class="fa-solid {{ session('whatsapp_success') ? 'fa-check-circle text-green-600 dark:text-green-400' : 'fa-times-circle text-red-600 dark:text-red-400' }} mt-0.5"></i>
            <div class="flex-1">
              <p class="font-bold text-sm {{ session('whatsapp_success') ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                {{ session('whatsapp_success') ? 'WhatsApp Sent Successfully' : 'WhatsApp Sending Failed' }}
              </p>
              <p class="text-xs mt-1 {{ session('whatsapp_success') ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                {{ session('whatsapp_result') }}
              </p>
              @if(session('whatsapp_data'))
                <details class="mt-2">
                  <summary class="text-xs cursor-pointer {{ session('whatsapp_success') ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }} hover:underline">View Details</summary>
                  <pre class="mt-2 text-xs bg-white dark:bg-dark-bg p-2 rounded overflow-auto max-h-40">{{ json_encode(session('whatsapp_data'), JSON_PRETTY_PRINT) }}</pre>
                </details>
              @endif
            </div>
          </div>
        </div>
        {{ session()->forget(['whatsapp_result', 'whatsapp_success', 'whatsapp_data']) }}
      @endif

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function testWhatsApp() {
    return {
      phone: '',
      template: '',
      selectedTemplate: '',
      personalisation: '',
      personalisationData: {},
      testMode: true,
      sending: false,
      templates: @json($templates),
      onTemplateChange() {
        if (this.selectedTemplate && this.selectedTemplate !== 'custom') {
          this.template = this.selectedTemplate;
          this.personalisationData = {};
          this.personalisation = '';
        }
      },
      getTemplateParameters() {
        const tmpl = this.templates.find(t => t.name === this.selectedTemplate);
        return tmpl ? tmpl.parameters : [];
      },
      formatParameterLabel(param) {
        return param.charAt(0).toUpperCase() + param.slice(1).replace(/_/g, ' ');
      },
      async sendTestWhatsApp() {
        if (!this.phone || !this.template) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please enter both phone number and template name.',
          });
          return;
        }

        this.sending = true;

        try {
          let personalisationData = null;
          
          // Use dynamic fields if template is selected
          if (this.selectedTemplate && this.selectedTemplate !== 'custom') {
            const params = this.getTemplateParameters();
            if (params.length > 0) {
              personalisationData = {};
              params.forEach(param => {
                if (this.personalisationData[param]) {
                  personalisationData[param] = this.personalisationData[param];
                }
              });
            }
          } else if (this.personalisation) {
            // Parse JSON for custom template
            try {
              personalisationData = JSON.parse(this.personalisation);
            } catch (e) {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Invalid JSON format for personalisation.',
              });
              this.sending = false;
              return;
            }
          }

          const response = await fetch('{{ route('admin.communication.whatsapp.test.send') }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
              phone: this.phone,
              template: this.template,
              personalisation: personalisationData,
              test: this.testMode,
            }),
          });

          const data = await response.json();

          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: data.message || 'Test WhatsApp sent successfully!',
              timer: 3000,
              showConfirmButton: false,
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message || 'Failed to send test WhatsApp',
            });
          }
        } catch (error) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to send test WhatsApp. Please try again.',
          });
        } finally {
          this.sending = false;
        }
      }
    }
  }
</script>
@endpush
