@extends('layouts.admin')

@section('breadcrumb', 'Members › Loans › Edit')
@section('page_title', 'Edit Loan')

@section('content')

<div class="max-w-4xl mx-auto">
  <div class="glass p-6">
    <form method="POST" action="{{ route('admin.loans.update', $loan->id) }}">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Member *</label>
          <select name="user_id" required class="form-input py-2.5 px-4">
            <option value="">Select a member</option>
            @foreach(\App\Models\User::where('is_member', true)->get() as $member)
              <option value="{{ $member->id }}" {{ old('user_id', $loan->user_id) == $member->id ? 'selected' : '' }}>
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
          <input type="text" name="member_number" value="{{ old('member_number', $loan->member_number) }}" required
                 placeholder="Enter member number"
                 class="form-input py-2.5 px-4">
          @error('member_number')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Loan Number</label>
          <input type="text" value="{{ $loan->loan_number }}" readonly
                 class="form-input py-2.5 px-4 bg-gray-100 dark:bg-gray-700">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Principal Amount (TSh) *</label>
          <input type="number" name="principal_amount" value="{{ old('principal_amount', $loan->principal_amount) }}" required min="0" step="0.01"
                 placeholder="Enter loan amount"
                 class="form-input py-2.5 px-4">
          @error('principal_amount')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Interest Rate (%) *</label>
          <input type="number" name="interest_rate" value="{{ old('interest_rate', $loan->interest_rate) }}" required min="0" max="100" step="0.01"
                 placeholder="Enter interest rate"
                 class="form-input py-2.5 px-4">
          @error('interest_rate')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Term (Months) *</label>
          <input type="number" name="term_months" value="{{ old('term_months', $loan->term_months) }}" required min="1"
                 placeholder="Enter loan term in months"
                 class="form-input py-2.5 px-4">
          @error('term_months')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Application Date *</label>
          <input type="date" name="application_date" value="{{ old('application_date', $loan->application_date ? $loan->application_date->format('Y-m-d') : '') }}" required
                 class="form-input py-2.5 px-4">
          @error('application_date')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Approval Date</label>
          <input type="date" name="approval_date" value="{{ old('approval_date', $loan->approval_date ? $loan->approval_date->format('Y-m-d') : '') }}"
                 class="form-input py-2.5 px-4">
          @error('approval_date')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Disbursement Date</label>
          <input type="date" name="disbursement_date" value="{{ old('disbursement_date', $loan->disbursement_date ? $loan->disbursement_date->format('Y-m-d') : '') }}"
                 class="form-input py-2.5 px-4">
          @error('disbursement_date')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Maturity Date</label>
          <input type="date" name="maturity_date" value="{{ old('maturity_date', $loan->maturity_date ? $loan->maturity_date->format('Y-m-d') : '') }}"
                 class="form-input py-2.5 px-4">
          @error('maturity_date')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Monthly Payment (TSh)</label>
          <input type="number" name="monthly_payment" value="{{ old('monthly_payment', $loan->monthly_payment) }}" min="0" step="0.01"
                 placeholder="Enter monthly payment"
                 class="form-input py-2.5 px-4">
          @error('monthly_payment')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Amount Due (TSh)</label>
          <input type="number" name="total_amount_due" value="{{ old('total_amount_due', $loan->total_amount_due) }}" min="0" step="0.01"
                 placeholder="Enter total amount due"
                 class="form-input py-2.5 px-4">
          @error('total_amount_due')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Amount Paid (TSh)</label>
          <input type="number" name="amount_paid" value="{{ old('amount_paid', $loan->amount_paid) }}" min="0" step="0.01"
                 placeholder="Enter amount paid"
                 class="form-input py-2.5 px-4">
          @error('amount_paid')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Balance (TSh)</label>
          <input type="number" name="balance" value="{{ old('balance', $loan->balance) }}" min="0" step="0.01"
                 placeholder="Enter balance"
                 class="form-input py-2.5 px-4">
          @error('balance')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status *</label>
          <select name="status" required class="form-input py-2.5 px-4">
            <option value="pending" {{ old('status', $loan->status) === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ old('status', $loan->status) === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="disbursed" {{ old('status', $loan->status) === 'disbursed' ? 'selected' : '' }}>Disbursed</option>
            <option value="active" {{ old('status', $loan->status) === 'active' ? 'selected' : '' }}>Active</option>
            <option value="paid" {{ old('status', $loan->status) === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="defaulted" {{ old('status', $loan->status) === 'defaulted' ? 'selected' : '' }}>Defaulted</option>
            <option value="rejected" {{ old('status', $loan->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
          </select>
          @error('status')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Purpose *</label>
          <select name="purpose" required class="form-input py-2.5 px-4">
            <option value="">Select purpose</option>
            <option value="business" {{ old('purpose', $loan->purpose) === 'business' ? 'selected' : '' }}>Business</option>
            <option value="education" {{ old('purpose', $loan->purpose) === 'education' ? 'selected' : '' }}>Education</option>
            <option value="agriculture" {{ old('purpose', $loan->purpose) === 'agriculture' ? 'selected' : '' }}>Agriculture</option>
            <option value="personal" {{ old('purpose', $loan->purpose) === 'personal' ? 'selected' : '' }}>Personal</option>
            <option value="emergency" {{ old('purpose', $loan->purpose) === 'emergency' ? 'selected' : '' }}>Emergency</option>
            <option value="other" {{ old('purpose', $loan->purpose) === 'other' ? 'selected' : '' }}>Other</option>
          </select>
          @error('purpose')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Purpose Description</label>
          <textarea name="purpose_description" rows="3"
                    placeholder="Describe the purpose of the loan (optional)"
                    class="form-input py-2.5 px-4">{{ old('purpose_description', $loan->purpose_description) }}</textarea>
          @error('purpose_description')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Collateral</label>
          <textarea name="collateral" rows="2"
                    placeholder="Describe collateral (optional)"
                    class="form-input py-2.5 px-4">{{ old('collateral', $loan->collateral) }}</textarea>
          @error('collateral')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Guarantor</label>
          <textarea name="guarantor" rows="2"
                    placeholder="Describe guarantor information (optional)"
                    class="form-input py-2.5 px-4">{{ old('guarantor', $loan->guarantor) }}</textarea>
          @error('guarantor')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
          <textarea name="notes" rows="2"
                    placeholder="Additional notes (optional)"
                    class="form-input py-2.5 px-4">{{ old('notes', $loan->notes) }}</textarea>
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
          Update Loan
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
