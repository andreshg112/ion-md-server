<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Cliente;
use DB;

class ClientesController extends Controller
{
    
    public function show($celular)
    {
        return Cliente::where('celular', $celular)->first();
    }
    
    public function index(Request $request)
    {
        $nombre_completo = $request->input('nombre_completo', '');
        //$establecimiento_id = $request->input('establecimiento_id', '');
        $administrador_id = $request->input('administrador_id', '');
        $limit = $request->get('limit');
        $consulta_base = Cliente::where('nombre_completo', 'like', "%$nombre_completo%")
        ->where('administrador_id', $administrador_id)
        ->orderBy('nombre_completo', 'asc');
        if(isset($limit) && is_numeric($limit)) {
            $consulta_base->limit($limit);
        }
        return $consulta_base->get();
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        $respuesta['result'] = false;
        DB::transaction(function() use($request, &$respuesta) {
            try {
                $rules = [
                'celular' => 'numeric|required_without:telefono|digits:10',
                'telefono' => 'numeric|required_without:celular|digits:7',
                'nombre_completo'  => 'required|string',
                'email' => 'email',
                'direccion_casa'  => 'string|required_without_all:direccion_oficina,direccion_otra',
                'direccion_oficina' => 'string|required_without_all:direccion_casa,direccion_otra',
                'direccion_otra' => 'string|required_without_all:direccion_oficina,direccion_casa',
                'fecha_nacimiento' => 'date',
                'genero' => 'in:masculino,femenino|required',
                'establecimiento_id' => 'required|exists:establecimientos,id'
                ];
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $respuesta['result'] = false;
                    $respuesta['validator'] = $validator->errors()->all();
                    $respuesta['mensaje'] = '¡Error!';
                } else {
                    $instancia = new Cliente($request->all());
                    $respuesta['result'] = $instancia->save();
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
}