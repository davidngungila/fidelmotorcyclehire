<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EmailSettings;
use Illuminate\Support\Facades\Config;

class MailConfigService
{
    /**
     * Configure mail settings from database
     */
    public function configureFromDatabase(): void
    {
        $emailSettings = EmailSettings::getActiveSettings();

        if (! $emailSettings || ! $emailSettings->is_active) {
            // Use default config from .env
            return;
        }

        Config::set('mail.mailers.smtp.host', $emailSettings->mail_host);
        Config::set('mail.mailers.smtp.port', $emailSettings->mail_port);
        Config::set('mail.mailers.smtp.username', $emailSettings->mail_username);
        Config::set('mail.mailers.smtp.password', $emailSettings->mail_password);
        Config::set('mail.mailers.smtp.encryption', $emailSettings->mail_encryption === 'null' ? null : $emailSettings->mail_encryption);
        Config::set('mail.from.address', $emailSettings->mail_from_address);
        Config::set('mail.from.name', $emailSettings->mail_from_name);

        // Set the driver
        Config::set('mail.default', $emailSettings->mail_driver);
    }

    /**
     * Check if email service is active
     */
    public function isActive(): bool
    {
        $emailSettings = EmailSettings::getActiveSettings();
        return $emailSettings && $emailSettings->is_active;
    }
}
