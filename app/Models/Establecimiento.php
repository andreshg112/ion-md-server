<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* La clase Establecimiento representa una Marca, por ejemplo ArteSano, Pizza Station,
* que puede tener una o varias sedes.
* Un Establecimiento (Marca) tiene un Dueno (administrador).
* El Dueno puede tener varias Marcas (Establecimientos).
*/
class Establecimiento extends Model
{
    use SoftDeletes;
    protected $table = 'establecimientos';
    protected $fillable = ['nombre', 'mensaje', 'administrador_id'];
    
    public function sedes() {
        return $this->hasMany(Sede::class);
    }
    
    public function administrador() {
        return $this->belongsTo(Administrador::class);
    }
    
    /**
    * Elimina al establecimiento, y con el, sus relaciones directas.
    * @return bool|null
    */
    public function delete()
    {
        $this->sedes()->delete();
        return parent::delete();
    }
    
}