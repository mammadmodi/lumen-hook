<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */
$router->get('/', function () {
    return "Welcome to HOOK";
});

$router->group([
    'prefix' => 'auth',
    'as' => 'auth.',
], function (Router $router) {
    $router->post('register', 'AuthController@register');
    $router->patch('verify', 'AuthController@verify');
    $router->post('login', 'AuthController@login');
    $router->get('logout', 'AuthController@logout');
    $router->get('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');
});

$router->group([
    'namespace' => 'V1',
    'prefix' => 'v1',
    'as' => 'v1.'
], function (Router $router) {
    $router->group([
        'prefix' => 'hooks',
        'as' => 'hooks.',
        'middleware' => ['auth:api', 'verify']
    ], function (Router $router) {
        $router->get('', ['as' => 'index', 'uses' => 'HookController@index']);
        $router->post('', ['as' => 'store', 'uses' => 'HookController@store']);
        $router->patch('/{id}', ['as' => 'update', 'uses' => 'HookController@update']);
    });
});
