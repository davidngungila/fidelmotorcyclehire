<div class="mb-4">
  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
    <i class="fa-solid fa-users mr-1 text-primary-500"></i>Recipient Phone Numbers
  </label>
  <textarea name="phone_numbers" rows="6" required
            placeholder="+2551234567890&#10;+2551234567891&#10;+2551234567892"
            class="w-full px-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none font-mono text-sm leading-6">{{ old('phone_numbers') }}</textarea>
  <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Enter one phone number per line — include the country code (e.g., +255 or 255).</p>
</div>
