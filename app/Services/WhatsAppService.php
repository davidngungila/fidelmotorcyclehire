<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $baseUrl;
    protected string $testUrl;
    protected ?string $apiKey;
    protected ?string $account;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.base_url', 'https://messaging-service.co.tz/api/whatsapp/v2/text/single');
        $this->testUrl = config('services.whatsapp.test_url', 'https://messaging-service.co.tz/api/whatsapp/v2/test/text/single');
        $this->apiKey = config('services.whatsapp.api_key');
        $this->account = config('services.whatsapp.account');
    }

    /**
     * Send a plain template message
     */
    public function sendTextMessage(array $recipients, string $template, bool $test = false): array
    {
        $url = $test ? $this->testUrl : $this->baseUrl;

        $payload = [
            'to' => $recipients,
            'account' => $this->account,
            'template' => $template,
        ];

        return $this->sendRequest($url, $payload);
    }

    /**
     * Send a personalized template message
     */
    public function sendPersonalizedMessage(array $recipients, string $template, array $personalisation, bool $test = false): array
    {
        $url = $test ? $this->testUrl : $this->baseUrl;

        $payload = [
            'to' => $recipients,
            'account' => $this->account,
            'template' => $template,
            'personalisation' => $personalisation,
        ];

        return $this->sendRequest($url, $payload);
    }

    /**
     * Send a template message with media (image/document)
     */
    public function sendMediaMessage(array $recipients, string $template, array $media, ?string $reference = null, bool $test = false): array
    {
        $url = $test ? $this->testUrl : $this->baseUrl;

        $payload = [
            'to' => $recipients,
            'account' => $this->account,
            'template' => $template,
            'header' => $media,
        ];

        if ($reference !== null) {
            $payload['reference'] = $reference;
        }

        return $this->sendRequest($url, $payload);
    }

    /**
     * Send a personalized template message with media
     */
    public function sendPersonalizedMediaMessage(array $recipients, string $template, array $personalisation, array $media, ?string $reference = null, bool $test = false): array
    {
        $url = $test ? $this->testUrl : $this->baseUrl;

        $payload = [
            'to' => $recipients,
            'account' => $this->account,
            'template' => $template,
            'personalisation' => $personalisation,
            'header' => $media,
        ];

        if ($reference !== null) {
            $payload['reference'] = $reference;
        }

        return $this->sendRequest($url, $payload);
    }

    /**
     * Send a template message with button personalization (OTP, coupon code, URL)
     */
    public function sendButtonMessage(array $recipients, string $template, array $buttonPersonalisation, bool $test = false): array
    {
        $url = $test ? $this->testUrl : $this->baseUrl;

        $payload = [
            'to' => $recipients,
            'account' => $this->account,
            'template' => $template,
            'button' => [
                'personalisation' => $buttonPersonalisation,
            ],
        ];

        return $this->sendRequest($url, $payload);
    }

    /**
     * Send a personalized template message with button
     */
    public function sendPersonalizedButtonMessage(array $recipients, string $template, array $personalisation, array $buttonPersonalisation, bool $test = false): array
    {
        $url = $test ? $this->testUrl : $this->baseUrl;

        $payload = [
            'to' => $recipients,
            'account' => $this->account,
            'template' => $template,
            'personalisation' => $personalisation,
            'button' => [
                'personalisation' => $buttonPersonalisation,
            ],
        ];

        return $this->sendRequest($url, $payload);
    }

    /**
     * Send a location-based template message
     */
    public function sendLocationMessage(array $recipients, string $template, array $location, bool $test = false): array
    {
        $url = $test ? $this->testUrl : $this->baseUrl;

        $payload = [
            'to' => $recipients,
            'account' => $this->account,
            'template' => $template,
            'header' => [
                'location' => $location,
            ],
        ];

        return $this->sendRequest($url, $payload);
    }

    /**
     * Schedule a message for future delivery
     */
    public function scheduleMessage(array $recipients, string $template, string $date, string $time, ?array $attributes = null, ?string $repeat = null, ?string $startDate = null, ?string $endDate = null, ?string $document = null, ?string $reference = null): array
    {
        $payload = [
            'to' => $recipients,
            'account' => $this->account,
            'template' => $template,
            'date' => $date,
            'time' => $time,
        ];

        if ($attributes !== null) {
            $payload['attributes'] = $attributes;
        }

        if ($repeat !== null) {
            $payload['repeat'] = $repeat;
        }

        if ($startDate !== null) {
            $payload['start_date'] = $startDate;
        }

        if ($endDate !== null) {
            $payload['end_date'] = $endDate;
        }

        if ($document !== null) {
            $payload['document'] = $document;
        }

        if ($reference !== null) {
            $payload['reference'] = $reference;
        }

        return $this->sendRequest($this->baseUrl, $payload);
    }

    /**
     * Send HTTP request to WhatsApp API
     */
    protected function sendRequest(string $url, array $payload): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('WhatsApp API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'error' => 'API request failed',
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp API exception', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'error' => 'Exception occurred',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Validate phone number format (must include country code)
     */
    protected function validatePhoneNumber(string $phone): bool
    {
        // Phone number should be numeric and at least 10 digits
        return preg_match('/^[0-9]{10,}$/', $phone);
    }

    /**
     * Format phone numbers to ensure country code
     */
    protected function formatPhoneNumbers(array $numbers): array
    {
        return array_map(function ($number) {
            $number = preg_replace('/[^0-9]/', '', $number);
            
            // If number doesn't start with country code (255 for Tanzania), add it
            if (!str_starts_with($number, '255')) {
                $number = '255' . ltrim($number, '0');
            }
            
            return $number;
        }, $numbers);
    }
}
