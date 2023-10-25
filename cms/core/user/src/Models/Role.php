<?php

namespace Dino\User\Models;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    // ! OVERRIDE HERE
    protected $fillable = [
        'name',
        'title',
        'description',
        'created_by',
        'guard_name',
        'is_default',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
