<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class MensajesController extends Controller
{
    
    public static function enviarMensaje($numero, $mensaje)
    {
        $mensaje  = str_replace(" ", "%20", $mensaje);
        curl_setopt_array($ch = curl_init(), array(
        CURLOPT_URL => "http://panel.smasivos.com/api.envio.new.php",
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_POSTFIELDS => array(
        "apikey" => "8d8f85de70729f67238d14f4dff1188d687d2565",
        "mensaje" => $mensaje,
        "numcelular" => $numero,
        "numregion" => "57"
        )
        )
        );
        $respuesta = curl_exec($ch);
        curl_close($ch);
        $respuesta = json_decode($respuesta);
        return $respuesta->mensaje;
    }
    
}