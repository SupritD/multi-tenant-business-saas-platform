<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * @return BelongsToMany<Plan, $this>
     */
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

    /**
     * @return BelongsToMany<Tenant, $this>
     */
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
