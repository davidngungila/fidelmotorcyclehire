@extends('layouts.member')

@section('breadcrumb', 'Statements')
@section('page_title', 'Account Statements')

@section('content')

<div class="space-y-6">

    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center">
                <i class="fa-solid fa-file-invoice text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-primary-900 dark:text-white">Download Statements</h2>
                <p class="text-sm text-primary-600 dark:text-primary-400">Generate and download your account statements in PDF format</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="GET" action="{{ route('member.statements.download', 'loans') }}" class="glass p-5 rounded-xl border border-primary-100 dark:border-dark-border hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 dark:bg-orange-900/30 text-orange-500 flex items-center justify-center">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-primary-900 dark:text-white text-sm">Loan Statement</h3>
                        <p class="text-[11px] text-primary-500 dark:text-primary-400">Loan repayment history</p>
                    </div>
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold text-white bg-gradient-to-br from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 shadow-lg shadow-orange-500/20 hover:shadow-orange-500/30 transition-all">
                    <i class="fa-solid fa-download text-xs"></i>
                    Download PDF
                </button>
            </form>

            <form method="GET" action="{{ route('member.statements.download', 'deposits') }}" class="glass p-5 rounded-xl border border-primary-100 dark:border-dark-border hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-500 flex items-center justify-center">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-primary-900 dark:text-white text-sm">Deposit Statement</h3>
                        <p class="text-[11px] text-primary-500 dark:text-primary-400">Fixed deposit account summary</p>
                    </div>
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold text-white bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 transition-all">
                    <i class="fa-solid fa-download text-xs"></i>
                    Download PDF
                </button>
            </form>
        </div>
    </div>

    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div>
                <h3 class="font-bold text-primary-900 dark:text-white text-sm">Statement Information</h3>
                <p class="text-[11px] text-primary-500 dark:text-primary-400">What you need to know</p>
            </div>
        </div>
        <ul class="space-y-3 text-sm text-primary-700 dark:text-primary-300">
            <li class="flex items-start gap-3">
                <i class="fa-solid fa-check text-green-500 mt-0.5 text-xs"></i>
                <span>Statements are generated in PDF format for easy printing and sharing</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fa-solid fa-check text-green-500 mt-0.5 text-xs"></i>
                <span>All statements include complete transaction history for the selected period</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fa-solid fa-check text-green-500 mt-0.5 text-xs"></i>
                <span>For custom date ranges, please contact your branch directly</span>
            </li>
            <li class="flex items-start gap-3">
                <i class="fa-solid fa-check text-green-500 mt-0.5 text-xs"></i>
                <span>Statements are available for all active and closed accounts</span>
            </li>
        </ul>
    </div>

</div>

@endsection
