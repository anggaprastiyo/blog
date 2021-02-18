<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'post'], function () use ($router) {

    $router->get('/', ['uses' => 'PostController@index']);
    $router->get('/show/{id}', ['uses' => 'PostController@show']);
    $router->post('/create', ['uses' => 'PostController@create']);
    $router->post('/update/{id}', ['uses' => 'PostController@update']);
    $router->delete('/{id}', ['uses' => 'PostController@destroy']);
    $router->get('/count', ['uses' => 'PostController@count']);
    
});
