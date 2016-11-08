<?php
Route::resource('vendedores', 'VendedoresController', ['only' => ['index', 'store', 'destroy']]);

Route::group(['prefix' => 'vendedores/{vendedor}'], function () {
    Route::get('establecimientos/{establecimiento_id}/productos', 'ProductosController@getProductosEstablecimiento');
});