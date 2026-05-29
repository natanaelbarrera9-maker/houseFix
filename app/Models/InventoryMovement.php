<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $guarded = []; // Permitimos asignación masiva para agilizar el desarrollo

    // Relación: Un movimiento pertenece a un Producto
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Relación: Un movimiento fue hecho por un Usuario (Vendedor/Admin)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}