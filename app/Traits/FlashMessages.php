<?php

namespace App\Traits;

trait FlashMessages
{
    public function success(string $msg): void
    {
        session()->flash('flash', ['level' => 'success', 'message' => $msg]);
    }

    public function error(string $msg): void
    {
        session()->flash('flash', ['level' => 'error', 'message' => $msg]);
    }

    public function info(string $msg): void
    {
        session()->flash('flash', ['level' => 'info', 'message' => $msg]);
    }

    public function warning(string $msg): void
    {
        session()->flash('flash', ['level' => 'warning', 'message' => $msg]);
    }
}
