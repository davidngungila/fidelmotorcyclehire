<?php

declare(strict_types=1);

use App\Services\EncryptedIdService;

if (! function_exists('encryptId')) {
    function encryptId(string $id): string
    {
        return app(EncryptedIdService::class)->encrypt($id);
    }
}

if (! function_exists('decryptId')) {
    function decryptId(string $encryptedId): string
    {
        return app(EncryptedIdService::class)->decrypt($encryptedId);
    }
}
