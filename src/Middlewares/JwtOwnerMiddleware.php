<?php

namespace ResultSystems\Acl\Middlewares;

use Tymon\JWTAuth\JWTAuth;

class JwtOwnerMiddleware extends OwnerMiddleware
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create a new BaseMiddleware instance.
     *
     * @param \Tymon\JWTAuth\JWTAuth  $auth
     */
    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int
     */
    public function handle($request)
    {
        $token = $this->auth->setRequest($request)->getToken();

        $owner_id = env('acl.middleware.owner_id', 'owner_id');

        return $this->auth->getPayload($token)->get($owner_id);
    }
}
