<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\ErrorMessagesService;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    protected ErrorMessagesService $errorService;

    public function __construct(ErrorMessagesService $errorService)
    {
        $this->errorService = $errorService;
    }

    public function show(Request $request, ?string $type = null, ?string $key = null, ?int $code = null)
    {
        $errorData = null;

        // Try to get error from service if type and key are provided
        if ($type && $key) {
            $errorData = $this->errorService->getError($type, $key);
        }

        // If no error data from service, try HTTP error code
        if (!$errorData && $code) {
            $errorData = $this->errorService->getHttpError($code);
        }

        // Fall back to session data or defaults
        $title = $errorData['title'] ?? session('error_title') ?? 'Something went wrong';
        $message = $errorData['description'] ?? session('error_message') ?? 'An error occurred while processing your request. Please try again later.';
        $details = session('error_details') ?? null;
        $icon = $errorData['icon'] ?? 'fa-triangle-exclamation';
        $color = $errorData['color'] ?? 'red';
        $errorCode = $errorData['code'] ?? $code ?? session('error_code') ?? null;

        return view('member.error', [
            'title' => $title,
            'message' => $message,
            'details' => $details,
            'icon' => $icon,
            'color' => $color,
            'code' => $errorCode,
        ]);
    }

    public function http(int $code)
    {
        $errorData = $this->errorService->getHttpError($code);

        return view('member.error', [
            'title' => $errorData['title'] ?? 'Error',
            'message' => $errorData['description'] ?? 'An error occurred.',
            'icon' => $errorData['icon'] ?? 'fa-triangle-exclamation',
            'color' => $errorData['color'] ?? 'red',
            'code' => $code,
        ]);
    }

    public function authentication(string $key)
    {
        $errorData = $this->errorService->getAuthenticationError($key);

        if (!$errorData) {
            return $this->http(401);
        }

        return view('member.error', [
            'title' => $errorData['title'],
            'message' => $errorData['description'],
            'icon' => $errorData['icon'],
            'color' => $errorData['color'],
            'code' => $errorData['code'] ?? 401,
        ]);
    }

    public function authorization(string $key)
    {
        $errorData = $this->errorService->getAuthorizationError($key);

        if (!$errorData) {
            return $this->http(403);
        }

        return view('member.error', [
            'title' => $errorData['title'],
            'message' => $errorData['description'],
            'icon' => $errorData['icon'],
            'color' => $errorData['color'],
            'code' => $errorData['code'] ?? 403,
        ]);
    }

    public function validation(string $key)
    {
        $errorData = $this->errorService->getValidationError($key);

        if (!$errorData) {
            return $this->http(422);
        }

        return view('member.error', [
            'title' => $errorData['title'],
            'message' => $errorData['description'],
            'icon' => $errorData['icon'],
            'color' => $errorData['color'],
            'code' => $errorData['code'] ?? 422,
        ]);
    }

    public function fileUpload(string $key)
    {
        $errorData = $this->errorService->getFileUploadError($key);

        if (!$errorData) {
            return $this->http(500);
        }

        return view('member.error', [
            'title' => $errorData['title'],
            'message' => $errorData['description'],
            'icon' => $errorData['icon'],
            'color' => $errorData['color'],
            'code' => $errorData['code'] ?? 500,
        ]);
    }

    public function database(string $key)
    {
        $errorData = $this->errorService->getDatabaseError($key);

        if (!$errorData) {
            return $this->http(500);
        }

        return view('member.error', [
            'title' => $errorData['title'],
            'message' => $errorData['description'],
            'icon' => $errorData['icon'],
            'color' => $errorData['color'],
            'code' => $errorData['code'] ?? 500,
        ]);
    }

    public function payment(string $key)
    {
        $errorData = $this->errorService->getPaymentError($key);

        if (!$errorData) {
            return $this->http(402);
        }

        return view('member.error', [
            'title' => $errorData['title'],
            'message' => $errorData['description'],
            'icon' => $errorData['icon'],
            'color' => $errorData['color'],
            'code' => $errorData['code'] ?? 402,
        ]);
    }

    public function network(string $key)
    {
        $errorData = $this->errorService->getNetworkError($key);

        if (!$errorData) {
            return $this->http(503);
        }

        return view('member.error', [
            'title' => $errorData['title'],
            'message' => $errorData['description'],
            'icon' => $errorData['icon'],
            'color' => $errorData['color'],
            'code' => $errorData['code'] ?? 503,
        ]);
    }

    public function system(string $key)
    {
        $errorData = $this->errorService->getSystemError($key);

        if (!$errorData) {
            return $this->http(500);
        }

        return view('member.error', [
            'title' => $errorData['title'],
            'message' => $errorData['description'],
            'icon' => $errorData['icon'],
            'color' => $errorData['color'],
            'code' => $errorData['code'] ?? 500,
        ]);
    }
}
