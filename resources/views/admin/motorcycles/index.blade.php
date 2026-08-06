@extends('layouts.admin')

@section('breadcrumb', 'Motorcycle Inventory \u203A List')
@section('page_title', 'Motorcycle Inventory')

@section('content')

<div x-data="motorcycleList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1 max-w-2xl">
      <div class="relative flex-1">
        <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
        <input type="text" 
               placeholder="Search by brand, model, engine number, chassis number..."
               class="form-input pl-9 py-2.5 text-sm"
               x-model="searchQuery"/>
        <button x-show="searchQuery" @click="clearSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
          <i class="fa-solid fa-xmark text-xs"></i>
        </button>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('admin.motorcycles.create') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
        <i class="fa-solid fa-plus text-[13px]"></i> Add Motorcycle
      </a>
    </div>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-list-check mr-1.5"></i> <span x-text="filteredMotorcycles.length"></span> Motorcycles Found
        </span>
        <span x-show="searchQuery" class="badge badge-blue text-[10px]">Search: <span x-text="searchQuery"></span></span>
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th>Brand & Model</th>
            <th>Engine Number</th>
            <th>Chassis Number</th>
            <th>Registration Number</th>
            <th>Colour</th>
            <th>Purchase Price</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <template x-for="(motorcycle, index) in filteredMotorcycles" :key="motorcycle.id">
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono" x-text="index + 1"></td>
              <td>
                <div>
                  <p class="text-sm font-semibold text-primary-900 dark:text-white" x-text="motorcycle.brand"></p>
                  <p class="text-xs text-primary-600 dark:text-primary-400" x-text="motorcycle.model"></p>
                </div>
              </td>
              <td>
                <span class="text-xs font-mono text-primary-700 dark:text-primary-300" x-text="motorcycle.engine_number"></span>
              </td>
              <td>
                <span class="text-xs font-mono text-primary-700 dark:text-primary-300" x-text="motorcycle.chassis_number"></span>
              </td>
              <td>
                <span class="text-xs font-mono text-primary-700 dark:text-primary-300" x-text="motorcycle.registration_number || '-'"></span>
              </td>
              <td>
                <span class="text-xs text-primary-700 dark:text-primary-300" x-text="motorcycle.colour"></span>
              </td>
              <td>
                <span class="text-xs font-mono text-primary-700 dark:text-primary-300" x-text="formatCurrency(motorcycle.purchase_price)"></span>
              </td>
              <td>
                <span class="badge" :class="getStatusBadgeClass(motorcycle.status)" x-text="motorcycle.status"></span>
              </td>
              <td class="text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-1.5">
                  <a :href="'/admin/motorcycles/' + motorcycle.encrypted_id"
                     class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm transition-colors"
                     title="View Details">
                    <i class="fa-solid fa-eye text-xs"></i>
                  </a>
                  <a :href="'/admin/motorcycles/' + motorcycle.encrypted_id + '/edit'"
                     class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-50 hover:bg-orange-100 dark:bg-orange-900/30 dark:hover:bg-orange-900/50 text-orange-700 dark:text-orange-300 text-sm transition-colors border border-orange-200 dark:border-orange-800/40"
                     title="Edit">
                    <i class="fa-solid fa-pen text-xs"></i>
                  </a>
                  <button @click="confirmDelete(motorcycle.encrypted_id)"
                          class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 text-sm transition-colors border border-red-200 dark:border-red-800/40"
                          title="Delete">
                    <i class="fa-solid fa-trash text-xs"></i>
                  </button>
                </div>
              </td>
            </tr>
          </template>
          <tr x-show="filteredMotorcycles.length === 0">
            <td colspan="9" class="text-center py-16 text-primary-500 dark:text-primary-400">
              <i class="fa-solid fa-motorcycle text-4xl mb-4 block opacity-30"></i>
              <p class="text-sm font-semibold mb-1">No motorcycles found</p>
              <p class="text-xs">Try adjusting your search terms</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    @if($motorcycles->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $motorcycles->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $motorcycles->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $motorcycles->total() }}</span> motorcycles
        </p>

        {{ $motorcycles->appends(request()->query())->links() }}
      </div>
    @endif
  </div>
</div>

@endsection

@push('scripts')
<script>
  function motorcycleList() {
    return {
      searchQuery: '',
      allMotorcycles: @json($motorcycles->items()),
      get filteredMotorcycles() {
        if (!this.searchQuery || this.searchQuery.trim() === '') {
          return this.allMotorcycles;
        }
        
        const query = this.searchQuery.toLowerCase().trim();
        return this.allMotorcycles.filter(motorcycle => {
          const brand = (motorcycle.brand || '').toLowerCase();
          const model = (motorcycle.model || '').toLowerCase();
          const engineNumber = (motorcycle.engine_number || '').toLowerCase();
          const chassisNumber = (motorcycle.chassis_number || '').toLowerCase();
          const registrationNumber = (motorcycle.registration_number || '').toLowerCase();
          
          return brand.includes(query) || 
                 model.includes(query) || 
                 engineNumber.includes(query) || 
                 chassisNumber.includes(query) ||
                 registrationNumber.includes(query);
        });
      },
      clearSearch() {
        this.searchQuery = '';
      },
      formatCurrency(value) {
        return new Intl.NumberFormat('sw-TZ', {
          style: 'currency',
          currency: 'TZS'
        }).format(value);
      },
      getStatusBadgeClass(status) {
        const classes = {
          'Available': 'badge-green',
          'Assigned': 'badge-blue',
          'Sold': 'badge-red',
          'Under Repair': 'badge-orange'
        };
        return classes[status] || 'badge-gray';
      },
      confirmDelete(id) {
        if (confirm('Are you sure you want to delete this motorcycle?')) {
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = `/admin/motorcycles/${id}`;
          const csrf = document.createElement('input');
          csrf.type = 'hidden';
          csrf.name = '_token';
          csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          form.appendChild(csrf);
          
          const method = document.createElement('input');
          method.type = 'hidden';
          method.name = '_method';
          method.value = 'DELETE';
          form.appendChild(method);
          
          document.body.appendChild(form);
          form.submit();
        }
      }
    };
  }
</script>
@endpush
