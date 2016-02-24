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
     * Get branch by user authentication with auth
     */
    'middleware' => [
        'autoload' => false, //Auto load middleware in all reques
        'branch'   => ResultSystems\Acl\Middlewares\AuthBranchMiddleware::class,
        /**
         * Get branch by user authentication with Jwt
         */
        //'branch'    => ResultSystems\Acl\Middlewares\JwtBranchMiddleware::class,

        /**
         * Field for compare branch_id
         */
        'branch_id' => 'branch_id',
        /**
         * Another exemple
         */
        //'branch_id' => 'owner_id',
    ],
];
