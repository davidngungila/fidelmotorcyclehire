@extends('layouts.admin')

@section('breadcrumb', 'Shares › View')
@section('page_title', 'Member Shares Details')

@php
  $fmt = fn($n) => number_format((float)$n, 2) . ' TSh';
  $fmtInt = fn($n) => number_format((int)$n);
@endphp

@section('content')

<div class="space-y-6">
  @if($member)
    <div class="glass p-6">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center text-2xl font-bold shadow-lg">
            {{ strtoupper(substr($member['name'] ?? 'M', 0, 1)) }}
          </div>
          <div>
            <h2 class="text-xl font-bold text-primary-900 dark:text-white">{{ $member['name'] ?? 'Unknown' }}</h2>
            <div class="flex items-center gap-3 mt-1">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/40 font-mono text-xs font-bold text-blue-700 dark:text-blue-300">
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
          <a href="{{ route('admin.members.show', encryptId($memberNumber)) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-user text-[10px]"></i> View Profile
          </a>
          <a href="{{ route('admin.shares.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800/50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold transition-colors">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Back
          </a>
        </div>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase mb-1">Gender</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['gender'] ?? '-' }}</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase mb-1">Branch</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['branch'] ?? '-' }}</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase mb-1">Phone</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['phone'] ?? '-' }}</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase mb-1">Email</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white truncate">{{ $member['email'] ?? '-' }}</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase mb-1">Occupation</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $member['occupation'] ?? '-' }}</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4">
          <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase mb-1">Employer</p>
          <p class="text-sm font-bold text-primary-900 dark:text-white truncate">{{ $member['employer'] ?? '-' }}</p>
        </div>
      </div>
    </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="glass p-5">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center shadow-sm">
          <i class="fa-solid fa-building-columns text-sm"></i>
        </div>
        <div>
          <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase">Total Shares</p>
          <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmtInt(collect($shares)->sum(fn($s) => $s['quantity'] ?? ($s['Quantity'] ?? 0))) }}</p>
        </div>
      </div>
    </div>
    <div class="glass p-5">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-400 to-green-600 text-white flex items-center justify-center shadow-sm">
          <i class="fa-solid fa-chart-line text-sm"></i>
        </div>
        <div>
          <p class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase">Total Value</p>
          <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt(collect($shares)->sum(fn($s) => ($s['quantity'] ?? ($s['Quantity'] ?? 0)) * ($s['value_per_share'] ?? ($s['ValuePerShare'] ?? 0)))) }}</p>
        </div>
      </div>
    </div>
    <div class="glass p-5">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 text-white flex items-center justify-center shadow-sm">
          <i class="fa-solid fa-coins text-sm"></i>
        </div>
        <div>
          <p class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase">Avg Value/Share</p>
          <p class="text-lg font-black text-primary-900 dark:text-white">{{ $fmt(collect($shares)->avg(fn($s) => $s['value_per_share'] ?? ($s['ValuePerShare'] ?? 0))) }}</p>
        </div>
      </div>
    </div>
  </div>

  @if(isset($shares) && is_array($shares) && count($shares) > 0)
    <div class="glass p-5">
      <h3 class="text-sm font-bold text-primary-900 dark:text-white mb-4 flex items-center gap-2">
        <i class="fa-solid fa-building-columns text-blue-500"></i> Share Holdings
      </h3>
      <div class="overflow-x-auto -webkit-scrollbar [&::-webkit-scrollbar]:hidden rounded-2xl">
        <table class="data-table">
          <thead>
            <tr>
              <th>Share #</th>
              <th>Type</th>
              <th class="text-right">Quantity</th>
              <th class="text-right">Value per Share</th>
              <th class="text-right">Total Value</th>
              <th>Purchase Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($shares as $share)
              @php
                $shareNo = $share['share_number'] ?? ($share['ShareNumber'] ?? '-');
                $type = $share['type'] ?? ($share['Type'] ?? 'Ordinary');
                $quantity = $share['quantity'] ?? ($share['Quantity'] ?? 0);
                $valuePerShare = $share['value_per_share'] ?? ($share['ValuePerShare'] ?? 0);
                $totalValue = $quantity * $valuePerShare;
                $purchaseDate = $share['purchase_date'] ?? ($share['PurchaseDate'] ?? '-');
                $status = $share['status'] ?? ($share['Status'] ?? 'active');
                $statusClass = $status === 'active' ? 'badge-green' : ($status === 'pending' ? 'badge-yellow' : 'badge-red');
              @endphp
              <tr>
                <td class="font-mono text-[11px] text-primary-700 dark:text-primary-300">{{ $shareNo }}</td>
                <td>
                  <span class="badge badge-blue">{{ ucfirst($type) }}</span>
                </td>
                <td class="text-right font-bold text-xs text-primary-900 dark:text-white">{{ $fmtInt($quantity) }}</td>
                <td class="text-right font-bold text-xs text-primary-900 dark:text-white">{{ $fmt($valuePerShare) }}</td>
                <td class="text-right font-bold text-xs text-green-600 dark:text-green-400">{{ $fmt($totalValue) }}</td>
                <td class="text-xs text-primary-700 dark:text-primary-300">{{ $purchaseDate }}</td>
                <td><span class="badge {{ $statusClass }}">{{ ucfirst($status) }}</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @else
    <div class="glass p-8 text-center">
      <i class="fa-solid fa-building-columns text-4xl text-primary-300 dark:text-primary-700 mb-3 block"></i>
      <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">No share holdings found</p>
    </div>
  @endif
</div>

@endsection
