<?php

namespace ResultSystems\Acl\Traits;

use ResultSystems\Acl\Branch;
use ResultSystems\Acl\Permission;
use ResultSystems\Acl\Role;

trait PermissionTrait
{
    /**
     * Pegas as filiais
     *
     * @return Collection
     */
    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_role')
            ->withPivot(['role_id'])
            ->with(['roles' => function ($query) {
                $query
                    ->with(['permissions' => function ($q) {
                        $q
                            ->where('allow', '=', true)
                            ->where(function ($qq) {
                                $qq
                                    ->where('expires', '>=', date('Y-m-d H:i:s'))
                                    ->where('expires', 'IS', 'NULL', 'OR');
                            });
                    }]);
            }]);
    }

    /**
     * Pega as Roles
     *
     * @return Collection
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, "role_user");
    }

    /**
     * Pega as permissões
     *
     * @return Collection
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, "permission_user");
    }

    /**
     * Verifica se o usuário tem a(s) permissão(ões) :permission
     *
     * Caso seja passado uma branch (filial)
     * Será verificado as permissões apenas nesta branch
     *
     * @param  string  $permission
     * @param  bool    $any
     * @param  int     $branch_id
     *
     * @return boolean
     */
    public function hasPermission($checkPermissions, $any = true, $branch_id = null)
    {
        $user_id = $this->id;
        $user    = $this->with(['branches' => function ($query) use ($branch_id, $user_id) {
            $query
                ->with(['roles' => function ($q) use ($user_id) {
                    $q->where("user_id", "=", $user_id);
                }]);
            if (!is_null($branch_id)) {
                $query
                    ->where("id", "=", $branch_id);
            }
        },
            'roles' => function ($query) {
                $query
                    ->with(['permissions' => function ($q) {
                        $q
                            ->where('allow', '=', true)
                            ->where(function ($qq) {
                                $qq
                                    ->where('expires', '>=', date('Y-m-d H:i:s'))
                                    ->where('expires', 'IS', 'NULL', 'OR');
                            });
                    }]);
            },
            "permissions" => function ($query) {
                $query->select("slug");
            }])
            ->where("id", $this->id);
        if ($branch_id) {
            $user = $user->whereHas("branches", function ($query) use ($branch_id) {
                $query->where('id', '=', $branch_id);
            });
        }
        $user = $user->first();

        if (is_null($user)) {
            return false;
        }

        if ($branch_id) {
            return $this->checkPermissionsByBranches($user->branches, $branch_id, $checkPermissions, $any);
        }

        if ($this->checkPermissions($user->permissions, $checkPermissions, $any)) {
            return true;
        }

        if ($this->checkPermissionsInRoles($user->roles, $checkPermissions, $any)) {
            return true;
        }

        return false;
    }

    /**
     * Verifica se o usuário tem a(s) permissão(ões) :checkPermissions na branch :branch_id
     *
     * @param  array         $branches
     * @param  int           $branch_id
     * @param  array|string  $checkPermissions
     * @param  bool          $any
     *
     * @return bool
     */
    private function checkPermissionsByBranches($branches, $branch_id, $checkPermissions, $any)
    {
        foreach ($branches as $branch) {
            if ($branch->id != $branch_id) {
                continue;
            }

            return $this->checkPermissionsInRoles($branch->roles, $checkPermissions, $any);
        }

        return false;
    }

    /**
     * Verifica se o usuário tem a(s) permissão(ões) :checkPermissions
     * Dentro de de alguma das roles :roles
     *
     * @param  array        $roles
     * @param  array|string $checkPermissions
     * @param  bool          $any
     *
     * @return bool
     */
    private function checkPermissionsInRoles($roles, $checkPermissions, $any)
    {
        foreach ($roles as $role) {
            return $this->checkPermissions($role->permissions, $checkPermissions, $any);
        }

        return false;
    }

    /**
     * Checka se a(s) permissão(ões) :checkPermissions
     * Está em :permissions
     *
     * @param  array        $permissions
     * @param  array|string $checkPermissions
     * @param  bool         $any
     *
     * @return bool
     */
    private function checkPermissions($permissions, $checkPermissions, $any)
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
