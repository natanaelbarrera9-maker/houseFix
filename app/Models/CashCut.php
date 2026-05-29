<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashCut extends Model
{
    protected $guarded = [];

    // Relación con el usuario que hizo el corte
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}