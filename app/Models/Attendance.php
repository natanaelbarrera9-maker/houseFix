<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    /**
     * Relación: Una asistencia pertenece a un Usuario.
     * Esto permite obtener el nombre del empleado (user.name) en la tabla del gerente.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}