@extends('layouts.admin')

@section('breadcrumb', 'System \u203A Users')
@section('page_title', 'User Management')

@section('content')

<div x-data="usersList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1 max-w-2xl">
      <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1" x-ref="searchForm">
        <div class="relative">
          <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
          <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
                 placeholder="Search by name, email, member number, phone..."
                 class="form-input pl-9 py-2.5 text-sm"
                 x-model="searchQuery"
                 @input.debounce.400ms="$refs.searchForm.submit()"/>
          @if($searchQuery)
            <a href="{{ route('admin.users.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600">
              <i class="fa-solid fa-xmark text-xs"></i>
            </a>
          @endif
        </div>
      </form>
    </div>

    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
      <i class="fa-solid fa-user-plus text-[13px]"></i> Create User
    </a>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-users mr-1.5"></i> {{ $users->total() }} Users Found
        </span>
        @if($searchQuery)
          <span class="badge badge-blue text-[10px]">Search: {{ $searchQuery }}</span>
        @endif
      </div>
      <div class="flex items-center gap-3">
        <label class="flex items-center gap-2 text-xs text-primary-600 dark:text-primary-400">
          Per page:
          <select name="per_page" class="form-input py-1.5 px-2 w-20 text-xs" @change="changePerPage($el.value)">
            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
          </select>
        </label>
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Member #</th>
            <th>Status</th>
            <th>Created At</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $index => $user)
            @php
              $rowNum = ($users->currentPage() - 1) * $users->perPage() + $index + 1;
              $userRole = $user->role ?? ($user->roles->first()->name ?? 'member');
              $userStatus = $user->status ?? 'active';
            @endphp
            <tr class="group">
              <td class="text-xs text-primary-400 dark:text-primary-500 font-mono">{{ $rowNum }}.</td>
              <td>
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr($user->name, 0, 1) ?? 'U') }}
                  </div>
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-primary-900 dark:text-white truncate max-w-[200px]">{{ $user->name }}</p>
                    @if($user->phone)
                      <p class="text-[11px] text-primary-500 dark:text-primary-400 truncate max-w-[200px]">
                        <i class="fa-solid fa-phone text-[9px] mr-1"></i>{{ $user->phone }}
                      </p>
                    @endif
                  </div>
                </div>
              </td>
              <td>
                <span class="text-xs text-primary-700 dark:text-primary-300 max-w-[200px] truncate block">{{ $user->email }}</span>
              </td>
              <td>
                @if($userRole === 'admin')
                  <span class="role-tag role-admin">Admin</span>
                @elseif($userRole === 'manager')
                  <span class="role-tag role-manager">Manager</span>
                @elseif($userRole === 'teller')
                  <span class="role-tag role-teller">Teller</span>
                @elseif($userRole === 'auditor')
                  <span class="role-tag role-auditor">Auditor</span>
                @else
                  <span class="role-tag role-member">Member</span>
                @endif
              </td>
              <td>
                @if($user->member_number)
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-primary-50 dark:bg-primary-900/40 font-mono text-xs font-bold text-primary-700 dark:text-primary-300">
                    <i class="fa-solid fa-id-card text-[9px] opacity-60"></i>
                    {{ $user->member_number }}
                  </span>
                @else
                  <span class="text-xs text-primary-300 dark:text-primary-600 italic">-</span>
                @endif
              </td>
              <td>
                @if(strtolower((string)$userStatus) === 'active')
                  <span class="badge badge-green"><i class="fa-solid fa-circle-check text-[9px] mr-1"></i> Active</span>
                @elseif(strtolower((string)$userStatus) === 'inactive' || strtolower((string)$userStatus) === 'disabled')
                  <span class="badge badge-gray"><i class="fa-solid fa-circle-xmark text-[9px] mr-1"></i> Inactive</span>
                @elseif(strtolower((string)$userStatus) === 'pending')
                  <span class="badge badge-yellow"><i class="fa-solid fa-clock text-[9px] mr-1"></i> Pending</span>
                @else
                  <span class="badge badge-blue">{{ ucfirst($userStatus) }}</span>
                @endif
              </td>
              <td>
                <span class="text-xs text-primary-600 dark:text-primary-400 block">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                <span class="text-[10px] text-primary-400 dark:text-primary-600">{{ $user->created_at ? $user->created_at->format('H:i') : '' }}</span>
              </td>
              <td class="text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-2">
                  <button @click="resetPassword({{ $user->id }}, '{{ addslashes($user->name) }}')"
                          class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-[11px] font-bold transition-colors border border-amber-200 dark:border-amber-800/40">
                    <i class="fa-solid fa-key text-[10px]"></i> Reset Password
                  </button>
                  <a href="{{ route('admin.users.edit', $user->id) }}"
                     class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors">
                    <i class="fa-solid fa-pen-to-square text-[10px]"></i> Edit
                  </a>
                  <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                        class="inline"
                        x-data
                        @submit.prevent="confirmDelete($el, '{{ addslashes($user->name) }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 text-[11px] font-bold transition-colors border border-red-200 dark:border-red-800/40">
                      <i class="fa-solid fa-trash text-[10px]"></i> Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center py-16 text-primary-500 dark:text-primary-400">
                <i class="fa-solid fa-users-slash text-4xl mb-4 block opacity-30"></i>
                <p class="text-sm font-semibold mb-1">No users found</p>
                <p class="text-xs">
                  @if($searchQuery)
                    Try adjusting your search terms or
                  @endif
                  <a href="{{ route('admin.users.create') }}" class="text-primary-600 dark:text-primary-400 underline hover:no-underline">create a new user</a>
                </p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($users->hasPages())
      <div class="mt-6 pt-5 border-t border-primary-100 dark:border-primary-900/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-xs text-primary-600 dark:text-primary-400">
          Showing <span class="font-bold text-primary-900 dark:text-white">{{ $users->firstItem() ?? 0 }}</span> to
          <span class="font-bold text-primary-900 dark:text-white">{{ $users->lastItem() ?? 0 }}</span> of
          <span class="font-bold text-primary-900 dark:text-white">{{ $users->total() }}</span> users
        </p>

        <nav class="flex items-center justify-center gap-1" role="navigation" aria-label="Pagination Navigation">
          @if($users->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-300 dark:text-primary-700 bg-primary-50 dark:bg-primary-900/20 cursor-not-allowed">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </span>
          @else
            <a href="{{ $users->appends(request()->query())->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 transition-colors">
              <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </a>
          @endif

          @php
            $start = max($users->currentPage() - 2, 1);
            $end = min($start + 4, $users->lastPage());
            if ($end - $start < 4) {
                $start = max($end - 4, 1);
            }
          @endphp

          @for($i = $start; $i <= $end; $i++)
            @if($i == $users->currentPage())
              <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-primary-600 shadow-sm">
                {{ $i }}
              </span>
            @else
              <a href="{{ $users->appends(request()->query())->url($i) }}"
                 class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-primary-700 dark:text-primary-300 bg-primary-50 hover:bg-primary-100 dark:bg-primary-900/30 dark:hover:bg-primary-900/50 transition-colors">
                {{ $i }}
              </a>
            @endif
          @endfor

          @if($users->hasMorePages())
            <a href="{{ $users->appends(request()->query())->nextPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 transition-colors">
              <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
          @else
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-primary-300 dark:text-primary-700 bg-primary-50 dark:bg-primary-900/20 cursor-not-allowed">
              <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </span>
          @endif
        </nav>
      </div>
    @endif
  </div>
</div>

<!-- Password Reset Modal -->
<div x-show="showPasswordModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
     style="display: none;">
  <div x-show="showPasswordModal"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="opacity-0 scale-95"
       x-transition:enter-end="opacity-100 scale-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="opacity-100 scale-100"
       x-transition:leave-end="opacity-0 scale-95"
       class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4">
    <div class="text-center">
      <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center">
        <i class="fa-solid fa-check text-2xl text-green-600 dark:text-green-400"></i>
      </div>
      <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Password Reset Successful</h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
        New password for <span class="font-semibold text-gray-900 dark:text-white" x-text="userName"></span>
      </p>
      <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-4">
        <p class="text-2xl font-mono font-bold text-primary-600 dark:text-primary-400 tracking-wider" x-text="newPassword"></p>
      </div>
      <div class="flex gap-3">
        <button @click="copyPassword()"
                class="flex-1 px-4 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-colors">
          <i class="fa-solid fa-copy mr-2"></i> Copy Password
        </button>
        <button @click="closePasswordModal()"
                class="flex-1 px-4 py-2.5 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm font-bold transition-colors">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function usersList() {
    return {
      searchQuery: @json($searchQuery ?? ''),
      showPasswordModal: false,
      newPassword: '',
      userName: '',
      changePerPage(value) {
        const params = new URLSearchParams(window.location.search);
        params.set('per_page', value);
        params.delete('page');
        window.location.href = window.location.pathname + '?' + params.toString();
      },
      confirmDelete(form, userName) {
        if (confirm('Are you sure you want to delete user "' + userName + '"? This action cannot be undone.')) {
          form.submit();
        }
      },
      async resetPassword(userId, userName) {
        if (!confirm('Are you sure you want to reset the password for "' + userName + '"?')) {
          return;
        }
        
        try {
          const response = await fetch('{{ route('admin.users.reset-password', '') }}'.replace(':id', userId), {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          });
          
          const data = await response.json();
          
          if (data.success) {
            this.newPassword = data.new_password;
            this.userName = data.user_name;
            this.showPasswordModal = true;
          } else {
            alert('Failed to reset password: ' + data.message);
          }
        } catch (error) {
          alert('Error resetting password: ' + error.message);
        }
      },
      copyPassword() {
        navigator.clipboard.writeText(this.newPassword);
        alert('Password copied to clipboard!');
      },
      closePasswordModal() {
        this.showPasswordModal = false;
        this.newPassword = '';
        this.userName = '';
      }
    }
  }
</script>
@endpush
