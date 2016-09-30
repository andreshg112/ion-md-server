<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;
    protected $table = 'planes';
    protected $fillable = ['nombre', 'cantidad_sms', 'cantidad_vendedores'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function establecimientos()
    {
        return $this->hasMany(Establecimiento::class);
    }
}