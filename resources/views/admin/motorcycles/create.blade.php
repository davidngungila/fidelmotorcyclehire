@extends('layouts.admin')

@section('breadcrumb', 'Motorcycle Inventory \u203A Add New')
@section('page_title', 'Add New Motorcycle')

@section('content')

<div class="max-w-4xl mx-auto">
  <form method="POST" action="{{ route('admin.motorcycles.store') }}" class="space-y-6">
    @csrf

    <div class="glass p-6">
      <h3 class="text-lg font-bold text-primary-900 dark:text-white mb-6">Motorcycle Details</h3>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Brand <span class="text-red-500">*</span></label>
          <input type="text" name="brand" required
                 class="form-input"
                 placeholder="e.g., Yamaha, Honda, Suzuki">
          @error('brand')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Model <span class="text-red-500">*</span></label>
          <input type="text" name="model" required
                 class="form-input"
                 placeholder="e.g., MT-15, NMAX, V-Strom">
          @error('model')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Engine Number <span class="text-red-500">*</span></label>
          <input type="text" name="engine_number" required
                 class="form-input"
                 placeholder="Unique engine number">
          @error('engine_number')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Chassis Number <span class="text-red-500">*</span></label>
          <input type="text" name="chassis_number" required
                 class="form-input"
                 placeholder="Unique chassis number">
          @error('chassis_number')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Registration Number</label>
          <input type="text" name="registration_number"
                 class="form-input"
                 placeholder="License plate number">
          @error('registration_number')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Colour <span class="text-red-500">*</span></label>
          <input type="text" name="colour" required
                 class="form-input"
                 placeholder="e.g., Black, Red, Blue">
          @error('colour')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Purchase Price (TZS) <span class="text-red-500">*</span></label>
          <input type="number" name="purchase_price" required min="0" step="0.01"
                 class="form-input"
                 placeholder="0.00">
          @error('purchase_price')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Selling Price (TZS)</label>
          <input type="number" name="selling_price" min="0" step="0.01"
                 class="form-input"
                 placeholder="0.00">
          @error('selling_price')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Status <span class="text-red-500">*</span></label>
          <select name="status" required class="form-input">
            <option value="Available">Available</option>
            <option value="Assigned">Assigned</option>
            <option value="Sold">Sold</option>
            <option value="Under Repair">Under Repair</option>
          </select>
          @error('status')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Purchase Date</label>
          <input type="date" name="purchase_date"
                 class="form-input">
          @error('purchase_date')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="mt-6">
        <label class="block text-sm font-medium text-primary-700 dark:text-primary-300 mb-2">Notes</label>
        <textarea name="notes" rows="3"
                  class="form-input"
                  placeholder="Additional notes about this motorcycle..."></textarea>
        @error('notes')
          <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="flex items-center justify-end gap-3">
      <a href="{{ route('admin.motorcycles.index') }}"
         class="px-5 py-2.5 rounded-xl border border-primary-300 dark:border-primary-700 text-primary-700 dark:text-primary-300 text-sm font-bold hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-all">
        Cancel
      </a>
      <button type="submit"
              class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
        <i class="fa-solid fa-save mr-2"></i> Save Motorcycle
      </button>
    </div>
  </form>
</div>

@endsection
