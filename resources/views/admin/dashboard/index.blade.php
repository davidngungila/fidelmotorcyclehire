@extends('layouts.admin')

@section('breadcrumb', 'Dashboard')
@section('page_title', 'Motorcycle Hire Purchase Dashboard')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
  $statusBadge = $totals['google_sheet_status_badge'] ?? ['label' => 'Unknown', 'class' => 'badge-gray', 'icon' => 'fa-question-circle'];
@endphp

@section('content')

<div x-data="dashboardAnimations()" class="space-y-6">

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

    <div class="stat-card glass" style="background: linear-gradient(135deg, rgba(52,211,153,0.15), rgba(16,185,129,0.05));">
      <div class="bg-blob bg-primary-400"></div>
      <div class="flex items-start justify-between relative z-10">
        <div class="icon-wrap bg-primary-100 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400">
          <i class="fa-solid fa-users"></i>
        </div>
        <span class="text-[10px] font-semibold badge badge-green">+3.2%</span>
      </div>
      <div class="mt-4 relative z-10">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-primary-600 dark:text-primary-400">Total Customers</p>
        <p class="text-2xl font-bold mt-1 text-primary-900 dark:text-white"
           x-data="{ val: 0 }"
           x-init="animateCounter($el, {{ $totals['total_members_raw'] ?? 0 }})"
           x-text="formatInt(val)">
          {{ $totals['total_members'] ?? '0' }}
        </p>
      </div>
    </div>

    <div class="stat-card glass" style="background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(37,99,235,0.05));">
      <div class="bg-blob bg-blue-400"></div>
      <div class="flex items-start justify-between relative z-10">
        <div class="icon-wrap bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400">
          <i class="fa-solid fa-motorcycle"></i>
        </div>
        <span class="text-[10px] font-semibold badge badge-blue">+5.1%</span>
      </div>
      <div class="mt-4 relative z-10">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-blue-600 dark:text-blue-400">Total Motorcycles</p>
        <p class="text-2xl font-bold mt-1 text-primary-900 dark:text-white"
           x-data="{ val: 0 }"
           x-init="animateCounter($el, {{ $totals['total_motorcycles_raw'] ?? 0 }})"
           x-text="formatInt(val)">
          {{ $totals['total_motorcycles'] ?? '0' }}
        </p>
      </div>
    </div>

    <div class="stat-card glass" style="background: linear-gradient(135deg, rgba(249,115,22,0.15), rgba(234,88,12,0.05));">
      <div class="bg-blob bg-orange-400"></div>
      <div class="flex items-start justify-between relative z-10">
        <div class="icon-wrap bg-orange-100 text-orange-600 dark:bg-orange-900/40 dark:text-orange-400">
          <i class="fa-solid fa-hand-holding-dollar"></i>
        </div>
        <span class="text-[10px] font-semibold badge badge-yellow">+2.4%</span>
      </div>
      <div class="mt-4 relative z-10">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-orange-600 dark:text-orange-400">Active Loans</p>
        <p class="text-2xl font-bold mt-1 text-primary-900 dark:text-white"
           x-data="{ val: 0 }"
           x-init="animateCounter($el, {{ $totals['active_loans_raw'] ?? 0 }})"
           x-text="formatInt(val)">
          {{ $totals['active_loans'] ?? '0' }}
        </p>
      </div>
    </div>

    <div class="stat-card glass" style="background: linear-gradient(135deg, rgba(168,85,247,0.15), rgba(147,51,234,0.05));">
      <div class="bg-blob bg-purple-400"></div>
      <div class="flex items-start justify-between relative z-10">
        <div class="icon-wrap bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-400">
          <i class="fa-solid fa-circle-check"></i>
        </div>
        <span class="text-[10px] font-semibold badge badge-green">+7.3%</span>
      </div>
      <div class="mt-4 relative z-10">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-purple-600 dark:text-purple-400">Available Motorcycles</p>
        <p class="text-2xl font-bold mt-1 text-primary-900 dark:text-white"
           x-data="{ val: 0 }"
           x-init="animateCounter($el, {{ $totals['available_motorcycles_raw'] ?? 0 }})"
           x-text="formatInt(val)">
          {{ $totals['available_motorcycles'] ?? '0' }}
        </p>
      </div>
    </div>

    <div class="stat-card glass" style="background: linear-gradient(135deg, rgba(132,204,22,0.15), rgba(101,163,13,0.05));">
      <div class="bg-blob bg-lime-400"></div>
      <div class="flex items-start justify-between relative z-10">
        <div class="icon-wrap bg-lime-100 text-lime-600 dark:bg-lime-900/40 dark:text-lime-400">
          <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <span class="text-[10px] font-semibold badge badge-green">+8.2%</span>
      </div>
      <div class="mt-4 relative z-10">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-lime-600 dark:text-lime-400">Total Revenue</p>
        <p class="text-2xl font-bold mt-1 text-primary-900 dark:text-white"
           x-data="{ val: 0 }"
           x-init="animateCounter($el, {{ $totals['total_revenue_raw'] ?? 0 }}, true)"
           x-text="formatMoney(val)">
          {{ $totals['total_revenue'] ?? '0.00 TSh' }}
        </p>
      </div>
    </div>

    <div class="stat-card glass" style="background: linear-gradient(135deg, rgba(6,182,212,0.15), rgba(8,145,178,0.05));">
      <div class="bg-blob bg-cyan-400"></div>
      <div class="flex items-start justify-between relative z-10">
        <div class="icon-wrap bg-cyan-100 text-cyan-600 dark:bg-cyan-900/40 dark:text-cyan-400">
          <i class="fa-solid fa-file-signature"></i>
        </div>
        <span class="text-[10px] font-semibold badge badge-blue">+4.1%</span>
      </div>
      <div class="mt-4 relative z-10">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-cyan-600 dark:text-cyan-400">Pending Applications</p>
        <p class="text-2xl font-bold mt-1 text-primary-900 dark:text-white"
           x-data="{ val: 0 }"
           x-init="animateCounter($el, {{ $totals['pending_applications_raw'] ?? 0 }})"
           x-text="formatInt(val)">
          {{ $totals['pending_applications'] ?? '0' }}
        </p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="glass p-5 lg:col-span-2">
      <div class="flex items-center justify-between mb-5">
        <div>
          <h3 class="font-bold text-primary-900 dark:text-white">Customer Growth</h3>
          <p class="text-xs text-primary-500 dark:text-primary-400 mt-0.5">New registrations over last 6 months</p>
        </div>
        <span class="badge badge-green text-[10px]">
          <i class="fa-solid fa-arrow-trend-up mr-1 text-[9px]"></i> +18.4%
        </span>
      </div>
      <div class="h-64">
        <canvas id="customerGrowthChart"></canvas>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center gap-2 mb-4">
        <div class="w-9 h-9 rounded-xl bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-400 flex items-center justify-center">
          <i class="fa-solid fa-motorcycle"></i>
        </div>
        <div class="flex-1 min-w-0">
          <h3 class="font-bold text-primary-900 dark:text-white text-sm">Motorcycle Inventory Status</h3>
          <p class="text-[11px] text-primary-500 dark:text-primary-400">Current fleet overview</p>
        </div>
      </div>

      <div class="space-y-3 mb-5">
        <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
          <span class="text-xs text-primary-600 dark:text-primary-400">Available</span>
          <span class="text-xs font-bold text-primary-900 dark:text-white">{{ $totals['available_motorcycles'] ?? '0' }}</span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
          <span class="text-xs text-primary-600 dark:text-primary-400">Assigned</span>
          <span class="text-xs font-bold text-primary-900 dark:text-white">{{ $totals['assigned_motorcycles'] ?? '0' }}</span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
          <span class="text-xs text-primary-600 dark:text-primary-400">Under Repair</span>
          <span class="text-xs font-bold text-primary-900 dark:text-white">{{ $totals['repair_motorcycles'] ?? '0' }}</span>
        </div>
        <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50 dark:bg-primary-900/30">
          <span class="text-xs text-primary-600 dark:text-primary-400">Sold</span>
          <span class="text-xs font-bold text-primary-900 dark:text-white">{{ $totals['sold_motorcycles'] ?? '0' }}</span>
        </div>
      </div>

      <a href="{{ route('admin.motorcycles.index') }}" class="w-full py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all active:scale-95 inline-flex items-center justify-center gap-2">
        <i class="fa-solid fa-list text-[11px]"></i>
        View All Motorcycles
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="glass p-5 lg:col-span-2">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="font-bold text-primary-900 dark:text-white">Recently Registered Customers</h3>
          <p class="text-xs text-primary-500 dark:text-primary-400 mt-0.5">Latest 5 customers onboarded</p>
        </div>
        <a href="{{ route('admin.members.index') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:text-primary-500 transition-colors">
          View All <i class="fa-solid fa-arrow-right ml-1 text-[10px]"></i>
        </a>
      </div>

      <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-5">
        <div class="relative">
          <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-primary-400"></i>
          <input type="text" name="q" value="{{ $searchQuery ?? '' }}"
                 placeholder="Quick search by customer number, name, phone..."
                 class="form-input pl-9 py-2.5 text-sm"/>
        </div>
      </form>

      @if($searchResults !== null)
        <div class="mb-4 p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-900/40">
          <p class="text-xs text-blue-700 dark:text-blue-300 font-semibold">
            <i class="fa-solid fa-search mr-1"></i> {{ count($searchResults) }} result(s) for "{{ $searchQuery }}"
          </p>
        </div>
        @php $displayMembers = $searchResults; @endphp
      @else
        @php $displayMembers = $recentMembers; @endphp
      @endif

      <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden">
        <table class="data-table">
          <thead>
            <tr>
              <th>Customer #</th>
              <th>Full Name</th>
              <th>Branch</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse(array_slice($displayMembers, 0, 5) as $member)
              @php
                $statusBadge = $dashboardService->memberStatusBadge($member['status'] ?? null);
                $memberNo = $member['member_number'] ?? ($member['MemberNumber'] ?? 'FTN-00000');
                $memberName = $member['name'] ?? ($member['Name'] ?? 'Unknown');
                $memberBranch = $member['branch'] ?? ($member['Branch'] ?? '-');
                $encryptedMemberNo = app(\App\Services\EncryptedIdService::class)->encrypt($memberNo);
              @endphp
              <tr>
                <td class="font-mono text-xs font-semibold text-primary-600 dark:text-primary-400">{{ $memberNo }}</td>
                <td class="font-semibold text-primary-900 dark:text-white">{{ $memberName }}</td>
                <td class="text-primary-600 dark:text-primary-400 text-xs">{{ $memberBranch }}</td>
                <td><span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span></td>
                <td class="text-right">
                  <a href="{{ route('admin.members.show', $encryptedMemberNo) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-[11px] font-bold transition-colors">
                    <i class="fa-solid fa-eye text-[10px]"></i> Profile
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-8 text-primary-500 dark:text-primary-400 text-xs">
                  <i class="fa-solid fa-user-slash text-2xl mb-2 block opacity-40"></i>
                  No customers found
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="glass p-5">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="font-bold text-primary-900 dark:text-white">Recent Loan Applications</h3>
          <p class="text-xs text-primary-500 dark:text-primary-400 mt-0.5">Latest 10 applications</p>
        </div>
        <a href="{{ route('admin.loans.applications') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:text-primary-500 transition-colors">
          View All <i class="fa-solid fa-arrow-right ml-1 text-[10px]"></i>
        </a>
      </div>

      <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Customer</th>
              <th>Status</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentApplications ?? [] as $application)
              @php
                $statusColors = [
                  'pending' => 'bg-yellow-100 text-yellow-700',
                  'approved' => 'bg-green-100 text-green-700',
                  'rejected' => 'bg-red-100 text-red-700',
                ];
                $color = $statusColors[$application['status']] ?? 'bg-gray-100 text-gray-700';
              @endphp
              <tr>
                <td class="text-xs text-primary-600 dark:text-primary-400">{{ $application['date'] }}</td>
                <td class="font-mono text-xs font-semibold text-primary-900 dark:text-white">{{ $application['customer_number'] }}</td>
                <td><span class="badge {{ $color }} text-[10px]">{{ ucfirst($application['status']) }}</span></td>
                <td class="text-right font-semibold text-primary-900 dark:text-white">
                  {{ number_format($application['amount'], 2) }} TSh
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center py-8 text-primary-500 dark:text-primary-400 text-xs">
                  <i class="fa-solid fa-file-signature text-2xl mb-2 block opacity-40"></i>
                  No applications found
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function dashboardAnimations() {
    return {
      formatInt(n) {
        return Math.floor(n).toLocaleString('en-US');
      },
      formatMoney(n) {
        return Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' TSh';
      },
      animateCounter(el, target, isMoney = false) {
        const duration = 1400;
        const start = performance.now();
        const step = (now) => {
          const progress = Math.min((now - start) / duration, 1);
          const eased = 1 - Math.pow(1 - progress, 3);
          const current = target * eased;
          this.val = current;
          if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
      }
    };
  }

  document.addEventListener('alpine:init', () => {
    const ctx = document.getElementById('customerGrowthChart');
    if (ctx) {
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
          datasets: [{
            label: 'New Customers',
            data: [42, 58, 67, 73, 89, 97],
            backgroundColor: 'rgba(16, 185, 129, 0.7)',
            borderColor: 'rgba(16, 185, 129, 1)',
            borderWidth: 1,
            borderRadius: 8,
            borderSkipped: false,
            maxBarThickness: 40,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#064e3b',
              titleColor: '#6ee7b7',
              bodyColor: '#fff',
              borderColor: '#059669',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 8,
              displayColors: false,
              callbacks: {
                label: (ctx) => `${ctx.parsed.y} customers`
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: { color: document.documentElement.classList.contains('dark') ? '#6ee7b7' : '#065f46', font: { size: 11, weight: 600 } }
            },
            y: {
              beginAtZero: true,
              grid: { color: document.documentElement.classList.contains('dark') ? 'rgba(26,51,40,0.5)' : 'rgba(209,250,229,0.6)' },
              ticks: { color: document.documentElement.classList.contains('dark') ? '#6ee7b7' : '#065f46', font: { size: 10 }, precision: 0 }
            }
          }
        }
      });
    }
  });
</script>
@endpush
