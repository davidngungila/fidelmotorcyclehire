@extends('layouts.member')

@section('breadcrumb', 'Loans \u203A Apply for Loan')
@section('page_title', 'Apply for Loan')

@section('content')
<div x-data="loanCreateForm()" class="space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ route('member.loans.index') }}"
       class="p-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
      <p class="text-sm text-primary-600 dark:text-primary-400">
        Apply for a new loan with comprehensive information
      </p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Main Form Area -->
    <div class="lg:col-span-3">
      <div class="glass p-6 rounded-2xl">
        <!-- Tabs Navigation -->
        <div class="flex flex-wrap gap-2 mb-6 border-b border-primary-100 dark:border-primary-900/50 pb-4">
          <button @click="currentTab = 1" :class="currentTab === 1 ? 'bg-primary-600 text-white' : 'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300'" class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-colors">
            <i class="fa-solid fa-user text-[10px]"></i> Basic Info
          </button>
          <button @click="currentTab = 2" :class="currentTab === 2 ? 'bg-primary-600 text-white' : 'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300'" class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-colors">
            <i class="fa-solid fa-calculator text-[10px]"></i> Loan Details
          </button>
          <button @click="currentTab = 3" :class="currentTab === 3 ? 'bg-primary-600 text-white' : 'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300'" class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-colors">
            <i class="fa-solid fa-shield-halved text-[10px]"></i> Collateral
          </button>
          <button @click="currentTab = 4" :class="currentTab === 4 ? 'bg-primary-600 text-white' : 'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300'" class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold transition-colors">
            <i class="fa-solid fa-note-sticky text-[10px]"></i> Additional
          </button>
        </div>

        <!-- Tab 1: Basic Information -->
        <div x-show="currentTab === 1" x-transition class="space-y-5">
          <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
            <i class="fa-solid fa-user text-primary-500 text-xs"></i> Basic Information
          </h3>
          <form id="basicInfoForm" @submit.prevent="saveBasicInfo" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Loan Product</label>
                <select name="loan_product_id" id="loan_product_id" class="form-input" onchange="updateLoanDetails()">
                  <option value="">Select a loan product (optional)</option>
                  @foreach(\App\Models\LoanProduct::active()->get() as $product)
                    <option value="{{ $product->id }}" 
                            data-min-amount="{{ $product->min_amount }}"
                            data-max-amount="{{ $product->max_amount }}"
                            data-interest-rate="{{ $product->interest_rate }}"
                            data-min-term="{{ $product->min_term_months }}"
                            data-max-term="{{ $product->max_term_months }}"
                            {{ old('loan_product_id') == $product->id ? 'selected' : '' }}>
                      {{ $product->name }} ({{ $product->code }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Member Number</label>
                <input type="text" name="member_number" value="{{ $memberNumber }}" class="form-input" readonly>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Application Date *</label>
                <input type="date" name="application_date" value="{{ old('application_date', date('Y-m-d')) }}" required class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Purpose *</label>
                <select name="purpose" required class="form-input">
                  <option value="">Select purpose</option>
                  <option value="business" {{ old('purpose') === 'business' ? 'selected' : '' }}>Business</option>
                  <option value="education" {{ old('purpose') === 'education' ? 'selected' : '' }}>Education</option>
                  <option value="agriculture" {{ old('purpose') === 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                  <option value="personal" {{ old('purpose') === 'personal' ? 'selected' : '' }}>Personal</option>
                  <option value="emergency" {{ old('purpose') === 'emergency' ? 'selected' : '' }}>Emergency</option>
                  <option value="other" {{ old('purpose') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
              </div>
              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Purpose Description</label>
                <textarea name="purpose_description" rows="3" placeholder="Describe the purpose of the loan (optional)" class="form-input">{{ old('purpose_description') }}</textarea>
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 pt-4">
              <button type="submit" :disabled="isSaving" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isSaving">Save & Continue</span>
                <span x-show="isSaving"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...</span>
              </button>
            </div>
          </form>
        </div>

        <!-- Tab 2: Loan Details -->
        <div x-show="currentTab === 2" x-transition class="space-y-5">
          <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
            <i class="fa-solid fa-calculator text-primary-500 text-xs"></i> Loan Details
          </h3>
          <form id="loanDetailsForm" @submit.prevent="saveLoanDetails" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Principal Amount (TSh) *</label>
                <input type="number" name="principal_amount" id="principal_amount" value="{{ old('principal_amount') }}" required min="0" step="0.01" placeholder="Enter loan amount" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Interest Rate (%) *</label>
                <input type="number" name="interest_rate" id="interest_rate" value="{{ old('interest_rate') }}" required min="0" max="100" step="0.01" placeholder="Auto-filled from product" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Term (Months) *</label>
                <input type="number" name="term_months" id="term_months" value="{{ old('term_months') }}" required min="1" placeholder="Auto-filled from product" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Repayment Frequency</label>
                <select name="repayment_frequency" class="form-input">
                  <option value="monthly">Monthly</option>
                  <option value="biweekly">Bi-weekly</option>
                  <option value="weekly">Weekly</option>
                </select>
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 pt-4">
              <button type="button" @click="currentTab = 1" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold transition-colors">
                Back
              </button>
              <button type="submit" :disabled="isSaving" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isSaving">Save & Continue</span>
                <span x-show="isSaving"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...</span>
              </button>
            </div>
          </form>
        </div>

        <!-- Tab 3: Collateral -->
        <div x-show="currentTab === 3" x-transition class="space-y-5">
          <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-primary-500 text-xs"></i> Collateral & Guarantor
          </h3>
          <form id="collateralForm" @submit.prevent="saveCollateral" class="space-y-5">
            @csrf
            <div class="space-y-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Collateral Description</label>
                <textarea name="collateral" rows="4" placeholder="Describe collateral (optional)" class="form-input">{{ old('collateral') }}</textarea>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Guarantor Information</label>
                <textarea name="guarantor" rows="4" placeholder="Describe guarantor information (optional)" class="form-input">{{ old('guarantor') }}</textarea>
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 pt-4">
              <button type="button" @click="currentTab = 2" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold transition-colors">
                Back
              </button>
              <button type="submit" :disabled="isSaving" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isSaving">Save & Continue</span>
                <span x-show="isSaving"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...</span>
              </button>
            </div>
          </form>
        </div>

        <!-- Tab 4: Additional -->
        <div x-show="currentTab === 4" x-transition class="space-y-5">
          <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
            <i class="fa-solid fa-note-sticky text-primary-500 text-xs"></i> Additional Information
          </h3>
          <form id="additionalForm" @submit.prevent="submitLoan" class="space-y-5">
            @csrf
            <div class="space-y-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Notes</label>
                <textarea name="notes" rows="4" placeholder="Additional notes (optional)" class="form-input">{{ old('notes') }}</textarea>
              </div>
            </div>
            <div class="flex items-center justify-end gap-3 pt-4">
              <button type="button" @click="currentTab = 3" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold transition-colors">
                Back
              </button>
              <button type="submit" :disabled="isSubmitting" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isSubmitting"><i class="fa-solid fa-check mr-2"></i> Submit Loan Application</span>
                <span x-show="isSubmitting"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Submitting...</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Progress Sidebar -->
    <div class="lg:col-span-1">
      <div class="glass p-5 rounded-2xl sticky top-6">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4">Application Progress</h3>
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <div :class="currentTab >= 1 ? 'bg-primary-600' : 'bg-primary-200 dark:bg-primary-800'" class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold transition-colors">1</div>
            <span :class="currentTab >= 1 ? 'text-primary-900 dark:text-white font-semibold' : 'text-primary-500'" class="text-sm transition-colors">Basic Info</span>
          </div>
          <div class="flex items-center gap-3">
            <div :class="currentTab >= 2 ? 'bg-primary-600' : 'bg-primary-200 dark:bg-primary-800'" class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold transition-colors">2</div>
            <span :class="currentTab >= 2 ? 'text-primary-900 dark:text-white font-semibold' : 'text-primary-500'" class="text-sm transition-colors">Loan Details</span>
          </div>
          <div class="flex items-center gap-3">
            <div :class="currentTab >= 3 ? 'bg-primary-600' : 'bg-primary-200 dark:bg-primary-800'" class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold transition-colors">3</div>
            <span :class="currentTab >= 3 ? 'text-primary-900 dark:text-white font-semibold' : 'text-primary-500'" class="text-sm transition-colors">Collateral</span>
          </div>
          <div class="flex items-center gap-3">
            <div :class="currentTab >= 4 ? 'bg-primary-600' : 'bg-primary-200 dark:bg-primary-800'" class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold transition-colors">4</div>
            <span :class="currentTab >= 4 ? 'text-primary-900 dark:text-white font-semibold' : 'text-primary-500'" class="text-sm transition-colors">Additional</span>
          </div>
        </div>
        <div class="mt-6 pt-4 border-t border-primary-100 dark:border-primary-900/50">
          <div class="flex items-center justify-between text-xs text-primary-600 dark:text-primary-400 mb-2">
            <span>Completion</span>
            <span x-text="progress + '%'"></span>
          </div>
          <div class="w-full bg-primary-100 dark:bg-primary-900/40 rounded-full h-2">
            <div :style="'width: ' + progress + '%'" class="bg-primary-600 h-2 rounded-full transition-all duration-300"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function loanCreateForm() {
  return {
    currentTab: 1,
    progress: 25,
    isSaving: false,
    isSubmitting: false,
    loanData: {},
    
    updateLoanDetails() {
      const select = document.getElementById('loan_product_id');
      const selectedOption = select.options[select.selectedIndex];
      
      if (selectedOption && selectedOption.value) {
        document.getElementById('interest_rate').value = selectedOption.getAttribute('data-interest-rate') || '';
        document.getElementById('term_months').value = selectedOption.getAttribute('data-min-term') || '';
        
        const minAmount = selectedOption.getAttribute('data-min-amount');
        const maxAmount = selectedOption.getAttribute('data-max-amount');
        const principalAmountInput = document.getElementById('principal_amount');
        
        if (principalAmountInput.value === '' || parseFloat(principalAmountInput.value) < parseFloat(minAmount)) {
          principalAmountInput.value = minAmount;
        }
        principalAmountInput.min = minAmount;
        principalAmountInput.max = maxAmount;
      } else {
        document.getElementById('interest_rate').value = '';
        document.getElementById('term_months').value = '';
        document.getElementById('principal_amount').value = '';
        document.getElementById('principal_amount').min = 0;
        document.getElementById('principal_amount').max = '';
      }
    },
    
    async saveBasicInfo() {
      this.isSaving = true;
      const form = document.getElementById('basicInfoForm');
      const formData = new FormData(form);
      
      try {
        const response = await fetch('/member/loans/store-basic-info', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          this.loanData = { ...this.loanData, ...data.loan_data };
          this.progress = 50;
          Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: 'Basic information saved successfully.',
            timer: 1500,
            showConfirmButton: false
          });
          this.currentTab = 2;
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message || 'Failed to save basic information.'
          });
        }
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to save basic information.'
        });
      }
      
      this.isSaving = false;
    },
    
    async saveLoanDetails() {
      this.isSaving = true;
      const form = document.getElementById('loanDetailsForm');
      const formData = new FormData(form);
      
      try {
        const response = await fetch('/member/loans/store-loan-details', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          this.loanData = { ...this.loanData, ...data.loan_data };
          this.progress = 75;
          Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: 'Loan details saved successfully.',
            timer: 1500,
            showConfirmButton: false
          });
          this.currentTab = 3;
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message || 'Failed to save loan details.'
          });
        }
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to save loan details.'
        });
      }
      
      this.isSaving = false;
    },
    
    async saveCollateral() {
      this.isSaving = true;
      const form = document.getElementById('collateralForm');
      const formData = new FormData(form);
      
      try {
        const response = await fetch('/member/loans/store-collateral', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          this.loanData = { ...this.loanData, ...data.loan_data };
          this.progress = 90;
          Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: 'Collateral information saved successfully.',
            timer: 1500,
            showConfirmButton: false
          });
          this.currentTab = 4;
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message || 'Failed to save collateral information.'
          });
        }
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to save collateral information.'
        });
      }
      
      this.isSaving = false;
    },
    
    async submitLoan() {
      this.isSubmitting = true;
      const form = document.getElementById('additionalForm');
      const formData = new FormData(form);
      
      // Add all accumulated data
      Object.keys(this.loanData).forEach(key => {
        formData.append(key, this.loanData[key]);
      });
      
      try {
        const response = await fetch('/member/loans', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
          this.progress = 100;
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Loan application submitted successfully.',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            window.location.href = '/member/loans';
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message || 'Failed to submit loan application.'
          });
        }
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to submit loan application.'
        });
      }
      
      this.isSubmitting = false;
    }
  };
}

// Auto-fill on page load
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('[x-data]');
  if (form && form.__x) {
    form.__x.$data.updateLoanDetails();
  }
});
</script>
@endpush

@endsection
