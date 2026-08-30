<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'feature_type',
        'is_system',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function plans(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Plan::class,
                'plan_features'
            )
            ->withPivot('is_enabled')
            ->withTimestamps();
    }

    public function tenants(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Tenant::class,
                'tenant_features'
            )
            ->withPivot('is_enabled')
            ->withTimestamps();
    }
}
