<?php

use function DI\object;

use App\Database\DatabaseInterface;
use App\Database\EloquentDatabase;

use App\Http\RequestInterface;
use App\Http\HttpFoundationRequest;
use App\Http\ResponseInterface;
use App\Http\HttpFoundationResponse;

return [

    DatabaseInterface::class => function() {

        $capsule = new EloquentDatabase;

        $capsule->addConnection([
            'driver' => getenv('DB_DRIVER'),
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_NAME'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    },

    RequestInterface::class => function() {

        $request = HttpFoundationRequest::createFromGlobals();

        return $request;
    },

    ResponseInterface::class => object(HttpFoundationResponse::class),

];
