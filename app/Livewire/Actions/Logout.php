<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(): void
    {
        $user = Auth::user();

        if ($user) {
            activity('auth')
                ->causedBy($user)
                ->performedOn($user)
                ->withProperties(['ip_address' => request()->ip()])
                ->event('logout')
                ->log('User logged out');
        }

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();
    }
}
