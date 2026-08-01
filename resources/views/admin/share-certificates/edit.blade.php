@extends('layouts.admin')

@section('breadcrumb', 'System › Share Certificates › Edit')
@section('page_title', 'Edit Share Certificate')

@section('content')
<div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-8">
  <form method="POST" action="{{ route('admin.share-certificates.update', $shareCertificate) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">User</label>
        <select name="user_id" required class="form-input py-2.5 px-4">
          <option value="">Select user</option>
          @foreach($users as $user)
          <option value="{{ $user->id }}" {{ old('user_id', $shareCertificate->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
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
          <option value="{{ $product->id }}" {{ old('share_product_id', $shareCertificate->share_product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }} - {{ $product->code }}</option>
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
          <option value="{{ $purchase->id }}" {{ old('share_purchase_id', $shareCertificate->share_purchase_id) == $purchase->id ? 'selected' : '' }}>{{ $purchase->id }} - {{ $purchase->shareProduct->name }}</option>
          @endforeach
        </select>
        @error('share_purchase_id')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Certificate Number</label>
        <input type="text" name="certificate_number" value="{{ old('certificate_number', $shareCertificate->certificate_number) }}" required
               placeholder="Enter certificate number (e.g., CERT-001)"
               class="form-input py-2.5 px-4">
        @error('certificate_number')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Number of Shares</label>
        <input type="number" name="number_of_shares" min="1" value="{{ old('number_of_shares', $shareCertificate->number_of_shares) }}" required
               placeholder="Enter number of shares"
               class="form-input py-2.5 px-4">
        @error('number_of_shares')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Issue Date</label>
        <input type="date" name="issue_date" value="{{ old('issue_date', $shareCertificate->issue_date?->format('Y-m-d')) }}" required
               class="form-input py-2.5 px-4">
        @error('issue_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
        <input type="date" name="expiry_date" value="{{ old('expiry_date', $shareCertificate->expiry_date?->format('Y-m-d')) }}"
               class="form-input py-2.5 px-4">
        @error('expiry_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
        <select name="status" required class="form-input py-2.5 px-4">
          <option value="">Select status</option>
          <option value="active" {{ old('status', $shareCertificate->status) === 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ old('status', $shareCertificate->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
          <option value="transferred" {{ old('status', $shareCertificate->status) === 'transferred' ? 'selected' : '' }}>Transferred</option>
          <option value="cancelled" {{ old('status', $shareCertificate->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                class="form-input py-2.5 px-4">{{ old('notes', $shareCertificate->notes) }}</textarea>
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
        Update Share Certificate
      </button>
    </div>
  </form>
</div>
@endsection
