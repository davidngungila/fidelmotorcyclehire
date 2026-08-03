@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Journal Entries \u203A Create Journal Entry')
@section('page_title', 'Create Journal Entry')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Journal Entry</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Create a new journal entry with double-entry bookkeeping</p>
    </div>
    <a href="{{ route('admin.journal-entries.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
  </div>

  <div class="glass rounded-xl p-8">
    <form action="{{ route('admin.journal-entries.store') }}" method="POST" id="journalEntryForm" class="space-y-8">
      @csrf
      
      <!-- Entry Header Section -->
      <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Entry Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Entry Date *</label>
            <input type="date" name="entry_date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" required
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            @error('entry_date')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Entry Type *</label>
            <select name="entry_type" required
              class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select Type</option>
              <option value="manual">Manual</option>
              <option value="automatic">Automatic</option>
              <option value="adjusting">Adjusting</option>
              <option value="closing">Closing</option>
              <option value="loan_disbursement">Loan Disbursement</option>
              <option value="loan_repayment">Loan Repayment</option>
              <option value="investment">Investment</option>
              <option value="share_purchase">Share Purchase</option>
              <option value="swf_contribution">SWF Contribution</option>
              <option value="deposit">Deposit</option>
            </select>
            @error('entry_type')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Financial Period</label>
            <select name="financial_period_id"
              class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
              <option value="">Select Period</option>
              @foreach($financialPeriods as $period)
                <option value="{{ $period->id }}" {{ old('financial_period_id') == $period->id ? 'selected' : '' }}>
                  {{ $period->name }} ({{ $period->start_date->format('M d, Y') }} - {{ $period->end_date->format('M d, Y') }})
                </option>
              @endforeach
            </select>
            @error('financial_period_id')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description *</label>
            <input type="text" name="description" value="{{ old('description') }}" required
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Enter journal entry description">
            @error('description')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Reference</label>
            <input type="text" name="reference" value="{{ old('reference') }}"
              class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
              placeholder="Invoice number, check number, etc.">
            @error('reference')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- Journal Entry Lines Section -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Journal Entry Lines</h3>
          <button type="button" onclick="addLine()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
            <i class="fa-solid fa-plus"></i> Add Line
          </button>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
          <div class="bg-gray-50 dark:bg-gray-800 px-6 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-12 gap-4 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">
              <div class="col-span-5">Account</div>
              <div class="col-span-3 text-right">Debit</div>
              <div class="col-span-3 text-right">Credit</div>
              <div class="col-span-1"></div>
            </div>
          </div>
          
          <div id="linesContainer" class="divide-y divide-gray-200 dark:divide-gray-700">
            <!-- Lines will be added dynamically -->
          </div>
        </div>

        <div class="mt-4 p-6 bg-primary-50 dark:bg-primary-900/20 rounded-xl border border-primary-200 dark:border-primary-800">
          <div class="grid grid-cols-3 gap-6">
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Total Debit:</span>
              <div id="totalDebit" class="text-2xl font-bold text-gray-900 dark:text-white">0.00</div>
            </div>
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Total Credit:</span>
              <div id="totalCredit" class="text-2xl font-bold text-gray-900 dark:text-white">0.00</div>
            </div>
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Balance Status:</span>
              <div id="balanceStatus" class="text-lg font-semibold"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.journal-entries.index') }}" class="px-6 py-3 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          Cancel
        </a>
        <button type="submit" class="px-8 py-3 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          <i class="fa-solid fa-check mr-2"></i> Create Journal Entry
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
let lineCount = 0;

const accounts = @json($accounts);

function addLine() {
  lineCount++;
  const container = document.getElementById('linesContainer');
  const lineHtml = `
    <div class="line-item px-6 py-4" data-line="${lineCount}">
      <div class="grid grid-cols-12 gap-4 items-center">
        <div class="col-span-5">
          <select name="lines[${lineCount}][account_id]" required onchange="updateTotals()"
            class="form-select py-2 px-3 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Account</option>
            ${accounts.map(acc => `
              <option value="${acc.id}" data-type="${acc.account_type}">
                ${acc.account_code} - ${acc.account_name}
              </option>
            `).join('')}
          </select>
        </div>
        <div class="col-span-3">
          <input type="number" name="lines[${lineCount}][debit_amount]" step="0.01" min="0" value="0" onchange="updateTotals()" placeholder="0.00"
            class="form-input py-2 px-3 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent text-right">
        </div>
        <div class="col-span-3">
          <input type="number" name="lines[${lineCount}][credit_amount]" step="0.01" min="0" value="0" onchange="updateTotals()" placeholder="0.00"
            class="form-input py-2 px-3 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent text-right">
        </div>
        <div class="col-span-1 text-right">
          <button type="button" onclick="removeLine(${lineCount})" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
            <i class="fa-solid fa-trash"></i>
          </button>
        </div>
      </div>
      <div class="mt-3">
        <input type="text" name="lines[${lineCount}][description]" placeholder="Line description (optional)"
          class="form-input py-2 px-3 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
      </div>
    </div>
  `;
  container.insertAdjacentHTML('beforeend', lineHtml);
  updateTotals();
}

function removeLine(lineId) {
  const line = document.querySelector(`[data-line="${lineId}"]`);
  if (line) {
    line.remove();
    updateTotals();
  }
}

function updateTotals() {
  let totalDebit = 0;
  let totalCredit = 0;
  
  document.querySelectorAll('.line-item').forEach(line => {
    const debit = parseFloat(line.querySelector('input[name$="[debit_amount]"]')?.value) || 0;
    const credit = parseFloat(line.querySelector('input[name$="[credit_amount]"]')?.value) || 0;
    totalDebit += debit;
    totalCredit += credit;
  });
  
  document.getElementById('totalDebit').textContent = totalDebit.toFixed(2);
  document.getElementById('totalCredit').textContent = totalCredit.toFixed(2);
  
  const balanceStatus = document.getElementById('balanceStatus');
  const difference = Math.abs(totalDebit - totalCredit);
  
  if (difference < 0.01) {
    balanceStatus.innerHTML = '<span class="text-green-600 dark:text-green-400"><i class="fa-solid fa-check-circle mr-1"></i> Balanced</span>';
  } else {
    balanceStatus.innerHTML = `<span class="text-red-600 dark:text-red-400"><i class="fa-solid fa-times-circle mr-1"></i> Unbalanced (Diff: ${difference.toFixed(2)})</span>`;
  }
}

// Add initial lines
addLine();
addLine();
</script>
@endpush
