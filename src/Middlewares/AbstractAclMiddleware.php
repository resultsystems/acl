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
    protected function getOwnerId($request, $owner_id = null)
    {
        if ($owner_id == 'middleware') {
            return $this->getOwnerByMiddleware($request);
        }

        if (!is_null($owner_id)) {
            return (int) $owner_id;
        }

        if (config('acl.middleware.autoload', false)) {
            return $this->getOwnerByMiddleware($request);
        }

        $routeActions = $this->getActions($request);

        return (int) array_get($routeActions, 'owner_id', 0);
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
    protected function getOwnerByMiddleware($request)
    {
        $handler = app()->make(config('acl.middleware.owner'));

        return ($handler instanceof OwnerMiddleware) ? $handler->handle($request) : response('Forbidden', 403);
    }
}
