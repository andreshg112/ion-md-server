<?php

Route::get('administradores/{administrador_id}/pedidos-dia-semana', 'AdministradoresController@getPedidosDiaSemana');

Route::get('administradores/{administrador_id}/clientes', 'AdministradoresController@getClientes');

Route::get('administradores/{administrador_id}/pedidos-por-dia-en-lapso', 'AdministradoresController@getPedidosPorDiaEnLapso');

Route::get('administradores/{administrador_id}/clientes-por-genero', 'AdministradoresController@getClientesPorGenero');

//Route::get('administradores/{administrador_id}/valor-pedidos-por-dia', 'AdministradoresController@getValorPedidosPorDia');

Route::post('administradores/{administrador_id}/felicitaciones', 'AdministradoresController@felicitarCliente');

Route::post('administradores/{administrador_id}/ofertas', 'AdministradoresController@enviarOferta');