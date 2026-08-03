@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Journal Entries')
@section('page_title', 'Journal Entries')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Journal Entries</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">Manage journal entries with double-entry bookkeeping</p>
    </div>
    <a href="{{ route('admin.journal-entries.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
      <i class="fa-solid fa-plus"></i> New Journal Entry
    </a>
  </div>

  <div class="glass rounded-xl p-6">
    <div class="overflow-x-auto">
      <table class="data-table">
        <thead>
          <tr>
            <th>Entry Number</th>
            <th>Date</th>
            <th>Description</th>
            <th>Type</th>
            <th class="text-right">Total Debit</th>
            <th class="text-right">Total Credit</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($journalEntries as $entry)
            <tr>
              <td class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $entry->entry_number }}</td>
              <td>{{ $entry->entry_date->format('M d, Y') }}</td>
              <td>
                <div class="font-semibold text-gray-900 dark:text-white">{{ Str::limit($entry->description, 50) }}</div>
                @if($entry->reference)
                  <div class="text-xs text-gray-500 dark:text-gray-400">Ref: {{ $entry->reference }}</div>
                @endif
              </td>
              <td>
                <span class="badge badge-{{ $entry->entry_type === 'manual' ? 'blue' : ($entry->entry_type === 'automatic' ? 'green' : 'purple') }}">
                  {{ ucfirst($entry->entry_type) }}
                </span>
              </td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ number_format($entry->total_debit, 2) }}
              </td>
              <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                {{ number_format($entry->total_credit, 2) }}
              </td>
              <td>
                @if($entry->status === 'posted')
                  <span class="badge badge-green">Posted</span>
                @elseif($entry->status === 'draft')
                  <span class="badge badge-yellow">Draft</span>
                @else
                  <span class="badge badge-red">Voided</span>
                @endif
              </td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.journal-entries.show', app('App\Services\EncryptedIdService')->encrypt($entry->id)) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  @if($entry->status === 'draft')
                    <a href="{{ route('admin.journal-entries.edit', app('App\Services\EncryptedIdService')->encrypt($entry->id)) }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                      <i class="fa-solid fa-edit"></i>
                    </a>
                    <button onclick="postJournalEntry('{{ app('App\Services\EncryptedIdService')->encrypt($entry->id) }}')" class="text-gray-600 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400 transition-colors" title="Post Entry">
                      <i class="fa-solid fa-check"></i>
                    </button>
                    <button onclick="voidJournalEntry('{{ app('App\Services\EncryptedIdService')->encrypt($entry->id) }}')" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors" title="Void Entry">
                      <i class="fa-solid fa-ban"></i>
                    </button>
                    <button onclick="deleteJournalEntry('{{ app('App\Services\EncryptedIdService')->encrypt($entry->id) }}')" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
          @if($journalEntries->isEmpty())
            <tr>
              <td colspan="8" class="text-center py-12 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-book-journal-whills text-3xl mb-3 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No journal entries found</p>
                <p class="text-xs">Create your first journal entry to get started</p>
              </td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function postJournalEntry(id) {
  Swal.fire({
    title: 'Post Journal Entry?',
    text: 'This will update account balances and cannot be undone.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#10b981',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, post it!'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`{{ route('admin.journal-entries.post', ':id') }}`.replace(':id', id), {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Posted!', 'Journal entry has been posted.', 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message || 'Failed to post journal entry', 'error');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to post journal entry', 'error');
      });
    }
  });
}

function voidJournalEntry(id) {
  Swal.fire({
    title: 'Void Journal Entry?',
    text: 'This action cannot be undone.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, void it!'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`{{ route('admin.journal-entries.void', ':id') }}`.replace(':id', id), {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Voided!', 'Journal entry has been voided.', 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message || 'Failed to void journal entry', 'error');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to void journal entry', 'error');
      });
    }
  });
}

function deleteJournalEntry(id) {
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
      fetch(`{{ route('admin.journal-entries.destroy', ':id') }}`.replace(':id', id), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Deleted!', 'Journal entry has been deleted.', 'success');
          location.reload();
        } else {
          Swal.fire('Error', data.message || 'Failed to delete journal entry', 'error');
        }
      })
      .catch(error => {
        Swal.fire('Error', 'Failed to delete journal entry', 'error');
      });
    }
  });
}
</script>
@endsection
