<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;
    protected $table = 'productos';
    protected $fillable = ['nombre', 'valor'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }
    
    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class);
    }
}