@extends('layouts.admin')

@section('breadcrumb', 'Accounting \u203A Journal Entries \u203A View Journal Entry')
@section('page_title', 'View Journal Entry')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Journal Entry #{{ $journalEntry->entry_number }}</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $journalEntry->entry_date->format('F d, Y') }}</p>
    </div>
    <div class="flex items-center gap-2">
      @if($journalEntry->status === 'draft')
        <a href="{{ route('admin.journal-entries.edit', app('App\Services\EncryptedIdService')->encrypt($journalEntry->id)) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-semibold transition-all">
          <i class="fa-solid fa-edit"></i> Edit
        </a>
        <button onclick="postJournalEntry('{{ app('App\Services\EncryptedIdService')->encrypt($journalEntry->id) }}')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-100 hover:bg-green-200 dark:bg-green-900/40 dark:hover:bg-green-900/60 text-green-700 dark:text-green-300 text-sm font-semibold transition-all">
          <i class="fa-solid fa-check"></i> Post
        </button>
        <button onclick="voidJournalEntry('{{ app('App\Services\EncryptedIdService')->encrypt($journalEntry->id) }}')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-100 hover:bg-red-200 dark:bg-red-900/40 dark:hover:bg-red-900/60 text-red-700 dark:text-red-300 text-sm font-semibold transition-all">
          <i class="fa-solid fa-ban"></i> Void
        </button>
      @endif
      <a href="{{ route('admin.journal-entries.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Entry Details</h3>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Entry Number:</span>
            <div class="font-mono font-bold text-gray-900 dark:text-white">{{ $journalEntry->entry_number }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Entry Date:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->entry_date->format('F d, Y') }}</div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Entry Type:</span>
            <div>
              <span class="badge badge-{{ $journalEntry->entry_type === 'manual' ? 'blue' : ($journalEntry->entry_type === 'automatic' ? 'green' : 'purple') }}">
                {{ ucfirst($journalEntry->entry_type) }}
              </span>
            </div>
          </div>
          <div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
            <div>
              @if($journalEntry->status === 'posted')
                <span class="badge badge-green">Posted</span>
              @elseif($journalEntry->status === 'draft')
                <span class="badge badge-yellow">Draft</span>
              @else
                <span class="badge badge-red">Voided</span>
              @endif
            </div>
          </div>
          <div class="col-span-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Description:</span>
            <div class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->description }}</div>
          </div>
          @if($journalEntry->reference)
            <div class="col-span-2">
              <span class="text-sm text-gray-600 dark:text-gray-400">Reference:</span>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->reference }}</div>
            </div>
          @endif
          @if($journalEntry->posted_at)
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Posted At:</span>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->posted_at->format('M d, Y H:i') }}</div>
            </div>
          @endif
        </div>
      </div>

      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Journal Entry Lines</h3>
        <div class="overflow-x-auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>Account</th>
                <th>Description</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
              </tr>
            </thead>
            <tbody>
              @foreach($journalEntry->lines as $line)
                <tr>
                  <td>
                    <div class="font-mono text-sm text-primary-700 dark:text-primary-300">{{ $line->account->account_code }}</div>
                    <div class="font-semibold text-gray-900 dark:text-white">{{ $line->account->account_name }}</div>
                  </td>
                  <td class="text-sm text-gray-600 dark:text-gray-400">{{ $line->description }}</td>
                  <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                    {{ $line->debit_amount > 0 ? number_format($line->debit_amount, 2) : '-' }}
                  </td>
                  <td class="text-right font-mono font-bold text-gray-900 dark:text-white">
                    {{ $line->credit_amount > 0 ? number_format($line->credit_amount, 2) : '-' }}
                  </td>
                </tr>
              @endforeach
              <tr class="bg-gray-50 dark:bg-gray-800 font-bold">
                <td colspan="2" class="text-right">Totals:</td>
                <td class="text-right font-mono text-gray-900 dark:text-white">{{ number_format($journalEntry->total_debit, 2) }}</td>
                <td class="text-right font-mono text-gray-900 dark:text-white">{{ number_format($journalEntry->total_credit, 2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Summary</h3>
        <div class="space-y-3">
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Debit:</span>
            <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($journalEntry->total_debit, 2) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">Total Credit:</span>
            <span class="font-mono font-bold text-gray-900 dark:text-white">{{ number_format($journalEntry->total_credit, 2) }}</span>
          </div>
          <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600 dark:text-gray-400">Balance:</span>
              <span class="font-mono font-bold {{ abs($journalEntry->total_debit - $journalEntry->total_credit) < 0.01 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ abs($journalEntry->total_debit - $journalEntry->total_credit) < 0.01 ? 'Balanced' : 'Unbalanced' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      @if($journalEntry->financialPeriod)
        <div class="glass rounded-xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Period</h3>
          <div class="space-y-2">
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Period:</span>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->financialPeriod->name }}</div>
            </div>
            <div>
              <span class="text-sm text-gray-600 dark:text-gray-400">Duration:</span>
              <div class="text-sm text-gray-900 dark:text-white">
                {{ $journalEntry->financialPeriod->start_date->format('M d, Y') }} - {{ $journalEntry->financialPeriod->end_date->format('M d, Y') }}
              </div>
            </div>
          </div>
        </div>
      @endif

      <div class="glass rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Created By</h3>
        @if($journalEntry->createdBy)
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
              <span class="text-primary-700 dark:text-primary-300 font-semibold">{{ strtoupper(substr($journalEntry->createdBy->name, 0, 1)) }}</span>
            </div>
            <div>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->createdBy->name }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ $journalEntry->created_at->format('M d, Y H:i') }}</div>
            </div>
          </div>
        @else
          <div class="text-sm text-gray-500 dark:text-gray-400">Unknown</div>
        @endif
      </div>

      @if($journalEntry->approvedBy)
        <div class="glass rounded-xl p-6">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Approved By</h3>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
              <span class="text-green-700 dark:text-green-300 font-semibold">{{ strtoupper(substr($journalEntry->approvedBy->name, 0, 1)) }}</span>
            </div>
            <div>
              <div class="font-semibold text-gray-900 dark:text-white">{{ $journalEntry->approvedBy->name }}</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">{{ $journalEntry->posted_at->format('M d, Y H:i') }}</div>
            </div>
          </div>
        </div>
      @endif
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
</script>
@endsection
