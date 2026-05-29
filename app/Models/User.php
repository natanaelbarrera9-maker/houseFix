<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden llenar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'two_factor_code',      // <--- Agregado
        'two_factor_expires_at', // <--- Agregado
    ];

    /**
     * Los atributos ocultos para la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_expires_at' => 'datetime', // <--- Importante para que funcione la fecha
        ];
    }

    /**
     * Verifica si el usuario es Gerente (ID 1)
     */
    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    // --- FUNCIONES DE SEGURIDAD 2FA (LAS QUE FALTABAN) ---

    /**
     * Genera un código de 6 dígitos y lo guarda en la BD
     */
    public function generateTwoFactorCode()
    {
        $this->timestamps = false; // No queremos cambiar la fecha de "updated_at"
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10); // Expira en 10 mins
        $this->save();
    }

    /**
     * Limpia el código cuando el usuario ya entró correctamente
     */
    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }
}