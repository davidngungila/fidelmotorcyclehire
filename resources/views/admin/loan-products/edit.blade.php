@extends('layouts.admin')

@section('page_title', 'Edit Loan Product')

@section('breadcrumb', 'Loan Products › Edit')

@section('content')
<div class="max-w-4xl mx-auto">
  <div class="glass p-6">
    <form method="POST" action="{{ route('admin.loan-products.update', $loanProduct->id) }}">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Name *</label>
          <input type="text" name="name" value="{{ old('name', $loanProduct->name) }}" required
                 placeholder="e.g., Business Loan"
                 class="form-input py-2.5 px-4">
          @error('name')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Code *</label>
          <input type="text" name="code" value="{{ old('code', $loanProduct->code) }}" required
                 placeholder="e.g., BL-001"
                 class="form-input py-2.5 px-4">
          @error('code')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
          <textarea name="description" rows="3"
                    placeholder="Describe the loan product..."
                    class="form-input py-2.5 px-4">{{ old('description', $loanProduct->description) }}</textarea>
          @error('description')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Minimum Amount (TSh) *</label>
          <input type="number" name="min_amount" value="{{ old('min_amount', $loanProduct->min_amount) }}" required min="0" step="0.01"
                 placeholder="e.g., 100000"
                 class="form-input py-2.5 px-4">
          @error('min_amount')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maximum Amount (TSh) *</label>
          <input type="number" name="max_amount" value="{{ old('max_amount', $loanProduct->max_amount) }}" required min="0" step="0.01"
                 placeholder="e.g., 5000000"
                 class="form-input py-2.5 px-4">
          @error('max_amount')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interest Rate (%) *</label>
          <input type="number" name="interest_rate" value="{{ old('interest_rate', $loanProduct->interest_rate) }}" required min="0" max="100" step="0.01"
                 placeholder="e.g., 15"
                 class="form-input py-2.5 px-4">
          @error('interest_rate')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interest Type *</label>
          <select name="interest_type" required class="form-input py-2.5 px-4">
            <option value="">Select type</option>
            <option value="flat" {{ old('interest_type', $loanProduct->interest_type) === 'flat' ? 'selected' : '' }}>Flat</option>
            <option value="reducing" {{ old('interest_type', $loanProduct->interest_type) === 'reducing' ? 'selected' : '' }}>Reducing Balance</option>
            <option value="compound" {{ old('interest_type', $loanProduct->interest_type) === 'compound' ? 'selected' : '' }}>Compound</option>
          </select>
          @error('interest_type')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Minimum Term (Months) *</label>
          <input type="number" name="min_term_months" value="{{ old('min_term_months', $loanProduct->min_term_months) }}" required min="1"
                 placeholder="e.g., 3"
                 class="form-input py-2.5 px-4">
          @error('min_term_months')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maximum Term (Months) *</label>
          <input type="number" name="max_term_months" value="{{ old('max_term_months', $loanProduct->max_term_months) }}" required min="1"
                 placeholder="e.g., 36"
                 class="form-input py-2.5 px-4">
          @error('max_term_months')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Repayment Frequency *</label>
          <select name="repayment_frequency" required class="form-input py-2.5 px-4">
            <option value="">Select frequency</option>
            <option value="monthly" {{ old('repayment_frequency', $loanProduct->repayment_frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="weekly" {{ old('repayment_frequency', $loanProduct->repayment_frequency) === 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="bi_weekly" {{ old('repayment_frequency', $loanProduct->repayment_frequency) === 'bi_weekly' ? 'selected' : '' }}>Bi-Weekly</option>
          </select>
          @error('repayment_frequency')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Processing Fee (TSh) *</label>
          <input type="number" name="processing_fee" value="{{ old('processing_fee', $loanProduct->processing_fee) }}" required min="0" step="0.01"
                 placeholder="e.g., 5000"
                 class="form-input py-2.5 px-4">
          @error('processing_fee')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Late Fee (TSh) *</label>
          <input type="number" name="late_fee" value="{{ old('late_fee', $loanProduct->late_fee) }}" required min="0" step="0.01"
                 placeholder="e.g., 1000"
                 class="form-input py-2.5 px-4">
          @error('late_fee')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status *</label>
          <select name="status" required class="form-input py-2.5 px-4">
            <option value="">Select status</option>
            <option value="active" {{ old('status', $loanProduct->status) === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $loanProduct->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('status')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Requirements</label>
          <div class="space-y-2 mt-2">
            <label class="flex items-center gap-2">
              <input type="checkbox" name="requires_collateral" value="1" {{ old('requires_collateral', $loanProduct->requires_collateral) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
              <span class="text-sm text-gray-700 dark:text-gray-300">Requires Collateral</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" name="requires_guarantor" value="1" {{ old('requires_guarantor', $loanProduct->requires_guarantor) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
              <span class="text-sm text-gray-700 dark:text-gray-300">Requires Guarantor</span>
            </label>
          </div>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.loan-products.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold transition-colors">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-colors">
          Update Loan Product
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
