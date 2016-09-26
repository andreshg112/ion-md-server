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
    header('Location: http://fidelivery.co');
    die();
    //return view('welcome');
});

Route::group(['middleware' => 'cors'], function() {
    
    Route::post('authenticate', 'AuthenticateController@authenticate');
    
    Route::group(['middleware' => 'jwt.auth'], function () {
        
        //Administradores
        Route::get('administradores/{administrador_id}/pedidos-dia-semana', 'AdministradoresController@getPedidosDiaSemana');
        Route::get('administradores/{administrador_id}/clientes', 'AdministradoresController@getClientes');
        Route::get('administradores/{administrador_id}/pedidos-por-dia-en-lapso', 'AdministradoresController@getPedidosPorDiaEnLapso');
        Route::get('administradores/{administrador_id}/clientes-por-genero', 'AdministradoresController@getClientesPorGenero');
        Route::post('administradores/{administrador_id}/felicitaciones', 'AdministradoresController@felicitarCliente');
        Route::post('administradores/{administrador_id}/ofertas', 'AdministradoresController@enviarOferta');
        
        //Clientes
        Route::resource('clientes', 'ClientesController', ['only' => ['index', 'show']]);
        Route::get('clientes/{cliente_id}/pedidos', 'PedidosController@getPedidosCliente');
        
        //Establecimientos
        Route::resource('establecimientos', 'EstablecimientosController', ['except' => ['create', 'edit']]);
        
        //Pedidos
        Route::resource('pedidos', 'PedidosController', ['only' => ['index', 'store', 'update', 'destroy']]);
        
        //Sedes
        Route::resource('sedes', 'SedesController', ['except' => ['create', 'edit']]);
        
        //Users
        Route::resource('users', 'UsersController', ['only' => ['index', 'store', 'update', 'destroy']]);
        
        //Vendedores
        Route::resource('vendedores', 'VendedoresController', ['only' => ['index', 'store', 'destroy']]);
    });
});