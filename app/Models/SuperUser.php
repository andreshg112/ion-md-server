<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* El usuario que registra los demas usuarios.
*/
class SuperUser extends Authenticatable
{
    use SoftDeletes;
    protected $table = 'super_users';
    protected $fillable = ['user_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}