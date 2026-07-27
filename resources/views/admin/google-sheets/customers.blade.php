@extends('layouts.admin')

@section('breadcrumb', 'Google Sheets \u203A Customers')
@section('page_title', 'Google Sheets Customers')

@section('content')

<div x-data="customersPage()" class="space-y-6">
  <div class="glass p-6 lg:p-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">Customers</h1>
        <p class="text-sm mt-1" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
          View and manage customer profiles synced from Google Sheets
        </p>
      </div>
      <div class="flex items-center gap-3">
        <button @click="loadCustomers()" :disabled="loading"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-sm font-bold transition-colors disabled:opacity-60">
          <i :class="loading ? 'fa-solid fa-circle-notch fa-spin' : 'fa-solid fa-rotate'" class="text-sm"></i>
          Refresh
        </button>
      </div>
    </div>

    <div class="flex flex-col md:flex-row gap-4 mb-6">
      <div class="flex-1">
        <input type="text" x-model="search" @keyup.enter="loadCustomers()" placeholder="Search customers..."
               class="w-full px-4 py-2.5 rounded-xl bg-white dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800/50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
               :class="darkMode ? 'text-white placeholder:text-primary-500' : 'text-primary-900 placeholder:text-primary-400'">
      </div>
      <select x-model="statusFilter" @change="loadCustomers()"
              class="px-4 py-2.5 rounded-xl bg-white dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800/50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
              :class="darkMode ? 'text-white' : 'text-primary-900'">
        <option value="">All Status</option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
        <option value="Suspended">Suspended</option>
      </select>
      <select x-model="memberTypeFilter" @change="loadCustomers()"
              class="px-4 py-2.5 rounded-xl bg-white dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800/50 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
              :class="darkMode ? 'text-white' : 'text-primary-900'">
        <option value="">All Member Types</option>
        <option value="Full">Full</option>
        <option value="Associate">Associate</option>
        <option value="Honorary">Honorary</option>
      </select>
    </div>

    <div x-show="loading" class="flex items-center justify-center py-12">
      <div class="w-10 h-10 border-4 border-primary-200 dark:border-primary-800 border-t-primary-600 rounded-full animate-spin"></div>
    </div>

    <div x-show="!loading && customers.length === 0" class="text-center py-12">
      <i class="fa-solid fa-users text-4xl text-primary-300 dark:text-primary-700 mb-4"></i>
      <p class="text-sm" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">No customers found</p>
    </div>

    <div x-show="!loading && customers.length > 0" class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th>Customer ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Member Type</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>Total Balance</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <template x-for="customer in customers" :key="customer.customer_id">
            <tr>
              <td>
                <span class="font-mono text-xs font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'" x-text="customer.customer_id"></span>
              </td>
              <td>
                <p class="text-sm font-semibold" :class="darkMode ? 'text-white' : 'text-primary-900'" x-text="customer.customer_name"></p>
              </td>
              <td>
                <p class="text-sm" :class="darkMode ? 'text-primary-300' : 'text-primary-700'" x-text="customer.email_address || '-'"></p>
              </td>
              <td>
                <p class="text-sm" :class="darkMode ? 'text-primary-300' : 'text-primary-700'" x-text="customer.phone_number || '-'"></p>
              </td>
              <td>
                <span class="badge badge-blue text-[10px]" x-text="customer.member_type || '-'"></span>
              </td>
              <td>
                <span class="badge" :class="customer.account_status === 'Active' ? 'badge-green' : 'badge-yellow'" x-text="customer.account_status"></span>
              </td>
              <td>
                <p class="text-sm" :class="darkMode ? 'text-primary-300' : 'text-primary-700'" x-text="customer.start_date || '-'"></p>
              </td>
              <td>
                <p class="text-sm font-bold" :class="darkMode ? 'text-white' : 'text-primary-900'">KES <span x-text="formatNumber(customer.total_balance || 0)"></span></p>
              </td>
              <td class="text-right whitespace-nowrap">
                <button @click="viewCustomer(customer.customer_id)"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors">
                  <i class="fa-solid fa-eye text-[10px]"></i> View
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>

    <div x-show="pagination.total > 0" class="flex items-center justify-between mt-6">
      <p class="text-sm" :class="darkMode ? 'text-primary-400' : 'text-primary-600'">
        Showing <span x-text="(pagination.current_page - 1) * pagination.per_page + 1"></span> to <span x-text="Math.min(pagination.current_page * pagination.per_page, pagination.total)"></span> of <span x-text="pagination.total"></span> customers
      </p>
      <div class="flex items-center gap-2">
        <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
                class="px-3 py-1.5 rounded-lg bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          Previous
        </button>
        <span class="text-sm font-bold px-3" :class="darkMode ? 'text-white' : 'text-primary-900'" x-text="pagination.current_page"></span>
        <button @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page"
                class="px-3 py-1.5 rounded-lg bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          Next
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function customersPage() {
    return {
      loading: false,
      search: '',
      statusFilter: '',
      memberTypeFilter: '',
      customers: [],
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 20,
        total: 0
      },
      darkMode: document.documentElement.classList.contains('dark'),
      async loadCustomers(page = 1) {
        this.loading = true;
        try {
          const params = new URLSearchParams({
            page: page,
            search: this.search,
            status: this.statusFilter,
            member_type: this.memberTypeFilter,
            per_page: 20
          });
          
          const response = await fetch('{{ route('admin.google-sheets.customers') }}?' + params.toString(), {
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });
          
          const data = await response.json();
          if (data.success) {
            this.customers = data.data.data;
            this.pagination = {
              current_page: data.data.current_page,
              last_page: data.data.last_page,
              per_page: data.data.per_page,
              total: data.data.total
            };
          }
        } catch (error) {
          console.error('Failed to load customers:', error);
        } finally {
          this.loading = false;
        }
      },
      changePage(page) {
        if (page >= 1 && page <= this.pagination.last_page) {
          this.loadCustomers(page);
        }
      },
      viewCustomer(customerId) {
        window.location.href = '{{ route('admin.google-sheets.customer', ':id') }}'.replace(':id', customerId);
      },
      formatNumber(num) {
        return new Intl.NumberFormat().format(num);
      },
      init() {
        this.loadCustomers();
      }
    }
  }
</script>
@endpush
