<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Producto;
use App\Models\Vendedor;
use DB;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function getProductosEstablecimiento(Request $request, $vendedor_id, $establecimiento_id)
    {
        $vendedor = Vendedor::find($vendedor_id);
        if(isset($vendedor) && $vendedor->sede->establecimiento->id == $establecimiento_id) {
            $consulta_base = Producto::where('sede_id', $vendedor->sede_id);
            return $consulta_base->get();
        } else {
            return response()->json(['mensaje' => 'No autorizado.'], 403);
        }
    }
}