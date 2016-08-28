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
    Route::get('alumnos/{alumno_id}/tutores/calificacion', 'UsersController@get_tutores_con_calificacion_alumno');
    Route::resource('calificaciones', 'CalificacionesController', ['only' => ['store', 'update']]);
    Route::resource('horarios', 'HorariosController', ['except' => ['create', 'edit']]);
    Route::post('horarios/{horario_id}/asistencias', 'AsistenciasController@store');
    Route::post('login', 'UsersController@login');
    Route::resource('materias', 'MateriasController', ['except' => ['create', 'edit']]);
    Route::resource('programas', 'ProgramasController', ['only' => ['index']]);
    Route::get('tutores', 'UsersController@get_tutores');
    Route::get('tutores/{tutor_id}/calificaciones', 'CalificacionesController@get_by_tutor');
    Route::get('tutores/{tutor_id}/asistencias', 'AsistenciasController@get_by_tutor');
    Route::resource('users', 'UsersController', ['only' => ['index', 'store']]);
});