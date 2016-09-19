<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
*
*/
class User extends Authenticatable
{
    use SoftDeletes;
    
    protected $fillable = [
    'username', 'email', 'password', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'genero', 'rol'
    ];
    
    protected $hidden = [
    'password', 'remember_token',
    ];
    
    public function superUser() {
        return $this->hasOne(SuperUser::class);
    }
    
    public function administrador() {
        return $this->hasOne(Administrador::class);
    }
    
    public function vendedor() {
        return $this->hasOne(Vendedor::class);
    }
    
    /**
    * Elimina al user, y con el, sus relaciones directas.
    * @return bool|null
    */
    public function delete()
    {
        $this->administrador()->delete();
        $this->vendedor()->delete();
        return parent::delete();
    }
    
    /**
    * Guarda al user. Si es ADMIN, lo guarda tambien en la tabla administradores.
    * @return bool
    */
    public function save(array $options = [])
    {
        $saved = false;
        $parent_saved = parent::save();
        if($parent_saved){
            if($this->rol == 'ADMIN') {
                $admin = new Administrador(['user_id' => $this->id]);
                $admin_saved = $admin->save();
                if(!$admin_saved) {
                    $this->delete();
                } else {
                    $saved = true;
                }
            } else {
                $saved = true;
            }
        }
        return $saved;
    }    
}