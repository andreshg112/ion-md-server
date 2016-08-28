<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencia extends Model
{
    use SoftDeletes;
    protected $table = 'asistencias';
    protected $fillable = ['horario_id', 'alumno_id', 'temas_tutoriados', 'fecha'];
    
    public function alumno()
    {
        return $this->belongsTo(User::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }
}