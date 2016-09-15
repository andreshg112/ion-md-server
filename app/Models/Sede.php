<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* La clase Sede representa las sedes fisicas (locales) de una marca.
* Por ejemplo, Cosechas de Los Mayales.
* La Sede pertenece a un Establecimiento, que puede tener varias sedes.
*/
class Sede extends Model
{
    use SoftDeletes;
    protected $table = 'sedes';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['nombre', 'establecimiento_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    
    public function establecimiento() {
        return $this->belongsTo(Establecimiento::class);
    }
    
    public function users() {
        return $this->hasMany(User::class);
    }
    
}