<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class usuarios extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario'; 

    
    protected $fillable = [
        'user_nombre',
        'user_apellido',
        'user_gmail',
        'user_password',
        'user_tipo',
    ];

    protected $hidden = [
        'user_password',
    ];

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }
    
    public $timestamps = false;
}
