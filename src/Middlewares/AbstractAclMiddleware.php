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
    protected function getBranchId($request)
    {
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
}
