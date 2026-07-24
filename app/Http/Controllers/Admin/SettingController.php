<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GoogleSheetsConfig;
use App\Traits\FlashMessages;
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
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'tab' => ['required', 'in:general,notifications,google_sheets,security'],
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
        ]);

        $tab = $validated['tab'];
        unset($validated['tab']);

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
            'subject_id' => $tab,
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
}
