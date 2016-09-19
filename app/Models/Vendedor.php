<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* El dueno de los establecimientos
*/
class Vendedor extends Authenticatable
{
    use SoftDeletes;
    protected $table = 'vendedores';
    protected $fillable = ['user_id', 'sede_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function pedidos()
    {
        return $this->hasMany(Pedidos::class);
    }
    
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}