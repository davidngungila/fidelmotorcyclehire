@extends('layouts.admin')

@section('breadcrumb', 'System › Share Dividends › #' . $shareDividend->id . ' › Edit')
@section('page_title', 'Edit Share Dividend #' . $shareDividend->id)

@section('content')
<div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-8">
  <form method="POST" action="{{ route('admin.share-dividends.update', app('App\Services\EncryptedIdService')->encrypt($shareDividend->id)) }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Share Product</label>
        <select name="share_product_id" required class="form-input py-2.5 px-4">
          <option value="">Select share product</option>
          @foreach($shareProducts as $product)
          <option value="{{ $product->id }}" {{ old('share_product_id', $shareDividend->share_product_id) == $product->id ? 'selected' : '' }}>{{ $product->name }} - {{ $product->code }}</option>
          @endforeach
        </select>
        @error('share_product_id')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">User</label>
        <select name="user_id" required class="form-input py-2.5 px-4">
          <option value="">Select user</option>
          @foreach($users as $user)
          <option value="{{ $user->id }}" {{ old('user_id', $shareDividend->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
          @endforeach
        </select>
        @error('user_id')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Share Certificate</label>
        <select name="share_certificate_id" class="form-input py-2.5 px-4">
          <option value="">Select share certificate (optional)</option>
          @foreach($shareCertificates as $certificate)
          <option value="{{ $certificate->id }}" {{ old('share_certificate_id', $shareDividend->share_certificate_id) == $certificate->id ? 'selected' : '' }}>{{ $certificate->certificate_number }}</option>
          @endforeach
        </select>
        @error('share_certificate_id')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Number of Shares</label>
        <input type="number" name="number_of_shares" min="1" value="{{ old('number_of_shares', $shareDividend->number_of_shares) }}" required
               placeholder="Enter number of shares"
               class="form-input py-2.5 px-4">
        @error('number_of_shares')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Dividend Per Share</label>
        <input type="number" name="dividend_per_share" step="0.01" min="0" value="{{ old('dividend_per_share', $shareDividend->dividend_per_share) }}" required
               placeholder="Enter dividend per share"
               class="form-input py-2.5 px-4">
        @error('dividend_per_share')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Total Dividend</label>
        <input type="number" name="total_dividend" step="0.01" min="0" value="{{ old('total_dividend', $shareDividend->total_dividend) }}" required
               placeholder="Enter total dividend"
               class="form-input py-2.5 px-4">
        @error('total_dividend')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Declaration Date</label>
        <input type="date" name="declaration_date" value="{{ old('declaration_date', $shareDividend->declaration_date?->format('Y-m-d')) }}" required
               class="form-input py-2.5 px-4">
        @error('declaration_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Payment Date</label>
        <input type="date" name="payment_date" value="{{ old('payment_date', $shareDividend->payment_date?->format('Y-m-d')) }}"
               class="form-input py-2.5 px-4">
        @error('payment_date')
          <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
        <select name="status" required class="form-input py-2.5 px-4">
          <option value="">Select status</option>
          <option value="declared" {{ old('status', $shareDividend->status) === 'declared' ? 'selected' : '' }}>Declared</option>
          <option value="paid" {{ old('status', $shareDividend->status) === 'paid' ? 'selected' : '' }}>Paid</option>
          <option value="pending" {{ old('status', $shareDividend->status) === 'pending' ? 'selected' : '' }}>Pending</option>
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
                class="form-input py-2.5 px-4">{{ old('notes', $shareDividend->notes) }}</textarea>
      @error('notes')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center gap-3 pt-4">
      <a href="{{ route('admin.share-dividends.index') }}"
         class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
        Cancel
      </a>
      <button type="submit"
              class="flex-1 px-6 py-2.5 bg-teal-600 hover:bg-teal-500 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md">
        Update Share Dividend
      </button>
    </div>
  </form>
</div>
@endsection
