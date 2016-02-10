<?php

namespace ResultSystems\Acl;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'branch_role');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'branch_role');
    }
}
