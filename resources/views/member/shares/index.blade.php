@extends('layouts.member')

@section('breadcrumb', 'My Accounts › My Shares')
@section('page_title', 'My Shares')

@section('page-header')
<div class="glass p-5 lg:p-6 rounded-2xl overflow-hidden relative"
     style="background: linear-gradient(135deg, rgba(59,130,246,0.08) 0%, rgba(99,102,241,0.06) 100%);">
    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-5">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20 flex-shrink-0">
                <i class="fa-solid fa-chart-pie text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl lg:text-2xl font-extrabold text-primary-900 dark:text-white leading-tight">
                    My Shares
                </h1>
                <p class="text-xs mt-1 text-primary-600 dark:text-primary-400 font-medium">
                    Manage your shareholdings and certificates
                </p>
            </div>
        </div>

        <div class="flex-shrink-0 min-w-[240px]">
            <div class="glass p-4 rounded-xl">
                <div class="flex items-end justify-between mb-2">
                    <p class="text-[11px] uppercase font-bold tracking-wider text-primary-500 dark:text-primary-400">Total Shares</p>
                    <p class="text-sm font-extrabold text-blue-600 dark:text-blue-400 tabular-nums">{{ $totalShares }}</p>
                </div>
                <div class="progress-bar h-2">
                    <div class="progress-fill bg-gradient-to-r from-blue-500 to-blue-600" style="width: 100%"></div>
                </div>
                <div class="mt-3">
                    <p class="text-primary-500 dark:text-primary-400 text-[11px]">Total Value</p>
                    <p class="text-primary-900 dark:text-white tabular-nums font-bold">TSh {{ number_format($totalValue, 2, '.', ',') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Share Purchases -->
        <div class="glass p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-shopping-cart text-blue-500 text-xs"></i>
                    Share Purchases
                </h3>
                <span class="text-[11px] font-semibold text-blue-600 dark:text-blue-400">
                    {{ $sharePurchases->count() }} purchases
                </span>
            </div>
            
            @forelse($sharePurchases as $purchase)
                <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800/50 mb-3 last:mb-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-bold text-primary-900 dark:text-white text-sm">{{ $purchase->shareProduct->name ?? 'N/A' }}</span>
                                <span class="badge badge-green text-[10px]">{{ $purchase->payment_status }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <p class="text-primary-500 dark:text-primary-400">Shares</p>
                                    <p class="font-bold text-primary-900 dark:text-white">{{ $purchase->number_of_shares }}</p>
                                </div>
                                <div>
                                    <p class="text-primary-500 dark:text-primary-400">Value</p>
                                    <p class="font-bold text-primary-900 dark:text-white">TSh {{ number_format($purchase->number_of_shares * ($purchase->shareProduct->share_value ?? 10000), 2, '.', ',') }}</p>
                                </div>
                            </div>
                            <p class="text-[10px] text-primary-500 dark:text-primary-400 mt-2">
                                {{ $purchase->purchase_date ? \Carbon\Carbon::parse($purchase->purchase_date)->format('M j, Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-400 flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-shopping-cart text-lg"></i>
                    </div>
                    <p class="text-sm text-primary-600 dark:text-primary-400">No share purchases yet</p>
                </div>
            @endforelse
        </div>

        <!-- Share Certificates -->
        <div class="glass p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-primary-900 dark:text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-certificate text-purple-500 text-xs"></i>
                    Share Certificates
                </h3>
                <span class="text-[11px] font-semibold text-purple-600 dark:text-purple-400">
                    {{ $shareCertificates->count() }} certificates
                </span>
            </div>
            
            @forelse($shareCertificates as $certificate)
                <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800/50 mb-3 last:mb-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-mono font-bold text-primary-900 dark:text-white text-xs">{{ $certificate->certificate_number }}</span>
                                <span class="badge {{ $certificate->status === 'active' ? 'badge-green' : 'badge-gray' }} text-[10px]">{{ ucfirst($certificate->status) }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <p class="text-primary-500 dark:text-primary-400">Shares</p>
                                    <p class="font-bold text-primary-900 dark:text-white">{{ $certificate->number_of_shares }}</p>
                                </div>
                                <div>
                                    <p class="text-primary-500 dark:text-primary-400">Issue Date</p>
                                    <p class="font-bold text-primary-900 dark:text-white">{{ $certificate->issue_date ? \Carbon\Carbon::parse($certificate->issue_date)->format('M j, Y') : 'N/A' }}</p>
                                </div>
                            </div>
                            <p class="text-[10px] text-primary-500 dark:text-primary-400 mt-2">
                                {{ $certificate->shareProduct->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-400 flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-certificate text-lg"></i>
                    </div>
                    <p class="text-sm text-primary-600 dark:text-primary-400">No share certificates yet</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
