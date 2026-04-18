<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Model;

class UsuarioModel extends Model
{
    protected $table = 'upbUsuarios';
    protected $primaryKey = 'usuID';
    public $timestamps = true;

    protected $fillable = [
        'usuID',
        'usuNombre',
        'usuApellido',
        'usuCelular',
        'usuFechaNacimiento',
    ];

    /**
     * Relación con users (Laravel default)
     * PK compartida
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'usuID', 'id');
    }
}
