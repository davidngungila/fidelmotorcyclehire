<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Feedtan Digital</title>

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
<body class="min-h-screen bg-gradient-to-br from-green-400 via-emerald-500 to-teal-600 relative overflow-hidden" x-data="{ toast: {{ session('flash') ? 'true' : 'false' }}, toastMsg: '{{ session('flash')['message'] ?? '' }}', toastLevel: '{{ session('flash')['level'] ?? 'info' }}', showPw: false, showConfirmPw: false }" x-init="if (toast) { setTimeout(() => toast = false, 5000); }">

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
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-emerald-100 rounded-2xl mb-4">
                        <i class="fa-solid fa-key text-emerald-600 text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Reset Password</h2>
                    <p class="text-gray-500 text-sm">Please enter your new password below.</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

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
                                value="{{ old('email', request('email')) }}"
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

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input
                                :type="showPw ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('password') border-red-300 focus:ring-red-500 @enderror"
                                placeholder="Enter new password"
                            >
                            <button type="button" @click="showPw = !showPw" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                <i :class="showPw ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input
                                :type="showConfirmPw ? 'text' : 'password'"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                placeholder="Confirm new password"
                            >
                            <button type="button" @click="showConfirmPw = !showConfirmPw" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                <i :class="showConfirmPw ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="fa-solid fa-rotate text-sm"></i>
                        <span>Reset Password</span>
                    </button>
                </form>
            </div>

            <p class="text-center text-green-100 text-sm mt-8">
                &copy; {{ date('Y') }} Feedtan Digital. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
