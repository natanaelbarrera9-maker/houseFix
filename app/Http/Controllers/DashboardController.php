<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\InventoryMovement; // Necesario para calcular ventas
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. VENTAS HOY (Cantidad de productos vendidos)
        $salesTodayCount = InventoryMovement::whereDate('created_at', Carbon::today())
            ->where('type', 'salida') // Asumiendo que 'salida' es venta
            ->sum('quantity');

        // 2. INGRESOS HOY (Dinero aproximado: Cantidad * Precio Actual)
        // Nota: Esto usa el precio actual del producto.
        $incomeToday = InventoryMovement::whereDate('inventory_movements.created_at', Carbon::today())
            ->where('inventory_movements.type', 'salida')
            ->join('inventories', 'inventory_movements.inventory_id', '=', 'inventories.id')
            ->sum(DB::raw('inventory_movements.quantity * inventories.price'));

        // 3. STOCK CRÍTICO (Solo el número para la tarjeta roja)
        $lowStock = Inventory::where('quantity', '<=', 5)
            ->where('is_active', true)
            ->count();

        // 4. PERSONAL (Asistencias activas vs Total empleados)
        $activeStaff = Attendance::whereDate('check_in', Carbon::today())
            ->whereNull('check_out')
            ->count();
            
        $totalStaff = User::where('role_id', 2)->count(); // ID 2 = Empleados

        // 5. ACTIVIDAD RECIENTE (Para la tabla de abajo)
        $recentActivity = InventoryMovement::with(['inventory', 'user'])
            ->where('type', 'salida')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($movement) {
                return (object) [
                    'product_name' => $movement->inventory->name ?? 'Producto Eliminado',
                    'quantity' => $movement->quantity,
                    'user_name' => $movement->user->name ?? 'Usuario',
                    'created_at' => $movement->created_at
                ];
            });

        return view('dashboard', compact(
            'salesTodayCount',
            'incomeToday',
            'lowStock',
            'activeStaff',
            'totalStaff',
            'recentActivity'
        ));
    }
}