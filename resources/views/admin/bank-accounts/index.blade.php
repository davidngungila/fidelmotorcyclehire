@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Bank Accounts')
@section('page_title', 'Bank Accounts')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bank Accounts</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Manage bank accounts for the SACCO</p>
    </div>
    <a href="{{ route('admin.bank-accounts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
      <i class="fa-solid fa-plus"></i> New Bank Account
    </a>
  </div>

  <div class="glass rounded-xl p-6">
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead>
          <tr>
            <th>Bank Name</th>
            <th>Account Number</th>
            <th>Type</th>
            <th>Currency</th>
            <th>Current Balance</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($bankAccounts as $bankAccount)
            <tr>
              <td>
                <div class="font-semibold text-gray-900 dark:text-white">{{ $bankAccount->bank_name }}</div>
                @if($bankAccount->branch_name)
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ $bankAccount->branch_name }}</div>
                @endif
              </td>
              <td class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $bankAccount->account_number }}</td>
              <td>
                <span class="badge badge-{{ $bankAccount->account_type === 'checking' ? 'blue' : ($bankAccount->account_type === 'savings' ? 'green' : 'purple') }}">
                  {{ ucfirst($bankAccount->account_type) }}
                </span>
              </td>
              <td class="font-mono text-sm text-gray-900 dark:text-white">{{ strtoupper($bankAccount->currency) }}</td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ number_format($bankAccount->current_balance, 2) }}
              </td>
              <td>
                @if($bankAccount->is_active)
                  <span class="badge badge-green">Active</span>
                @else
                  <span class="badge badge-red">Inactive</span>
                @endif
              </td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.bank-accounts.show', $bankAccount->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  <a href="{{ route('admin.bank-accounts.edit', $bankAccount->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                    <i class="fa-solid fa-edit"></i>
                  </a>
                  <button onclick="deleteBankAccount({{ $bankAccount->id }})" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-building-columns text-3xl mb-3 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No bank accounts found</p>
                <p class="text-xs">Create your first bank account to get started</p>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function deleteBankAccount(id) {
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
      fetch(`{{ route('admin.bank-accounts.destroy', ':id') }}`.replace(':id', id), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Deleted!', 'Bank account has been deleted.', 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message || 'Failed to delete bank account', 'error');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to delete bank account', 'error');
      });
    }
  });
}
</script>
