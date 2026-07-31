@extends('layouts.admin')

@section('breadcrumb', 'Communication / WhatsApp')
@section('page_title', 'WhatsApp')

@section('content')
<div class="space-y-6">
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-500 flex items-center justify-center">
                <i class="fa-brands fa-whatsapp text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary-900 dark:text-white">WhatsApp Messaging</h2>
                <p class="text-sm text-primary-500 dark:text-primary-400">Send WhatsApp messages to members</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-green-600 dark:text-green-400 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        @if(session('result'))
                            <pre class="text-xs text-green-700 dark:text-green-300 mt-2 overflow-auto">{{ json_encode(session('result'), JSON_PRETTY_PRINT) }}</pre>
                        @endif
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

        <form action="{{ route('admin.communication.whatsapp.send') }}" method="POST" x-data="{ messageType: 'plain' }">
            @csrf

            <div class="space-y-6">
                <!-- Recipients -->
                <div>
                    <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Recipients</label>
                    <textarea name="recipients[]" rows="3" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" placeholder="Enter phone numbers (one per line, with country code e.g., 255123456789)"></textarea>
                    <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">All numbers should include country code (255 for Tanzania)</p>
                </div>

                <!-- Template -->
                <div>
                    <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Template Name</label>
                    <input type="text" name="template" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" placeholder="e.g., medicine_reminder" required>
                    <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">Enter the template name as configured in your WhatsApp Business account</p>
                </div>

                <!-- Message Type -->
                <div>
                    <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Message Type</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <label class="relative">
                            <input type="radio" name="message_type" value="plain" x-model="messageType" class="peer sr-only" checked>
                            <div class="p-3 rounded-xl border-2 border-primary-200 dark:border-dark-border peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 cursor-pointer transition-all">
                                <div class="text-center">
                                    <i class="fa-solid fa-message text-primary-500 mb-1"></i>
                                    <p class="text-xs font-semibold text-primary-900 dark:text-white">Plain</p>
                                </div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="message_type" value="personalized" x-model="messageType" class="peer sr-only">
                            <div class="p-3 rounded-xl border-2 border-primary-200 dark:border-dark-border peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 cursor-pointer transition-all">
                                <div class="text-center">
                                    <i class="fa-solid fa-user-pen text-primary-500 mb-1"></i>
                                    <p class="text-xs font-semibold text-primary-900 dark:text-white">Personalized</p>
                                </div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="message_type" value="media" x-model="messageType" class="peer sr-only">
                            <div class="p-3 rounded-xl border-2 border-primary-200 dark:border-dark-border peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 cursor-pointer transition-all">
                                <div class="text-center">
                                    <i class="fa-solid fa-image text-primary-500 mb-1"></i>
                                    <p class="text-xs font-semibold text-primary-900 dark:text-white">Media</p>
                                </div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="message_type" value="button" x-model="messageType" class="peer sr-only">
                            <div class="p-3 rounded-xl border-2 border-primary-200 dark:border-dark-border peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 cursor-pointer transition-all">
                                <div class="text-center">
                                    <i class="fa-solid fa-square-poll-vertical text-primary-500 mb-1"></i>
                                    <p class="text-xs font-semibold text-primary-900 dark:text-white">Button</p>
                                </div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="message_type" value="scheduled" x-model="messageType" class="peer sr-only">
                            <div class="p-3 rounded-xl border-2 border-primary-200 dark:border-dark-border peer-checked:border-primary-500 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 cursor-pointer transition-all">
                                <div class="text-center">
                                    <i class="fa-solid fa-calendar text-primary-500 mb-1"></i>
                                    <p class="text-xs font-semibold text-primary-900 dark:text-white">Scheduled</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Personalisation (for personalized messages) -->
                <div x-show="messageType === 'personalized'" x-transition>
                    <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Personalisation (JSON)</label>
                    <textarea name="personalisation" rows="4" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all font-mono text-xs" placeholder='[{"name": "John"}, {"name": "Jane"}]'></textarea>
                    <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">Array of personalisation objects matching the number of recipients</p>
                </div>

                <!-- Media (for media messages) -->
                <div x-show="messageType === 'media'" x-transition>
                    <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Media Configuration (JSON)</label>
                    <textarea name="media" rows="6" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all font-mono text-xs" placeholder='{
  "image": {
    "file": "https://example.com/image.png",
    "name": "Image Name"
  }
}'></textarea>
                    <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">Media configuration for image, document, or location</p>
                </div>

                <!-- Reference (for media messages) -->
                <div x-show="messageType === 'media'" x-transition>
                    <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Reference (Optional)</label>
                    <input type="text" name="reference" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" placeholder="e.g., xaefcgt">
                </div>

                <!-- Button Personalisation (for button messages) -->
                <div x-show="messageType === 'button'" x-transition>
                    <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Button Personalisation (JSON)</label>
                    <textarea name="button_personalisation" rows="6" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all font-mono text-xs" placeholder='{
  "copy_otp_code": {
    "parameters": ["12345"]
  }
}'></textarea>
                    <p class="text-xs text-primary-500 dark:text-primary-400 mt-1">Button personalisation for OTP, coupon codes, or URL links</p>
                </div>

                <!-- Scheduled Message Options -->
                <div x-show="messageType === 'scheduled'" x-transition class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Date</label>
                            <input type="date" name="date" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Time</label>
                            <input type="time" name="time" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Attributes (JSON, Optional)</label>
                        <textarea name="attributes" rows="3" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all font-mono text-xs" placeholder='[{"name": "John"}]'></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Repeat (Optional)</label>
                            <select name="repeat" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                <option value="">No repeat</option>
                                <option value="hourly">Hourly</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Document URL (Optional)</label>
                            <input type="text" name="document" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" placeholder="https://example.com/document.pdf">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Start Date (Optional)</label>
                            <input type="date" name="start_date" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">End Date (Optional)</label>
                            <input type="date" name="end_date" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-primary-900 dark:text-white mb-2">Reference (Optional)</label>
                        <input type="text" name="reference" class="w-full px-4 py-3 rounded-xl border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all" placeholder="e.g., xaefcgt">
                    </div>
                </div>

                <!-- Test Mode -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="test" id="test_mode" class="w-4 h-4 rounded border-primary-300 text-primary-600 focus:ring-primary-500">
                    <label for="test_mode" class="text-sm text-primary-700 dark:text-primary-300">Test Mode (No actual message will be sent)</label>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-3">
                    <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all shadow-lg shadow-green-500/20">
                        <i class="fa-brands fa-whatsapp mr-2"></i>Send Message
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
