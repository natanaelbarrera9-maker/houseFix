<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

    // Relación con el Cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relación con el Técnico (Usuario)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper para colores de estado (Diseño Serio)
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'recibido' => 'bg-slate-100 text-slate-700 border-slate-200',
            'revision' => 'bg-blue-50 text-blue-700 border-blue-200',
            'espera_refaccion' => 'bg-amber-50 text-amber-700 border-amber-200',
            'reparado' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'entregado' => 'bg-gray-50 text-gray-500 border-gray-200',
            'cancelado' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-slate-100 text-slate-700'
        };
    }

    // Helper para nombres legibles
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'recibido' => 'Por Revisar',
            'revision' => 'En Diagnóstico',
            'espera_refaccion' => 'Esperando Pieza',
            'reparado' => 'Listo para Entregar',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
            default => ucfirst($this->status)
        };
    }
}