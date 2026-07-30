@extends('layouts.admin')

@section('breadcrumb', 'Saving Plans \u203A List')
@section('page_title', 'Saving Plans Management')

@section('content')

<div x-data="savingPlansList()" class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1 max-w-2xl">
      <form method="GET" action="{{ route('admin.saving-plans.index') }}" class="flex-1">
        <div class="relative">
          <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
          <input type="text" name="member_number" value="{{ request('member_number') }}"
                 placeholder="Search by member number..."
                 class="form-input pl-9 py-2.5 text-sm">
        </div>
      </form>
      <form method="GET" action="{{ route('admin.saving-plans.index') }}" class="flex-1">
        <select name="membership" class="form-input py-2.5 px-3 text-sm">
          <option value="">All Memberships</option>
          <option value="individual" {{ request('membership') == 'individual' ? 'selected' : '' }}>Individual</option>
          <option value="corporate" {{ request('membership') == 'corporate' ? 'selected' : '' }}>Corporate</option>
          <option value="group" {{ request('membership') == 'group' ? 'selected' : '' }}>Group</option>
        </select>
      </form>
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('admin.saving-plans.create') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
        <i class="fa-solid fa-plus text-[13px]"></i> New Saving Plan
      </a>
    </div>
  </div>

  <div class="glass p-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
      <div class="flex items-center gap-3">
        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
          <i class="fa-solid fa-list-check mr-1.5"></i> {{ $savingPlans->total() }} Saving Plans Found
        </span>
        @if(request('member_number'))
          <span class="badge badge-blue text-[10px]">Member: {{ request('member_number') }}</span>
        @endif
        @if(request('membership'))
          <span class="badge badge-green text-[10px]">Membership: {{ request('membership') }}</span>
        @endif
      </div>
    </div>

    <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
      <table class="data-table">
        <thead>
          <tr>
            <th class="w-12">#</th>
            <th>Name</th>
            <th>Member Number</th>
            <th>Membership</th>
            <th>Target Date</th>
            <th>Status</th>
            <th class="text-right">Monthly Goal</th>
            <th class="text-right">Goal</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($savingPlans as $index => $savingPlan)
            <tr>
              <td>{{ ($savingPlans->currentPage() - 1) * $savingPlans->perPage() + $index + 1 }}</td>
              <td>
                <span class="font-semibold text-primary-600">{{ $savingPlan->name }}</span>
              </td>
              <td>{{ $savingPlan->member_number }}</td>
              <td>
                @php
                  $membershipColors = [
                    'individual' => 'bg-blue-100 text-blue-700',
                    'corporate' => 'bg-purple-100 text-purple-700',
                    'group' => 'bg-orange-100 text-orange-700',
                  ];
                  $color = $membershipColors[$savingPlan->membership] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="badge {{ $color }} text-[10px]">{{ ucfirst($savingPlan->membership) }}</span>
              </td>
              <td>{{ $savingPlan->target_date ? $savingPlan->target_date->format('M d, Y') : '-' }}</td>
              <td>
                @php
                  $statusColors = [
                    'active' => 'bg-green-100 text-green-700',
                    'completed' => 'bg-blue-100 text-blue-700',
                    'paused' => 'bg-yellow-100 text-yellow-700',
                  ];
                  $statusColor = $statusColors[$savingPlan->status ?? 'active'] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="badge {{ $statusColor }} text-[10px]">{{ ucfirst($savingPlan->status ?? 'active') }}</span>
              </td>
              <td class="text-right font-semibold">{{ number_format($savingPlan->monthly_goal, 2) }}</td>
              <td class="text-right font-semibold">{{ number_format($savingPlan->goal, 2) }}</td>
              <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.saving-plans.edit', $savingPlan->id) }}"
                     class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-all active:scale-95">
                    <i class="fa-solid fa-pen text-xs"></i>
                  </a>
                  <form method="POST" action="{{ route('admin.saving-plans.destroy', $savingPlan->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-all active:scale-95"
                            onclick="return confirm('Are you sure you want to delete this saving plan?')">
                      <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center py-12 text-gray-500">
                <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                <p>No saving plans found</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($savingPlans->hasPages())
      <div class="mt-6">
        {{ $savingPlans->appends(request()->query())->links() }}
      </div>
    @endif
  </div>
</div>

<script>
function savingPlansList() {
  return {
    searchQuery: '',
    submitSearch() {
      this.$refs.searchForm.submit();
    }
  }
}
</script>

@endsection
