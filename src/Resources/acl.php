<?php

return [
    /**
     * tables
     */
    'tables' => [
        'user' => 'users',
    ],

    /**
     * Model user
     */
    'model' => \App\User::class,

    /*
     * Forbidden callback
     */
    'forbidden_callback' => ResultSystems\Acl\Handlers\ForbiddenHandler::class,

    /**
     * Get owner by user authentication with auth
     */
    'middleware' => [
        'autoload' => false, //Auto load middleware in all reques
        'owner'    => ResultSystems\Acl\Middlewares\AuthOwnerMiddleware::class,

        /**
         * Get owner by user authentication with Jwt
         */
        //'owner'    => ResultSystems\Acl\Middlewares\JwtOwnerMiddleware::class,

        /**
         * Field for compare owner_id
         */
        'owner_id' => 'owner_id',

        /**
         * Another exemple
         */
        //'owner_id' => 'branch_id',
    ],
];
