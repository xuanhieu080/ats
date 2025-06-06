<?php

namespace Packages\Permission\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends \Spatie\Permission\Models\Permission
{
    protected $fillable = [
        'name', 'title', 'guard_name', 'parent_id'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Permission::class,'parent_id')
            ->with('parent');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Permission::class,'parent_id')
            ->with('children');
    }
}
