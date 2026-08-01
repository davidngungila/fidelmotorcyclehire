@extends('layouts.admin')

@section('breadcrumb', 'System › Share Certificates › ' . $shareCertificate->certificate_number)
@section('page_title', 'Share Certificate: ' . $shareCertificate->certificate_number)

@section('content')

<div class="space-y-6">

  <div class="flex items-center gap-3">
    <a href="{{ route('admin.share-certificates.index') }}"
       class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors text-sm">
      <i class="fa-solid fa-arrow-left"></i> Back to Share Certificates
    </a>
  </div>

  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">Certificate Information</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Certificate Number:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareCertificate->certificate_number }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">User:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareCertificate->user->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Share Product:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareCertificate->shareProduct->name ?? 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Number of Shares:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareCertificate->number_of_shares }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Share Purchase:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareCertificate->sharePurchase->id ?? 'N/A' }}</span>
          </div>
        </div>
      </div>

      <div>
        <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-4">Status & Dates</h3>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
            <span class="text-sm font-medium">
              @if($shareCertificate->status === 'active')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Active</span>
              @elseif($shareCertificate->status === 'inactive')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400">Inactive</span>
              @elseif($shareCertificate->status === 'transferred')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Transferred</span>
              @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Cancelled</span>
              @endif
            </span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Issue Date:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareCertificate->issue_date ? $shareCertificate->issue_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Expiry Date:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareCertificate->expiry_date ? $shareCertificate->expiry_date->format('M d, Y') : 'N/A' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Created At:</span>
            <span class="text-sm text-primary-900 dark:text-white font-medium">{{ $shareCertificate->created_at->format('M d, Y H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    @if($shareCertificate->notes)
    <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-800">
      <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-3">Notes</h3>
      <p class="text-sm text-primary-700 dark:text-primary-300">{{ $shareCertificate->notes }}</p>
    </div>
    @endif

    @if($shareCertificate->shareTransfers->count() > 0)
    <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-800">
      <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-3">Transfer History ({{ $shareCertificate->shareTransfers->count() }})</h3>
      <div class="space-y-2">
        @foreach($shareCertificate->shareTransfers as $transfer)
        <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
          <span class="text-sm text-primary-900 dark:text-white">{{ $transfer->fromUser->name ?? 'N/A' }} → {{ $transfer->toUser->name ?? 'N/A' }}</span>
          <span class="text-sm text-primary-700 dark:text-primary-300">{{ $transfer->transfer_date ? $transfer->transfer_date->format('M d, Y') : 'N/A' }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    @if($shareCertificate->shareDividends->count() > 0)
    <div class="mt-6 pt-6 border-t border-primary-100 dark:border-primary-800">
      <h3 class="text-sm font-semibold text-primary-600 dark:text-primary-400 mb-3">Dividend History ({{ $shareCertificate->shareDividends->count() }})</h3>
      <div class="space-y-2">
        @foreach($shareCertificate->shareDividends as $dividend)
        <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
          <span class="text-sm text-primary-900 dark:text-white">{{ number_format($dividend->total_dividend, 2) }}</span>
          <span class="text-sm text-primary-700 dark:text-primary-300">{{ $dividend->declaration_date ? $dividend->declaration_date->format('M d, Y') : 'N/A' }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-primary-100 dark:border-primary-800">
      <button type="button"
              onclick="previewCertificate()"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md">
        <i class="fa-solid fa-eye"></i> Preview Certificate
      </button>
      <button type="button"
              onclick="printCertificate()"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md">
        <i class="fa-solid fa-print"></i> Print Certificate
      </button>
      <a href="{{ route('admin.share-certificates.edit', encrypt($shareCertificate->id)) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-teal-600 hover:bg-teal-500 text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md">
        <i class="fa-solid fa-edit"></i> Edit
      </a>
      <button type="button"
              onclick="deleteShareCertificate('{{ route('admin.share-certificates.destroy', encrypt($shareCertificate->id)) }}')"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white text-sm font-semibold transition-all shadow-sm hover:shadow-md">
        <i class="fa-solid fa-trash"></i> Delete
      </button>
    </div>
  </div>

</div>

<!-- Certificate Preview Modal -->
<div id="certificateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white dark:bg-dark-card rounded-xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-auto">
    <div class="p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Certificate Preview</h3>
        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
          <i class="fa-solid fa-times text-xl"></i>
        </button>
      </div>
      <div id="certificatePreview" class="border border-gray-200 dark:border-gray-700 rounded-lg p-8">
        <!-- Certificate content will be loaded here -->
      </div>
    </div>
  </div>
</div>

<script>
const certificateData = {
  certificate_number: '{{ $shareCertificate->certificate_number }}',
  user_name: '{{ $shareCertificate->user->name ?? 'N/A' }}',
  share_product: '{{ $shareCertificate->shareProduct->name ?? 'N/A' }}',
  number_of_shares: {{ $shareCertificate->number_of_shares }},
  issue_date: '{{ $shareCertificate->issue_date ? $shareCertificate->issue_date->format('M d, Y') : 'N/A' }}',
  expiry_date: '{{ $shareCertificate->expiry_date ? $shareCertificate->expiry_date->format('M d, Y') : 'N/A' }}',
  status: '{{ ucfirst($shareCertificate->status) }}'
};

const certificateBackground = '{{ \Illuminate\Support\Facades\Cache::get('share_settings', [])['certificate_background'] ?? '' }}';

function previewCertificate() {
  const modal = document.getElementById('certificateModal');
  const preview = document.getElementById('certificatePreview');
  
  let backgroundStyle = '';
  if (certificateBackground) {
    backgroundStyle = `background-image: url('{{ asset('storage/' . certificateBackground) }}'); background-size: cover; background-position: center; background-repeat: no-repeat;`;
  }
  
  preview.innerHTML = `
    <div style="${backgroundStyle} min-height: 400px; padding: 40px; position: relative;">
      <div style="background: rgba(255, 255, 255, 0.95); padding: 30px; border-radius: 10px; position: relative; z-index: 1;">
        <div style="text-align: center; margin-bottom: 30px;">
          <h1 style="font-size: 24px; font-weight: bold; color: #1e40af; margin-bottom: 10px;">Share Certificate</h1>
          <p style="color: #6b7280;">Certificate of Ownership</p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Certificate Number:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.certificate_number}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Status:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.status}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Shareholder:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.user_name}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Share Product:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.share_product}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Number of Shares:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.number_of_shares}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Issue Date:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.issue_date}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Expiry Date:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.expiry_date}</p>
          </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
          <p style="color: #6b7280; font-size: 12px;">This certificate certifies that the above-named person is the registered owner of the stated number of shares.</p>
        </div>
      </div>
    </div>
  `;
  
  modal.classList.remove('hidden');
  modal.classList.add('flex');
}

function closeModal() {
  const modal = document.getElementById('certificateModal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
}

function printCertificate() {
  const modal = document.getElementById('certificateModal');
  const preview = document.getElementById('certificatePreview');
  
  let backgroundStyle = '';
  if (certificateBackground) {
    backgroundStyle = `background-image: url('{{ asset('storage/' . certificateBackground) }}'); background-size: cover; background-position: center; background-repeat: no-repeat;`;
  }
  
  const printContent = `
    <div style="${backgroundStyle} min-height: 400px; padding: 40px; position: relative;">
      <div style="background: rgba(255, 255, 255, 0.95); padding: 30px; border-radius: 10px; position: relative; z-index: 1;">
        <div style="text-align: center; margin-bottom: 30px;">
          <h1 style="font-size: 24px; font-weight: bold; color: #1e40af; margin-bottom: 10px;">Share Certificate</h1>
          <p style="color: #6b7280;">Certificate of Ownership</p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Certificate Number:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.certificate_number}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Status:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.status}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Shareholder:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.user_name}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Share Product:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.share_product}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Number of Shares:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.number_of_shares}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Issue Date:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.issue_date}</p>
          </div>
          <div>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Expiry Date:</p>
            <p style="font-weight: 600; color: #1f2937;">${certificateData.expiry_date}</p>
          </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
          <p style="color: #6b7280; font-size: 12px;">This certificate certifies that the above-named person is the registered owner of the stated number of shares.</p>
        </div>
      </div>
    </div>
  `;
  
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <html>
      <head>
        <title>Share Certificate - ${certificateData.certificate_number}</title>
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

function deleteShareCertificate(url) {
  Swal.fire({
    title: 'Are you sure?',
    text: 'You will not be able to recover this share certificate!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = url;
      const csrf = document.createElement('input');
      csrf.type = 'hidden';
      csrf.name = '_token';
      csrf.value = '{{ csrf_token() }}';
      form.appendChild(csrf);
      const method = document.createElement('input');
      method.type = 'hidden';
      method.name = '_method';
      method.value = 'DELETE';
      form.appendChild(method);
      document.body.appendChild(form);
      form.submit();
    }
  });
}
</script>

@endsection
