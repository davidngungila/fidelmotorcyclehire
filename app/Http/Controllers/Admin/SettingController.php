<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\EmailSettings;
use App\Models\GoogleSheetsConfig;
use App\Models\SmsSettings;
use App\Models\WhatsAppSettings;
use App\Services\SmsService;
use App\Traits\FlashMessages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    use FlashMessages;

    public function index(Request $request)
    {
        $cachedSettings = Cache::get('admin_settings', []);

        $generalSettings = [
            'app_name' => $cachedSettings['app_name'] ?? config('app.name', 'FEEDTAN DIGITAL'),
            'support_email' => $cachedSettings['support_email'] ?? 'support@feedtan.co.tz',
            'timezone' => $cachedSettings['timezone'] ?? config('app.timezone', 'Africa/Dar_es_Salaam'),
            'currency' => $cachedSettings['currency'] ?? 'TSh',
            'default_branch' => $cachedSettings['default_branch'] ?? 'Dar es Salaam',
            'date_format' => $cachedSettings['date_format'] ?? 'd/m/Y',
        ];

        $notificationSettings = [
            'email_notifications' => $cachedSettings['email_notifications'] ?? true,
            'sms_notifications' => $cachedSettings['sms_notifications'] ?? false,
            'push_notifications' => $cachedSettings['push_notifications'] ?? true,
            'loan_alerts' => $cachedSettings['loan_alerts'] ?? true,
            'savings_alerts' => $cachedSettings['savings_alerts'] ?? true,
            'investment_alerts' => $cachedSettings['investment_alerts'] ?? true,
            'weekly_report' => $cachedSettings['weekly_report'] ?? true,
            'monthly_report' => $cachedSettings['monthly_report'] ?? true,
        ];

        $googleSheetsSettings = [
            'spreadsheet_id' => $cachedSettings['spreadsheet_id'] ?? '',
            'auto_sync' => $cachedSettings['auto_sync'] ?? true,
            'sync_interval' => $cachedSettings['sync_interval'] ?? '30',
        ];

        $securitySettings = [
            'password_expiry_days' => $cachedSettings['password_expiry_days'] ?? '90',
            'two_factor_enabled' => $cachedSettings['two_factor_enabled'] ?? false,
            'session_timeout_minutes' => $cachedSettings['session_timeout_minutes'] ?? '60',
            'login_attempts' => $cachedSettings['login_attempts'] ?? '5',
            'lockout_minutes' => $cachedSettings['lockout_minutes'] ?? '30',
            'require_strong_password' => $cachedSettings['require_strong_password'] ?? true,
        ];

        $emailSettings = EmailSettings::first() ?? new EmailSettings();
        $smsSettings = SmsSettings::first() ?? new SmsSettings();
        $whatsappSettings = WhatsAppSettings::first() ?? new WhatsAppSettings();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed settings page',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.settings.index', [
            'generalSettings' => $generalSettings,
            'notificationSettings' => $notificationSettings,
            'googleSheetsSettings' => $googleSheetsSettings,
            'securitySettings' => $securitySettings,
            'emailSettings' => $emailSettings,
            'smsSettings' => $smsSettings,
            'whatsappSettings' => $whatsappSettings,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'tab' => ['required', 'in:general,notifications,google_sheets,security,email,sms,whatsapp'],
            'app_name' => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email'],
            'timezone' => ['nullable', 'string'],
            'currency' => ['nullable', 'string'],
            'default_branch' => ['nullable', 'string'],
            'date_format' => ['nullable', 'string'],
            'email_notifications' => ['nullable', 'boolean'],
            'sms_notifications' => ['nullable', 'boolean'],
            'push_notifications' => ['nullable', 'boolean'],
            'loan_alerts' => ['nullable', 'boolean'],
            'savings_alerts' => ['nullable', 'boolean'],
            'investment_alerts' => ['nullable', 'boolean'],
            'weekly_report' => ['nullable', 'boolean'],
            'monthly_report' => ['nullable', 'boolean'],
            'spreadsheet_id' => ['nullable', 'string'],
            'auto_sync' => ['nullable', 'boolean'],
            'sync_interval' => ['nullable', 'integer', 'min:5', 'max:1440'],
            'password_expiry_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'two_factor_enabled' => ['nullable', 'boolean'],
            'session_timeout_minutes' => ['nullable', 'integer', 'min:5', 'max:1440'],
            'login_attempts' => ['nullable', 'integer', 'min:1', 'max:20'],
            'lockout_minutes' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'require_strong_password' => ['nullable', 'boolean'],
            // Email settings
            'mail_driver' => ['nullable', 'string', 'in:smtp,mailgun,ses,sendmail'],
            'mail_host' => ['nullable', 'string'],
            'mail_port' => ['nullable', 'integer'],
            'mail_username' => ['nullable', 'string'],
            'mail_password' => ['nullable', 'string'],
            'mail_encryption' => ['nullable', 'string', 'in:tls,ssl,null'],
            'mail_from_address' => ['nullable', 'email'],
            'mail_from_name' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            // SMS settings
            'sms_api_token' => ['nullable', 'string'],
            'sms_sender_id' => ['nullable', 'string'],
            'sms_is_active' => ['nullable', 'boolean'],
            // WhatsApp settings
            'personal_access_token' => ['nullable', 'string'],
            'session_api_key' => ['nullable', 'string'],
            'session_name' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string'],
            'session_status' => ['nullable', 'string'],
            'whatsapp_is_active' => ['nullable', 'boolean'],
        ]);

        $tab = $validated['tab'];
        unset($validated['tab']);

        // Handle email settings separately
        if ($tab === 'email') {
            $emailSettings = EmailSettings::first();
            if (! $emailSettings) {
                $emailSettings = new EmailSettings();
            }
            
            $emailSettings->fill([
                'mail_driver' => $validated['mail_driver'] ?? 'smtp',
                'mail_host' => $validated['mail_host'] ?? 'smtp.mailtrap.io',
                'mail_port' => $validated['mail_port'] ?? 2525,
                'mail_username' => $validated['mail_username'] ?? null,
                'mail_password' => $validated['mail_password'] ?? null,
                'mail_encryption' => $validated['mail_encryption'] ?? 'tls',
                'mail_from_address' => $validated['mail_from_address'] ?? 'noreply@example.com',
                'mail_from_name' => $validated['mail_from_name'] ?? 'Member Portal',
                'is_active' => $validated['is_active'] ?? true,
            ]);
            $emailSettings->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Admin updated email settings',
                'subject_type' => 'email_settings',
                'subject_id' => $emailSettings->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $this->success('Email settings saved successfully.');
            return redirect()->back();
        }

        // Handle SMS settings separately
        if ($tab === 'sms') {
            $smsSettings = SmsSettings::first();
            if (! $smsSettings) {
                $smsSettings = new SmsSettings();
            }
            
            $smsSettings->fill([
                'api_token' => $validated['sms_api_token'] ?? null,
                'sender_id' => $validated['sms_sender_id'] ?? 'FEEDTAN',
                'is_active' => $validated['sms_is_active'] ?? false,
            ]);
            $smsSettings->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Admin updated SMS settings',
                'subject_type' => 'sms_settings',
                'subject_id' => $smsSettings->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $this->success('SMS settings saved successfully.');
            return redirect()->back();
        }

        // Handle WhatsApp settings separately
        if ($tab === 'whatsapp') {
            $whatsappSettings = WhatsAppSettings::first();
            if (! $whatsappSettings) {
                $whatsappSettings = new WhatsAppSettings();
            }
            
            $whatsappSettings->fill([
                'personal_access_token' => $validated['personal_access_token'] ?? null,
                'session_api_key' => $validated['session_api_key'] ?? null,
                'session_name' => $validated['session_name'] ?? null,
                'phone_number' => $validated['phone_number'] ?? null,
                'session_status' => $validated['session_status'] ?? 'disconnected',
                'is_active' => $validated['whatsapp_is_active'] ?? false,
            ]);
            $whatsappSettings->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Admin updated WhatsApp settings',
                'subject_type' => 'whatsapp_settings',
                'subject_id' => $whatsappSettings->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $this->success('WhatsApp settings saved successfully.');
            return redirect()->back();
        }

        $settings = Cache::get('admin_settings', []);
        $updatedFields = [];

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $settings[$key] = $value;
                $updatedFields[] = $key;
            }
        }

        if (! empty($settings['spreadsheet_id'])) {
            try {
                $gsConfig = GoogleSheetsConfig::first();
                if (! $gsConfig) {
                    GoogleSheetsConfig::create([
                        'spreadsheet_id' => $settings['spreadsheet_id'],
                        'sheet_names' => ['Members', 'Loans', 'Savings', 'Deposits', 'SWF', 'Investments', 'Transactions'],
                        'is_active' => true,
                    ]);
                } else {
                    $gsConfig->update(['spreadsheet_id' => $settings['spreadsheet_id']]);
                }
            } catch (\Throwable $e) {
            }
        }

        Cache::put('admin_settings', $settings, now()->addYears(5));

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => "Admin updated {$tab} settings",
            'subject_type' => 'settings',
            'subject_id' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'tab' => $tab,
                'updated_fields' => $updatedFields,
            ],
        ]);

        $this->success(ucfirst(str_replace('_', ' ', $tab)) . ' settings saved successfully.');

        return redirect()->back();
    }

    public function testSms(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        $smsService = new SmsService();
        
        if (! $smsService->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'SMS service is not configured or inactive. Please configure SMS settings first.',
            ], 400);
        }

        $result = $smsService->sendSingle($validated['phone'], $validated['message']);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin sent test SMS',
            'subject_type' => 'sms_test',
            'subject_id' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'phone' => $validated['phone'],
                'message' => $validated['message'],
                'result' => $result,
            ],
        ]);

        return response()->json($result);
    }

    public function testSmsPage(Request $request)
    {
        $smsSettings = SmsSettings::first() ?? new SmsSettings();

        return view('admin.settings.test-sms', [
            'smsSettings' => $smsSettings,
        ]);
    }

    public function testWhatsApp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'message' => ['required', 'string'],
        ]);

        $whatsappSettings = WhatsAppSettings::getActiveSettings();
        
        if (! $whatsappSettings || ! $whatsappSettings->session_api_key) {
            return response()->json([
                'success' => false,
                'message' => 'WhatsApp service is not configured or inactive. Please configure WhatsApp settings first.',
            ], 400);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $whatsappSettings->session_api_key,
            ])->post('https://www.wasenderapi.com/api/send-message', [
                'phone_number' => $validated['phone'],
                'message' => $validated['message'],
            ]);

            $result = $response->json();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Admin sent test WhatsApp message',
                'subject_type' => 'whatsapp_test',
                'subject_id' => null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'properties' => [
                    'phone' => $validated['phone'],
                    'message' => $validated['message'],
                    'result' => $result,
                ],
            ]);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test message: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function testWhatsAppPage(Request $request)
    {
        $whatsappSettings = WhatsAppSettings::first() ?? new WhatsAppSettings();

        return view('admin.settings.test-whatsapp', [
            'whatsappSettings' => $whatsappSettings,
        ]);
    }

    public function checkWhatsAppConnection(Request $request): JsonResponse
    {
        $whatsappSettings = WhatsAppSettings::first();
        
        if (!$whatsappSettings || !$whatsappSettings->session_api_key) {
            return response()->json([
                'success' => false,
                'message' => 'WhatsApp API key not configured.',
                'status' => 'not_configured'
            ], 400);
        }

        if (!$whatsappSettings->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'WhatsApp service is inactive.',
                'status' => 'inactive'
            ], 400);
        }

        try {
            // Test connection by checking sessions
            if ($whatsappSettings->personal_access_token) {
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $whatsappSettings->personal_access_token,
                ])->get('https://www.wasenderapi.com/api/whatsapp-sessions');

                if ($response->successful()) {
                    $sessions = $response->json('data', []);
                    $connectedSession = collect($sessions)->firstWhere('status', 'connected');
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'WhatsApp connection is working.',
                        'status' => 'connected',
                        'sessions' => $sessions,
                        'active_session' => $connectedSession
                    ]);
                }
            }

            // If no personal token, try to send a test message
            $testResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $whatsappSettings->session_api_key,
            ])->post('https://www.wasenderapi.com/api/send-message', [
                'phone_number' => '0000000000', // Invalid number to test API connectivity
                'message' => 'Connection test',
            ]);

            if ($testResponse->successful() || $testResponse->status() === 400) {
                // 400 might mean API is working but phone number is invalid
                return response()->json([
                    'success' => true,
                    'message' => 'WhatsApp API is accessible.',
                    'status' => 'api_accessible'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'WhatsApp API connection failed.',
                'status' => 'connection_failed'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
}
