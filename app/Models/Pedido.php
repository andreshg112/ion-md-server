<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* Un pedido lo realiza un User de una Sede.
* Los clientes de una Marca deben ser compartidos, es decir,
* aparecer en las busquedas de sus sedes.
* Para ello se consulta a los clientes que tienen pedidos en un establecimiento.
* Si se quiere consultar los pedidos en cola de una sede, se consulta a traves del user.
* Ya que el User pertenece a una Sede especificada en sus atributos.
*/
class Pedido extends Model
{
    use SoftDeletes;
    protected $table = 'pedidos';
    protected $fillable = ['cliente_id', 'detalles', 'direccion', 'numero', 'enviado', 'vendedor_id', 'created_at', 'user_id', 'tiempo_despacho'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
}