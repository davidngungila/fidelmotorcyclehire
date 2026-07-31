@extends('layouts.admin')

@section('breadcrumb', 'SWF \u203A Contributions \u203A Add')
@section('page_title', 'Add Contribution')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.swf.members.show', $swfMember->id) }}" class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Back to Member</span>
    </a>
  </div>

  <div class="glass overflow-hidden">
    <div class="p-6 lg:p-8">
      
      <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 text-white flex items-center justify-center text-2xl shadow-md">
          <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <div>
          <h2 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Add SWF Contribution</h2>
          <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Record contribution for {{ $swfMember->membership_number }} - {{ $swfMember->user->name }}</p>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.swf.contributions.store') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="swf_member_id" value="{{ $swfMember->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Amount (TSh)</label>
            <input type="number" name="amount" step="0.01" min="0" class="form-input" placeholder="e.g., 50000" required>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Enter the contribution amount</p>
          </div>

          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Contribution Date</label>
            <input type="date" name="contribution_date" value="{{ now()->format('Y-m-d') }}" class="form-input" required>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">The date of the contribution</p>
          </div>

          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Payment Method</label>
            <select name="payment_method" class="form-input" required>
              <option value="cash">Cash</option>
              <option value="bank_transfer">Bank Transfer</option>
              <option value="mobile_money">Mobile Money (M-Pesa/Tigo Pesa)</option>
              <option value="cheque">Cheque</option>
              <option value="other">Other</option>
            </select>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">How the payment was made</p>
          </div>

          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Reference Number (Optional)</label>
            <input type="text" name="reference_number" class="form-input" placeholder="e.g., TXN-123456">
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Transaction reference number</p>
          </div>

          <div class="md:col-span-2">
            <label class="form-label uppercase tracking-wider" :class="darkMode ? 'text-primary-300' : 'text-primary-700'">Notes (Optional)</label>
            <textarea name="notes" rows="3" class="form-input" placeholder="Any additional notes..."></textarea>
            <p class="text-xs mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">Additional information about the contribution</p>
          </div>
        </div>

        <div class="pt-6 border-t border-primary-100 dark:border-primary-900/50 flex justify-end gap-3">
          <a href="{{ route('admin.swf.members.show', $swfMember->id) }}" class="px-6 py-3 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold transition-all hover:bg-gray-300 dark:hover:bg-gray-600">
            Cancel
          </a>
          <button type="submit" class="px-8 py-3 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold transition-all shadow-sm hover:shadow-md active:scale-95">
            <i class="fa-solid fa-plus mr-2"></i>Record Contribution
          </button>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
