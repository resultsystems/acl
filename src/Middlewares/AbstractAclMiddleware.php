<?php

namespace ResultSystems\Acl\Middlewares;

use Auth;
use ResultSystems\Acl\Handles\ForbiddenHandler;

/**
 * Class AbstractMiddleware.
 */
abstract class AbstractAclMiddleware
{
    /**
     * The current logged in user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function getPermissions($request)
    {
        $routeActions = $this->getActions($request);

        return array_get($routeActions, 'permission', null);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function getAny($request)
    {
        $routeActions = $this->getActions($request);

        return (bool) array_get($routeActions, 'any', false);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function getBranchId($request, $branch_id = null)
    {
        if ($branch_id == 'middleware') {
            return $this->getBranchByMiddleware($request);
        }

        if (!is_null($branch_id)) {
            return (int) $branch_id;
        }

        if (config('acl.middleware.autoload', false)) {
            return $this->getBranchByMiddleware($request);
        }

        $routeActions = $this->getActions($request);

        return (int) array_get($routeActions, 'branch_id', 0);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function getActions($request)
    {
        $routeActions = $request->route()->getAction();

        return $routeActions;
    }

    /**
     * Handles the forbidden response.
     *
     * @return mixed
     */
    protected function forbiddenResponse()
    {
        $handler = app()->make(config('acl.forbidden_callback'));

        return ($handler instanceof ForbiddenHandler) ? $handler->handle() : response('Forbidden', 403);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    protected function getBranchByMiddleware($request)
    {
        $handler = app()->make(config('acl.middleware.branch'));

        return ($handler instanceof BranchMiddleware) ? $handler->handle($request) : response('Forbidden', 403);
    }
}
