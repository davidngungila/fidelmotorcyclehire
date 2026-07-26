<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MailConfigService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    use FlashMessages;

    protected MailConfigService $mailConfigService;

    public function __construct(MailConfigService $mailConfigService)
    {
        $this->mailConfigService = $mailConfigService;
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        // Configure mail settings from database before sending
        $this->mailConfigService->configureFromDatabase();

        $response = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT) {
            $this->success(trans($response));
            return back()->with('status', trans($response));
        }

        throw ValidationException::withMessages([
            'email' => [trans($response)],
        ]);
    }
}
