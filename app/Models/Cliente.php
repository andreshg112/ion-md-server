<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;
    protected $table = 'clientes';
    protected $fillable = ['administrador_id', 'celular', 'telefono', 'nombre_completo', 'email', 'direccion_casa', 'direccion_oficina', 'direccion_otra', 'fecha_nacimiento', 'establecimiento_id', 'genero'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }
    
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
    
}