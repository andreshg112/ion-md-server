<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* Clase de las ofertas que envia un administrador.
*/
class Oferta extends Model
{
    use SoftDeletes;
    protected $table = 'ofertas';
    protected $fillable = ['mensaje', 'administrador_id'];
    
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class)->withTimestamps();
    }
    
    public function administrador() {
        return $this->belongsTo(Administrador::class);
    }
}