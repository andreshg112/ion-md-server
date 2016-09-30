<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Plan;

class PlanesController extends Controller
{
    public function index()
    {
        return Plan::all();
    }
}