<?php

namespace App\Http\Controllers;

use DateTime;

class Utilities extends Controller
{
    public static function concatenarDestinatarios($destinatarios) {
        $cadena = '';
        foreach ($destinatarios as $cliente) {
            $cadena .= '57'.$cliente['celular'].',';
        }
        return trim($cadena, ',');
    }
    
    
    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}