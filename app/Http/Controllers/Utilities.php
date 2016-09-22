<?php

namespace App\Http\Controllers;

class Utilities extends Controller
{
    public static function concatenarDestinatarios($destinatarios) {
        $cadena = '';
        foreach ($destinatarios as $cliente) {
            $cadena .= '57'.$cliente['celular'].',';
        }
        return trim($cadena, ',');
    }
}