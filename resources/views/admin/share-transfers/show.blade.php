@extends('layouts.admin')

@section('breadcrumb', 'System › Share Transfers › ' . $shareTransfer->id)
@section('page_title', 'Share Transfer #' . $shareTransfer->id)

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-3">
    <a href="{{ route('admin.share-transfers.index') }}"
       class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors text-sm">
      <i class="fa-solid fa-arrow-left"></i> Back to Share Transfers
    </a>
  </div>

  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">Transfer Information</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">From User:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareTransfer->fromUser->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">To User:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareTransfer->toUser->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Certificate:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareTransfer->shareCertificate->certificate_number ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Number of Shares:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareTransfer->number_of_shares }}</span>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">Status & Dates</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
            <span class="text-sm font-medium">
              @if($shareTransfer->status === 'completed')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Completed</span>
              @elseif($shareTransfer->status === 'approved')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Approved</span>
              @elseif($shareTransfer->status === 'pending')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pending</span>
              @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Rejected</span>
              @endif
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Transfer Date:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareTransfer->transfer_date ? $shareTransfer->transfer_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Created At:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareTransfer->created_at->format('M d, Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    @if($shareTransfer->reason)
    <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-800">
      <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-3">Reason</h3>
      <p class="text-sm text-primary-700 dark:text-primary-300">{{ $shareTransfer->reason }}</p>
    </div>
    @endif

    @if($shareTransfer->notes)
    <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-800">
      <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-3">Notes</h3>
      <p class="text-sm text-primary-700 dark:text-primary-300">{{ $shareTransfer->notes }}</p>
    </div>
    @endif

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-100 dark:border-primary-800">
      <a href="{{ route('admin.share-transfers.edit', app('App\Services\EncryptedIdService')->encrypt($shareTransfer->id)) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-teal-600 hover:bg-teal-500 text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <button type="button"
              onclick="deleteShareTransfer('{{ route('admin.share-transfers.destroy', app('App\Services\EncryptedIdService')->encrypt($shareTransfer->id)) }}')"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md">
        <i class="fa-solid fa-trash"></i> Delete
      </button>
    </div>
  </div>

</div>

<script>
function deleteShareTransfer(url) {
  Swal.fire({
    title: 'Are you sure?',
    text: 'You will not be able to recover this share transfer!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = url;
      const csrf = document.createElement('input');
      csrf.type = 'hidden';
      csrf.name = '_token';
      csrf.value = '{{ csrf_token() }}';
      form.appendChild(csrf);
      const method = document.createElement('input');
      method.type = 'hidden';
      method.name = '_method';
      method.value = 'DELETE';
      form.appendChild(method);
      document.body.appendChild(form);
      form.submit();
    }
  });
}
</script>

@endsection
