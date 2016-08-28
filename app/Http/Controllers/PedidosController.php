<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Cliente;
use App\Models\Pedido;
use \DB;

class PedidosController extends Controller
{
    
    public function index()
    {
        return Pedido::all();
    }
    
    public function store(Request $request)
    {
        $respuesta = [];
        DB::transaction(function () use($request, &$respuesta) {
            if (!is_array($request->all())) {
                $respuesta['result'] = false;
                $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
            } else {
                $rules = [
                'celular' => 'required|numeric',
                'nombres'  => 'required|string',
                'apellidos'  => 'required|string',
                'email'  => 'email',
                'pedido.detalles'  => 'required|string',
                'pedido.dia'  => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado,domingo'
                ];
                try {
                    $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        $respuesta['result'] = false;
                        $respuesta['validator'] = $validator->errors()->all();
                        $respuesta['mensaje'] = '¡Error!';
                    } else {
                        $datos = $request->all();
                        $pedido = $datos['pedido'];
                        unset($datos['pedido']);
                        $cliente = Cliente::firstOrNew(['celular' => $datos['celular']]);
                        $cliente->fill($datos)->save();
                        $respuesta['result'] = $cliente->pedidos()->save(new Pedido($pedido));
                        if ($respuesta['result']) {
                            $respuesta['mensaje'] = "Registrado correctamente.";
                            $respuesta['result'] = $pedido;
                        } else {
                            $respuesta['mensaje'] = "No se pudo registrar.";
                        }
                    }
                } catch (Exception $e) {
                    $respuesta['result'] = false;
                    $respuesta['mensaje'] = "Error: $e";
                }
            }
        });
        return $respuesta;
    }
    
    public function update(Request $request, $id)
    {
        $respuesta = [];
        if (!is_array($request->all())) {
            $respuesta['result'] = false;
            $respuesta['mensaje'] = 'Los datos enviados no tienen el formato correcto.';
        } else {
            $instancia = Horario::find($id);
            if ($instancia) {
                $instancia->fill($request->all());
                $rules = [
                'materia_id'      => 'required|exists:materias,id',
                'tutor_id'  => 'required|exists:users,id,tipo_usuario,tutor',
                'dia'  => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado',
                'hora_inicio'  => 'required',
                'hora_fin'  => 'required'
                ];
                try {
                    $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        $respuesta['result'] = false;
                        $respuesta['validator'] = $validator->errors()->all();
                        $respuesta['mensaje'] = '¡Error!';
                    } else {
                        $respuesta['result'] = $instancia->save();
                        if ($respuesta['result']) {
                            $respuesta['mensaje'] = "Actualizado correctamente.";
                            $respuesta['result'] = $instancia;
                        } else {
                            $respuesta['mensaje'] = "No se pudo actualizar.";
                        }
                    }
                } catch (Exception $e) {
                    $respuesta['result'] = false;
                    $respuesta['mensaje'] = "Error: $e";
                }
            } else {
                $respuesta['result'] = false;
                $respuesta['mensaje'] = 'No se encuentra registrado.';
            }
        }
        return $respuesta;
    }
    
    public function show($id)
    {
        return Horario::findOrFail($id);
    }
    
}