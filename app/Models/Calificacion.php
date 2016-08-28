<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calificacion extends Model
{
    use SoftDeletes;
    protected $table = 'calificaciones';
    protected $fillable = ['alumno_id', 'tutor_id', 'nota', 'observaciones'];
    
}