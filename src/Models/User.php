<?php

namespace Lampminds\Customization\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * Return the fillable attributes so child classes can merge their own.
     *
     * @return list<string>
     */
    public static function getFillableAttributes(): array
    {
        return [
            'name',
            'email',
            'kicked_out',
            'last_login_at',
            'last_login_ip',
            'last_seen_at',
            'email_verified_at',
        ];
    }

    /**
     * Return the hidden attributes so child classes can merge their own.
     *
     * @return list<string>
     */
    public static function getHiddenAttributes(): array
    {
        return ['password', 'remember_token'];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'kicked_out' => 'boolean',
            'last_login_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Get the user's initials (static for extension in child classes).
     */
    public static function getInitials(self $user): string
    {
        return Str::of($user->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's initials (instance method delegates to static).
     */
    public function initials(): string
    {
        return static::getInitials($this);
    }

    /**
     * Check if user is admin (static for extension in child classes).
     */
    public static function checkIsAdmin(self $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Check if user is admin (instance method delegates to static).
     */
    public function isAdmin(): bool
    {
        return static::checkIsAdmin($this);
    }

    /**
     * Check if user can manage parameters (static for extension in child classes).
     */
    public static function checkCanManageParameters(self $user): bool
    {
        return $user->hasAnyRole(['admin', 'account_owner']);
    }

    /**
     * Check if user can manage parameters (instance method delegates to static).
     */
    public function canManageParameters(): bool
    {
        return static::checkCanManageParameters($this);
    }

    /**
     * Check if user can manage pages (static for extension in child classes).
     */
    public static function checkCanManagePages(self $user): bool
    {
        return $user->hasAnyRole(['admin', 'account_owner']);
    }

    /**
     * Check if user can manage pages (instance method delegates to static).
     */
    public function canManagePages(): bool
    {
        return static::checkCanManagePages($this);
    }
}
