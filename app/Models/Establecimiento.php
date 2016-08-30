<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    
    public function user() {
        return $this->hasMany(User::class);
    }
    
}