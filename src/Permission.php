<?php

namespace ResultSystems\Acl;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function users()
    {
        return $this->belongsToMany(config('acl.model'), 'permission_user', 'user_id');
    }
}
