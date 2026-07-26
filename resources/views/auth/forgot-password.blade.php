<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Feedtan Digital</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-white" x-data="{ 
    toast: {{ session('flash') ? 'true' : 'false' }}, 
    toastMsg: '{{ session('flash')['message'] ?? '' }}', 
    toastLevel: '{{ session('flash')['level'] ?? 'info' }}',
    loading: false,
    loadingSteps: [
        'Validating email address...',
        'Checking user account...',
        'Generating secure token...',
        'Configuring email service...',
        'Sending password reset link...',
        'Email sent successfully!'
    ],
    currentStep: 0
}" x-init="if (toast) { setTimeout(() => toast = false, 5000); }">

    <div class="fixed top-6 right-6 z-50" x-show="toast" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4" x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4">
        <div :class="{
            'bg-green-50 border border-green-200': toastLevel === 'success',
            'bg-red-50 border border-red-200': toastLevel === 'error',
            'bg-blue-50 border border-blue-200': toastLevel === 'info',
            'bg-yellow-50 border border-yellow-200': toastLevel === 'warning',
        }" class="shadow-xl rounded-xl p-4 flex items-start gap-3 min-w-[320px]">
            <div :class="{
                'text-green-500': toastLevel === 'success',
                'text-red-500': toastLevel === 'error',
                'text-blue-500': toastLevel === 'info',
                'text-yellow-500': toastLevel === 'warning',
            }" class="mt-0.5">
                <i :class="{
                    'fa-solid fa-circle-check': toastLevel === 'success',
                    'fa-solid fa-circle-xmark': toastLevel === 'error',
                    'fa-solid fa-circle-info': toastLevel === 'info',
                    'fa-solid fa-triangle-exclamation': toastLevel === 'warning',
                }" class="text-xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900" x-text="toastMsg"></p>
            </div>
            <button @click="toast = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div x-show="loading" x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-center">
            <div class="w-20 h-20 border-4 border-green-200 border-t-green-600 rounded-full animate-spin mx-auto mb-6"></div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Sending Reset Link...</h3>
            <div class="min-h-[40px] flex items-center justify-center">
                <p class="text-lg font-medium text-green-600" x-text="loadingSteps[currentStep]"></p>
            </div>
            <div class="flex justify-center gap-2 mt-6">
                <template x-for="(step, index) in loadingSteps" :key="index">
                    <div class="w-2 h-2 rounded-full transition-all duration-300"
                         :class="index < currentStep ? 'bg-green-600' : (index === currentStep ? 'bg-green-400 w-3' : 'bg-gray-300')"></div>
                </template>
            </div>
        </div>
    </div>

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8" x-show="!loading">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl shadow-lg mb-4">
                    <i class="fa-solid fa-leaf text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Feedtan Digital</h1>
                <p class="text-gray-500 text-sm">Membership Portal</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 sm:p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-50 rounded-full mb-4">
                        <i class="fa-solid fa-unlock-keyhole text-3xl text-green-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">Forgot Password</h2>
                    <p class="text-gray-500 text-sm">Enter your email to receive a reset link</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                        <p class="text-sm text-green-800">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fa-solid fa-envelope text-sm"></i>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('email') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="you@example.com"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        @click="loading = true; currentStep = 0; const interval = setInterval(() => { if (currentStep < loadingSteps.length - 1) { currentStep++; } else { clearInterval(interval); } }, 300);"
                        class="w-full py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                        <span>Send Password Reset Link</span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-green-600 transition-colors">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span>Back to login</span>
                    </a>
                </div>
            </div>

            <p class="text-center text-gray-400 text-xs mt-6">
                &copy; {{ date('Y') }} Feedtan Digital. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
