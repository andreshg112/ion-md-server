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
    /*
    Route::resource('calificaciones', 'CalificacionesController', ['only' => ['store', 'update']]);
    Route::resource('horarios', 'HorariosController', ['except' => ['create', 'edit']]);
    Route::get('tutores', 'UsersController@get_tutores');
    Route::get('tutores/{tutor_id}/calificaciones', 'CalificacionesController@get_by_tutor');
    Route::get('tutores/{tutor_id}/asistencias', 'AsistenciasController@get_by_tutor');
    Route::resource('users', 'UsersController', ['only' => ['index', 'store']]);*/
    
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('jwt', function(){ return ['mensaje' => 'JWT'];});
        Route::resource('pedidos', 'PedidosController', ['only' => ['index', 'store', 'update', 'destroy']]);
        Route::resource('clientes', 'ClientesController', ['only' => ['index', 'show']]);
        Route::get('clientes/{cliente_id}/pedidos', 'PedidosController@getPedidosCliente');
        Route::get('establecimientos/{establecimiento_id}/pedidos-dia-semana', 'EstablecimientosController@getPedidosDiaSemana');
        Route::get('establecimientos/{establecimiento_id}/clientes', 'EstablecimientosController@getClientesEstablecimiento');
    });
});