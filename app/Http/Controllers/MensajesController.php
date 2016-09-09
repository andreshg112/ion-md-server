<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class MensajesController extends Controller
{
    
    /*public static function enviarMensaje($numero, $mensaje)
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
    "numregion" => "57",
    "sandbox" => 1
    )
    )
    );
    $respuesta = curl_exec($ch);
    curl_close($ch);
    $respuesta = json_decode($respuesta);
    return $respuesta->mensaje;
    }*/
    
    public static function enviarMensaje($numero, $mensaje)
    {
        return MensajesController::AltiriaSMS('57'.$numero, $mensaje);
    }
    
    // sDestination: lista de numeros de telefono separados por comas.
    // Cada numero debe comenzar por el prefijo internacional de pais.
    // sMessage: hasta 160 caracteres
    // sSenderId: remitente autorizado por Altiria al dar de alta el servicio.
    // Omitir el parametro si no se cuenta con ninguno.
    // debug: Si es true muestra por pantalla la respuesta completa del servidor
    // XX, YY y ZZ se corresponden con los valores de identificacion del
    // usuario en el sistema.
    // Como ejemplo la peticion se envia a www.altiria.net/sustituirPOSTsms
    // Se debe reemplazar la cadena '/sustituirPOSTsms' por la parte correspondiente
    // de la URL suministrada por Altiria al dar de alta el servicio
    private static function AltiriaSMS($sDestination, $sMessage, $debug=false){
        $sData ='cmd=sendsms&';
        $sData .='domainId=unicesar&';
        $sData .='login=marthabonilla@unicesar.edu.co&';
        $sData .='passwd=uni16ces9ar&';
        // Omitir la linea del remitente si no se cuenta con ninguno autorizado
        //$sData .='senderId='.$sSenderId.'&';
        $sData .='dest='.str_replace(',','&dest=',$sDestination).'&';
        $sData .='msg='.urlencode(utf8_encode(substr($sMessage,0,160)));
        //Tiempo maximo de espera para conectar con el servidor = 5 seg
        $timeOut = 5;
        $fp = fsockopen('www.altiria.net', 80, $errno, $errstr, $timeOut);
        if (!$fp) {
            //Error de conexion o tiempo maximo de conexion rebasado
            $output = "ERROR de conexion: $errno - $errstr<br />\n";
            $output .= "Compruebe que ha configurado correctamente la direccion/url ";
            $output .= "suministrada por altiria<br>";
            return $output;
        } else {
            // Reemplazar la cadena '/sustituirPOSTsms' por la parte correspondiente
            // de la URL suministrada por Altiria al dar de alta el servicio
            $buf = "POST /api/http HTTP/1.0\r\n";
            $buf .= "Host: www.altiria.net\r\n";
            $buf .= "Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\n";
            $buf .= "Content-length: ".strlen($sData)."\r\n";
            $buf .= "\r\n";
            $buf .= $sData;
            fputs($fp, $buf);
            $buf = "";
            //Tiempo maximo de espera de respuesta del servidor = 60 seg
            $responseTimeOut = 60;
            stream_set_timeout($fp,$responseTimeOut);
            stream_set_blocking ($fp, true);
            if (!feof($fp)){
                if (($buf=fgets($fp,128))===false){
                    // TimeOut?
                    $info = stream_get_meta_data($fp);
                    if ($info['timed_out']){
                        $output = 'ERROR Tiempo de respuesta agotado';
                        return $output;
                    } else {
                        $output = 'ERROR de respuesta';
                        return $output;
                    }
                } else{
                    while(!feof($fp)){
                        $buf.=fgets($fp,128);
                    }
                }
            } else {
                $output = 'ERROR de respuesta';
                return $output;
            }
            fclose($fp);
            //Si la llamada se hace con debug, se muestra la respuesta completa del servidor
            if ($debug){
                print "Respuesta del servidor: <br>".$buf."<br>";
            }
            //Se comprueba que se ha conectado realmente con el servidor
            //y que se obtenga un codigo HTTP OK 200
            if (strpos($buf,"HTTP/1.1 200 OK") === false){
                $output = "ERROR. Codigo error HTTP: ".substr($buf,9,3)."<br />\n";
                $output .= "Compruebe que ha configurado correctamente la direccion/url ";
                $output .= "suministrada por Altiria<br>";
                return $output;
            }
            //Se comprueba la respuesta de Altiria
            if (strstr($buf,"ERROR")){
                $output = $buf."<br />\n";
                $output .= " Ha ocurrido algun error. Compruebe la especificacion<br>";
                return $output;
            } else {
                $output = $buf."<br />\n";
                $output .= " Exito<br>";
                return $output;
            }
        }
    }
    
}