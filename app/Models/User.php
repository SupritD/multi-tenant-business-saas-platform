<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'status',
        'last_login_at',
        'last_login_ip',
        'password_changed_at',
    ];

    /**
     * Hidden attributes.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Tenant this user belongs to.
     *
     * Platform users such as Super Admin may have tenant_id = null.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Roles assigned to this user.
     */
    public function roles(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Role::class,
                'user_roles',
                // 'user_id',
                // 'role_id'
            )
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    /**
     * Check whether the user has a specific role.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this
            ->roles()
            ->where('roles.slug', $roleSlug)
            ->where('roles.is_active', true)
            ->exists();
    }

    /**
     * Check whether the user has any of the given roles.
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this
            ->roles()
            ->whereIn('roles.slug', $roleSlugs)
            ->where('roles.is_active', true)
            ->exists();
    }

    /**
     * Check whether the user has a permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this
            ->roles()
            ->where('roles.is_active', true)
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query
                    ->where('permissions.slug', $permissionSlug)
                    ->where('permissions.is_active', true)
                    ->where('role_permissions.is_allowed', true);
            })
            ->exists();
    }

    /**
     * Check whether the user is a platform-level user.
     */
    public function isPlatformUser(): bool
    {
        return is_null($this->tenant_id);
    }

    /**
     * Check whether the user belongs to a tenant.
     */
    public function isTenantUser(): bool
    {
        return !is_null($this->tenant_id);
    }

    /**
     * Check whether the account is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
