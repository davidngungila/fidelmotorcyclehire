@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Chart of Accounts')
@section('page_title', 'Chart of Accounts')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Chart of Accounts</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your SACCO's chart of accounts</p>
    </div>
    <a href="{{ route('admin.accounts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
      <i class="fa-solid fa-plus"></i> Add Account
    </a>
  </div>

  <div class="glass rounded-xl p-6">
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead>
          <tr>
            <th>Code</th>
            <th>Account Name</th>
            <th>Type</th>
            <th>Subtype</th>
            <th class="text-right">Balance</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($accounts as $account)
            <tr>
              <td class="font-mono text-sm text-primary-700 dark:text-primary-300">
                @if($account->level > 1)
                  @for($i = 1; $i < $account->level; $i++)
                    <span class="text-gray-400">&nbsp;&nbsp;</span>
                  @endfor
                  <i class="fa-solid fa-turn-up text-[10px] mr-1"></i>
                @endif
                {{ $account->account_code }}
              </td>
              <td>
                <div class="font-semibold text-gray-900 dark:text-white">{{ $account->account_name }}</div>
                @if($account->description)
                  <div class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($account->description, 50) }}</div>
                @endif
              </td>
              <td>
                <span class="badge badge-{{ $account->account_type === 'asset' || $account->account_type === 'expense' ? 'blue' : 'green' }}">
                  {{ ucfirst($account->account_type) }}
                </span>
              </td>
              <td class="text-sm text-gray-600 dark:text-gray-400">
                {{ $account->account_subtype ? str_replace('_', ' ', ucfirst($account->account_subtype)) : '-' }}
              </td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ number_format($account->current_balance, 2) }}
              </td>
              <td>
                @if($account->is_system_account)
                  <span class="badge badge-purple">System</span>
                @elseif($account->is_active)
                  <span class="badge badge-green">Active</span>
                @else
                  <span class="badge badge-red">Inactive</span>
                @endif
              </td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.accounts.show', $account->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  @if(!$account->is_system_account)
                    <a href="{{ route('admin.accounts.edit', $account->id) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                      <i class="fa-solid fa-edit"></i>
                    </a>
                    <button onclick="deleteAccount({{ $account->id }})" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-book text-3xl mb-3 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No accounts found</p>
                <p class="text-xs">Create your first account to get started</p>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function deleteAccount(id) {
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
      fetch(`{{ route('admin.accounts.destroy', ':id') }}`.replace(':id', id), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Deleted!', 'Account has been deleted.', 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message || 'Failed to delete account', 'error');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to delete account', 'error');
      });
    }
  });
}
</script>
@endsection
