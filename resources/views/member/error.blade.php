@extends('layouts.member')

@section('breadcrumb', 'Error')
@section('page_title', 'Error')

@section('content')

<div class="flex items-center justify-center min-h-[60vh]">
  <div class="text-center max-w-lg">
    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-{{ $color ?? 'red' }}-100 dark:bg-{{ $color ?? 'red' }}-900/20 flex items-center justify-center">
      <i class="fa-solid {{ $icon ?? 'fa-triangle-exclamation' }} text-4xl text-{{ $color ?? 'red' }}-500"></i>
    </div>
    
    @if(isset($code))
      <div class="inline-block px-4 py-2 rounded-full bg-{{ $color ?? 'red' }}-100 dark:bg-{{ $color ?? 'red' }}-900/20 mb-4">
        <span class="text-lg font-bold text-{{ $color ?? 'red' }}-600 dark:text-{{ $color ?? 'red' }}-400">{{ $code }}</span>
      </div>
    @endif
    
    <h1 class="text-3xl lg:text-4xl font-bold text-primary-900 dark:text-white mb-4">
      {{ $title ?? 'Something went wrong' }}
    </h1>
    
    <p class="text-lg text-primary-600 dark:text-primary-400 mb-8">
      {{ $message ?? 'An error occurred while processing your request. Please try again later.' }}
    </p>
    
    @if(isset($details))
      <div class="p-4 rounded-xl bg-primary-50 dark:bg-primary-900/20 border border-primary-100 dark:border-primary-800 mb-8 text-left">
        <p class="text-sm text-primary-700 dark:text-primary-300">
          <strong>Details:</strong> {{ $details }}
        </p>
      </div>
    @endif
    
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
      <a href="{{ route('member.dashboard') }}" 
         class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-semibold transition-all active:scale-95">
        <i class="fa-solid fa-home"></i>
        Go to Dashboard
      </a>
      
      <button onclick="history.back()" 
              class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-100 hover:bg-primary-200 dark:bg-primary-900/40 dark:hover:bg-primary-900/60 text-primary-700 dark:text-primary-300 font-semibold transition-all active:scale-95">
        <i class="fa-solid fa-arrow-left"></i>
        Go Back
      </button>
    </div>
    
    @if(config('app.debug') && isset($exception))
      <div class="mt-8 p-4 rounded-xl bg-gray-900 border border-gray-800 text-left overflow-x-auto">
        <p class="text-xs text-red-400 font-mono mb-2">{{ $exception->getMessage() }}</p>
        <p class="text-[10px] text-gray-500 font-mono">{{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
      </div>
    @endif
  </div>
</div>

@endsection
