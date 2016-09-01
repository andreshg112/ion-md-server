<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Cliente;

class ClientesController extends Controller
{
    
    public function show($celular)
    {
        return Cliente::where('celular', $celular)->first();
    }
    
    public function index(Request $request)
    {
        $nombre_completo = $request->input('nombre_completo', '');
        return Cliente::where('nombre_completo', 'like', "%$nombre_completo%")->limit(5)->get();
    }
}