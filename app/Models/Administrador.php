<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* El dueno de los establecimientos
*/
class Administrador extends Authenticatable
{
    use SoftDeletes;
    protected $table = 'administradores';
    protected $fillable = ['user_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function establecimientos()
    {
        return $this->hasMany(Establecimiento::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}