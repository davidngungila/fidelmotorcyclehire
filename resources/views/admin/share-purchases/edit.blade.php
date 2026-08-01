@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Purchases \u203A Edit')
@section('page_title', 'Edit Share Purchase')

@section('content')

<div class="space-y-6">

  <div class="glass p-6">
    <form action="{{ route('admin.share-purchases.update', $sharePurchase) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">User *</label>
          <select name="user_id" required class="form-input">
            <option value="">Select User</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('user_id', $sharePurchase->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
          </select>
          @error('user_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Share Product *</label>
          <select name="share_product_id" required class="form-input">
            <option value="">Select Share Product</option>
            @foreach($shareProducts as $product)
            <option value="{{ $product->id }}" {{ old('share_product_id', $sharePurchase->share_product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }} - {{ $product->code }}</option>
            @endforeach
          </select>
          @error('share_product_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Number of Shares *</label>
          <input type="number" name="number_of_shares" min="1" value="{{ old('number_of_shares', $sharePurchase->number_of_shares) }}" required
                 class="form-input"
                 placeholder="Enter number of shares">
          @error('number_of_shares') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Price Per Share *</label>
          <input type="number" name="price_per_share" step="0.01" min="0" value="{{ old('price_per_share', $sharePurchase->price_per_share) }}" required
                 class="form-input"
                 placeholder="0.00">
          @error('price_per_share') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Total Amount *</label>
          <input type="number" name="total_amount" step="0.01" min="0" value="{{ old('total_amount', $sharePurchase->total_amount) }}" required
                 class="form-input"
                 placeholder="0.00">
          @error('total_amount') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Purchase Date *</label>
          <input type="date" name="purchase_date" value="{{ old('purchase_date', $sharePurchase->purchase_date->format('Y-m-d')) }}" required
                 class="form-input">
          @error('purchase_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Payment Status *</label>
          <select name="payment_status" required class="form-input">
            <option value="pending" {{ old('payment_status', $sharePurchase->payment_status) === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ old('payment_status', $sharePurchase->payment_status) === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="cancelled" {{ old('payment_status', $sharePurchase->payment_status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
          </select>
          @error('payment_status') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-primary-300 mb-2">Notes</label>
        <textarea name="notes" rows="4"
                  class="form-input"
                  placeholder="Enter notes">{{ old('notes', $sharePurchase->notes) }}</textarea>
        @error('notes') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="flex items-center gap-3 pt-4">
        <a href="{{ route('admin.share-purchases.index') }}"
           class="px-5 py-2.5 rounded-xl bg-primary-700 hover:bg-primary-600 text-white text-sm font-bold transition-all">
          Cancel
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
          Update Share Purchase
        </button>
      </div>
    </form>
  </div>

</div>

@endsection
