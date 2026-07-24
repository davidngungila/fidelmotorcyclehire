<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\FlashMessages;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use FlashMessages;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show()
    {
        return view('auth.verify');
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->user()->isAdmin()) {
                return redirect('/admin/dashboard');
            }
            return redirect('/member/dashboard');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $this->success('Your email address has been verified successfully.');

        if ($request->user()->isAdmin()) {
            return redirect('/admin/dashboard');
        }
        return redirect('/member/dashboard');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            if ($request->user()->isAdmin()) {
                return redirect('/admin/dashboard');
            }
            return redirect('/member/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        $this->success('A fresh verification link has been sent to your email address.');

        return back()->with('status', 'verification-link-sent');
    }
}
