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
    
}