<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Tablero de Taller (Vista Kanban)
     */
    public function index()
    {
        // Obtenemos solo los servicios activos (no entregados ni cancelados)
        // para el tablero principal.
        $services = Service::with('client')
            ->whereNotIn('status', ['entregado', 'cancelado'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Agrupamos por etapas para las columnas
        $columns = [
            'pendientes' => $services->where('status', 'recibido'),
            'en_proceso' => $services->whereIn('status', ['revision', 'espera_refaccion']),
            'listos'     => $services->where('status', 'reparado'),
        ];

        return view('services.index', compact('columns'));
    }

    /**
     * Formulario de Nueva Orden
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Guardar Nuevo Servicio
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'device_type' => 'required|string',
            'brand_model' => 'required|string',
            'issue_description' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buscar o Crear Cliente (por teléfono)
            $client = Client::firstOrCreate(
                ['phone' => $request->client_phone],
                ['name' => $request->client_name, 'email' => $request->client_email]
            );

            // 2. Crear la Orden de Servicio
            Service::create([
                'client_id' => $client->id,
                'user_id'   => Auth::id(), // Técnico que recibe
                'device_type' => $request->device_type,
                'brand_model' => $request->brand_model,
                'issue_description' => $request->issue_description,
                'status' => 'recibido',
                'received_date' => now(),
            ]);
        });

        return redirect()->route('services.index')->with('success', 'Orden de servicio generada correctamente.');
    }

    /**
     * Actualizar Estado (Mover tarjeta)
     */
    public function updateStatus(Request $request, Service $service)
    {
        $request->validate(['status' => 'required']);
        
        $service->update(['status' => $request->status]);

        if ($request->status === 'entregado') {
            $service->update(['delivered_date' => now()]);
        }

        return back()->with('success', 'Estado actualizado.');
    }
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }
}