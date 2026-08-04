<div class="mb-4">
  <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
    {{ $label }}
    @if(!isset($required) || !$required)
      <span class="text-gray-400 font-normal">(optional)</span>
    @endif
  </label>
  <div class="relative">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
      <i class="fa-solid fa-link text-gray-400"></i>
    </div>
    <input type="url" name="{{ $name }}" placeholder="{{ $placeholder ?? 'https://...' }}"
           {{ (isset($required) && $required) ? 'required' : '' }}
           value="{{ old($name) }}"
           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-primary-200 dark:border-dark-border bg-white dark:bg-dark-card text-primary-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
  </div>
  @isset($help)
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $help }}</p>
  @endisset
</div>
