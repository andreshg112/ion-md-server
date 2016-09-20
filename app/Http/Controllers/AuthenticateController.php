<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use DB;
//use Illuminate\Support\Facades\Hash;

class AuthenticateController extends Controller
{
    public function __construct()
    {
        $this->middleware('cors');
        $this->middleware('jwt.auth', ['except' => ['authenticate']]);
    }
    
    public function authenticate(Request $request)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, &$respuesta) {
            try {
                $rules = [
                'username'      => 'required|string',
                'password'  => 'required|string',
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $credentials = $request->only('username', 'password');
                    $user = User::where('username', $credentials['username'])->first();
                    if ($user) {
                        if (password_verify($credentials['password'], $user->password)) {
                            $user->token = JWTAuth::fromUser($user);
                            if($user->rol == 'VENDEDOR') {
                                $user->load(['vendedor', 'vendedor.sede', 'vendedor.sede.establecimiento']);
                                if(!isset($user->vendedor)) {
                                    $user = false;
                                    $respuesta['mensaje'] = 'El vendedor no tiene una sede asignada.';
                                }
                            } elseif ($user->rol == 'ADMIN') {
                                $user->load(['administrador', 'administrador.establecimientos', 'administrador.establecimientos.sedes']);
                            }
                            $respuesta['result'] = $user;
                        } else {
                            $respuesta['mensaje'] = 'Contraseña incorrecta.';
                        }
                    } else {
                        $respuesta['mensaje'] = 'El usuario ingresado no se encuentra registrado.';
                    }
                }
            } catch (Exception $e) {
                $respuesta['mensaje'] = $e;
            }
        });
        return $respuesta;
    }
}