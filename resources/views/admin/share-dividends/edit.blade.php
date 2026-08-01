@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Dividends \u203A Edit')
@section('page_title', 'Edit Share Dividend')

@section('content')

<div class="space-y-6">

  <div class="glass p-6">
    <form action="{{ route('admin.share-dividends.update', $shareDividend) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Share Product *</label>
          <select name="share_product_id" required class="form-input">
            <option value="">Select Share Product</option>
            @foreach($shareProducts as $product)
            <option value="{{ $product->id }}" {{ old('share_product_id', $shareDividend->share_product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }} - {{ $product->code }}</option>
            @endforeach
          </select>
          @error('share_product_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">User *</label>
          <select name="user_id" required class="form-input">
            <option value="">Select User</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('user_id', $shareDividend->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
          </select>
          @error('user_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Share Certificate</label>
          <select name="share_certificate_id" class="form-input">
            <option value="">Select Share Certificate (Optional)</option>
            @foreach($shareCertificates as $certificate)
            <option value="{{ $certificate->id }}" {{ old('share_certificate_id', $shareDividend->share_certificate_id) == $certificate->id ? 'selected' : '' }}>{{ $certificate->certificate_number }}</option>
            @endforeach
          </select>
          @error('share_certificate_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Number of Shares *</label>
          <input type="number" name="number_of_shares" min="1" value="{{ old('number_of_shares', $shareDividend->number_of_shares) }}" required
                 class="form-input"
                 placeholder="Enter number of shares">
          @error('number_of_shares') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Dividend Per Share *</label>
          <input type="number" name="dividend_per_share" step="0.01" min="0" value="{{ old('dividend_per_share', $shareDividend->dividend_per_share) }}" required
                 class="form-input"
                 placeholder="0.00">
          @error('dividend_per_share') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Total Dividend *</label>
          <input type="number" name="total_dividend" step="0.01" min="0" value="{{ old('total_dividend', $shareDividend->total_dividend) }}" required
                 class="form-input"
                 placeholder="0.00">
          @error('total_dividend') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Declaration Date *</label>
          <input type="date" name="declaration_date" value="{{ old('declaration_date', $shareDividend->declaration_date->format('Y-m-d')) }}" required
                 class="form-input">
          @error('declaration_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Payment Date</label>
          <input type="date" name="payment_date" value="{{ old('payment_date', $shareDividend->payment_date?->format('Y-m-d')) }}"
                 class="form-input">
          @error('payment_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Status *</label>
          <select name="status" required class="form-input">
            <option value="declared" {{ old('status', $shareDividend->status) === 'declared' ? 'selected' : '' }}>Declared</option>
            <option value="paid" {{ old('status', $shareDividend->status) === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ old('status', $shareDividend->status) === 'pending' ? 'selected' : '' }}>Pending</option>
          </select>
          @error('status') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-primary-300 mb-2">Notes</label>
        <textarea name="notes" rows="4"
                  class="form-input"
                  placeholder="Enter notes">{{ old('notes', $shareDividend->notes) }}</textarea>
        @error('notes') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="flex items-center gap-3 pt-4">
        <a href="{{ route('admin.share-dividends.index') }}"
           class="px-5 py-2.5 rounded-xl bg-primary-700 hover:bg-primary-600 text-white text-sm font-bold transition-all">
          Cancel
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
          Update Share Dividend
        </button>
      </div>
    </form>
  </div>

</div>

@endsection
