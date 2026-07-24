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
        .glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-400 via-emerald-500 to-teal-600 relative overflow-hidden" x-data="{ toast: {{ session('flash') ? 'true' : 'false' }}, toastMsg: '{{ session('flash')['message'] ?? '' }}', toastLevel: '{{ session('flash')['level'] ?? 'info' }}' }" x-init="if (toast) { setTimeout(() => toast = false, 5000); }">

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-green-300/30 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-300/30 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-teal-400/20 rounded-full blur-3xl"></div>
    </div>

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

    <div class="relative min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-lg mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-leaf text-white text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Feedtan Digital</h1>
                <p class="text-green-100 text-sm">Membership Portal</p>
            </div>

            <div class="glass rounded-3xl shadow-2xl p-8">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-green-100 rounded-2xl mb-4">
                        <i class="fa-solid fa-unlock-keyhole text-green-600 text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Forgot Password</h2>
                    <p class="text-gray-500 text-sm">No worries! Enter your email and we'll send you a reset link.</p>
                </div>

                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
                        <i class="fa-solid fa-circle-check text-green-500 mt-0.5"></i>
                        <p class="text-sm text-green-800">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('email') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="you@example.com"
                            >
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                        <span>Send Password Reset Link</span>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-green-600 transition-colors">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span>Back to login</span>
                    </a>
                </div>
            </div>

            <p class="text-center text-green-100 text-sm mt-8">
                &copy; {{ date('Y') }} Feedtan Digital. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
