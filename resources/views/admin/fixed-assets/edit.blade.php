@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Fixed Assets \u203A Edit Fixed Asset')
@section('page_title', 'Edit Fixed Asset')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Fixed Asset</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $fixedAsset->asset_name }} - {{ $fixedAsset->asset_code }}</p>
    </div>
    <a href="{{ route('admin.fixed-assets.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
  </div>

  <div class="glass rounded-xl p-8">
    <form action="{{ route('admin.fixed-assets.update', $fixedAsset->id) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Linked Account *</label>
          <select name="account_id" required
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Account</option>
            @foreach($accounts as $account)
              <option value="{{ $account->id }}" {{ old('account_id', $fixedAsset->account_id) == $account->id ? 'selected' : '' }}>
                {{ $account->account_code }} - {{ $account->account_name }}
              </option>
            @endforeach
          </select>
          @error('account_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Asset Code *</label>
          <input type="text" name="asset_code" value="{{ old('asset_code', $fixedAsset->asset_code) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="e.g., FA-001">
          @error('asset_code')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Asset Name *</label>
          <input type="text" name="asset_name" value="{{ old('asset_name', $fixedAsset->asset_name) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="Enter asset name">
          @error('asset_name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Purchase Date *</label>
          <input type="date" name="purchase_date" value="{{ old('purchase_date', $fixedAsset->purchase_date->format('Y-m-d')) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          @error('purchase_date')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Purchase Cost *</label>
          <input type="number" name="purchase_cost" step="0.01" min="0" value="{{ old('purchase_cost', $fixedAsset->purchase_cost) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="0.00">
          @error('purchase_cost')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Salvage Value</label>
          <input type="number" name="salvage_value" step="0.01" min="0" value="{{ old('salvage_value', $fixedAsset->salvage_value) }}"
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="0.00">
          @error('salvage_value')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Useful Life (Years) *</label>
          <input type="number" name="useful_life_years" min="1" value="{{ old('useful_life_years', $fixedAsset->useful_life_years) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="e.g., 5">
          @error('useful_life_years')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Depreciation Method *</label>
          <select name="depreciation_method" required
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Method</option>
            <option value="straight_line" {{ old('depreciation_method', $fixedAsset->depreciation_method) === 'straight_line' ? 'selected' : '' }}>Straight Line</option>
            <option value="declining_balance" {{ old('depreciation_method', $fixedAsset->depreciation_method) === 'declining_balance' ? 'selected' : '' }}>Declining Balance</option>
            <option value="double_declining_balance" {{ old('depreciation_method', $fixedAsset->depreciation_method) === 'double_declining_balance' ? 'selected' : '' }}>Double Declining Balance</option>
          </select>
          @error('depreciation_method')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Location</label>
          <input type="text" name="location" value="{{ old('location', $fixedAsset->location) }}"
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="Enter location">
          @error('location')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Responsible Person</label>
          <select name="responsible_person_id"
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Person</option>
            @foreach($responsiblePersons as $person)
              <option value="{{ $person->id }}" {{ old('responsible_person_id', $fixedAsset->responsible_person_id) == $person->id ? 'selected' : '' }}>
                {{ $person->name }}
              </option>
            @endforeach
          </select>
          @error('responsible_person_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Serial Number</label>
          <input type="text" name="serial_number" value="{{ old('serial_number', $fixedAsset->serial_number) }}"
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="Enter serial number">
          @error('serial_number')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
        <textarea name="description" rows="3"
          class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Enter asset description">{{ old('description', $fixedAsset->description) }}</textarea>
        @error('description')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
        <textarea name="notes" rows="3"
          class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Enter any additional notes">{{ old('notes', $fixedAsset->notes) }}</textarea>
        @error('notes')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex items-center">
        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $fixedAsset->is_active ? 'checked' : '' }}
          class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
        <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</label>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.fixed-assets.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          Update Fixed Asset
        </button>
      </div>
    </form>
  </div>
</div>
