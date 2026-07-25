<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class EncryptedIdService
{
    /**
     * Encrypt an ID for use in URLs
     */
    public function encrypt(string $id): string
    {
        try {
            return Crypt::encryptString($id);
        } catch (\Exception $e) {
            // Fallback to base64 if encryption fails
            return base64_encode($id);
        }
    }

    /**
     * Decrypt an ID from URL
     */
    public function decrypt(string $encryptedId): string
    {
        try {
            return Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            // Try base64 fallback
            try {
                $decoded = base64_decode($encryptedId, true);
                if ($decoded !== false) {
                    return $decoded;
                }
            } catch (\Exception) {
                // Ignore base64 errors
            }
            
            // If all else fails, return the original (for backward compatibility)
            return $encryptedId;
        }
    }

    /**
     * Generate a route with encrypted ID
     */
    public function route(string $routeName, string $id, array $parameters = []): string
    {
        $parameters['id'] = $this->encrypt($id);
        
        return route($routeName, $parameters);
    }

    /**
     * Encrypt multiple IDs
     */
    public function encryptMany(array $ids): array
    {
        return array_map(fn ($id) => $this->encrypt($id), $ids);
    }

    /**
     * Decrypt multiple IDs
     */
    public function decryptMany(array $encryptedIds): array
    {
        return array_map(fn ($id) => $this->decrypt($id), $encryptedIds);
    }
}
