@extends('layouts.member')

@section('breadcrumb', 'My Profile')
@section('page_title', 'My Profile')

@php
    function fmtTsh($val): string {
        return 'TSh ' . number_format((float)$val, 2, '.', ',');
    }

    $displayName = $fullName ?? $user->name ?? 'Member User';
    $displayInitials = $initials ?? strtoupper(substr($displayName, 0, 1));
    $displayMemberNumber = $user->member_number ?? 'FTN-00001';
    $status = $user->status ?? 'Active';
    $statusActive = strtolower($status) === 'active';

    $fullNameVal = $user->name ?? '—';
    $genderVal = $user->gender ?? '—';
    $phoneVal = $user->phone ?? '—';
    $emailVal = $user->email ?? '—';
    $addressVal = $user->address ?? '—';
    $occupationVal = $user->occupation ?? '—';
    $employerVal = $user->employer ?? '—';

    $branchVal = $user->branch ?? '—';
    $regDateVal = optional($user->created_at)->format('Y-m-d') ?? '—';
    if ($regDateVal !== '—' && is_string($regDateVal)) {
        $regDateFormatted = \Carbon\Carbon::parse($regDateVal)->format('F j, Y');
    } else {
        $regDateFormatted = '—';
    }
@endphp

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div class="glass rounded-2xl p-6 lg:p-8 border border-primary-100 dark:border-dark-border">
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="flex-shrink-0 mx-auto md:mx-0 relative">
                @if($user->photo)
                  <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover shadow-xl shadow-primary-500/20">
                @else
                  <div class="w-24 h-24 rounded-full flex items-center justify-center shadow-xl shadow-primary-500/20"
                       style="background: linear-gradient(135deg, #34d399 0%, #059669 55%, #064e3b 100%);">
                    <span class="text-4xl font-extrabold text-white tracking-wide">{{ $displayInitials }}</span>
                  </div>
                @endif
            </div>

            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-extrabold text-primary-900 dark:text-white leading-tight">
                            {{ $displayName }}
                        </h1>
                        <div class="mt-2 flex flex-wrap items-center justify-center md:justify-start gap-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-mono text-sm font-bold bg-primary-50 dark:bg-primary-900/40 text-primary-800 dark:text-primary-200 border border-primary-200 dark:border-primary-800/60">
                                <i class="fa-solid fa-id-card mr-2 text-primary-500 text-xs"></i>
                                {{ $displayMemberNumber }}
                            </span>
                            <span class="badge {{ $statusActive ? 'badge-green' : 'badge-gray' }} inline-flex items-center gap-1.5 py-1.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusActive ? 'bg-primary-500 animate-pulse' : 'bg-gray-400' }}"></span>
                                {{ $status }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-bold border border-indigo-100 dark:border-indigo-800/50">
                                <i class="fa-solid fa-user-shield"></i>
                                Member Account
                            </span>
                            <button onclick="openCertificateModal()" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold border border-blue-100 dark:border-blue-800/50 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                                <i class="fa-solid fa-certificate"></i>
                                View Certificates
                            </button>
                        </div>
                    </div>
                    <a href="{{ route('member.profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
                        <i class="fa-solid fa-pen text-[11px]"></i> Edit Profile
                    </a>
                </div>
                @if(session('hint') === 'missing_member_number')
                    <div class="mt-4 inline-flex items-start gap-2 p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 text-left max-w-lg">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-200">Member number not assigned</p>
                            <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">Your account is missing a member number. Please contact the administrator to complete your profile registration.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div class="glass rounded-2xl overflow-hidden border border-primary-100 dark:border-dark-border">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-primary-100 dark:border-dark-border bg-primary-50/40 dark:bg-primary-900/20">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-id-card text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-primary-900 dark:text-white text-sm">PERSONAL INFO</h3>
                    <p class="text-[11px] text-primary-500 dark:text-primary-400">Read-only profile details</p>
                </div>
            </div>
            <div class="divide-y divide-primary-50 dark:divide-primary-800/50">
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Full Name</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white break-words">{{ $fullNameVal }}</p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Gender</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $genderVal }}</p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Phone</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-phone text-primary-500 text-[11px]"></i>
                            {{ $phoneVal }}
                        </p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Email</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2 break-all">
                            <i class="fa-solid fa-envelope text-primary-500 text-[11px] flex-shrink-0"></i>
                            {{ $emailVal }}
                        </p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Address</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white break-words">{{ $addressVal }}</p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Occupation</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white">{{ $occupationVal }}</p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Employer</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white break-words">{{ $employerVal }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass rounded-2xl overflow-hidden border border-primary-100 dark:border-dark-border">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-primary-100 dark:border-dark-border bg-primary-50/40 dark:bg-primary-900/20">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-leaf text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-primary-900 dark:text-white text-sm">MEMBERSHIP</h3>
                    <p class="text-[11px] text-primary-500 dark:text-primary-400">Account details</p>
                </div>
            </div>
            <div class="divide-y divide-primary-50 dark:divide-primary-800/50">
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Branch</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-primary-500 text-[11px]"></i>
                            {{ $branchVal }} Branch
                        </p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Registration Date</p>
                        <p class="text-sm font-bold text-primary-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-calendar-check text-primary-500 text-[11px]"></i>
                            {{ $regDateFormatted }}
                        </p>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Status</p>
                        <span class="badge {{ $statusActive ? 'badge-green' : 'badge-gray' }} inline-flex items-center gap-1.5 py-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusActive ? 'bg-primary-500 animate-pulse' : 'bg-gray-400' }}"></span>
                            {{ $status }}
                        </span>
                    </div>
                </div>
                <div class="flex items-start justify-between px-5 py-3.5 gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-0.5">Member Number</p>
                        <span class="inline-flex items-center mt-0.5 px-3 py-1.5 rounded-lg font-mono text-sm font-bold bg-primary-50 dark:bg-primary-900/40 text-primary-800 dark:text-primary-200 border border-primary-200 dark:border-primary-800/60">
                            <i class="fa-solid fa-id-card mr-2 text-primary-500 text-xs"></i>
                            {{ $displayMemberNumber }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-5 bg-gradient-to-br from-primary-50/70 dark:from-primary-900/20 to-transparent border-t border-primary-100 dark:border-dark-border">
                <p class="text-[11px] uppercase tracking-wider font-bold text-primary-500 dark:text-primary-400 mb-3">Account Summary</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 rounded-xl bg-white/60 dark:bg-dark-card/60 border border-primary-100 dark:border-dark-border">
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-1">Savings</p>
                        <p class="text-sm font-extrabold text-primary-900 dark:text-white tabular-nums">
                            {{ isset($savingsBalance) ? fmtTsh($savingsBalance) : '—' }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-white/60 dark:bg-dark-card/60 border border-primary-100 dark:border-dark-border">
                        <p class="text-[10px] uppercase tracking-wider font-bold text-gray-500 dark:text-primary-500 mb-1">SWF Fund</p>
                        <p class="text-sm font-extrabold text-primary-900 dark:text-white tabular-nums">
                            {{ isset($swfBalance) ? fmtTsh($swfBalance) : '—' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="glass rounded-2xl p-5 border border-primary-100 dark:border-dark-border flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                <i class="fa-solid fa-circle-info text-white"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-primary-900 dark:text-white">Need help with your account?</p>
                <p class="text-xs text-primary-600 dark:text-primary-400 mt-0.5">Download your statement, view certificates, or contact our support team for assistance.</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
            <a href="{{ route('member.certificates.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-lg shadow-blue-500/25 transition-all hover:shadow-blue-500/40 hover:-translate-y-0.5"
               style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <i class="fa-solid fa-certificate"></i>
                View Certificates
            </a>
            <a href="{{ route('member.statements.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-lg shadow-primary-500/25 transition-all hover:shadow-primary-500/40 hover:-translate-y-0.5"
               style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                Download Statement
            </a>
            <a href="mailto:admin@feedtan.co.tz?subject={{ urlencode('Profile Help - Member ' . $displayMemberNumber) }}&body={{ urlencode("Hello FEEDTAN Admin,\n\nI need assistance with my member account.\n\nMember Name: {$displayName}\nMember Number: {$displayMemberNumber}\n\n") }}"
               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-primary-700 dark:text-primary-200 bg-white dark:bg-dark-card border border-primary-200 dark:border-dark-border transition-all hover:bg-primary-50 dark:hover:bg-primary-900/30 hover:-translate-y-0.5">
                <i class="fa-solid fa-envelope-open-text"></i>
                Contact Admin
            </a>
        </div>
    </div>

    <!-- Certificate Modal -->
    <div id="certificateModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-dark-card rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white dark:bg-dark-card border-b border-gray-200 dark:border-dark-border p-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Certificate Preview</h3>
                <button onclick="closeCertificateModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <div id="certificateContent" class="p-6">
                <!-- Certificate content will be loaded here -->
            </div>
            <div class="sticky bottom-0 bg-white dark:bg-dark-card border-t border-gray-200 dark:border-dark-border p-4 flex justify-end gap-3">
                <button onclick="closeCertificateModal()" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold transition-colors">
                    Close
                </button>
                <a id="printCertificateBtn" href="#" target="_blank" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white font-semibold transition-colors">
                    <i class="fa-solid fa-print mr-2"></i>Print
                </a>
            </div>
        </div>
    </div>

    <script>
    function openCertificateModal() {
        const modal = document.getElementById('certificateModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        loadMembershipCertificate();
    }

    function closeCertificateModal() {
        const modal = document.getElementById('certificateModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function loadMembershipCertificate() {
        const content = document.getElementById('certificateContent');
        content.innerHTML = '<div class="text-center py-8"><i class="fa-solid fa-spinner fa-spin text-2xl text-primary-500"></i><p class="mt-2 text-gray-600 dark:text-gray-400">Loading certificate...</p></div>';

        try {
            const response = await fetch('{{ route('member.certificates.membership-preview') }}', {
                headers: {
                    'Accept': 'application/json',
                }
            });
            const data = await response.json();
            
            const certificateHtml = `
                <div class="text-center space-y-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center mx-auto shadow-lg">
                        <i class="fa-solid fa-certificate text-3xl text-white"></i>
                    </div>
                    
                    <div>
                        <h2 class="text-2xl font-bold text-primary-900 dark:text-white mb-2">Certificate of Membership</h2>
                        <p class="text-lg font-semibold text-primary-600 dark:text-primary-400">${data.organization}</p>
                    </div>
                    
                    <div class="bg-primary-50 dark:bg-primary-900/20 rounded-xl p-6 space-y-4">
                        <p class="text-gray-600 dark:text-gray-400 text-sm">This is to certify that</p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">${data.name}</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">is a registered member of</p>
                        <p class="text-lg font-semibold text-primary-700 dark:text-primary-300">${data.organization}</p>
                    </div>
                    
                    <div class="flex flex-wrap justify-center gap-4 text-sm">
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg px-4 py-2">
                            <span class="text-gray-500 dark:text-gray-400">Membership Number:</span>
                            <span class="font-mono font-bold text-gray-900 dark:text-white ml-1">${data.member_number}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg px-4 py-2">
                            <span class="text-gray-500 dark:text-gray-400">Registration Date:</span>
                            <span class="font-bold text-gray-900 dark:text-white ml-1">${data.registration_date}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg px-4 py-2">
                            <span class="text-gray-500 dark:text-gray-400">Branch:</span>
                            <span class="font-bold text-gray-900 dark:text-white ml-1">${data.branch}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg px-4 py-2">
                            <span class="text-gray-500 dark:text-gray-400">Status:</span>
                            <span class="font-bold ${data.status === 'Active' ? 'text-green-600' : 'text-gray-600'} ml-1">${data.status}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">This certificate confirms the membership status and entitles the holder to all rights and privileges of membership.</p>
                    </div>
                    
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <p>Issued by ${data.organization} • ${data.issue_date}</p>
                    </div>
                </div>
            `;
            
            content.innerHTML = certificateHtml;
            
            // Update print button to print the modal content
            document.getElementById('printCertificateBtn').onclick = function(e) {
                e.preventDefault();
                const printContent = content.innerHTML;
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Membership Certificate</title>
                        <script src="https://cdn.tailwindcss.com"><\/script>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                        <style>
                            body { font-family: Georgia, serif; padding: 40px; }
                            @media print { body { padding: 20px; } }
                        </style>
                    </head>
                    <body class="bg-gray-50">
                        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8">
                            ${printContent}
                        </div>
                    </body>
                    </html>
                `);
                printWindow.document.close();
                setTimeout(() => printWindow.print(), 500);
            };
            
        } catch (error) {
            content.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fa-solid fa-exclamation-circle text-2xl mb-2"></i><p>Failed to load certificate</p></div>';
        }
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCertificateModal();
        }
    });

    // Close modal on backdrop click
    document.getElementById('certificateModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCertificateModal();
        }
    });
    </script>
</div>

@endsection
