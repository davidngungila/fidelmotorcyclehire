@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Share Transfers \u203A Edit')
@section('page_title', 'Edit Share Transfer')

@section('content')

<div class="space-y-6">

  <div class="glass p-6">
    <form action="{{ route('admin.share-transfers.update', $shareTransfer) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">From User *</label>
          <select name="from_user_id" required class="form-input">
            <option value="">Select From User</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('from_user_id', $shareTransfer->from_user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
          </select>
          @error('from_user_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">To User *</label>
          <select name="to_user_id" required class="form-input">
            <option value="">Select To User</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" {{ old('to_user_id', $shareTransfer->to_user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
          </select>
          @error('to_user_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Share Certificate *</label>
          <select name="share_certificate_id" required class="form-input">
            <option value="">Select Share Certificate</option>
            @foreach($shareCertificates as $certificate)
            <option value="{{ $certificate->id }}" {{ old('share_certificate_id', $shareTransfer->share_certificate_id) == $certificate->id ? 'selected' : '' }}>{{ $certificate->certificate_number }} - {{ $certificate->shareProduct->name }}</option>
            @endforeach
          </select>
          @error('share_certificate_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Number of Shares *</label>
          <input type="number" name="number_of_shares" min="1" value="{{ old('number_of_shares', $shareTransfer->number_of_shares) }}" required
                 class="form-input"
                 placeholder="Enter number of shares">
          @error('number_of_shares') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Transfer Date *</label>
          <input type="date" name="transfer_date" value="{{ old('transfer_date', $shareTransfer->transfer_date->format('Y-m-d')) }}" required
                 class="form-input">
          @error('transfer_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-primary-300 mb-2">Status *</label>
          <select name="status" required class="form-input">
            <option value="pending" {{ old('status', $shareTransfer->status) === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ old('status', $shareTransfer->status) === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ old('status', $shareTransfer->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
            <option value="completed" {{ old('status', $shareTransfer->status) === 'completed' ? 'selected' : '' }}>Completed</option>
          </select>
          @error('status') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-primary-300 mb-2">Reason</label>
        <textarea name="reason" rows="3"
                  class="form-input"
                  placeholder="Enter reason for transfer">{{ old('reason', $shareTransfer->reason) }}</textarea>
        @error('reason') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-primary-300 mb-2">Notes</label>
        <textarea name="notes" rows="3"
                  class="form-input"
                  placeholder="Enter notes">{{ old('notes', $shareTransfer->notes) }}</textarea>
        @error('notes') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="flex items-center gap-3 pt-4">
        <a href="{{ route('admin.share-transfers.index') }}"
           class="px-5 py-2.5 rounded-xl bg-primary-700 hover:bg-primary-600 text-white text-sm font-bold transition-all">
          Cancel
        </a>
        <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all">
          Update Share Transfer
        </button>
      </div>
    </form>
  </div>

</div>

@endsection
