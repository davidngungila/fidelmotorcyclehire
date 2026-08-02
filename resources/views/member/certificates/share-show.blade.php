@extends('layouts.member')

@section('breadcrumb', 'My Certificates \u203A Share Certificate')
@section('page_title', 'Share Certificate')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Share Certificate</h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $certificate->certificate_number }}</p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('member.certificates.share-print', $certificate->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-semibold transition-all">
        <i class="fa-solid fa-print"></i> Print Certificate
      </a>
      <a href="{{ route('member.certificates.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-semibold transition-all">
        <i class="fa-solid fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  @php
    $settings = \Illuminate\Support\Facades\Cache::get('share_settings', []);
    $certificateBackgroundPath = $settings['certificate_background'] ?? '';
    $certificateBackgroundUrl = $certificateBackgroundPath ? asset('storage/' . $certificateBackgroundPath) : '';
    $memberName = $certificate->sharePurchase->member->name ?? 'N/A';
    $shareProduct = $certificate->sharePurchase->shareProduct->name ?? 'N/A';
    $totalValue = $certificate->number_of_shares * $certificate->share_value_per_share;
  @endphp

  <div class="glass rounded-xl p-8 max-w-4xl mx-auto border-4 border-blue-200 dark:border-blue-800">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
      .great-vibes-regular {
        font-family: "Great Vibes", cursive;
        font-weight: 400;
        font-style: normal;
      }
    </style>
    
    <div style="background-image: url('{{ $certificateBackgroundUrl }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 500px; padding: 40px; position: relative;">
      <div style="padding: 40px; position: relative; z-index: 1;">
        <div style="text-align: center; margin-bottom: 20px;">
          <h1 style="font-size: 32px; font-weight: bold; color: #1e40af; margin-bottom: 5px; font-family: 'Times New Roman', serif; text-shadow: 2px 2px 4px rgba(255,255,255,0.8);">CERTIFICATE OF OWNERSHIP</h1>
        </div>
        
        <div style="text-align: center; margin-bottom: 30px;">
          <p style="color: #1f2937; font-size: 16px; margin-bottom: 10px; text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">THIS CERTIFICATE IS PROUDLY PRESENTED TO</p>
          <h2 class="great-vibes-regular" style="font-size: 36px; color: #1e40af; margin: 10px 0; text-shadow: 2px 2px 4px rgba(255,255,255,0.8);">{{ $memberName }}</h2>
          <div style="width: 350px; height: 2px; background: linear-gradient(to right, transparent, #1e40af, transparent); margin: 8px auto;"></div>
          <p style="color: #1f2937; font-size: 16px; text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">This certifies that {{ $memberName }} is the registered owner of <strong>{{ $certificate->number_of_shares }} {{ $shareProduct }}</strong> in FEEDTAN COMMUNITY MICROFINANCE GROUP with a total value of <strong>TZS {{ number_format($totalValue, 2) }}</strong>. The shares are issued in accordance with the organization's Constitution, Share Policy, and applicable regulations, granting the shareholder all rights and responsibilities of ownership.</p>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding: 20px; background: rgba(255, 255, 255, 0.4); border-radius: 8px; backdrop-filter: blur(2px);">
          <p style="color: #1f2937; font-size: 14px; line-height: 1.8; text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">
            Certificate No: <strong>{{ $certificate->certificate_number }}</strong> | 
            Status: <strong>{{ $certificate->is_active ? 'Active' : 'Inactive' }}</strong> | 
            Share Product: <strong>{{ $shareProduct }}</strong> | 
            Issue Date: <strong>{{ $certificate->issue_date->format('d F Y') }}</strong> | 
            Ownership Status: <strong>Fully Paid</strong> | 
            Expiry: <strong>N/A (Permanent)</strong>
          </p>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid rgba(255,255,255,0.5);">
          <p style="color: #1f2937; font-size: 14px; text-shadow: 1px 1px 2px rgba(255,255,255,0.8);">This certificate serves as official proof of share ownership and remains valid according to the organization's governing policies.</p>
        </div>
      </div>
    </div>
  </div>
</div>
