<?php

namespace App\Policies;

use App\Models\User;

class SwfPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, $swf): bool
    {
        if (is_string($swf)) {
            return $user->member_number === $swf;
        }

        return $user->member_number === ($swf->member_number ?? $swf->memberNumber ?? null);
    }
}
