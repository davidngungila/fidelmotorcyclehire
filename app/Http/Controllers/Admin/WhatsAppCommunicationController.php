<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppSettings;
use App\Models\WhatsAppMessageHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WhatsAppCommunicationController extends Controller
{
    public function index()
    {
        $settings = WhatsAppSettings::first();
        $sessions = [];
        $messageHistory = WhatsAppMessageHistory::latest()->paginate(50);
        
        if ($settings && $settings->personal_access_token) {
            $sessions = $this->getSessions($settings->personal_access_token);
        }

        return view('admin.communication.whatsapp.index', compact('settings', 'sessions', 'messageHistory'));
    }

    public function storePersonalAccessToken(Request $request)
    {
        $request->validate([
            'personal_access_token' => 'required|string',
        ]);

        $settings = WhatsAppSettings::firstOrCreate([]);
        $settings->personal_access_token = $request->personal_access_token;
        $settings->save();

        return redirect()->route('admin.communication.whatsapp')
            ->with('success', 'Personal Access Token saved successfully.');
    }

    public function getSessions($token)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('https://www.wasenderapi.com/api/whatsapp-sessions');

            if ($response->successful()) {
                return $response->json('data', []);
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function createSession(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        $settings = WhatsAppSettings::first();
        if (!$settings || !$settings->personal_access_token) {
            return back()->with('error', 'Personal Access Token is required.');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $settings->personal_access_token,
            ])->post('https://www.wasenderapi.com/api/whatsapp-sessions', [
                'name' => $request->name,
                'phone_number' => $request->phone_number,
            ]);

            if ($response->successful()) {
                $sessionData = $response->json('data');
                
                $settings->session_name = $sessionData['name'] ?? $request->name;
                $settings->phone_number = $sessionData['phone_number'] ?? $request->phone_number;
                $settings->session_status = $sessionData['status'] ?? 'pending';
                $settings->save();

                return redirect()->route('admin.communication.whatsapp')
                    ->with('success', 'WhatsApp session created successfully.');
            }

            return back()->with('error', 'Failed to create session: ' . ($response->json('message', 'Unknown error')));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create session: ' . $e->getMessage());
        }
    }

    public function storeSessionApiKey(Request $request)
    {
        $request->validate([
            'session_api_key' => 'required|string',
        ]);

        $settings = WhatsAppSettings::first();
        if (!$settings) {
            $settings = new WhatsAppSettings();
        }

        $settings->session_api_key = $request->session_api_key;
        $settings->is_active = true;
        $settings->save();

        return redirect()->route('admin.communication.whatsapp')
            ->with('success', 'Session API Key saved successfully.');
    }

    public function sendSingleMessage(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string',
        ]);

        $settings = WhatsAppSettings::getActiveSettings();
        if (!$settings || !$settings->session_api_key) {
            return back()->with('error', 'Active WhatsApp session not configured.');
        }

        // Create message history record
        $messageHistory = WhatsAppMessageHistory::create([
            'user_id' => Auth::id(),
            'phone_number' => $request->phone_number,
            'message' => $request->message,
            'message_type' => 'single',
            'status' => 'pending',
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $settings->session_api_key,
            ])->post('https://www.wasenderapi.com/api/send-message', [
                'phone_number' => $request->phone_number,
                'message' => $request->message,
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                $messageHistory->update([
                    'status' => 'sent',
                    'response' => $responseData,
                    'sent_at' => now(),
                ]);

                return back()->with('success', 'Message sent successfully.');
            }

            $messageHistory->update([
                'status' => 'failed',
                'response' => $responseData,
                'error_message' => $responseData['message'] ?? 'Unknown error',
            ]);

            return back()->with('error', 'Failed to send message: ' . ($responseData['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            $messageHistory->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    public function sendBulkMessage(Request $request)
    {
        $request->validate([
            'phone_numbers' => 'required|string',
            'message' => 'required|string',
        ]);

        $settings = WhatsAppSettings::getActiveSettings();
        if (!$settings || !$settings->session_api_key) {
            return back()->with('error', 'Active WhatsApp session not configured.');
        }

        $phoneNumbers = array_filter(array_map('trim', explode("\n", $request->phone_numbers)));
        $message = $request->message;
        $successCount = 0;
        $failCount = 0;

        foreach ($phoneNumbers as $phoneNumber) {
            $messageHistory = WhatsAppMessageHistory::create([
                'user_id' => Auth::id(),
                'phone_number' => $phoneNumber,
                'message' => $message,
                'message_type' => 'bulk',
                'status' => 'pending',
            ]);

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $settings->session_api_key,
                ])->post('https://www.wasenderapi.com/api/send-message', [
                    'phone_number' => $phoneNumber,
                    'message' => $message,
                ]);

                $responseData = $response->json();

                if ($response->successful()) {
                    $messageHistory->update([
                        'status' => 'sent',
                        'response' => $responseData,
                        'sent_at' => now(),
                    ]);
                    $successCount++;
                } else {
                    $messageHistory->update([
                        'status' => 'failed',
                        'response' => $responseData,
                        'error_message' => $responseData['message'] ?? 'Unknown error',
                    ]);
                    $failCount++;
                }
            } catch (\Exception $e) {
                $messageHistory->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                $failCount++;
            }
        }

        return back()->with('success', "Bulk message sent: {$successCount} successful, {$failCount} failed.");
    }

    public function toggleStatus(Request $request)
    {
        $settings = WhatsAppSettings::first();
        if (!$settings) {
            return back()->with('error', 'Settings not found.');
        }

        $settings->is_active = !$settings->is_active;
        $settings->save();

        return back()->with('success', 'WhatsApp status updated successfully.');
    }
}
