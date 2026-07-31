<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunicationController extends Controller
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function sms(): View
    {
        return view('admin.communication.sms');
    }

    public function email(): View
    {
        return view('admin.communication.email');
    }

    public function whatsapp(): View
    {
        return view('admin.communication.whatsapp');
    }

    public function sendWhatsApp(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'required|string',
            'template' => 'required|string',
            'message_type' => 'required|in:plain,personalized,media,button,scheduled',
        ]);

        $recipients = $request->input('recipients');
        $template = $request->input('template');
        $messageType = $request->input('message_type');
        $test = $request->input('test', false);

        try {
            $result = match ($messageType) {
                'plain' => $this->whatsappService->sendTextMessage($recipients, $template, $test),
                'personalized' => $this->whatsappService->sendPersonalizedMessage(
                    $recipients,
                    $template,
                    $request->input('personalisation', []),
                    $test
                ),
                'media' => $this->whatsappService->sendMediaMessage(
                    $recipients,
                    $template,
                    $request->input('media', []),
                    $request->input('reference'),
                    $test
                ),
                'button' => $this->whatsappService->sendButtonMessage(
                    $recipients,
                    $template,
                    $request->input('button_personalisation', []),
                    $test
                ),
                'scheduled' => $this->whatsappService->scheduleMessage(
                    $recipients,
                    $template,
                    $request->input('date'),
                    $request->input('time'),
                    $request->input('attributes'),
                    $request->input('repeat'),
                    $request->input('start_date'),
                    $request->input('end_date'),
                    $request->input('document'),
                    $request->input('reference')
                ),
                default => ['success' => false, 'error' => 'Invalid message type'],
            };

            return back()->with('success', 'Message sent successfully!')->with('result', $result);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send message: ' . $e->getMessage());
        }
    }
}
