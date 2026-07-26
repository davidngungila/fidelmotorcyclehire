<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden - Member Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-br from-red-500 to-rose-600 p-8 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 right-0 w-60 h-60 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
                </div>
                <div class="relative z-10">
                    <div class="w-24 h-24 mx-auto mb-4 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <i class="fa-solid fa-ban text-5xl text-white"></i>
                    </div>
                    <h1 class="text-6xl font-black text-white mb-2">403</h1>
                    <p class="text-xl font-semibold text-white/90">Forbidden</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 md:p-10">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Access Denied</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        You don't have permission to access this resource. This page is restricted to authorized personnel only.
                    </p>
                </div>

                <!-- What This Means -->
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-6 mb-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-4">
                        <i class="fa-solid fa-circle-info mr-2"></i>What This Means
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-shield-halved text-red-500 mt-0.5"></i>
                            <span>The resource you're trying to access requires specific permissions or authentication level</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-user-lock text-red-500 mt-0.5"></i>
                            <span>Your current account doesn't have the necessary role or privileges</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-key text-red-500 mt-0.5"></i>
                            <span>You may need to log in with a different account or contact an administrator</span>
                        </li>
                    </ul>
                </div>

                <!-- Possible Solutions -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 mb-8">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-4">
                        <i class="fa-solid fa-lightbulb mr-2"></i>What You Can Do
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-check-circle text-blue-500 mt-0.5"></i>
                            <span>Log in with an account that has the required permissions</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-check-circle text-blue-500 mt-0.5"></i>
                            <span>Contact your system administrator to request access</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-check-circle text-blue-500 mt-0.5"></i>
                            <span>Return to the <a href="{{ url('/') }}" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">dashboard</a> and navigate to an authorized section</span>
                        </li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ url('/') }}" 
                       class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-500 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl active:scale-95">
                        <i class="fa-solid fa-home"></i>
                        Go to Dashboard
                    </a>
                    <a href="javascript:history.back()" 
                       class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl transition-all active:scale-95">
                        <i class="fa-solid fa-arrow-left"></i>
                        Go Back
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 dark:bg-gray-900/50 px-8 py-4 text-center border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Error Code: 403 • Timestamp: {{ now()->format('Y-m-d H:i:s') }} • 
                    <a href="{{ url('/') }}" class="text-primary-600 dark:text-primary-400 hover:underline">Member Portal</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
