<?php

namespace App\Models\Usuario;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioModel extends Model
{
    protected $table      = 'upbUsuarios';
    protected $primaryKey = 'usuID';
    public $timestamps    = true;

    protected $fillable = [
        'usuID',
        'usuNombre',
        'usuApellido',
        'usuCelular',
        'usuFechaNacimiento',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuID', 'id');
    }

    /**
     * Crea o actualiza el perfil de usuario durante el checkout.
     * Si el usuario no existe se genera con contraseña = celular (debe cambiarse en el primer login).
     */
    public static function crearDesdeCheckout(array $datos): self
    {
        $user    = Auth::check() ? Auth::user() : User::where('email', $datos['email'])->first();
        $esNuevo = !$user;

        if ($esNuevo) {
            // Contraseña inicial = celular — el usuario debe cambiarla en el primer login
            $user = User::create([
                'name'     => $datos['nombre'] . ' ' . $datos['apellido'],
                'email'    => $datos['email'],
                'password' => Hash::make($datos['celular']),
            ]);
        }

        $perfil = self::find($user->id);

        if (!$perfil) {
            $perfil          = new self();
            $perfil->usuID   = $user->id;
            $perfil->usuNombre          = $datos['nombre'];
            $perfil->usuApellido        = $datos['apellido'];
            $perfil->usuCelular         = $datos['celular'];
            $perfil->usuFechaNacimiento = $datos['fecha_nacimiento'] ?? null;
            $perfil->save();
        } else {
            if ($perfil->usuCelular !== ($datos['celular'] ?? '')) {
                $perfil->usuCelular = $datos['celular'];
                $perfil->save();
            }
        }

        return $perfil;
    }
}
