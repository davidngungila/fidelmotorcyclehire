@extends('layouts.admin')

@section('breadcrumb', 'Transactions \u203A List')
@section('page_title', 'Transactions Management')

@section('content')

<div x-data="transactionsList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1 max-w-2xl">
      <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex-1">
        <div class="relative">
          <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
          <input type="text" name="membercode" value="{{ request('membercode') }}"
                 placeholder="Search by member code..."
                 class="form-input pl-9 py-2.5 text-sm">
        </div>
      </form>
      <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex-1">
        <select name="transaction_type" class="form-input py-2.5 px-3 text-sm">
          <option value="">All Types</option>
          <option value="deposit" {{ request('transaction_type') == 'deposit' ? 'selected' : '' }}>Deposit</option>
          <option value="withdrawal" {{ request('transaction_type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
          <option value="transfer" {{ request('transaction_type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
        </select>
      </form>
    </div>

    <div class="flex items-center gap-3">
      <form method="GET" action="{{ route('admin.transactions.export') }}" class="inline">
        <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
          <i class="fa-solid fa-file-export text-[13px]"></i> Export Excel
        </button>
      </form>
      <button type="button" @click="$dispatch('open-import-modal')"
             class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
        <i class="fa-solid fa-file-import text-[13px]"></i> Import Excel
      </button>
      <a href="{{ route('admin.transactions.create') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
        <i class="fa-solid fa-plus text-[13px]"></i> New Transaction
      </a>
    </div>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-list-check mr-1.5"></i> {{ $transactions->total() }} Transactions Found
        </span>
        @if(request('membercode'))
          <span class="badge badge-blue text-[10px]">Member: {{ request('membercode') }}</span>
        @endif
        @if(request('transaction_type'))
          <span class="badge badge-green text-[10px]">Type: {{ request('transaction_type') }}</span>
        @endif
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th>Date</th>
            <th>Member Code</th>
            <th>Transaction Type</th>
            <th>Reference No</th>
            <th class="text-right">Amount</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $index => $transaction)
            <tr>
              <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}</td>
              <td>{{ $transaction->date->format('Y-m-d') }}</td>
              <td>
                <span class="font-semibold text-primary-600">{{ $transaction->membercode }}</span>
              </td>
              <td>
                @php
                  $typeColors = [
                    'deposit' => 'bg-green-100 text-green-700',
                    'withdrawal' => 'bg-red-100 text-red-700',
                    'transfer' => 'bg-blue-100 text-blue-700',
                  ];
                  $color = $typeColors[$transaction->transaction_type] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="badge {{ $color }} text-[10px]">{{ ucfirst($transaction->transaction_type) }}</span>
              </td>
              <td>{{ $transaction->reference_no ?? '-' }}</td>
              <td class="text-right font-semibold">{{ number_format($transaction->amount, 2) }}</td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                     class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-all active:scale-95">
                    <i class="fa-solid fa-pen text-xs"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.transactions.destroy', $transaction->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-all active:scale-95"
                            onclick="return confirm('Are you sure you want to delete this transaction?')">
                      <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-12 text-gray-500">
                <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                <p>No transactions found</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($transactions->hasPages())
      <div class="mt-6">
        {{ $transactions->appends(request()->query())->links() }}
      </div>
    @endif
  </div>
</div>

<!-- Import Modal -->
<div x-data="{ show: false }" @open-import-modal.window="show = true" @close-import-modal.window="show = false"
     x-show="show" x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full"
       @click.away="$dispatch('close-import-modal')">
    <div class="text-center mb-6">
      <div class="w-16 h-16 mx-auto mb-4 bg-emerald-100 rounded-2xl flex items-center justify-center">
        <i class="fa-solid fa-file-import text-2xl text-emerald-600"></i>
      </div>
      <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Import Transactions</h3>
      <p class="text-gray-500 dark:text-gray-400 text-sm">Upload an Excel file with transactions data</p>
    </div>

    <form method="POST" action="{{ route('admin.transactions.import') }}" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Excel File</label>
        <input type="file" name="file" accept=".xlsx,.xls,.csv" required
               class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
      </div>
      <div class="flex gap-3">
        <button type="button" @click="$dispatch('close-import-modal')"
                class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl transition-all">
          Cancel
        </button>
        <button type="submit"
                class="flex-1 px-4 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-xl transition-all">
          Import
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function transactionsList() {
  return {
    searchQuery: '',
    submitSearch() {
      this.$refs.searchForm.submit();
    }
  }
}
</script>

@endsection
