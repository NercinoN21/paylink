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

use App\Http\Middleware\VerifyToken;
use  App\Models\AuthToken;

$router->get('/', function () use ($router) {
   return 'Api paylink...';
});

$router->group(['prefix' => 'users'], function() use ($router) {
    $router->get('/', 'UsersController@listAll');
    $router->get('/{id}', 'UsersController@listSpecific');

    $router->post('/', 'UsersController@createUser');
    $router->put('/{id}', 'UsersController@updateUser');
    $router->delete('/{id}', 'UsersController@deleteUser');
});


$router->group(['prefix' => 'endereco'], function() use ($router) {
    $router->get('/', 'EnderecoController@listAll');
    $router->post('/', 'EnderecoController@createEndereco');
});

$router->group(['prefix' => 'transacao'], function() use ($router) {
    $router->get('/', 'TransacaoController@listAll');
    $router->post('/SolicitarCodigo', 'TransacaoController@createCodigo');
    $router->post('/deposito', 'TransacaoController@createDeposito');
    $router->post('/transferencia', 'TransacaoController@createTransferencia');
});

$router->get('/conta', 'ContaController@listAll');
$router->get('/agencia', 'AgenciaController@listAll');

$router->post('/login', 'AuthController@login');
$router->get('/CodigoTransacao', 'CodigoTransacaoController@listAll');
