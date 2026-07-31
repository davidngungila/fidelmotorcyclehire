<?php

namespace App\Services;

use App\Models\SmsSettings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $baseUrl = 'https://messaging-service.co.tz';
    protected ?SmsSettings $settings;

    public function __construct()
    {
        $this->settings = SmsSettings::first();
    }

    /**
     * Send SMS to a single recipient
     */
    public function sendSingle(string $to, string $message, ?string $from = null): array
    {
        if (! $this->settings || ! $this->settings->is_active) {
            Log::warning('SMS service is not configured or inactive');
            return [
                'success' => false,
                'message' => 'SMS service is not configured or inactive',
            ];
        }

        $senderId = $from ?? $this->settings->sender_id ?? 'FEEDTAN';
        
        // Format phone number - ensure it starts with country code
        $to = $this->formatPhoneNumber($to);

        try {
            $url = $this->baseUrl . '/link/sms/v2/text/single';
            
            $response = Http::get($url, [
                'token' => $this->settings->api_key,
                'from' => $senderId,
                'to' => $to,
                'text' => $message,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('SMS sent successfully', [
                    'to' => $to,
                    'message_id' => $data['messages'][0]['messageId'] ?? null,
                    'status' => $data['messages'][0]['status'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'data' => $data,
                ];
            }

            Log::error('SMS sending failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'SMS sending failed: ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('SMS service error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'SMS service error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS to multiple recipients
     */
    public function sendMultiple(array $recipients, string $message, ?string $from = null): array
    {
        if (! $this->settings || ! $this->settings->is_active) {
            Log::warning('SMS service is not configured or inactive');
            return [
                'success' => false,
                'message' => 'SMS service is not configured or inactive',
            ];
        }

        $senderId = $from ?? $this->settings->sender_id ?? 'FEEDTAN';
        
        // Format phone numbers
        $formattedRecipients = array_map([$this, 'formatPhoneNumber'], $recipients);

        try {
            $url = $this->baseUrl . '/link/sms/v2/text/multi';
            
            $response = Http::get($url, [
                'token' => $this->settings->api_key,
                'from' => $senderId,
                'to' => implode(',', $formattedRecipients),
                'text' => $message,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Bulk SMS sent successfully', [
                    'count' => count($formattedRecipients),
                    'data' => $data,
                ]);

                return [
                    'success' => true,
                    'message' => 'Bulk SMS sent successfully',
                    'data' => $data,
                ];
            }

            Log::error('Bulk SMS sending failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Bulk SMS sending failed: ' . $response->body(),
            ];

        } catch (\Exception $e) {
            Log::error('Bulk SMS service error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Bulk SMS service error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test SMS connection
     */
    public function testConnection(): array
    {
        if (! $this->settings || ! $this->settings->api_key) {
            return [
                'success' => false,
                'message' => 'SMS settings not configured',
            ];
        }

        try {
            $url = $this->baseUrl . '/api/sms/v2/test/text/single';
            
            $response = Http::asJson()->withHeaders([
                'Authorization' => 'Bearer ' . $this->settings->api_key,
                'Accept' => 'application/json',
            ])->post($url, [
                'from' => $this->settings->sender_id ?? 'FEEDTAN',
                'to' => '255123456789',
                'text' => 'Test message from Members Portal',
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'SMS connection test successful',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'SMS connection test failed: ' . $response->body(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'SMS connection test error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Format phone number to ensure it starts with country code
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If number starts with 0, replace with 255 (Tanzania country code)
        if (str_starts_with($phone, '0')) {
            $phone = '255' . substr($phone, 1);
        }
        
        // If number doesn't start with country code, add Tanzania code
        if (! str_starts_with($phone, '255')) {
            $phone = '255' . $phone;
        }
        
        return $phone;
    }

    /**
     * Check if SMS service is configured and active
     */
    public function isActive(): bool
    {
        return $this->settings && $this->settings->is_active && ! empty($this->settings->api_key);
    }
}
