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
        
        include('Routes/administradores.php');
        include('Routes/vendedores.php');
        
        //Clientes
        Route::resource('clientes', 'ClientesController', ['only' => ['index', 'show', 'store']]);
        Route::get('clientes/{cliente_id}/pedidos', 'PedidosController@getPedidosCliente');
        
        //Establecimientos
        Route::resource('establecimientos', 'EstablecimientosController', ['except' => ['create', 'edit']]);
        
        //Pedidos
        Route::resource('pedidos', 'PedidosController', ['only' => ['index', 'store', 'update', 'destroy']]);
        
        //Sedes
        Route::resource('sedes', 'SedesController', ['except' => ['create', 'edit']]);
        Route::get('sedes/{sede_id}/resumen-dia', 'SedesController@getResumenDia');
        
        //Planes
        Route::resource('planes', 'PlanesController', ['only' => ['index']]);
        
        //Users
        Route::resource('users', 'UsersController', ['only' => ['index', 'store', 'update', 'destroy']]);

        
    });
});