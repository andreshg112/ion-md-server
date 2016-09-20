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
    
    public function vendedores() {
        return $this->hasMany(Vendedor::class);
    }
    
    public function delete()
    {
        $this->vendedores()->delete();
        return parent::delete();
    }
    
    /*public function save(array $options = [])
    {
        $saved = false;
        $parent_saved = parent::save();
        if($parent_saved){
            print_r($this->vendedores);
            if(count($this->vendedores) > 0){
                $vendedores_saved = $this->vendedores()->saveMany(
                array_map(function ($user) {
                    return new Vendedor(['user_id' => $user['id']]);
                },
                $this->vendedores)
                );
                if($vendedores_saved){
                    $saved = true;
                } else {
                    $this->delete();
                }
            } else {
                $saved = true;
            }
        }
        return $saved;
    }*/
}