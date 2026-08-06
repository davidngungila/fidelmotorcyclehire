@extends('layouts.admin')

@section('breadcrumb', 'Loans \u203A Create Loan Contract')
@section('page_title', 'Create Loan Contract')

@section('content')
<div x-data="loanCreateForm()" class="space-y-6">
  <div class="flex items-center gap-4">
    <a href="{{ route('admin.loans.index') }}"
       class="p-2.5 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 transition-colors">
      <i class="fa-solid fa-arrow-left text-sm"></i>
    </a>
    <div>
      <p class="text-sm text-primary-600 dark:text-primary-400">
        Create a new motorcycle hire purchase contract
      </p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Main Form Area -->
    <div class="lg:col-span-3">
      <div class="glass p-6 rounded-2xl">
        <form id="loanForm" @submit.prevent="submitLoan" class="space-y-8">
          @csrf
          
          <!-- Customer Selection -->
          <div>
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-user text-primary-500 text-xs"></i> Customer Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Customer *</label>
                <select name="user_id" id="user_id" required class="form-input" @change="updateCustomerInfo()">
                  <option value="">Select a customer</option>
                  @foreach(\App\Models\User::where('role', 'member')->get() as $customer)
                    <option value="{{ $customer->id }}" 
                            data-member-number="{{ $customer->member_number }}"
                            data-phone="{{ $customer->phone }}"
                            {{ old('user_id') == $customer->id ? 'selected' : '' }}>
                      {{ $customer->name }} ({{ $customer->member_number }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Customer Number</label>
                <input type="text" name="member_number" id="member_number" value="{{ old('member_number') }}"
                       placeholder="Auto-filled from customer selection"
                       class="form-input" readonly>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Phone</label>
                <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                       placeholder="Auto-filled from customer selection"
                       class="form-input" readonly>
              </div>
            </div>
          </div>

          <!-- Motorcycle Selection -->
          <div class="border-t border-primary-100 dark:border-primary-900/50 pt-8">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-motorcycle text-primary-500 text-xs"></i> Motorcycle Selection
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="md:col-span-2">
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Motorcycle *</label>
                <select name="motorcycle_id" id="motorcycle_id" required class="form-input" @change="updateMotorcycleInfo()">
                  <option value="">Select a motorcycle</option>
                  @foreach(\App\Models\Motorcycle::where('status', 'Available')->get() as $motorcycle)
                    <option value="{{ $motorcycle->id }}" 
                            data-brand="{{ $motorcycle->brand }}"
                            data-model="{{ $motorcycle->model }}"
                            data-selling-price="{{ $motorcycle->selling_price }}"
                            data-engine-number="{{ $motorcycle->engine_number }}"
                            data-chassis-number="{{ $motorcycle->chassis_number }}"
                            data-registration-number="{{ $motorcycle->registration_number }}"
                            {{ old('motorcycle_id') == $motorcycle->id ? 'selected' : '' }}>
                      {{ $motorcycle->brand }} {{ $motorcycle->model }} ({{ $motorcycle->registration_number }}) - TSh {{ number_format($motorcycle->selling_price, 2) }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Brand</label>
                <input type="text" name="motorcycle_brand" id="motorcycle_brand" value="{{ old('motorcycle_brand') }}"
                       placeholder="Auto-filled from motorcycle selection"
                       class="form-input" readonly>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Model</label>
                <input type="text" name="motorcycle_model" id="motorcycle_model" value="{{ old('motorcycle_model') }}"
                       placeholder="Auto-filled from motorcycle selection"
                       class="form-input" readonly>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Registration Number</label>
                <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number') }}"
                       placeholder="Auto-filled from motorcycle selection"
                       class="form-input" readonly>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Engine Number</label>
                <input type="text" name="engine_number" id="engine_number" value="{{ old('engine_number') }}"
                       placeholder="Auto-filled from motorcycle selection"
                       class="form-input" readonly>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Chassis Number</label>
                <input type="text" name="chassis_number" id="chassis_number" value="{{ old('chassis_number') }}"
                       placeholder="Auto-filled from motorcycle selection"
                       class="form-input" readonly>
              </div>
            </div>
          </div>

          <!-- Loan Details -->
          <div class="border-t border-primary-100 dark:border-primary-900/50 pt-8">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-calculator text-primary-500 text-xs"></i> Loan Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Selling Price (TSh) *</label>
                <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price') }}" required min="0" step="0.01" placeholder="Auto-filled from motorcycle" class="form-input" @input="calculateRepayment()">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Down Payment (TSh) *</label>
                <input type="number" name="down_payment" id="down_payment" value="{{ old('down_payment', 0) }}" required min="0" step="0.01" placeholder="Enter down payment amount" class="form-input" @input="calculateRepayment()">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Interest Rate (%) *</label>
                <input type="number" name="interest_rate" id="interest_rate" value="{{ old('interest_rate', 15) }}" required min="0" max="100" step="0.01" placeholder="Enter interest rate" class="form-input" @input="calculateRepayment()">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Payment Frequency *</label>
                <select name="payment_frequency" id="payment_frequency" required class="form-input" @change="calculateRepayment()">
                  <option value="daily">Daily</option>
                  <option value="weekly" selected>Weekly</option>
                  <option value="biweekly">Bi-weekly</option>
                  <option value="monthly">Monthly</option>
                </select>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Number of Payments *</label>
                <input type="number" name="number_of_payments" id="number_of_payments" value="{{ old('number_of_payments', 52) }}" required min="1" placeholder="Enter number of payments" class="form-input" @input="calculateRepayment()">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Payment Amount (TSh) *</label>
                <input type="number" name="manual_payment_amount" id="manual_payment_amount" value="{{ old('manual_payment_amount') }}" min="0" step="0.01" placeholder="Auto-calculated or enter manually" class="form-input" @input="updateManualPayment()">
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Start Date *</label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="form-input" @input="calculateRepayment()">
              </div>
            </div>
          </div>

          <!-- Repayment Summary -->
          <div class="border-t border-primary-100 dark:border-primary-900/50 pt-8">
            <div class="flex items-center justify-between mb-4">
              <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-primary-500 text-xs"></i> Repayment Summary
              </h3>
              <button type="button" @click="showPreviewModal = true" class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 font-semibold flex items-center gap-1">
                <i class="fa-solid fa-eye"></i> Preview Schedule
              </button>
            </div>
            <div class="bg-primary-50 dark:bg-primary-900/30 rounded-xl p-5">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Principal Amount</p>
                  <p class="text-lg font-bold text-primary-900 dark:text-white" x-text="formatCurrency(principalAmount)">TSh 0.00</p>
                </div>
                <div>
                  <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Total Interest</p>
                  <p class="text-lg font-bold text-orange-600 dark:text-orange-400" x-text="formatCurrency(totalInterest)">TSh 0.00</p>
                </div>
                <div>
                  <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Total Repayment</p>
                  <p class="text-lg font-bold text-primary-600 dark:text-primary-300" x-text="formatCurrency(totalRepayment)">TSh 0.00</p>
                </div>
                <div>
                  <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Payment Amount</p>
                  <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400" x-text="formatCurrency(paymentAmount)">TSh 0.00</p>
                </div>
                <div>
                  <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">Payment Frequency</p>
                  <p class="text-lg font-bold text-primary-900 dark:text-white" x-text="paymentFrequencyLabel">Weekly</p>
                </div>
                <div>
                  <p class="text-xs text-primary-600 dark:text-primary-400 mb-1">End Date</p>
                  <p class="text-lg font-bold text-primary-900 dark:text-white" x-text="endDate">—</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Collateral & Guarantor -->
          <div class="border-t border-primary-100 dark:border-primary-900/50 pt-8">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-shield-halved text-primary-500 text-xs"></i> Collateral & Guarantor
            </h3>
            <div class="space-y-5">
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Collateral Description</label>
                <textarea name="collateral" rows="3" placeholder="Describe additional collateral (optional)" class="form-input">{{ old('collateral') }}</textarea>
              </div>
              <div>
                <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Guarantor Information</label>
                <textarea name="guarantor" rows="3" placeholder="Describe guarantor information (optional)" class="form-input">{{ old('guarantor') }}</textarea>
              </div>
            </div>
          </div>

          <!-- Additional Information -->
          <div class="border-t border-primary-100 dark:border-primary-900/50 pt-8">
            <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2 mb-4">
              <i class="fa-solid fa-note-sticky text-primary-500 text-xs"></i> Additional Information
            </h3>
            <div>
              <label class="form-label uppercase tracking-wider text-primary-700 dark:text-primary-300">Notes</label>
              <textarea name="notes" rows="3" placeholder="Additional notes (optional)" class="form-input">{{ old('notes') }}</textarea>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.loans.index') }}" class="px-6 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-bold transition-all">
              Cancel
            </a>
            <button type="submit" :disabled="loading" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed">
              <i :class="loading ? 'fa-solid fa-spinner fa-spin' : 'fa-solid fa-check'" class="mr-1.5"></i>
              <span x-text="loading ? 'Creating Contract...' : 'Create Contract'">Create Contract</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Right Sidebar Summary -->
    <div class="lg:col-span-1">
      <div class="glass p-6 rounded-2xl sticky top-6">
        <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-4 flex items-center gap-2">
          <i class="fa-solid fa-clipboard-list text-primary-500 text-xs"></i> Contract Summary
        </h3>
        <div class="space-y-4">
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Customer</p>
            <p class="text-sm font-semibold text-primary-900 dark:text-white" x-text="customerName || '—'">—</p>
          </div>
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Motorcycle</p>
            <p class="text-sm font-semibold text-primary-900 dark:text-white" x-text="motorcycleName || '—'">—</p>
          </div>
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Selling Price</p>
            <p class="text-sm font-semibold text-primary-900 dark:text-white" x-text="formatCurrency(sellingPrice)">TSh 0.00</p>
          </div>
          <div>
            <p class="text-xs text-primary-600 dark:text-primary-400">Down Payment</p>
            <p class="text-sm font-semibold text-primary-900 dark:text-white" x-text="formatCurrency(downPayment)">TSh 0.00</p>
          </div>
          <div class="pt-4 border-t border-primary-100 dark:border-primary-900/50">
            <p class="text-xs text-primary-600 dark:text-primary-400 mb-2">Payment Schedule</p>
            <div class="space-y-2 max-h-64 overflow-y-auto">
              <template x-for="(payment, index) in paymentSchedule" :key="index">
                <div class="flex justify-between items-center text-xs bg-primary-50 dark:bg-primary-900/30 p-2 rounded">
                  <span x-text="'Payment ' + (index + 1)"></span>
                  <span x-text="formatCurrency(payment.amount)" class="font-semibold"></span>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Payment Schedule Preview Modal -->
<div x-show="showPreviewModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
  <div class="absolute inset-0 bg-black/50" @click="showPreviewModal = false"></div>
  <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-hidden">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Payment Schedule Preview</h3>
        <button @click="showPreviewModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
          <i class="fa-solid fa-xmark text-xl"></i>
        </button>
      </div>
    </div>
    <div class="p-6 overflow-y-auto max-h-[60vh]">
      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <p class="text-gray-600 dark:text-gray-400">Payment Frequency</p>
            <p class="font-semibold text-gray-900 dark:text-white" x-text="paymentFrequencyLabel">Weekly</p>
          </div>
          <div>
            <p class="text-gray-600 dark:text-gray-400">Total Payments</p>
            <p class="font-semibold text-gray-900 dark:text-white" x-text="paymentSchedule.length">0</p>
          </div>
          <div>
            <p class="text-gray-600 dark:text-gray-400">Payment Amount</p>
            <p class="font-semibold text-emerald-600 dark:text-emerald-400" x-text="formatCurrency(paymentAmount)">TSh 0.00</p>
          </div>
          <div>
            <p class="text-gray-600 dark:text-gray-400">Total Repayment</p>
            <p class="font-semibold text-primary-600 dark:text-primary-300" x-text="formatCurrency(totalRepayment)">TSh 0.00</p>
          </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
          <table class="w-full text-sm">
            <thead>
              <tr class="text-left text-gray-600 dark:text-gray-400">
                <th class="pb-2">#</th>
                <th class="pb-2">Due Date</th>
                <th class="pb-2 text-right">Amount</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="(payment, index) in paymentSchedule" :key="index">
                <tr class="border-t border-gray-100 dark:border-gray-700">
                  <td class="py-2 text-gray-900 dark:text-white" x-text="index + 1"></td>
                  <td class="py-2 text-gray-600 dark:text-gray-400" x-text="payment.date"></td>
                  <td class="py-2 text-right font-semibold text-gray-900 dark:text-white" x-text="formatCurrency(payment.amount)"></td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
      <button @click="showPreviewModal = false" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
        Close
      </button>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function loanCreateForm() {
  return {
    loading: false,
    showPreviewModal: false,
    customerName: '',
    motorcycleName: '',
    sellingPrice: 0,
    downPayment: 0,
    principalAmount: 0,
    totalInterest: 0,
    totalRepayment: 0,
    paymentAmount: 0,
    paymentFrequencyLabel: 'Weekly',
    endDate: '—',
    paymentSchedule: [],
    useManualPayment: false,
    
    updateCustomerInfo() {
      const select = document.getElementById('user_id');
      const selectedOption = select.options[select.selectedIndex];
      
      if (selectedOption && selectedOption.value) {
        document.getElementById('member_number').value = selectedOption.getAttribute('data-member-number') || '';
        document.getElementById('customer_phone').value = selectedOption.getAttribute('data-phone') || '';
        this.customerName = selectedOption.text.split('(')[0].trim();
      } else {
        document.getElementById('member_number').value = '';
        document.getElementById('customer_phone').value = '';
        this.customerName = '';
      }
    },
    
    updateMotorcycleInfo() {
      const select = document.getElementById('motorcycle_id');
      const selectedOption = select.options[select.selectedIndex];
      
      if (selectedOption && selectedOption.value) {
        document.getElementById('motorcycle_brand').value = selectedOption.getAttribute('data-brand') || '';
        document.getElementById('motorcycle_model').value = selectedOption.getAttribute('data-model') || '';
        document.getElementById('registration_number').value = selectedOption.getAttribute('data-registration-number') || '';
        document.getElementById('engine_number').value = selectedOption.getAttribute('data-engine-number') || '';
        document.getElementById('chassis_number').value = selectedOption.getAttribute('data-chassis-number') || '';
        
        const sellingPrice = parseFloat(selectedOption.getAttribute('data-selling-price')) || 0;
        document.getElementById('selling_price').value = sellingPrice;
        this.sellingPrice = sellingPrice;
        this.motorcycleName = selectedOption.text.split('-')[0].trim();
        
        this.calculateRepayment();
      } else {
        document.getElementById('motorcycle_brand').value = '';
        document.getElementById('motorcycle_model').value = '';
        document.getElementById('registration_number').value = '';
        document.getElementById('engine_number').value = '';
        document.getElementById('chassis_number').value = '';
        document.getElementById('selling_price').value = '';
        this.sellingPrice = 0;
        this.motorcycleName = '';
        
        this.calculateRepayment();
      }
    },
    
    calculateRepayment() {
      const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
      const downPayment = parseFloat(document.getElementById('down_payment').value) || 0;
      const interestRate = parseFloat(document.getElementById('interest_rate').value) || 0;
      const paymentFrequency = document.getElementById('payment_frequency').value;
      const numberOfPayments = parseInt(document.getElementById('number_of_payments').value) || 1;
      const startDate = document.getElementById('start_date').value;
      
      this.sellingPrice = sellingPrice;
      this.downPayment = downPayment;
      
      // Calculate principal amount
      this.principalAmount = Math.max(0, sellingPrice - downPayment);
      
      // Calculate total interest (simple interest)
      this.totalInterest = this.principalAmount * (interestRate / 100);
      
      // Calculate total repayment
      this.totalRepayment = this.principalAmount + this.totalInterest;
      
      // Calculate payment amount (unless manual override is set)
      if (!this.useManualPayment) {
        this.paymentAmount = this.totalRepayment / numberOfPayments;
        document.getElementById('manual_payment_amount').value = this.paymentAmount.toFixed(2);
      }
      
      // Set payment frequency label
      const frequencyLabels = {
        'daily': 'Daily',
        'weekly': 'Weekly',
        'biweekly': 'Bi-weekly',
        'monthly': 'Monthly'
      };
      this.paymentFrequencyLabel = frequencyLabels[paymentFrequency] || 'Weekly';
      
      // Calculate end date and payment schedule
      if (startDate && numberOfPayments > 0) {
        const startDateObj = new Date(startDate);
        const paymentIntervals = {
          'daily': 1,
          'weekly': 7,
          'biweekly': 14,
          'monthly': 30
        };
        const intervalDays = paymentIntervals[paymentFrequency] || 7;
        
        this.paymentSchedule = [];
        let currentDate = new Date(startDateObj);
        
        for (let i = 0; i < numberOfPayments; i++) {
          currentDate.setDate(currentDate.getDate() + intervalDays);
          this.paymentSchedule.push({
            amount: this.paymentAmount,
            date: currentDate.toISOString().split('T')[0]
          });
        }
        
        this.endDate = currentDate.toISOString().split('T')[0];
      } else {
        this.paymentSchedule = [];
        this.endDate = '—';
      }
    },
    
    updateManualPayment() {
      const manualAmount = parseFloat(document.getElementById('manual_payment_amount').value) || 0;
      if (manualAmount > 0) {
        this.useManualPayment = true;
        this.paymentAmount = manualAmount;
        // Recalculate total repayment based on manual payment
        const numberOfPayments = parseInt(document.getElementById('number_of_payments').value) || 1;
        this.totalRepayment = this.paymentAmount * numberOfPayments;
        // Recalculate interest
        this.totalInterest = this.totalRepayment - this.principalAmount;
      } else {
        this.useManualPayment = false;
        this.calculateRepayment();
      }
    },
    
    formatCurrency(amount) {
      return 'TSh ' + (amount || 0).toLocaleString('en-TZ', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    
    async submitLoan() {
      this.loading = true;
      const form = document.getElementById('loanForm');
      const formData = new FormData(form);
      
      // Add calculated values
      formData.append('principal_amount', this.principalAmount);
      formData.append('total_interest', this.totalInterest);
      formData.append('total_repayment', this.totalRepayment);
      formData.append('payment_amount', this.paymentAmount);
      formData.append('end_date', this.endDate);
      formData.append('payment_schedule', JSON.stringify(this.paymentSchedule));
      
      try {
        const response = await fetch('{{ route('admin.loans.store') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
          },
          body: formData
        });
        
        const contentType = response.headers.get('content-type');
        
        if (!response.ok) {
          if (contentType && contentType.includes('application/json')) {
            const data = await response.json();
            let errorMessage = 'Failed to create loan contract.';
            if (data.errors) {
              const errorMessages = Object.values(data.errors).flat();
              errorMessage = errorMessages.join('\n');
            } else if (data.message) {
              errorMessage = data.message;
            }
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: errorMessage
            });
          } else {
            const text = await response.text();
            console.error('Server returned HTML instead of JSON:', text);
            Swal.fire({
              icon: 'error',
              title: 'Server Error',
              text: 'Please check the form for validation errors.'
            });
          }
          this.loading = false;
          return;
        }
        
        const data = await response.json();
        
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Loan contract created successfully.',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            window.location.href = '{{ route('admin.loans.index') }}';
          });
        } else {
          let errorMessage = 'Failed to create loan contract.';
          if (data.errors) {
            const errorMessages = Object.values(data.errors).flat();
            errorMessage = errorMessages.join('\n');
          } else if (data.message) {
            errorMessage = data.message;
          }
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage
          });
          this.loading = false;
        }
      } catch (error) {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: error.message || 'Failed to create loan contract.'
        });
        this.loading = false;
      }
    }
  };
}
</script>
@endpush
@endsection
