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
    protected $fillable = ['nombre', 'mensaje', 'administrador_id', 'plan_id'];
    
    public function administrador()
    {
        return $this->belongsTo(Administrador::class);
    }
    
    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }
    
    public function vendedores()
    {
        return $this->hasManyThrough(Vendedor::class, Sede::class);
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
    
    public static function restarSMS($establecimiento_id) {
        $instancia = Establecimiento::find($establecimiento_id);
        $instancia->sms_restantes--;
        if($instancia->save()){
            return $instancia->sms_restantes;
        } else {
            
        }
    }
}