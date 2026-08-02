@php
    function fmtTsh2($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }

    $settings = \Illuminate\Support\Facades\Cache::get('share_settings', []);
    $certificateBackgroundPath = $settings['certificate_background'] ?? '';
    $certificateBackgroundUrl = $certificateBackgroundPath ? asset('storage/' . $certificateBackgroundPath) : '';
    $memberName = auth()->user()->name;
@endphp

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
                        <button type="button" onclick="previewShareCertificate({{ $certificate->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 text-xs font-bold hover:bg-purple-200 dark:hover:bg-purple-900/70 transition-colors">
                            <i class="fa-solid fa-certificate text-[10px]"></i> Preview
                        </button>
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

    <!-- Share Certificate Preview Modal -->
    <div id="shareCertificateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[9999]">
        <div class="bg-white dark:bg-dark-card rounded-xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Share Certificate Preview</h3>
                    <button onclick="closeShareModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>
                <div id="shareCertificatePreview">
                    <!-- Certificate content will be loaded here -->
                </div>
                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="printShareCertificate()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-sm font-semibold transition-all">
                        <i class="fa-solid fa-print"></i> Print Certificate
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
  const certificates = @json($shareCertificates);

  function previewShareCertificate(certificateId) {
    const modal = document.getElementById('shareCertificateModal');
    const preview = document.getElementById('shareCertificatePreview');
    
    const certificate = certificates.find(c => c.id === certificateId);
    if (!certificate) return;
    
    let backgroundStyle = '';
    if ('{{ $certificateBackgroundUrl }}') {
      backgroundStyle = `background-image: url('{{ $certificateBackgroundUrl }}'); background-size: cover; background-position: center; background-repeat: no-repeat;`;
    }
    
    const totalValue = certificate.number_of_shares * (certificate.share_product?.share_value || 10000);
    
    preview.innerHTML = `
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
      <div style="${backgroundStyle} min-height: 500px; padding: 40px; position: relative;">
        <div style="padding: 40px; position: relative; z-index: 1;">
          <div style="text-align: center; margin-bottom: 20px;">
            <h1 style="font-size: 32px; font-weight: bold; color: #1e40af; margin-bottom: 5px; font-family: 'Times New Roman', serif;">CERTIFICATE OF OWNERSHIP</h1>
          </div>
          
          <div style="text-align: center; margin-bottom: 30px;">
            <p style="color: #1f2937; font-size: 16px; margin-bottom: 10px;">THIS CERTIFICATE IS PROUDLY PRESENTED TO</p>
            <h2 style="font-size: 36px; color: #1e40af; margin: 10px 0; font-family: 'Great Vibes', cursive;">{{ $memberName }}</h2>
            <div style="width: 350px; height: 2px; background: linear-gradient(to right, transparent, #1e40af, transparent); margin: 8px auto;"></div>
            <p style="color: #1f2937; font-size: 16px;">This certifies that {{ $memberName }} is the registered owner of <strong>${certificate.number_of_shares} ${certificate.share_product?.name || 'Shares'}</strong> in FEEDTAN COMMUNITY MICROFINANCE GROUP with a total value of <strong>TZS ${totalValue.toLocaleString()}</strong>. The shares are issued in accordance with the organization's Constitution, Share Policy, and applicable regulations, granting the shareholder all rights and responsibilities of ownership.</p>
          </div>
          
          <div style="text-align: center; margin-top: 30px; padding: 20px; background: rgba(255, 255, 255, 0.4); border-radius: 8px;">
            <p style="color: #1f2937; font-size: 14px; line-height: 1.8;">
              Certificate No: <strong>${certificate.certificate_number}</strong> | 
              Status: <strong>${certificate.status}</strong> | 
              Share Product: <strong>${certificate.share_product?.name || 'N/A'}</strong> | 
              Issue Date: <strong>${new Date(certificate.issue_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' })}</strong> | 
              Ownership Status: <strong>Fully Paid</strong> | 
              Expiry: <strong>N/A (Permanent)</strong>
            </p>
          </div>
          
          <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid rgba(0,0,0,0.1);">
            <p style="color: #1f2937; font-size: 14px;">This certificate serves as official proof of share ownership and remains valid according to the organization's governing policies.</p>
          </div>
        </div>
      </div>
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeShareModal() {
    const modal = document.getElementById('shareCertificateModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  function printShareCertificate() {
    const preview = document.getElementById('shareCertificatePreview');
    const printContent = preview.innerHTML;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
      <html>
        <head>
          <title>Share Certificate</title>
          <style>
            body { margin: 0; padding: 20px; font-family: Arial, sans-serif; }
            @media print { body { margin: 0; } }
          </style>
        </head>
        <body>${printContent}</body>
      </html>
    `);
    printWindow.document.close();
    printWindow.print();
  }
</script>
@endpush
