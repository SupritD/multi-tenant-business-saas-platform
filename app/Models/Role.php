<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'role_type',
        'is_system',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'user_roles')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this
            ->belongsToMany(Permission::class, 'role_permissions')
            ->withPivot('is_allowed')
            ->withTimestamps();
    }
}
