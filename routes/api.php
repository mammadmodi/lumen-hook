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
