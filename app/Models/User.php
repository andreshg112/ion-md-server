<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* La clase User representa un usuario de una sede (sede es un atributo, no una clase).
* Un User de una sede, pertenece a una Marca (Establecimiento).
* Podran haber varios Users en una sede, que se identificaran por el atributo sede.
* Si tienen igual valor en el atributo sede, pertenecen a una misma sede.
* El atributo tipo_usuario se usa para almacenar el rol ADMIN o EMPLEADO, de una sede.
* Actualmente el usuario representa la sede.
*/
class User extends Authenticatable
{
    use SoftDeletes;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
    'email', 'password', 'tipo_documento', 'numero_documento', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'genero', 'tipo_usuario', 'sede_id'
    ];
    
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
    'password', 'remember_token',
    ];
    
    public function establecimiento() {
        return $this->belongsTo(Establecimiento::class);
    }

    public function sede() {
        return $this->belongsTo(Sede::class);
    }
    
}