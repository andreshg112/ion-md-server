<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Models\User;
use App\Models\Administrador;

class UsersController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $rol = $request->input('rol', null);
        //$super_user_id = $request->input('user_id', null);
        if(is_null($rol)){
            return User::whereIn('rol', ['ADMIN', 'VENDEDOR'])->get();
        } elseif($rol == 'ADMIN') {
            return User::with('administrador')->where('rol', $rol)->has('administrador')->get();
        } else {
            $sin_sede = $request->input('sin_sede', false);
            if($sin_sede){
                return User::where('rol', $rol)->has('vendedor', '=', 0)->get();
            }
            return User::where('rol', $rol)->get();
        }
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, &$respuesta) {
            try {
                $rules = [
                'username'      => 'string|unique:users|required',
                'email'      => 'email|unique:users',
                'password'  => 'string|confirmed|required',
                'primer_nombre' => 'string|required',
                'segundo_nombre' => 'string',
                'primer_apellido' => 'string|required',
                'segundo_apellido' => 'string',
                'genero' => 'in:masculino,femenino,otro|required',
                'rol' => 'in:ADMIN,VENDEDOR|required',
                'registrado_por' => 'exists:super_users,user_id|required'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $user = new User($request->all());
                    $user->password = password_hash($user->password, PASSWORD_DEFAULT);
                    $respuesta['result'] = $user->save();
                    if ($respuesta['result']) {
                        $respuesta['mensaje'] = "Registrado correctamente.";
                    } else {
                        $respuesta['mensaje'] = "No se pudo registrar.";
                    }
                }
            } catch (Exception $e) {
                $respuesta['mensaje'] = $e->getMessage();
            }
        });
        return $respuesta;
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, $id, &$respuesta) {
            try {
                $rules = [
                'username'      => "string|unique:users,username,$id|required",
                'email'      => 'email|unique:users,email,'.$id,
                'password'  => 'string|confirmed|required',
                'primer_nombre' => 'string|required',
                'segundo_nombre' => 'string',
                'primer_apellido' => 'string|required',
                'segundo_apellido' => 'string',
                'genero' => 'in:masculino,femenino,otro|required',
                'rol' => 'in:ADMIN,VENDEDOR|required',
                'registrado_por' => 'exists:super_users,user_id|required'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $datos = $request->all();
                    $user = User::find($id);
                    $user->fill($datos);
                    $user->password = password_hash($datos['password'], PASSWORD_DEFAULT);
                    $respuesta['result'] = $user->save();
                    if ($respuesta['result']) {
                        $respuesta['mensaje'] = "Modificado correctamente.";
                    } else {
                        $respuesta['mensaje'] = "No se pudo registrar.";
                    }
                }
            } catch (Exception $e) {
                $respuesta['mensaje'] = $e->getMessage();
            }
        });
        return $respuesta;
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $instancia = User::find($id);
        $respuesta = [];
        if ($instancia) {
            $instancia->delete();
            $respuesta['result'] = $instancia->trashed();
            if ($respuesta['result']) {
                $respuesta['mensaje'] = "Usuario desactivado correctamente.";
                $respuesta['eliminado'] = $instancia;
            } else {
                $respuesta['mensaje'] = "Error tratando de desactivar.";
            }
        } else {
            $respuesta['mensaje'] = "No se encuentra registrado.";
        }
        return $respuesta;
    }
}