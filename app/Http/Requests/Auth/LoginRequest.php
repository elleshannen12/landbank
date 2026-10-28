<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Maximum failed attempts before the username is locked.
     */
    private const MAX_ATTEMPTS = 3;

    /**
     * Lock time in seconds after too many failed attempts.
     */
    private const LOCK_SECONDS = 300;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:3', 'max:40'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Try to log in with username + password.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = [
            'username' => strtolower($this->string('username')->trim()->toString()),
            'password' => $this->input('password'),
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), self::LOCK_SECONDS);

            // Message depends on whether this failed attempt reached the limit
            if (RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
                $this->throwLockedMessage();
            }

            throw ValidationException::withMessages([
                'username' => 'Invalid username or password.',
            ]);
        }

                RateLimiter::clear($this->throttleKey());

        // Credentials are correct, but the account must be approved first
        $status = Auth::user()->status;

        if ($status !== 'active') {
            Auth::logout();

            throw ValidationException::withMessages([
                'username' => $this->inactiveMessage($status),
            ]);
        }
    }

    private function inactiveMessage(?string $status): string
    {
        return match ($status) {
            'pending' => 'Your account is pending activation. You will receive an email once it is approved and ready for login.',
            default   => 'Your account is not active (' . ($status ?? 'unknown') . '). Please contact LANDBANK Customer Care at (02) 8405-7000.',
        };
    }

    /**
     * Block the request if the username is locked.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS)) {
            return;
        }

        event(new Lockout($this));

        $this->throwLockedMessage();
    }

    private function throwLockedMessage(): void
    {
        throw ValidationException::withMessages([
            'username' => 'Your account is locked due to too many failed login attempts. Please use Unlock ID or contact LANDBANK Customer Care at (02) 8405-7000.',
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')).'|'.$this->ip());
    }
}