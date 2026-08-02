@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Revenues \u203A Create Revenue')
@section('page_title', 'Create Revenue')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create Revenue</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Record a new business revenue</p>
    </div>
    <a href="{{ route('admin.revenues.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
      <i class="fa-solid fa-arrow-left"></i> Back
    </a>
  </div>

  <div class="glass rounded-xl p-8">
    <form action="{{ route('admin.revenues.store') }}" method="POST" id="revenueForm" class="space-y-6">
      @csrf
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Revenue Date *</label>
          <input type="date" name="entry_date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" required
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
          @error('entry_date')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Category *</label>
          <select name="category" required
            class="form-select py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Category</option>
            <option value="interest">Interest Income</option>
            <option value="fees">Fees</option>
            <option value="loans">Loan Interest</option>
            <option value="investments">Investment Returns</option>
            <option value="membership">Membership Fees</option>
            <option value="other">Other</option>
          </select>
          @error('category')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Reference</label>
          <input type="text" name="reference" value="{{ old('reference') }}"
            class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            placeholder="Invoice number, receipt number, etc.">
          @error('reference')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description *</label>
        <input type="text" name="description" value="{{ old('description') }}" required
          class="form-input py-2.5 px-4 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Enter revenue description">
        @error('description')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
      </div>

      <div>
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Lines</h3>
          <button type="button" onclick="addLine()" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-semibold transition-all">
            <i class="fa-solid fa-plus"></i> Add Line
          </button>
        </div>

        <div id="linesContainer" class="space-y-3">
          <!-- Lines will be added dynamically -->
        </div>

        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Total Debit:</span>
              <div id="totalDebit" class="text-xl font-bold text-gray-900 dark:text-white">0.00</div>
            </div>
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Total Credit:</span>
              <div id="totalCredit" class="text-xl font-bold text-gray-900 dark:text-white">0.00</div>
            </div>
          </div>
          <div id="balanceStatus" class="mt-2 text-sm font-semibold"></div>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('admin.revenues.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
          Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
          Create & Post Revenue
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
let lineCount = 0;

const accounts = @json($accounts);
const assetAccounts = @json($assetAccounts);

function addLine() {
  lineCount++;
  const container = document.getElementById('linesContainer');
  const lineHtml = `
    <div class="line-item bg-white dark:bg-dark-card rounded-lg p-4 border border-gray-200 dark:border-gray-700" data-line="${lineCount}">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
          <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Account *</label>
          <select name="lines[${lineCount}][account_id]" required onchange="updateTotals()"
            class="form-select py-2 px-3 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <option value="">Select Account</option>
            <optgroup label="Revenue Accounts">
              ${accounts.map(acc => `
                <option value="${acc.id}" data-type="${acc.account_type}">
                  ${acc.account_code} - ${acc.account_name}
                </option>
              `).join('')}
            </optgroup>
            <optgroup label="Asset Accounts">
              ${assetAccounts.map(acc => `
                <option value="${acc.id}" data-type="${acc.account_type}">
                  ${acc.account_code} - ${acc.account_name}
                </option>
              `).join('')}
            </optgroup>
          </select>
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Debit</label>
          <input type="number" name="lines[${lineCount}][debit_amount]" step="0.01" min="0" value="0" onchange="updateTotals()"
            class="form-input py-2 px-3 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Credit</label>
          <input type="number" name="lines[${lineCount}][credit_amount]" step="0.01" min="0" value="0" onchange="updateTotals()"
            class="form-input py-2 px-3 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
        </div>
      </div>
      <div class="mt-3">
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Description</label>
        <input type="text" name="lines[${lineCount}][description]"
          class="form-input py-2 px-3 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-dark-card text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          placeholder="Line description (optional)">
      </div>
      <button type="button" onclick="removeLine(${lineCount})" class="mt-3 text-xs text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
        <i class="fa-solid fa-trash"></i> Remove Line
      </button>
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
    balanceStatus.textContent = '✓ Balanced';
    balanceStatus.className = 'mt-2 text-sm font-semibold text-green-600 dark:text-green-400';
  } else {
    balanceStatus.textContent = `✗ Unbalanced (Difference: ${difference.toFixed(2)})`;
    balanceStatus.className = 'mt-2 text-sm font-semibold text-red-600 dark:text-red-400';
  }
}

// Add initial lines
addLine();
addLine();
</script>
@endpush
