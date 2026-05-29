<?php

namespace App\Http\Controllers;

use App\Models\CashCut;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CashCutController extends Controller
{
    /**
     * Muestra el historial de cortes.
     */
    public function index()
    {
        // Solo el gerente debería ver el historial completo
        if (Auth::user()->role_id !== 1) {
            return redirect()->route('dashboard');
        }

        $cuts = CashCut::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('cash_cuts.index', compact('cuts'));
    }

    /**
     * Muestra la pantalla para HACER el corte del día.
     */
    public function create()
    {
        // 1. Calcular Ventas de HOY
        // Sumamos (Cantidad * Precio) de todas las salidas de hoy
        $salesToday = InventoryMovement::whereDate('inventory_movements.created_at', Carbon::today())
            ->where('inventory_movements.type', 'salida')
            ->join('inventories', 'inventory_movements.inventory_id', '=', 'inventories.id')
            ->sum(DB::raw('inventory_movements.quantity * inventories.price'));

        return view('cash_cuts.create', compact('salesToday'));
    }

    /**
     * Guarda el corte en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sales_amount' => 'required|numeric',
            'expenses_amount' => 'required|numeric',
            'counted_amount' => 'required|numeric',
        ]);

        // Cálculo final
        $expected = $request->sales_amount - $request->expenses_amount;
        $difference = $request->counted_amount - $expected;

        CashCut::create([
            'user_id' => Auth::id(),
            'sales_amount' => $request->sales_amount,
            'expenses_amount' => $request->expenses_amount,
            'expected_amount' => $expected,
            'counted_amount' => $request->counted_amount,
            'difference' => $difference,
            'notes' => $request->notes
        ]);

        return redirect()->route('cash_cuts.index')->with('success', 'Corte de caja registrado correctamente.');
    }
}