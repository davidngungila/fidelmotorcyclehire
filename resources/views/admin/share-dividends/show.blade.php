@extends('layouts.admin')

@section('breadcrumb', 'System › Share Dividends › ' . $shareDividend->id)
@section('page_title', 'Share Dividend #' . $shareDividend->id)

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-3">
    <a href="{{ route('admin.share-dividends.index') }}"
       class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors text-sm">
      <i class="fa-solid fa-arrow-left"></i> Back to Share Dividends
    </a>
  </div>

  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">Dividend Information</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">User:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareDividend->user->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Share Product:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareDividend->shareProduct->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Share Certificate:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareDividend->shareCertificate->certificate_number ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Number of Shares:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareDividend->number_of_shares }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Dividend Per Share:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ number_format($shareDividend->dividend_per_share, 2) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Dividend:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ number_format($shareDividend->total_dividend, 2) }}</span>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">Status & Dates</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
            <span class="text-sm font-medium">
              @if($shareDividend->status === 'paid')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Paid</span>
              @elseif($shareDividend->status === 'declared')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Declared</span>
              @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pending</span>
              @endif
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Declaration Date:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareDividend->declaration_date ? $shareDividend->declaration_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Payment Date:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareDividend->payment_date ? $shareDividend->payment_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Created At:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareDividend->created_at->format('M d, Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    @if($shareDividend->notes)
    <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-800">
      <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-3">Notes</h3>
      <p class="text-sm text-primary-700 dark:text-primary-300">{{ $shareDividend->notes }}</p>
    </div>
    @endif

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-100 dark:border-primary-800">
      <a href="{{ route('admin.share-dividends.edit', $shareDividend) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-teal-600 hover:bg-teal-500 text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <button type="button"
              onclick="deleteShareDividend('{{ route('admin.share-dividends.destroy', $shareDividend) }}')"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md">
        <i class="fa-solid fa-trash"></i> Delete
      </button>
    </div>
  </div>

</div>

<script>
function deleteShareDividend(url) {
  Swal.fire({
    title: 'Are you sure?',
    text: 'You will not be able to recover this share dividend!',
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
