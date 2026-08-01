@extends('layouts.admin')

@section('breadcrumb', 'System › Share Certificates › New')
@section('page_title', 'Create Share Certificate')

@section('content')
<div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-8">
  <form method="POST" action="{{ route('admin.share-certificates.store') }}" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">User</label>
        <select name="user_id" required class="form-input py-2.5 px-4">
          <option value="">Select user</option>
          @foreach($users as $user)
          <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
          @endforeach
        </select>
        @error('user_id')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Share Product</label>
        <select name="share_product_id" required class="form-input py-2.5 px-4">
          <option value="">Select share product</option>
          @foreach($shareProducts as $product)
          <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->code }}</option>
          @endforeach
        </select>
        @error('share_product_id')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Share Purchase</label>
        <select name="share_purchase_id" class="form-input py-2.5 px-4">
          <option value="">Select share purchase (optional)</option>
          @foreach($sharePurchases as $purchase)
          <option value="{{ $purchase->id }}">{{ $purchase->id }} - {{ $purchase->shareProduct->name }}</option>
          @endforeach
        </select>
        @error('share_purchase_id')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Certificate Number</label>
        <input type="text" name="certificate_number" required
               placeholder="Enter certificate number (e.g., CERT-001)"
               class="form-input py-2.5 px-4">
        @error('certificate_number')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Number of Shares</label>
        <input type="number" name="number_of_shares" min="1" required
               placeholder="Enter number of shares"
               class="form-input py-2.5 px-4">
        @error('number_of_shares')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Issue Date</label>
        <input type="date" name="issue_date" required
               class="form-input py-2.5 px-4">
        @error('issue_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
        <input type="date" name="expiry_date"
               class="form-input py-2.5 px-4">
        @error('expiry_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
        <select name="status" required class="form-input py-2.5 px-4">
          <option value="">Select status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
          <option value="transferred">Transferred</option>
          <option value="cancelled">Cancelled</option>
        </select>
        @error('status')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div>
      <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Notes</label>
      <textarea name="notes" rows="4"
                placeholder="Enter notes (optional)"
                class="form-input py-2.5 px-4"></textarea>
      @error('notes')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center gap-3 pt-4">
      <a href="{{ route('admin.share-certificates.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Create Share Certificate
      </button>
    </div>
  </form>
</div>
@endsection
