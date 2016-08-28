<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Models\Calificacion;
use \stdClass;

class UsersController extends Controller
{
    
    public function get_tutores()
    {
        $respuesta = [];
        $respuesta['result'] = User::where('tipo_usuario', 'tutor')->with('programa')->get();
        if (count($respuesta['result']) == 0) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = "No hay registros.";
        }
        return $respuesta;
    }
    
    public function get_tutores_con_calificacion_alumno($alumno_id)
    {
        $respuesta = [];
        $respuesta['result'] = User::where('tipo_usuario', 'tutor')->with('programa')->get();
        foreach ($respuesta['result'] as $tutor) {
            $tutor->calificacion = Calificacion::where('alumno_id', $alumno_id)
            ->where('tutor_id', $tutor->id)->first();
        }
        if (count($respuesta['result']) == 0) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = "No hay registros.";
        }
        return $respuesta;
    }
    
    public function index()
    {
        $respuesta = [];
        $respuesta['result'] = User::where('tipo_usuario', '!=', 'administrador')->with('programa')->get();
        if (count($respuesta['result']) == 0) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = "No hay registros.";
        }
        return $respuesta;
    }
    
    public function login(Request $request)
    {
        $rules = [
        'email'      => 'required|string',
        'password'  => 'required|string',
        ];
        try {
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $respuesta['result'] = false;
                $respuesta['validator'] = $validator->errors()->all();
                $respuesta['mensaje'] = '¡Error!';
            } else {
                $recibido = $request->all();
                $instancia = User::where('email', $recibido['email'])->first();
                $respuesta = [];
                if ($instancia) {
                    if ($instancia->password == $recibido['password']) {
                        $respuesta['result'] = $instancia;
                        $respuesta['mensaje'] = "¡Bienvenido(a) ".$instancia->primer_nombre."!";
                    } else {
                        $respuesta['result'] = false;
                        $respuesta['mensaje'] = "Contraseña incorrecta.";
                    }
                } else {
                    $respuesta['result'] = false;
                    $respuesta['mensaje'] = "El email ingresado no se encuentra registrado.";
                }
            }
        } catch (Exception $e) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = "Error: ".$e;
        }
        return $respuesta;
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        if (!is_array($request->all())) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = "Los datos enviados no tienen el formato correcto.";
        } else {
            $rules = [
            'tipo_documento'      => 'required|string|in:CC,CE,TI',
            'numero_documento'      => 'required|unique:users|numeric',
            'primer_nombre'      => 'required|string',
            'primer_apellido'  => 'required|string',
            'genero'  => 'required|string|in:masculino,femenino,otro',
            'tipo_usuario'  => 'required|string|in:tutor,alumno',
            'programa_id'  => 'required|exists:programas,id',
            'email'  => 'required|email|unique:users',
            'password'  => 'required|string',
            ];
            try {
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = "¡Error!";
                } else {
                    $instancia = new User($request->all());
                    $respuesta['result'] = $instancia->save();
                    if ($respuesta['result']) {
                        $respuesta['mensaje'] = "Registrado correctamente.";
                        $respuesta['result'] = $instancia;
                    } else {
                        $respuesta['mensaje'] = "No se pudo registrar.";
                    }
                }
            } catch (Exception $e) {
                $respuesta['result'] = false;
                $respuesta['mensaje'] = "Error:".$e;
            }
        }
        return $respuesta;
    }
    
}