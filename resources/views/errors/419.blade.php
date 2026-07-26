<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 Page Expired - Feedtan Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
<body class="min-h-screen bg-white flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-400 to-cyan-600 rounded-2xl shadow-lg mb-4">
                <i class="fa-solid fa-hourglass-end text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Feedtan Digital</h1>
            <p class="text-gray-500 text-sm">Membership Portal</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 sm:p-8">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-50 rounded-full mb-4">
                    <i class="fa-solid fa-clock text-3xl text-orange-500"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">419 Page Expired</h2>
                <p class="text-gray-500 text-sm">Session Timeout</p>
            </div>

            <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6">
                <p class="text-sm text-gray-700 leading-relaxed">
                    Your session has expired due to inactivity. For security reasons, we automatically log out users after a period of no activity.
                </p>
            </div>

            <div class="space-y-3 mb-6">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500">What This Means</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-clock text-teal-500 mt-0.5 text-xs"></i>
                        <span>Your session has timed out due to inactivity</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-shield-check text-teal-500 mt-0.5 text-xs"></i>
                        <span>This is a security feature to protect your account</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-key text-teal-500 mt-0.5 text-xs"></i>
                        <span>The security token for this page has expired</span>
                    </li>
                </ul>
            </div>

            <div class="space-y-3">
                <a href="{{ route('login') }}" 
                   class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Log In Again</span>
                </a>
                <a href="javascript:location.reload()" 
                   class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-all duration-200">
                    <i class="fa-solid fa-rotate"></i>
                    <span>Reload Page</span>
                </a>
            </div>
        </div>

        <p class="text-center text-gray-400 text-xs mt-6">
            &copy; {{ date('Y') }} Feedtan Digital. All rights reserved.
        </p>
    </div>
</body>
</html>
