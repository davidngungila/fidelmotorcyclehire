@extends('layouts.admin')

@section('breadcrumb', 'Communication / WhatsApp / Test')
@section('page_title', 'Test WhatsApp')

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

        @if(!$whatsappSettings->is_active || empty($whatsappSettings->api_key))
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-yellow-600 dark:text-yellow-400 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-200">WhatsApp Not Configured</p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Please configure WhatsApp settings in <a href="{{ route('admin.settings.index') }}" class="underline font-bold">Settings</a> before testing.</p>
                    </div>
                </div>
            </div>
        @endif

        <form id="testWhatsAppForm" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" placeholder="e.g., 255123456789" required>
                    <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">Enter phone number with country code (255 for Tanzania)</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Template Name</label>
                    <input type="text" name="template" id="template" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" placeholder="e.g., medicine_reminder" required>
                    <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">Enter the template name as configured in your WhatsApp Business account</p>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="test" id="test_mode" class="w-4 h-4 rounded border-primary-300 text-primary-600 focus:ring-primary-500" checked>
                    <label for="test_mode" class="text-sm text-primary-700 dark:text-primary-300">Test Mode (No actual message will be sent)</label>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" id="sendBtn" class="px-6 py-3 rounded-xl bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all shadow-lg shadow-green-500/20 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-brands fa-whatsapp mr-2"></i>Send Test Message
                </button>
            </div>
        </form>

        <div id="result" class="hidden mt-6"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('testWhatsAppForm');
    const sendBtn = document.getElementById('sendBtn');
    const resultDiv = document.getElementById('result');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Sending...';

        const formData = new FormData(form);
        formData.append('test', document.getElementById('test_mode').checked ? '1' : '0');

        fetch('{{ route('admin.communication.whatsapp.test') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            resultDiv.classList.remove('hidden');
            if (data.success) {
                resultDiv.innerHTML = `
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-green-600 dark:text-green-400 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-semibold text-green-800 dark:text-green-200">${data.message}</p>
                                <pre class="text-xs text-green-700 dark:text-green-300 mt-2 overflow-auto bg-green-100 dark:bg-green-900/30 p-2 rounded">${JSON.stringify(data.result, null, 2)}</pre>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-xmark text-red-600 dark:text-red-400 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-semibold text-red-800 dark:text-red-200">${data.message}</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = `
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-circle-xmark text-red-600 dark:text-red-400 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-red-800 dark:text-red-200">Error: ${error.message}</p>
                        </div>
                    </div>
                </div>
            `;
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fa-brands fa-whatsapp mr-2"></i>Send Test Message';
        });
    });
});
</script>
@endpush
