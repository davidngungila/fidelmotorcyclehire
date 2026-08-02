<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - FEED TAN CMG SACCO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-8">
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                <i class="fa-solid fa-shield-check text-3xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Certificate Verification</h1>
            <p class="text-gray-600">FEED TAN CMG SACCO</p>
        </div>

        <div class="bg-gray-50 rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-semibold text-gray-500">Verification Code:</span>
                <span class="font-mono font-bold text-blue-600 text-lg">{{ $code }}</span>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Valid Certificate Format</p>
                        <p class="text-sm text-gray-600">This verification code follows the official format for FEED TAN CMG SACCO membership certificates.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-info-circle text-blue-600"></i>
                About This Verification
            </h3>
            <ul class="text-sm text-gray-700 space-y-2">
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-green-500 mt-1"></i>
                    <span>This certificate was issued by FEED TAN CMG SACCO</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-green-500 mt-1"></i>
                    <span>The verification code is unique and traceable</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-green-500 mt-1"></i>
                    <span>This system ensures authenticity of membership certificates</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fa-solid fa-check text-green-500 mt-1"></i>
                    <span>For official verification, contact FEED TAN CMG SACCO administration</span>
                </li>
            </ul>
        </div>

        <div class="text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition-colors">
                <i class="fa-solid fa-home"></i>
                Return to Home
            </a>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} FEED TAN CMG SACCO. All rights reserved.<br>
                This verification system is for authenticity confirmation purposes only.
            </p>
        </div>
    </div>
</body>
</html>
