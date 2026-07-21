<?php

namespace App\Livewire\Forms;

use App\Core\Shared\Enums\UserStatus;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        $user = Auth::user();

        if (! $user) {
            Auth::logout();

            throw ValidationException::withMessages([
                'form.email' => trans('auth.failed'),
            ]);
        }

        if ($user->tenant === null || ! $user->tenant->isActive()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'form.email' => 'Your company account is not active.',
            ]);
        }

        if ($user->isSuspended()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'form.email' => 'Your account is suspended.',
            ]);
        }

        if ($user->isInactive() || $user->getRawOriginal('status') === UserStatus::Invited->value) {
            Auth::logout();

            throw ValidationException::withMessages([
                'form.email' => 'Your account is not active.',
            ]);
        }

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ])->save();

        activity('auth')
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'tenant_id' => $user->tenant_id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->event('login')
            ->log('User logged in');

        RateLimiter::clear($this->throttleKey());
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
