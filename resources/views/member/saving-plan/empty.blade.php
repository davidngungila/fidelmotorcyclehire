@extends('layouts.member')

@section('breadcrumb', 'My Saving Plan')
@section('page_title', 'My Saving Plan')

@section('content')

<div class="glass p-12 rounded-2xl text-center">
    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
        <i class="fa-solid fa-piggy-bank text-4xl text-primary-400"></i>
    </div>
    <h2 class="text-2xl font-bold text-primary-900 dark:text-white mb-3">No Saving Plan Found</h2>
    <p class="text-primary-600 dark:text-primary-400 mb-8 max-w-md mx-auto">
        You don't have a saving plan set up yet. Contact the administrator to create a personalized saving plan for you.
    </p>
    <div class="flex justify-center gap-4">
        <a href="{{ route('member.savings.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-bold transition-all">
            <i class="fa-solid fa-eye"></i>
            View My Savings
        </a>
    </div>
</div>

@endsection
