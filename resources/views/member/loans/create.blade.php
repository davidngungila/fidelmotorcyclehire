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
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Purpose Description *</label>
                <textarea name="purpose_description" rows="3" placeholder="Describe the purpose of the loan in detail" required class="form-input">{{ old('purpose_description') }}</textarea>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Employment Status *</label>
                <select name="employment_status" required class="form-input">
                  <option value="">Select employment status</option>
                  <option value="employed" {{ old('employment_status') === 'employed' ? 'selected' : '' }}>Employed</option>
                  <option value="self-employed" {{ old('employment_status') === 'self-employed' ? 'selected' : '' }}>Self-Employed</option>
                  <option value="business-owner" {{ old('employment_status') === 'business-owner' ? 'selected' : '' }}>Business Owner</option>
                  <option value="retired" {{ old('employment_status') === 'retired' ? 'selected' : '' }}>Retired</option>
                  <option value="unemployed" {{ old('employment_status') === 'unemployed' ? 'selected' : '' }}>Unemployed</option>
                </select>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Employer Name</label>
                <input type="text" name="employer_name" value="{{ old('employer_name') }}" placeholder="Current employer" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Monthly Income (TSh) *</label>
                <input type="number" name="monthly_income" value="{{ old('monthly_income') }}" required min="0" step="0.01" placeholder="Enter monthly income" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Other Income (TSh)</label>
                <input type="number" name="other_income" value="{{ old('other_income') }}" min="0" step="0.01" placeholder="Additional income sources" class="form-input">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Work Experience (Years)</label>
                <input type="number" name="work_experience" value="{{ old('work_experience') }}" min="0" placeholder="Years of experience" class="form-input">
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
                <input type="number" name="principal_amount" id="principal_amount" value="{{ old('principal_amount') }}" required min="0" step="0.01" placeholder="Enter loan amount" class="form-input" @input="calculateRepayment">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Interest Rate (%) *</label>
                <input type="number" name="interest_rate" id="interest_rate" value="{{ old('interest_rate') }}" required min="0" max="100" step="0.01" placeholder="Auto-filled from product" class="form-input" @input="calculateRepayment">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Term (Months) *</label>
                <input type="number" name="term_months" id="term_months" value="{{ old('term_months') }}" required min="1" placeholder="Auto-filled from product" class="form-input" @input="calculateRepayment">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Repayment Frequency</label>
                <select name="repayment_frequency" class="form-input" @change="calculateRepayment">
                  <option value="monthly">Monthly</option>
                  <option value="biweekly">Bi-weekly</option>
                  <option value="weekly">Weekly</option>
                </select>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Preferred Repayment Date</label>
                <select name="preferred_repayment_date" class="form-input">
                  <option value="1">1st of month</option>
                  <option value="5">5th of month</option>
                  <option value="10">10th of month</option>
                  <option value="15">15th of month</option>
                  <option value="20">20th of month</option>
                  <option value="25">25th of month</option>
                  <option value="30">30th of month</option>
                </select>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Collateral Value (TSh)</label>
                <input type="number" name="collateral_value" value="{{ old('collateral_value') }}" min="0" step="0.01" placeholder="Estimated collateral value" class="form-input">
              </div>
            </div>
            
            <!-- Repayment Summary Preview -->
            <div x-show="repaymentSummary.monthlyPayment > 0" class="p-4 rounded-xl bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800">
              <h4 class="font-bold text-primary-900 dark:text-white text-xs mb-3">Repayment Summary</h4>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div>
                  <p class="text-[10px] text-primary-600 dark:text-primary-400">Monthly Payment</p>
                  <p class="text-sm font-bold text-primary-900 dark:text-white" x-text="'TSh ' + formatNumber(repaymentSummary.monthlyPayment)"></p>
                </div>
                <div>
                  <p class="text-[10px] text-primary-600 dark:text-primary-400">Total Interest</p>
                  <p class="text-sm font-bold text-primary-900 dark:text-white" x-text="'TSh ' + formatNumber(repaymentSummary.totalInterest)"></p>
                </div>
                <div>
                  <p class="text-[10px] text-primary-600 dark:text-primary-400">Total Repayment</p>
                  <p class="text-sm font-bold text-primary-900 dark:text-white" x-text="'TSh ' + formatNumber(repaymentSummary.totalRepayment)"></p>
                </div>
                <div>
                  <p class="text-[10px] text-primary-600 dark:text-primary-400">Debt-to-Income</p>
                  <p class="text-sm font-bold" :class="repaymentSummary.debtToIncome <= 40 ? 'text-green-600' : 'text-red-600'" x-text="repaymentSummary.debtToIncome + '%'"></p>
                </div>
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
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <div :class="currentTab >= 1 ? 'bg-primary-600' : 'bg-primary-200 dark:bg-primary-800'" class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold transition-colors flex-shrink-0">1</div>
            <div class="flex-1">
              <p :class="currentTab >= 1 ? 'text-primary-900 dark:text-white font-semibold' : 'text-primary-500'" class="text-sm transition-colors">Basic Info</p>
              <p class="text-[10px] text-primary-400">Personal & employment details</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <div :class="currentTab >= 2 ? 'bg-primary-600' : 'bg-primary-200 dark:bg-primary-800'" class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold transition-colors flex-shrink-0">2</div>
            <div class="flex-1">
              <p :class="currentTab >= 2 ? 'text-primary-900 dark:text-white font-semibold' : 'text-primary-500'" class="text-sm transition-colors">Loan Details</p>
              <p class="text-[10px] text-primary-400">Amount, interest & term</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <div :class="currentTab >= 3 ? 'bg-primary-600' : 'bg-primary-200 dark:bg-primary-800'" class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold transition-colors flex-shrink-0">3</div>
            <div class="flex-1">
              <p :class="currentTab >= 3 ? 'text-primary-900 dark:text-white font-semibold' : 'text-primary-500'" class="text-sm transition-colors">Collateral</p>
              <p class="text-[10px] text-primary-400">Security & guarantor info</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <div :class="currentTab >= 4 ? 'bg-primary-600' : 'bg-primary-200 dark:bg-primary-800'" class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold transition-colors flex-shrink-0">4</div>
            <div class="flex-1">
              <p :class="currentTab >= 4 ? 'text-primary-900 dark:text-white font-semibold' : 'text-primary-500'" class="text-sm transition-colors">Additional</p>
              <p class="text-[10px] text-primary-400">Notes & final review</p>
            </div>
          </div>
        </div>
        <div class="mt-6 pt-4 border-t border-primary-100 dark:border-primary-900/50">
          <div class="flex items-center justify-between text-xs text-primary-600 dark:text-primary-400 mb-2">
            <span>Completion</span>
            <span x-text="progress + '%'"></span>
          </div>
          <div class="w-full bg-primary-100 dark:bg-primary-900/40 rounded-full h-3">
            <div :style="'width: ' + progress + '%'" class="bg-primary-600 h-3 rounded-full transition-all duration-300"></div>
          </div>
          <div class="mt-3 grid grid-cols-2 gap-2 text-[10px]">
            <div class="p-2 rounded-lg bg-primary-50 dark:bg-primary-900/20">
              <p class="text-primary-500">Steps Completed</p>
              <p class="font-bold text-primary-900 dark:text-white" x-text="currentTab + ' / 4'"></p>
            </div>
            <div class="p-2 rounded-lg bg-primary-50 dark:bg-primary-900/20">
              <p class="text-primary-500">Est. Time</p>
              <p class="font-bold text-primary-900 dark:text-white">~5 min</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Repayment Schedule Preview -->
      <div x-show="repaymentSchedule.length > 0" class="glass p-5 rounded-2xl sticky top-72 mt-4">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4">Repayment Schedule Preview</h3>
        <div class="max-h-64 overflow-y-auto">
          <table class="w-full text-xs">
            <thead class="sticky top-0 bg-white dark:bg-gray-800">
              <tr class="text-left text-primary-500">
                <th class="pb-2">#</th>
                <th class="pb-2">Due Date</th>
                <th class="pb-2">Payment</th>
                <th class="pb-2">Balance</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="(payment, index) in repaymentSchedule.slice(0, 6)" :key="index">
                <tr class="border-b border-primary-100 dark:border-primary-800">
                  <td class="py-2" x-text="payment.installment"></td>
                  <td class="py-2" x-text="payment.dueDate"></td>
                  <td class="py-2 font-bold" x-text="'TSh ' + formatNumber(payment.amount)"></td>
                  <td class="py-2" x-text="'TSh ' + formatNumber(payment.balance)"></td>
                </tr>
              </template>
              <tr x-show="repaymentSchedule.length > 6">
                <td colspan="4" class="py-2 text-center text-primary-500">
                  + <span x-text="repaymentSchedule.length - 6"></span> more payments
                </td>
              </tr>
            </tbody>
          </table>
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
    repaymentSummary: {
      monthlyPayment: 0,
      totalInterest: 0,
      totalRepayment: 0,
      debtToIncome: 0
    },
    repaymentSchedule: [],
    
    formatNumber(num) {
      return parseFloat(num).toLocaleString('en-TZ', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    
    calculateRepayment() {
      const principal = parseFloat(document.getElementById('principal_amount')?.value) || 0;
      const interestRate = parseFloat(document.getElementById('interest_rate')?.value) || 0;
      const termMonths = parseInt(document.getElementById('term_months')?.value) || 0;
      const monthlyIncome = parseFloat(this.loanData.monthly_income) || 0;
      
      if (principal > 0 && interestRate > 0 && termMonths > 0) {
        // Calculate monthly payment using amortization formula
        const monthlyRate = interestRate / 100 / 12;
        const monthlyPayment = principal * (monthlyRate * Math.pow(1 + monthlyRate, termMonths)) / (Math.pow(1 + monthlyRate, termMonths) - 1);
        const totalRepayment = monthlyPayment * termMonths;
        const totalInterest = totalRepayment - principal;
        const debtToIncome = monthlyIncome > 0 ? ((monthlyPayment / monthlyIncome) * 100).toFixed(1) : 0;
        
        this.repaymentSummary = {
          monthlyPayment: monthlyPayment.toFixed(2),
          totalInterest: totalInterest.toFixed(2),
          totalRepayment: totalRepayment.toFixed(2),
          debtToIncome: debtToIncome
        };
        
        // Generate repayment schedule
        this.generateRepaymentSchedule(principal, monthlyRate, termMonths, monthlyPayment);
      } else {
        this.repaymentSummary = {
          monthlyPayment: 0,
          totalInterest: 0,
          totalRepayment: 0,
          debtToIncome: 0
        };
        this.repaymentSchedule = [];
      }
    },
    
    generateRepaymentSchedule(principal, monthlyRate, termMonths, monthlyPayment) {
      const schedule = [];
      let balance = principal;
      const startDate = new Date();
      
      for (let i = 1; i <= termMonths; i++) {
        const interestPortion = balance * monthlyRate;
        const principalPortion = monthlyPayment - interestPortion;
        balance = Math.max(0, balance - principalPortion);
        
        const dueDate = new Date(startDate);
        dueDate.setMonth(dueDate.getMonth() + i);
        
        schedule.push({
          installment: i,
          dueDate: dueDate.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }),
          amount: monthlyPayment.toFixed(2),
          principal: principalPortion.toFixed(2),
          interest: interestPortion.toFixed(2),
          balance: balance.toFixed(2)
        });
      }
      
      this.repaymentSchedule = schedule;
    },
    
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
        
        this.calculateRepayment();
      } else {
        document.getElementById('interest_rate').value = '';
        document.getElementById('term_months').value = '';
        document.getElementById('principal_amount').value = '';
        document.getElementById('principal_amount').min = 0;
        document.getElementById('principal_amount').max = '';
        
        this.repaymentSummary = {
          monthlyPayment: 0,
          totalInterest: 0,
          totalRepayment: 0,
          debtToIncome: 0
        };
        this.repaymentSchedule = [];
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
          this.calculateRepayment();
          Swal.fire({
            icon: 'success',
            title: 'Saved!',
            text: 'Basic information saved successfully.',
            timer: 1500,
            showConfirmButton: false
          });
          this.currentTab = 2;
        } else {
          let errorMessage = data.message || 'Failed to save basic information.';
          if (data.errors) {
            const errorMessages = Object.values(data.errors).flat();
            errorMessage = errorMessages.join(', ');
          }
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage
          });
        }
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.message || 'Failed to save basic information.'
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
          let errorMessage = data.message || 'Failed to save loan details.';
          if (data.errors) {
            const errorMessages = Object.values(data.errors).flat();
            errorMessage = errorMessages.join(', ');
          }
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage
          });
        }
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.message || 'Failed to save loan details.'
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
          let errorMessage = data.message || 'Failed to save collateral information.';
          if (data.errors) {
            const errorMessages = Object.values(data.errors).flat();
            errorMessage = errorMessages.join(', ');
          }
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage
          });
        }
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.message || 'Failed to save collateral information.'
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
          let errorMessage = data.message || 'Failed to submit loan application.';
          if (data.errors) {
            const errorMessages = Object.values(data.errors).flat();
            errorMessage = errorMessages.join(', ');
          }
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage
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
