@extends('layouts.admin')

@section('breadcrumb', 'Members › Loans › Create')
@section('page_title', 'Create Loan Application')

@section('content')

<div class="max-w-4xl mx-auto">
  <div class="glass p-6">
    <form method="POST" action="{{ route('admin.loans.store') }}">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Member *</label>
          <select name="user_id" required class="form-input py-2.5 px-4">
            <option value="">Select a member</option>
            @foreach(\App\Models\User::where('is_member', true)->get() as $member)
              <option value="{{ $member->id }}" {{ old('user_id') == $member->id ? 'selected' : '' }}>
                {{ $member->name }} ({{ $member->member_number }})
              </option>
            @endforeach
          </select>
          @error('user_id')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Member Number *</label>
          <input type="text" name="member_number" value="{{ old('member_number') }}" required
                 placeholder="Enter member number"
                 class="form-input py-2.5 px-4">
          @error('member_number')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Principal Amount (TSh) *</label>
          <input type="number" name="principal_amount" value="{{ old('principal_amount') }}" required min="0" step="0.01"
                 placeholder="Enter loan amount"
                 class="form-input py-2.5 px-4">
          @error('principal_amount')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interest Rate (%) *</label>
          <input type="number" name="interest_rate" value="{{ old('interest_rate', 0) }}" required min="0" max="100" step="0.01"
                 placeholder="Enter interest rate"
                 class="form-input py-2.5 px-4">
          @error('interest_rate')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Term (Months) *</label>
          <input type="number" name="term_months" value="{{ old('term_months') }}" required min="1"
                 placeholder="Enter loan term in months"
                 class="form-input py-2.5 px-4">
          @error('term_months')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Application Date *</label>
          <input type="date" name="application_date" value="{{ old('application_date', date('Y-m-d')) }}" required
                 class="form-input py-2.5 px-4">
          @error('application_date')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Purpose *</label>
          <select name="purpose" required class="form-input py-2.5 px-4">
            <option value="">Select purpose</option>
            <option value="business" {{ old('purpose') === 'business' ? 'selected' : '' }}>Business</option>
            <option value="education" {{ old('purpose') === 'education' ? 'selected' : '' }}>Education</option>
            <option value="agriculture" {{ old('purpose') === 'agriculture' ? 'selected' : '' }}>Agriculture</option>
            <option value="personal" {{ old('purpose') === 'personal' ? 'selected' : '' }}>Personal</option>
            <option value="emergency" {{ old('purpose') === 'emergency' ? 'selected' : '' }}>Emergency</option>
            <option value="other" {{ old('purpose') === 'other' ? 'selected' : '' }}>Other</option>
          </select>
          @error('purpose')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Purpose Description</label>
          <textarea name="purpose_description" rows="3"
                    placeholder="Describe the purpose of the loan (optional)"
                    class="form-input py-2.5 px-4">{{ old('purpose_description') }}</textarea>
          @error('purpose_description')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Collateral</label>
          <textarea name="collateral" rows="2"
                    placeholder="Describe collateral (optional)"
                    class="form-input py-2.5 px-4">{{ old('collateral') }}</textarea>
          @error('collateral')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Guarantor</label>
          <textarea name="guarantor" rows="2"
                    placeholder="Describe guarantor information (optional)"
                    class="form-input py-2.5 px-4">{{ old('guarantor') }}</textarea>
          @error('guarantor')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
          <textarea name="notes" rows="2"
                    placeholder="Additional notes (optional)"
                    class="form-input py-2.5 px-4">{{ old('notes') }}</textarea>
          @error('notes')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.loans.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold transition-colors">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-colors">
          Create Loan Application
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
