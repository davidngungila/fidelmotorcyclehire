@extends('layouts.admin')

@section('breadcrumb', 'Members › Deposits › Details')
@section('page_title', 'Deposit Certificate Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);

  $certNo = $deposit['certificate_number'] ?? ($deposit['CertificateNumber'] ?? $certificateNumber);
  $product = $deposit['product'] ?? ($deposit['Product'] ?? '-');
  $statusBadge = $dashboardService->depositStatusBadge($deposit['status'] ?? ($deposit['Status'] ?? null));
  $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? '-');
  $memberName = $member['name'] ?? ($member['Name'] ?? 'Unknown');
  $memberStatus = $dashboardService->memberStatusBadge($member['status'] ?? null);
@endphp

@section('content')

<div x-data="depositShow()" class="space-y-6">

  <!-- Tab Navigation -->
  <div class="glass p-2 rounded-xl flex gap-1 overflow-x-auto">
    <template x-for="tab in tabs" :key="tab.id">
      <button @click="activeTab = tab.id"
              :class="activeTab === tab.id ? 'bg-white dark:bg-primary-900 shadow-sm text-primary-900 dark:text-white' : 'text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/30'"
              class="flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">
        <i :class="tab.icon" class="text-[11px]"></i>
        <span x-text="tab.label"></span>
        <span x-if="tab.badge" class="badge badge-purple ml-1" x-text="tab.badge"></span>
      </button>
    </template>
  </div>

  <!-- Overview Tab -->
  <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-80 h-80 rounded-full bg-gradient-to-br from-purple-200/30 to-transparent dark:from-purple-900/20 -mr-40 -mt-40"></div>

    <div class="flex flex-col lg:flex-row lg:items-start gap-6 relative z-10">
      <div class="flex-shrink-0 w-full lg:w-auto">
        <div class="flex items-start gap-5">
          <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 text-white flex items-center justify-center shadow-xl ring-4 ring-white dark:ring-primary-900/40 flex-shrink-0">
            <i class="fa-solid fa-money-bill-trend-up text-3xl"></i>
          </div>
          <div class="min-w-0 flex-1">
            <div class="flex items-center gap-2 mb-2 flex-wrap">
              <p class="text-[10px] font-bold uppercase tracking-wider text-purple-500">Certificate Number</p>
              <span class="badge badge-purple" style="background:#f3e8ff;color:#6b21a8;">
                <i class="fa-solid fa-certificate text-[9px] mr-1"></i> FD
              </span>
            </div>
            <h1 class="font-mono text-2xl lg:text-3xl font-black text-primary-900 dark:text-white tracking-wider break-all">{{ $certNo }}</h1>
            <div class="flex items-center gap-2 mt-3 flex-wrap">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300 text-xs font-bold">
                <i class="fa-solid fa-tag text-[10px]"></i>
                {{ $product }}
              </span>
              <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full lg:ml-auto lg:w-auto lg:max-w-md">
        <div class="p-4 rounded-2xl bg-purple-50 dark:bg-purple-900/30 border border-purple-100 dark:border-purple-900/50">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 text-white flex items-center justify-center text-lg font-bold flex-shrink-0 shadow-sm">
              {{ strtoupper(substr($memberName, 0, 1) ?? 'M') }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between gap-2 flex-wrap">
                <p class="text-sm font-bold text-primary-900 dark:text-white truncate">{{ $memberName }}</p>
                <a href="{{ route('admin.members.show', $memberNo) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-white dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 text-[10px] font-bold hover:bg-primary-100 dark:hover:bg-primary-900 transition-colors">
                  <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                  View Profile
                </a>
              </div>
              <div class="mt-2 space-y-1">
                <div class="flex items-center gap-2 text-[11px]">
                  <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-white dark:bg-primary-900/50 font-mono font-bold text-primary-700 dark:text-primary-300">
                    <i class="fa-solid fa-id-card text-[9px] opacity-60"></i>
                    {{ $memberNo }}
                  </span>
                  <span class="badge {{ $memberStatus['class'] }} !text-[9px] !py-0.5 !px-1.5">{{ $memberStatus['label'] }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <a href="{{ route('admin.deposits.index') }}" class="mt-3 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800/50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold transition-colors">
          <i class="fa-solid fa-arrow-left text-[11px]"></i> Back to Deposits
        </a>
      </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6 pt-6 border-t border-primary-100 dark:border-primary-900/50">
      <div class="p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-primary-500 dark:text-primary-400 mb-1"><i class="fa-solid fa-money-bill mr-1"></i>Principal Amount</p>
        <p class="text-sm font-black text-primary-900 dark:text-white">{{ $fmt($amount) }}</p>
      </div>
      <div class="p-3 rounded-xl bg-purple-50 dark:bg-purple-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 mb-1"><i class="fa-solid fa-percent mr-1"></i>Interest Rate</p>
        <p class="text-sm font-black text-purple-700 dark:text-purple-400">{{ number_format($interestRate, 2) }}%</p>
      </div>
      <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-green-600 dark:text-green-400 mb-1"><i class="fa-solid fa-coins mr-1"></i>Interest Amount</p>
        <p class="text-sm font-black text-green-700 dark:text-green-400">{{ $fmt($interest) }}</p>
      </div>
      <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-900/30">
        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1"><i class="fa-solid fa-chart-line mr-1"></i>Current Value</p>
        <p class="text-sm font-black text-blue-700 dark:text-blue-400">{{ $fmt($currentValue) }}</p>
      </div>
    </div>

    <div class="mt-6 p-4 rounded-2xl bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30">
      <div class="flex items-center justify-between mb-2">
        <p class="text-xs font-bold text-purple-700 dark:text-purple-300 flex items-center gap-1.5">
          <i class="fa-solid fa-hourglass-half text-[11px]"></i> Maturity Progress
        </p>
        <span class="font-mono text-xs font-black text-primary-900 dark:text-white">{{ number_format($progress, 1) }}%</span>
      </div>
      <div class="progress-bar h-2.5">
        <div class="progress-fill" style="width: {{ $progress }}%; background: linear-gradient(90deg, #a855f7, #ec4899);"></div>
      </div>
      <div class="flex justify-between mt-2">
        <span class="text-[10px] font-mono text-purple-600 dark:text-purple-400"><i class="fa-solid fa-play mr-1"></i> {{ $startDate }}</span>
        <span class="text-[10px] font-mono text-pink-600 dark:text-pink-400"><i class="fa-solid fa-flag-checkered mr-1"></i> {{ $maturityDate }}</span>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="glass p-6">
      <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
        <i class="fa-solid fa-timeline text-purple-500 text-xs"></i> Placement Timeline
      </h3>

      <div class="relative">
        <div class="absolute left-[19px] top-1 bottom-1 w-0.5 bg-gradient-to-b from-purple-300 via-primary-300 to-green-300 dark:from-purple-800 dark:via-primary-800 dark:to-green-800"></div>

        <div class="space-y-6">
          @foreach($timeline as $event)
            @php
              $colorMap = [
                'primary' => 'bg-primary-500 border-primary-200 dark:border-primary-900',
                'yellow' => 'bg-yellow-500 border-yellow-200 dark:border-yellow-900',
                'blue' => 'bg-blue-500 border-blue-200 dark:border-blue-900',
                'green' => 'bg-green-500 border-green-200 dark:border-green-900',
                'purple' => 'bg-purple-500 border-purple-200 dark:border-purple-900',
              ];
              $dotColor = $colorMap[$event['color'] ?? 'primary'] ?? $colorMap['primary'];
            @endphp
            <div class="relative flex gap-4 pl-0">
              <div class="w-10 h-10 rounded-full {{ $dotColor }} ring-4 ring-white dark:ring-primary-900 flex items-center justify-center text-white z-10 flex-shrink-0 shadow-sm">
                <i class="fa-solid {{ $event['icon'] }} text-[11px]"></i>
              </div>
              <div class="flex-1 pb-1">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $event['title'] }}</p>
                  <span class="font-mono text-[11px] text-primary-500 dark:text-primary-400">{{ $event['date'] }}</span>
                </div>
                <p class="text-xs text-primary-600 dark:text-primary-400 mt-1">{{ $event['description'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="glass p-6">
      <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
        <i class="fa-solid fa-file-lines text-blue-500 text-xs"></i> Certificate Details
      </h3>
      <div class="space-y-4">
        <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
          <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Certificate #</span>
          <span class="font-mono text-sm font-bold text-primary-900 dark:text-white">{{ $certNo }}</span>
        </div>
        <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
          <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Product Type</span>
          <span class="text-sm font-bold text-primary-900 dark:text-white">{{ $product }}</span>
        </div>
        <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
          <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Holder</span>
          <span class="text-sm font-bold text-primary-900 dark:text-white">{{ $memberName }} ({{ $memberNo }})</span>
        </div>
        <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
          <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Placement Date</span>
          <span class="text-sm font-mono font-bold text-primary-900 dark:text-white">{{ $startDate }}</span>
        </div>
        <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
          <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Maturity Date</span>
          <span class="text-sm font-mono font-bold text-primary-900 dark:text-white">{{ $maturityDate }}</span>
        </div>
        <div class="flex items-start justify-between pb-4 border-b border-primary-100 dark:border-primary-900/50">
          <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Principal + Interest</span>
          <span class="text-sm font-black text-green-700 dark:text-green-400">{{ $fmt($amount + $interest) }}</span>
        </div>
        <div class="flex items-start justify-between">
          <span class="text-xs font-semibold text-primary-500 dark:text-primary-400">Status</span>
          <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- Accounts Tab -->
  <div x-show="activeTab === 'accounts'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-6">
      <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
        <i class="fa-solid fa-wallet text-purple-500 text-xs"></i>
        Sub-Accounts
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-900/10 border border-red-200 dark:border-red-900/30">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center">
              <i class="fa-solid fa-bolt"></i>
            </div>
            <div>
              <p class="text-xs font-bold text-red-700 dark:text-red-400">Emergency</p>
              <p class="text-[10px] text-red-600 dark:text-red-500">Quick Access</p>
            </div>
          </div>
          <p class="text-xl font-bold text-red-900 dark:text-red-300">{{ $fmt($emergencyBalance ?? 0) }}</p>
        </div>
        <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10 border border-blue-200 dark:border-blue-900/30">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-500 text-white flex items-center justify-center">
              <i class="fa-solid fa-arrows-rotate"></i>
            </div>
            <div>
              <p class="text-xs font-bold text-blue-700 dark:text-blue-400">Flex</p>
              <p class="text-[10px] text-blue-600 dark:text-blue-500">Flexible</p>
            </div>
          </div>
          <p class="text-xl font-bold text-blue-900 dark:text-blue-300">{{ $fmt($flexBalance ?? 0) }}</p>
        </div>
        <div class="p-4 rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-900/10 border border-green-200 dark:border-green-900/30">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-green-500 text-white flex items-center justify-center">
              <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
              <p class="text-xs font-bold text-green-700 dark:text-green-400">RDA</p>
              <p class="text-[10px] text-green-600 dark:text-green-500">Regular Deposit</p>
            </div>
          </div>
          <p class="text-xl font-bold text-green-900 dark:text-green-300">{{ $fmt($rdaBalance ?? 0) }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Transactions Tab -->
  <div x-show="activeTab === 'transactions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-6">
      <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
        <i class="fa-solid fa-clock-rotate-left text-purple-500 text-xs"></i>
        Transaction History
      </h3>
      <div class="overflow-x-auto rounded-xl">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Account</th>
              <th class="text-right">Amount</th>
              <th class="text-right">Balance</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transactions ?? [] as $tx)
              <tr>
                <td class="text-xs font-mono text-primary-700 dark:text-primary-300">{{ $tx['date'] ?? '-' }}</td>
                <td>
                  <span class="badge {{ $tx['type'] === 'credit' ? 'badge-green' : 'badge-red' }}">
                    {{ ucfirst($tx['type'] ?? 'debit') }}
                  </span>
                </td>
                <td class="text-xs text-primary-900 dark:text-white">{{ $tx['account'] ?? '-' }}</td>
                <td class="text-right text-xs font-bold {{ $tx['type'] === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                  {{ $tx['type'] === 'credit' ? '+' : '-' }}{{ $fmt($tx['amount'] ?? 0) }}
                </td>
                <td class="text-right text-xs font-bold text-primary-900 dark:text-white">{{ $fmt($tx['balance'] ?? 0) }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-12 text-primary-500 dark:text-primary-400">
                  <i class="fa-solid fa-receipt text-3xl mb-3 block opacity-30"></i>
                  <p class="text-sm font-semibold mb-1">No transactions yet</p>
                  <p class="text-xs">Transaction history will appear here</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Saving Plan Tab -->
  <div x-show="activeTab === 'saving-plan'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-6">
      <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
        <i class="fa-solid fa-bullseye text-purple-500 text-xs"></i>
        Saving Plan
      </h3>
      <div class="space-y-4">
        <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-900/50">
          <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-primary-700 dark:text-primary-300">Target Amount</p>
            <p class="text-lg font-bold text-primary-900 dark:text-white">{{ $fmt($targetAmount ?? 0) }}</p>
          </div>
          <div class="progress-bar h-2">
            <div class="progress-fill" style="width: {{ $savingProgress ?? 0 }}%"></div>
          </div>
          <p class="text-[10px] text-primary-600 dark:text-primary-400 mt-2">{{ number_format($savingProgress ?? 0, 1) }}% of target achieved</p>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="p-3 rounded-xl bg-purple-50 dark:bg-purple-900/30">
            <p class="text-[10px] font-bold text-purple-600 dark:text-purple-400 mb-1">Monthly Contribution</p>
            <p class="text-sm font-bold text-purple-900 dark:text-purple-300">{{ $fmt($monthlyContribution ?? 0) }}</p>
          </div>
          <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/30">
            <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mb-1">Total Saved</p>
            <p class="text-sm font-bold text-green-900 dark:text-green-300">{{ $fmt($totalSaved ?? 0) }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Statements Tab -->
  <div x-show="activeTab === 'statements'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="glass p-6">
      <h3 class="font-bold text-primary-900 dark:text-white text-sm mb-5 flex items-center gap-2">
        <i class="fa-solid fa-file-lines text-purple-500 text-xs"></i>
        Statements
      </h3>
      <div class="space-y-3">
        @forelse($statements ?? [] as $stmt)
          <div class="flex items-center justify-between p-4 rounded-xl bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-900/50">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg bg-primary-500 text-white flex items-center justify-center">
                <i class="fa-solid fa-file-pdf"></i>
              </div>
              <div>
                <p class="text-xs font-bold text-primary-900 dark:text-white">{{ $stmt['period'] ?? 'Statement' }}</p>
                <p class="text-[10px] text-primary-600 dark:text-primary-400">{{ $stmt['date'] ?? '-' }}</p>
              </div>
            </div>
            <button class="px-3 py-1.5 rounded-lg bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold transition-colors">
              <i class="fa-solid fa-download mr-1"></i> Download
            </button>
          </div>
        @empty
          <div class="text-center py-12 text-primary-500 dark:text-primary-400">
            <i class="fa-solid fa-file-lines text-3xl mb-3 block opacity-30"></i>
            <p class="text-sm font-semibold mb-1">No statements available</p>
            <p class="text-xs">Statements will be generated monthly</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>

</div>

@push('scripts')
<script>
  function depositShow() {
    return {
      activeTab: 'overview',
      tabs: [
        { id: 'overview', label: 'Overview', icon: 'fa-solid fa-circle-info', badge: null },
        { id: 'accounts', label: 'Accounts', icon: 'fa-solid fa-wallet', badge: null },
        { id: 'transactions', label: 'Transactions', icon: 'fa-solid fa-clock-rotate-left', badge: null },
        { id: 'saving-plan', label: 'Saving Plan', icon: 'fa-solid fa-bullseye', badge: null },
        { id: 'statements', label: 'Statements', icon: 'fa-solid fa-file-lines', badge: null },
      ],
      init() {
        const hash = window.location.hash.replace('#tab-', '');
        const validTabs = this.tabs.map(t => t.id);
        if (hash && validTabs.includes(hash)) {
          this.activeTab = hash;
        }
      },
      updateHash(tabId) {
        if (history.pushState) {
          history.pushState(null, null, '#tab-' + tabId);
        }
      }
    }
  }
</script>
@endpush

@endsection
