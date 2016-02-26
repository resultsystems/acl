<?php

namespace ResultSystems\Acl\Traits;

use ResultSystems\Acl\Permission;
use ResultSystems\Acl\Role;

trait PermissionTrait
{
    /**
     * Pega as Roles
     *
     * @return Collection
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, "role_user", 'user_id');
    }

    /**
     * Pega as permissões
     *
     * @return Collection
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, "permission_user", 'user_id');
    }

    /**
     * Verifica se o usuário tem a permissão :permission
     *
     * @param  string  $permission
     * @param  bool    $any
     * @param  int     $owner_id
     *
     * @return boolean
     */
    public function hasPermission($permission, $any = false, $owner_id = null)
    {
        if (!is_array($permission)) {
            $permission = [$permission];
        }

        return $this->hasPermissions($permission, $any $owner_id);
    }

    /**
     * Verifica se o usuário tem a(s) permissão(ões) :permissions
     *
     * @param  array  $permissions
     * @param  bool    $any
     * @param  int     $owner_id
     *
     * @return bool
     */
    public function hasPermissions(array $permissions, $any = false, $owner_id = null)
    {
        $user_id = $this->id;
        $user    = $this->with(['roles' => function ($query) use ($owner_id) {
            $query
                ->with(['permissions' => function ($q) {
                    $q
                        ->where('allow', '=', true)
                        ->where(function ($qq) {
                            $qq
                                ->where('expires', '>=', date('Y-m-d H:i:s'))
                                ->orWhereNull('expires');
                        });

                    if (is_null($owner_id)) {
                        $q->where('owner_id', '=', $owner_id);
                    } else {
                        $q->whereNull('owner_id');
                    }
                }]);
        },
            "permissions" => function ($query) {
                $query->select("slug");
            }])
            ->where("id", $this->id)
            ->first();

        if (is_null($user)) {
            return false;
        }

        return $this->checkPermissions($user->permissions, $permissions, $any);
    }

    /**
     * Verifica se o usuário tem a(s) permissão(ões) :checkPermissions
     * Dentro de de alguma das roles :roles
     *
     * @param  array        $roles
     * @param  array $permissions
     * @param  bool          $any
     *
     * @return bool
     */
    private function checkPermissionsInRoles(array $roles, array $permissions, $any)
    {
        foreach ($roles as $role) {
            return $this->checkPermissions($role->permissions, $permissions, $any);
        }

        return false;
    }

    /**
     * Checa se a(s) permissão(ões) :permissions
     * Está em :permissions
     *
     * @param  array        $permissions
     * @param  array $checkPermissions
     * @param  bool         $any
     *
     * @return bool
     */
    private function checkPermissions(array $permissions, array $checkPermissions, $any = false)
    {
        if (!is_array($checkPermissions)) {
            $checkPermissions = array($checkPermissions);
        }

        $filtered = [];
        foreach ($permissions as $item) {
            $filtered[] = $item->slug;
        }

        $total = 0;
        foreach ($checkPermissions as $permission) {
            $has = in_array($permission, $filtered);
            if ($has and $any) {
                return true;
            }

            if (!$has and !$any) {
                return false;
            }

            if ($has) {
                $total++;
            }
        }

        return $total == count($checkPermissions);
    }
}
