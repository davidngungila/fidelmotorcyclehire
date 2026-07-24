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

<div x-data class="space-y-6">

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

@endsection
