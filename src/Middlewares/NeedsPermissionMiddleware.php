<?php

namespace ResultSystems\Acl\Middlewares;

use Closure;

/**
 * Class NeedsPermissionMiddleware.
 */
class NeedsPermissionMiddleware extends AbstractAclMiddleware
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param callable                 $next
     * @param string|$array         $permissions
     * @param bool                       $any
     * @param int|string                $branch_id
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions = null, $any = true, $branch_id = null)
    {
        if (is_null($this->user)) {
            return $this->forbiddenResponse();
        }

        $checkPermissions = explode('|', $permissions); // Laravel 5.1 up - Using parameters
        $any              = $this->getBool($any);
        $branch_id        = $this->getBranchId($request, $branch_id);

        if (is_null($permissions)) {
            $checkPermissions = $this->getPermissions($request);
            $any              = $this->getAny($request);
        }

        if ($branch_id == 0) {
            $branch_id = null;
        }

        $hasPermission = $this->user->hasPermission($checkPermissions, $any, $branch_id);

        if (!$hasPermission) {
            return $this->forbiddenResponse();
        }

        return $next($request);
    }

    /**
     * Transforma uma string em boolean
     *
     * @param  string $value
     * @return bool
     */
    public function getBool($value)
    {
        return ($value !== "false" and $value !== false and $value !== 0 and $value !== "0");
    }
}
