@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Certificates \u203A Create')
@section('page_title', 'Create Share Certificate')

@section('content')

<div class="space-y-6">

  <div class="glass p-6">
    <form action="{{ route('admin.share-certificates.store') }}" method="POST" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">User *</label>
          <select name="user_id" required class="form-input">
            <option value="">Select User</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
          </select>
          @error('user_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Share Product *</label>
          <select name="share_product_id" required class="form-input">
            <option value="">Select Share Product</option>
            @foreach($shareProducts as $product)
            <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->code }}</option>
            @endforeach
          </select>
          @error('share_product_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Share Purchase</label>
          <select name="share_purchase_id" class="form-input">
            <option value="">Select Share Purchase (Optional)</option>
            @foreach($sharePurchases as $purchase)
            <option value="{{ $purchase->id }}">{{ $purchase->id }} - {{ $purchase->shareProduct->name }}</option>
            @endforeach
          </select>
          @error('share_purchase_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Certificate Number *</label>
          <input type="text" name="certificate_number" required
                 class="form-input"
                 placeholder="e.g., CERT-001">
          @error('certificate_number') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Number of Shares *</label>
          <input type="number" name="number_of_shares" min="1" required
                 class="form-input"
                 placeholder="Enter number of shares">
          @error('number_of_shares') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Issue Date *</label>
          <input type="date" name="issue_date" required
                 class="form-input">
          @error('issue_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Expiry Date</label>
          <input type="date" name="expiry_date"
                 class="form-input">
          @error('expiry_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Status *</label>
          <select name="status" required class="form-input">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="transferred">Transferred</option>
            <option value="cancelled">Cancelled</option>
          </select>
          @error('status') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-primary-300 mb-2">Notes</label>
        <textarea name="notes" rows="4"
                  class="form-input"
                  placeholder="Enter notes"></textarea>
        @error('notes') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="flex items-center gap-3 pt-4">
        <a href="{{ route('admin.share-certificates.index') }}"
           class="px-5 py-2.5 rounded-xl bg-primary-700 hover:bg-primary-600 text-white text-sm font-bold transition-all">
          Cancel
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
          Create Share Certificate
        </button>
      </div>
    </form>
  </div>

</div>

@endsection
