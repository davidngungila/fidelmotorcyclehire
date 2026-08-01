@extends('layouts.admin')

@section('breadcrumb', 'System › Share Certificates')
@section('page_title', 'Share Certificates Management')

@section('content')

<div class="space-y-6">

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
        <i class="fa-solid fa-certificate mr-1.5"></i> {{ $shareCertificates->total() }} Share Certificates
      </span>
    </div>

    <a href="{{ route('admin.share-certificates.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-500 text-white text-sm font-bold transition-all shadow-sm hover:shadow-md active:scale-95 whitespace-nowrap">
      <i class="fa-solid fa-plus text-[13px]"></i> Create Share Certificate
    </a>
  </div>

  <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-primary-100 dark:border-primary-800 p-5">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-primary-50 dark:bg-primary-900/20">
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Certificate #</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">User</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Share Product</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Shares</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Issue Date</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Expiry Date</th>
            <th class="text-left py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Status</th>
            <th class="text-center py-3 px-4 text-xs font-semibold text-primary-600 dark:text-primary-400">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($shareCertificates as $certificate)
          <tr class="border-b border-primary-100 dark:border-primary-800 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-colors">
            <td class="py-3 px-4 text-sm font-medium text-primary-900 dark:text-white">{{ $certificate->certificate_number }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $certificate->user->name ?? 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $certificate->shareProduct->name ?? 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $certificate->number_of_shares }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $certificate->issue_date ? $certificate->issue_date->format('M d, Y') : 'N/A' }}</td>
            <td class="py-3 px-4 text-sm text-primary-700 dark:text-primary-300">{{ $certificate->expiry_date ? $certificate->expiry_date->format('M d, Y') : 'N/A' }}</td>
            <td class="py-3 px-4">
              @if($certificate->status === 'active')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Active</span>
              @elseif($certificate->status === 'inactive')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400">Inactive</span>
              @elseif($certificate->status === 'transferred')
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Transferred</span>
              @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Cancelled</span>
              @endif
            </td>
            <td class="py-3 px-4 text-center">
              <div class="flex items-center justify-center gap-2">
                <a href="{{ route('admin.share-certificates.show', encrypt($certificate->id)) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-100 hover:bg-teal-200 dark:bg-teal-900/40 dark:hover:bg-teal-900/60 text-teal-700 dark:text-teal-300 text-xs font-medium transition-colors">
                  <i class="fa-solid fa-eye text-[10px]"></i> View
                </a>
                <a href="{{ route('admin.share-certificates.edit', encrypt($certificate->id)) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 text-xs font-medium transition-colors">
                  <i class="fa-solid fa-edit text-[10px]"></i> Edit
                </a>
                <form action="{{ route('admin.share-certificates.destroy', encrypt($certificate->id)) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this share certificate?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-100 hover:bg-red-200 dark:bg-red-900/40 dark:hover:bg-red-900/60 text-red-700 dark:text-red-300 text-xs font-medium transition-colors">
                    <i class="fa-solid fa-trash text-[10px]"></i> Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="py-8 text-center text-primary-600 dark:text-primary-400 text-sm">No share certificates found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($shareCertificates->hasPages())
    <div class="flex items-center justify-between mt-5 pt-5 border-t border-primary-100 dark:border-primary-800">
      <span class="text-xs text-primary-600 dark:text-primary-400">Showing {{ $shareCertificates->firstItem() }} to {{ $shareCertificates->lastItem() }} of {{ $shareCertificates->total() }} results</span>
      {{ $shareCertificates->links() }}
    </div>
    @endif
  </div>

</div>

@endsection
