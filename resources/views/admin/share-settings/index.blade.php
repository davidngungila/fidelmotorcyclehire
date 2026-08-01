@extends('layouts.admin')

@section('breadcrumb', 'System › Share Settings')
@section('page_title', 'Share Settings')

@section('content')

<div class="space-y-6">

  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <form action="{{ route('admin.share-settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">General Settings</h3>
          <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
              <div>
                <label class="text-sm text-primary-900 dark:text-white font-medium">Enable Share Purchases</label>
                <p class="text-xs text-primary-600 dark:text-primary-400">Allow members to purchase shares</p>
              </div>
              <input type="checkbox" name="enable_share_purchases" value="1" {{ $settings['enable_share_purchases'] ? 'checked' : '' }} class="w-5 h-5 rounded border-primary-600 bg-white dark:bg-dark-card text-primary-600 focus:ring-primary-400">
            </div>

            <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
              <div>
                <label class="text-sm text-primary-900 dark:text-white font-medium">Enable Share Transfers</label>
                <p class="text-xs text-primary-600 dark:text-primary-400">Allow members to transfer shares</p>
              </div>
              <input type="checkbox" name="enable_share_transfers" value="1" {{ $settings['enable_share_transfers'] ? 'checked' : '' }} class="w-5 h-5 rounded border-primary-600 bg-white dark:bg-dark-card text-primary-600 focus:ring-primary-400">
            </div>

            <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
              <div>
                <label class="text-sm text-primary-900 dark:text-white font-medium">Enable Share Dividends</label>
                <p class="text-xs text-primary-600 dark:text-primary-400">Allow dividend distribution</p>
              </div>
              <input type="checkbox" name="enable_share_dividends" value="1" {{ $settings['enable_share_dividends'] ? 'checked' : '' }} class="w-5 h-5 rounded border-primary-600 bg-white dark:bg-dark-card text-primary-600 focus:ring-primary-400">
            </div>

            <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
              <div>
                <label class="text-sm text-primary-900 dark:text-white font-medium">Auto-Generate Certificates</label>
                <p class="text-xs text-primary-600 dark:text-primary-400">Automatically create certificates on purchase</p>
              </div>
              <input type="checkbox" name="certificate_auto_generate" value="1" {{ $settings['certificate_auto_generate'] ? 'checked' : '' }} class="w-5 h-5 rounded border-primary-600 bg-white dark:bg-dark-card text-primary-600 focus:ring-primary-400">
            </div>
          </div>
        </div>

        <div>
          <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">Financial Settings</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Minimum Purchase Amount</label>
              <input type="number" name="minimum_purchase_amount" step="0.01" min="0" value="{{ $settings['minimum_purchase_amount'] }}"
                     class="form-input py-2.5 px-4"
                     placeholder="1000">
              @error('minimum_purchase_amount') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maximum Purchase Amount</label>
              <input type="number" name="maximum_purchase_amount" step="0.01" min="0" value="{{ $settings['maximum_purchase_amount'] }}"
                     class="form-input py-2.5 px-4"
                     placeholder="Optional">
              @error('maximum_purchase_amount') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Transfer Fee (%)</label>
              <input type="number" name="transfer_fee_percentage" step="0.01" min="0" max="100" value="{{ $settings['transfer_fee_percentage'] }}"
                     class="form-input py-2.5 px-4"
                     placeholder="0">
              @error('transfer_fee_percentage') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Dividend Tax (%)</label>
              <input type="number" name="dividend_tax_percentage" step="0.01" min="0" max="100" value="{{ $settings['dividend_tax_percentage'] }}"
                     class="form-input py-2.5 px-4"
                     placeholder="0">
              @error('dividend_tax_percentage') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">Certificate Background</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Upload Background Image</label>
            <input type="file" name="certificate_background" accept="image/*" class="form-input py-2.5 px-4">
            @error('certificate_background') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Upload an image to be used as background for share certificates (PNG, JPG, WEBP)</p>
          </div>
          
          @if($settings['certificate_background'])
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Current Background</label>
            <div class="mt-2">
              <img src="{{ asset('storage/' . $settings['certificate_background']) }}" alt="Certificate Background" class="max-w-xs rounded-lg border border-primary-200 dark:border-primary-800">
            </div>
          </div>
          @endif
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">Notification Settings</h3>
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notification Email</label>
          <input type="email" name="notification_email" value="{{ $settings['notification_email'] }}"
                 class="form-input py-2.5 px-4"
                 placeholder="notifications@example.com">
          @error('notification_email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
          <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Email address for share-related notifications</p>
        </div>
      </div>

      <div class="flex items-center gap-3 pt-4">
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
          Save Settings
        </button>
      </div>
    </form>
  </div>

</div>

@endsection
