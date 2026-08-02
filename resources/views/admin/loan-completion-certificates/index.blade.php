@extends('layouts.admin')

@section('breadcrumb', 'Loans \u203A Completion Certificates')
@section('page_title', 'Loan Completion Certificates')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Loan Completion Certificates</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Manage certificates for completed loans</p>
    </div>
  </div>

  <div class="glass rounded-xl p-6">
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead>
          <tr>
            <th>Certificate Number</th>
            <th>Member</th>
            <th>Loan Number</th>
            <th>Completion Date</th>
            <th>Original Amount</th>
            <th>Total Paid</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($certificates as $certificate)
            <tr>
              <td class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $certificate->certificate_number }}</td>
              <td>
                <div class="font-semibold text-gray-900 dark:text-white">{{ $certificate->user->name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $certificate->user->member_number }}</div>
              </td>
              <td class="font-mono text-sm">{{ $certificate->loan->loan_number }}</td>
              <td>{{ $certificate->completion_date->format('M d, Y') }}</td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ number_format($certificate->original_amount, 2) }}
              </td>
              <td class="text-right font-mono font-bold text-green-600 dark:text-green-400">
                {{ number_format($certificate->total_paid, 2) }}
              </td>
              <td>
                @if($certificate->is_active)
                  <span class="badge badge-green">Active</span>
                @else
                  <span class="badge badge-red">Inactive</span>
                @endif
              </td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.loan-completion-certificates.show', $certificate->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  <a href="{{ route('admin.loan-completion-certificates.print', $certificate->id) }}" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors" target="_blank">
                    <i class="fa-solid fa-print"></i>
                  </a>
                  <button onclick="deleteCertificate({{ $certificate->id }})" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-certificate text-3xl mb-3 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No certificates found</p>
                <p class="text-xs">Certificates will be generated when loans are completed</p>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function deleteCertificate(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`{{ route('admin.loan-completion-certificates.destroy', ':id') }}`.replace(':id', id), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Deleted!', 'Certificate has been deleted.', 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message || 'Failed to delete certificate', 'error');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to delete certificate', 'error');
      });
    }
  });
}
</script>
