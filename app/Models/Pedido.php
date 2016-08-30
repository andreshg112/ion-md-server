<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;
    protected $table = 'pedidos';
    protected $fillable = ['cliente_id', 'detalles', 'enviado', 'establecimiento_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}