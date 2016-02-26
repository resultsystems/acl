<?php

namespace ResultSystems\Acl\Traits;

use Mockery as m;

class PermissionForTrait
{
    use PermissionTrait;
}

class PermissionTraitTest extends \TestCase
{
    public function testHasPermissionInCheckPermissionsInRoles()
    {
        $permissions = [];

        for ($i = 0; $i <= 10; $i++) {
            $permission       = new \stdClass();
            $permission->slug = 'permission' . $i;

            $permissions[] = $permission;
        }

        $roles             = [];
        $role              = new \stdClass();
        $role->permissions = $permissions;
        $role->name        = 'admin';

        $roles[] = $role;

        $mock = m::mock(PermissionForTrait::class);

        $checkPermissions = ['permission1', 'permission10'];

        $check = $mock->checkPermissionsInRoles($roles, $checkPermissions, false);

        $this->assertTrue($check);
    }

    public function testHaveAnyPermissionInCheckPermissionsInRoles()
    {
        $permissions = [];

        for ($i = 0; $i <= 10; $i++) {
            $permission       = new \stdClass();
            $permission->slug = 'permission' . $i;

            $permissions[] = $permission;
        }

        $roles             = [];
        $role              = new \stdClass();
        $role->permissions = $permissions;
        $role->name        = 'admin';

        $roles[] = $role;

        $mock = m::mock(PermissionForTrait::class);

        $checkPermissions = ['permission1', 'permission15'];

        $check = $mock->checkPermissionsInRoles($roles, $checkPermissions, true);

        $this->assertTrue($check);
    }

    public function testHaventPermissionInCheckPermissionsInRoles()
    {
        $permissions = [];

        for ($i = 0; $i <= 10; $i++) {
            $permission       = new \stdClass();
            $permission->slug = 'permission' . $i;

            $permissions[] = $permission;
        }

        $roles             = [];
        $role              = new \stdClass();
        $role->permissions = $permissions;
        $role->name        = 'admin';

        $roles[] = $role;

        $mock = m::mock(PermissionForTrait::class);

        $checkPermissions = ['permission15'];

        $check = $mock->checkPermissionsInRoles($roles, $checkPermissions, false);

        $this->assertTrue(!$check);
    }

    public function testHasOnePermissionInCheckPermissionsInRoles()
    {
        $permissions = [];

        for ($i = 0; $i <= 10; $i++) {
            $permission       = new \stdClass();
            $permission->slug = 'permission' . $i;

            $permissions[] = $permission;
        }

        $roles             = [];
        $role              = new \stdClass();
        $role->permissions = $permissions;
        $role->name        = 'admin';

        $roles[] = $role;

        $mock = m::mock(PermissionForTrait::class);

        $checkPermissions = ['permission10'];

        $check = $mock->checkPermissionsInRoles($roles, $checkPermissions, false);

        $this->assertTrue($check);
    }

    public function testHasPermissionInCheckPermissions()
    {
        $permissions = [];

        for ($i = 0; $i <= 10; $i++) {
            $permission       = new \stdClass();
            $permission->slug = 'permission' . $i;

            $permissions[] = $permission;
        }

        $mock = m::mock(PermissionForTrait::class);

        $checkPermissions = ['permission1', 'permission10'];

        $check = $mock->checkPermissions($permissions, $checkPermissions, false);

        $this->assertTrue($check);
    }

    public function testHaveAnyPermissionInCheckPermissions()
    {
        $permissions = [];

        for ($i = 0; $i <= 10; $i++) {
            $permission       = new \stdClass();
            $permission->slug = 'permission' . $i;

            $permissions[] = $permission;
        }

        $mock = m::mock(PermissionForTrait::class);

        $checkPermissions = ['permission1', 'permission15'];

        $check = $mock->checkPermissions($permissions, $checkPermissions, true);

        $this->assertTrue($check);
    }

    public function testHaventPermissionInCheckPermissions()
    {
        $permissions = [];

        for ($i = 0; $i <= 10; $i++) {
            $permission       = new \stdClass();
            $permission->slug = 'permission' . $i;

            $permissions[] = $permission;
        }

        $mock = m::mock(PermissionForTrait::class);

        $checkPermissions = ['permission15'];

        $check = $mock->checkPermissions($permissions, $checkPermissions, false);

        $this->assertTrue(!$check);
    }

    public function testHasOnePermissionInCheckPermissions()
    {
        $permissions = [];

        for ($i = 0; $i <= 10; $i++) {
            $permission       = new \stdClass();
            $permission->slug = 'permission' . $i;

            $permissions[] = $permission;
        }

        $mock = m::mock(PermissionForTrait::class);

        $checkPermissions = ['permission10'];

        $check = $mock->checkPermissions($permissions, $checkPermissions, false);

        $this->assertTrue($check);
    }
}
