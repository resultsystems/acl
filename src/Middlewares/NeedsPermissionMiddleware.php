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
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions = null, $any = true, $branch_id = null)
    {
        $checkPermissions = explode('|', $permissions); // Laravel 5.1 - Using parameters
        $any              = $this->getBool($any);
        if (!is_null($branch_id)) {
            $branch_id = (int) $branch_id;
        }
        if (is_null($permissions)) {
            $checkPermissions = $this->getPermissions($request);
            $any              = $this->getAny($request);
            $branch_id        = $this->getBranchId($request);
        }
        if (is_null($this->user)) {
            return $this->forbiddenResponse();
        }

        if ($branch_id == 0) {
            $branch_id = null;
        }
        dd([$checkPermissions, $any, $branch_id]);
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
        return ($value !== "false" and $value !== 0 and $value !== "0");
    }
}
