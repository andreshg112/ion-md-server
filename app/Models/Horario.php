<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horario extends Model
{
    use SoftDeletes;
    protected $table = 'horarios';
    protected $fillable = ['materia_id', 'tutor_id', 'dia', 'hora_inicio', 'hora_fin'];
    
    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
    
    public function tutor()
    {
        return $this->belongsTo(User::class);
    }
}