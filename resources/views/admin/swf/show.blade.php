@extends('layouts.admin')

@section('breadcrumb', 'Members › SWF › View')
@section('page_title', 'SWF Account Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
@endphp

@section('content')

<div x-data="swfShow()" class="space-y-6">

  <!-- Tab Navigation -->
  <div class="glass p-2 rounded-xl flex gap-1 overflow-x-auto">
    <template x-for="tab in tabs" :key="tab.id">
      <button @click="activeTab = tab.id"
              :class="activeTab === tab.id ? 'bg-white dark:bg-primary-900 shadow-sm text-primary-900 dark:text-white' : 'text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/30'"
              class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">
        <i :class="tab.icon" class="text-[11px]"></i>
        <span x-text="tab.label"></span>
      </button>
    </template>
  </div>

  <!-- Overview Tab -->
  <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
  @if($member)
    <div class="glass p-6">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 text-white flex items-center justify-center text-2xl font-bold shadow-lg">
            {{ strtoupper(substr($member['full_name'] ?? 'M', 0, 1)) }}
          </div>
          <div>
            <h2 class="text-xl font-bold text-primary-900 dark:text-white">{{ $member['full_name'] ?? 'Unknown' }}</h2>
            <div class="flex items-center gap-3 mt-1">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 dark:bg-purple-900/40 font-mono text-xs font-bold text-purple-700 dark:text-purple-300">
                <i class="fa-solid fa-id-card text-[10px]"></i>
                {{ $memberNumber }}
              </span>
              <span class="badge {{ $dashboardService->memberStatusBadge($member['status'] ?? null)['class'] }}">
                {{ $dashboardService->memberStatusBadge($member['status'] ?? null)['label'] }}
              </span>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <a href="tel:{{ $member['phone'] ?? '' }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-100 hover:bg-green-200 dark:bg-green-900/40 dark:hover:bg-green-900/60 text-green-700 dark:text-green-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-phone text-[10px]"></i> Call
          </a>
          <a href="mailto:{{ $member['email'] ?? '' }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/40 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-envelope text-[10px]"></i> Email
          </a>
          <a href="{{ route('admin.members.show', encryptId($memberNumber)) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
          </a>
        </div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase mb-1">Gender</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['gender'] ?? '-' }}</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase mb-1">Branch</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['branch'] ?? '-' }}</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase mb-1">Phone</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['phone'] ?? '-' }}</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase mb-1">Email</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white truncate">{{ $member['email'] ?? '-' }}</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase mb-1">Occupation</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['occupation'] ?? '-' }}</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase mb-1">Employer</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white truncate">{{ $member['employer'] ?? '-' }}</p>
        </div>
      </div>
    </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="glass p-5">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 text-white flex items-center justify-center shadow-sm">
          <i class="fa-solid fa-coins text-sm"></i>
        </div>
        <div>
          <p class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase">Total Contribution</p>
          <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt($swfData['total_contribution'] ?? 0) }}</p>
        </div>
      </div>
    </div>
    <div class="glass p-5">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center shadow-sm">
          <i class="fa-solid fa-hand-holding-heart text-sm"></i>
        </div>
        <div>
          <p class="text-[10px] font-bold text-orange-600 dark:text-orange-400 uppercase">Benefits Paid</p>
          <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt($swfData['benefits_paid'] ?? 0) }}</p>
        </div>
      </div>
    </div>
    <div class="glass p-5">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-green-600 text-white flex items-center justify-center shadow-sm">
          <i class="fa-solid fa-wallet text-sm"></i>
        </div>
        <div>
          <p class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase">Current Balance</p>
          <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt($swfData['current_balance'] ?? 0) }}</p>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- Contributions Tab -->
  <div x-show="activeTab === 'contributions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-5">
      <h3 class="text-sm font-bold text-primary-900 dark:text-white mb-4 flex items-center gap-2">
        <i class="fa-solid fa-history text-purple-500"></i> Contribution History
      </h3>
      @if(isset($swfData['contributions']) && is_array($swfData['contributions']) && count($swfData['contributions']) > 0)
      <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th class="text-right">Amount</th>
              <th class="text-right">Running Balance</th>
            </tr>
          </thead>
          <tbody>
            @foreach($swfData['contributions'] as $contribution)
              @php
                $date = $contribution['date'] ?? '-';
                $type = $contribution['type'] ?? '-';
                $amount = $contribution['amount'] ?? 0;
                $balance = $contribution['running_balance'] ?? 0;
                $isCredit = strcasecmp($type, 'contribution') === 0 || strcasecmp($type, 'deposit') === 0;
              @endphp
              <tr>
                <td class="font-mono text-[11px] text-primary-700 dark:text-primary-300">{{ $date }}</td>
                <td>
                  <span class="badge {{ $isCredit ? 'badge-green' : 'badge-red' }}">
                    {{ ucfirst($type) }}
                  </span>
                </td>
                <td class="text-right font-bold text-xs {{ $isCredit ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                  {{ $isCredit ? '+' : '-' }}{{ $fmt($amount) }}
                </td>
                <td class="text-right font-black text-sm text-purple-700 dark:text-purple-400">{{ $fmt($balance) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
      <div class="text-center py-12">
        <i class="fa-solid fa-inbox text-4xl text-primary-300 dark:text-primary-700 mb-3 block"></i>
        <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">No contribution history available</p>
      </div>
      @endif
    </div>
  </div>

  <!-- Benefits Tab -->
  <div x-show="activeTab === 'benefits'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-5">
      <h3 class="text-sm font-bold text-primary-900 dark:text-white mb-4 flex items-center gap-2">
        <i class="fa-solid fa-hand-holding-heart text-orange-500"></i> Benefits History
      </h3>
      @if(isset($swfData['benefits']) && is_array($swfData['benefits']) && count($swfData['benefits']) > 0)
      <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Description</th>
              <th class="text-right">Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($swfData['benefits'] as $benefit)
              @php
                $date = $benefit['date'] ?? '-';
                $type = $benefit['type'] ?? '-';
                $description = $benefit['description'] ?? '-';
                $amount = $benefit['amount'] ?? 0;
                $status = $benefit['status'] ?? 'pending';
                $statusClass = $status === 'paid' ? 'badge-green' : ($status === 'pending' ? 'badge-yellow' : 'badge-red');
              @endphp
              <tr>
                <td class="font-mono text-[11px] text-primary-700 dark:text-primary-300">{{ $date }}</td>
                <td>
                  <span class="badge badge-purple">{{ ucfirst($type) }}</span>
                </td>
                <td class="text-xs text-primary-900 dark:text-white">{{ $description }}</td>
                <td class="text-right font-bold text-xs text-orange-600 dark:text-orange-400">{{ $fmt($amount) }}</td>
                <td><span class="badge {{ $statusClass }}">{{ ucfirst($status) }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
      <div class="text-center py-12">
        <i class="fa-solid fa-hand-holding-heart text-4xl text-primary-300 dark:text-primary-700 mb-3 block"></i>
        <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">No benefits history available</p>
      </div>
      @endif
    </div>
  </div>

</div>

@push('scripts')
<script>
  function swfShow() {
    return {
      activeTab: 'overview',
      tabs: [
        { id: 'overview', label: 'Overview', icon: 'fa-solid fa-circle-info' },
        { id: 'contributions', label: 'Contributions', icon: 'fa-solid fa-coins' },
        { id: 'benefits', label: 'Benefits', icon: 'fa-solid fa-hand-holding-heart' },
      ],
      init() {
        const hash = window.location.hash.replace('#tab-', '');
        const validTabs = this.tabs.map(t => t.id);
        if (hash && validTabs.includes(hash)) {
          this.activeTab = hash;
        }
      }
    }
  }
</script>
@endpush

@endsection
