<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => 'cors'], function() {
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::group(['middleware' => 'jwt.auth'], function () {
        
        //Clientes
        Route::resource('clientes', 'ClientesController', ['only' => ['index', 'show']]);
        Route::get('clientes/{cliente_id}/pedidos', 'PedidosController@getPedidosCliente');
        
        //Establecimientos
        Route::resource('establecimientos', 'EstablecimientosController', ['except' => ['create', 'edit']]);
        Route::get('establecimientos/{establecimiento_id}/pedidos-dia-semana', 'EstablecimientosController@getPedidosDiaSemana');
        Route::get('establecimientos/{establecimiento_id}/clientes', 'EstablecimientosController@getClientesEstablecimiento');
        
        //Pedidos
        Route::resource('pedidos', 'PedidosController', ['only' => ['index', 'store', 'update', 'destroy']]);
        
        //Users
        Route::resource('users', 'UsersController', ['only' => ['index', 'store', 'update', 'destroy']]);
        
    });
});