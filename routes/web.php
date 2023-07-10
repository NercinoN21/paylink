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
    return 'Api lumen test...';
});

$router->group(['prefix' => 'users'], function() use ($router) {
    $router->get('/', 'UsersController@listAll');
    $router->get('/{id}', 'UsersController@listSpecific');

    $router->post('/', 'UsersController@createUser');
    $router->put('/{id}', 'UsersController@updateUser');
    $router->delete('/{id}', 'UsersController@deleteUser');

    /*
        Recurso: Usuarios (users)
        endpoint: /users (usuarios)
        Verbos: GET, POST, PUT/PATCH, DELETE
     */
});


$router->group(['prefix' => 'endereco'], function() use ($router) {
    $router->get('/', 'EnderecoController@listAll');
    $router->post('/', 'EnderecoController@createEndereco');
});

$router->get('/conta', 'ContaController@listAll');
$router->get('/agencia', 'AgenciaController@listAll');
$router->get('/transacao', 'TransacaoController@listAll'); //TODO: Criar grup para deposito e transferencia


$router->post('/login', 'AuthController@login');
