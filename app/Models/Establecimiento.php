<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* La clase Establecimiento representa una Marca, por ejemplo ArteSano, Pizza Station,
* que puede tener una o varias sedes (user).
* Un Establecimiento (Marca) tiene un Dueno.
* El Dueno puede tener varias Marcas (Establecimientos).
*/
class Establecimiento extends Model
{
    use SoftDeletes;
    protected $table = 'establecimientos';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['nombre'];
    
    public function sedes() {
        return $this->hasMany(Sede::class);
    }
    
    public function users() {
        return $this->hasMany(User::class);
    }
    
}