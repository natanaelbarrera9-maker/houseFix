<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class InventoryController extends Controller
{
    /**
     * Muestra la lista de productos (Corregido para enviar $inventories).
     */
    public function index(Request $request)
    {
        $query = Inventory::where('is_active', true);

        // Lógica de búsqueda
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('sku', 'LIKE', '%' . $request->search . '%');
            });
        }

        // CORRECCIÓN CLAVE: Usamos el nombre $inventories que espera tu vista
        $inventories = $query->orderBy('name', 'asc')->paginate(10);

        return view('inventory.index', compact('inventories'));
    }

    /**
     * Muestra el formulario para CREAR (Esta función te faltaba).
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Guarda el nuevo producto en la BD.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'sku'       => 'nullable|string|max:50',
            'price'     => 'required|numeric|min:0',
            'quantity'  => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:1',
            'description' => 'nullable|string'
        ]);

        try {
            DB::transaction(function () use ($data) {
                $product = Inventory::create([
                    'name'        => $data['name'],
                    'description' => $data['description'] ?? null,
                    'quantity'    => $data['quantity'],
                    'price'       => $data['price'],
                    'min_stock'   => $data['min_stock'] ?? 5,
                    'is_active'   => true
                ]);

                // Registro inicial
                InventoryMovement::create([
                    'inventory_id' => $product->id,
                    'user_id'      => Auth::id(),
                    'type'         => 'entrada',
                    'quantity'     => $data['quantity'],
                    'reason'       => 'Inventario Inicial'
                ]);
            });

            return redirect()->route('inventory.index')->with('success', 'Producto creado exitosamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al crear producto: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para EDITAR (Esta función te faltaba).
     */
    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Actualiza el producto en la BD (Esta función te faltaba).
     */
    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'sku'       => 'nullable|string|max:50',
            'price'     => 'required|numeric|min:0',
            'quantity'  => 'required|integer|min:0',
            'description' => 'nullable|string'
        ]);

        // Detectar si hubo cambio manual de stock para registrar el movimiento (ajuste)
        $stockDifference = $data['quantity'] - $inventory->quantity;

        try {
            DB::transaction(function () use ($inventory, $data, $stockDifference) {
                // Actualizamos datos básicos
                $inventory->update([
                    'name'        => $data['name'],
                    'description' => $data['description'] ?? null,
                    'price'       => $data['price'],
                    'quantity'    => $data['quantity'], // Actualizamos stock directo
                    'sku'         => $data['sku'] ?? null,
                ]);

                // Si cambiaron la cantidad manualmente, guardamos historial de "ajuste"
                if ($stockDifference != 0) {
                    InventoryMovement::create([
                        'inventory_id' => $inventory->id,
                        'user_id'      => Auth::id(),
                        'type'         => $stockDifference > 0 ? 'entrada' : 'salida', // Ajuste positivo o negativo
                        'quantity'     => abs($stockDifference),
                        'reason'       => 'Ajuste manual desde edición'
                    ]);
                }
            });

            return redirect()->route('inventory.index')->with('success', 'Producto actualizado correctamente.');

        } catch (Exception $e) {
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Elimina el producto (Esta función te faltaba).
     */
    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        
        // Soft delete (desactivar) en lugar de borrar físico para no romper historial
        $inventory->update(['is_active' => false]);

        return redirect()->route('inventory.index')->with('success', 'Producto eliminado del catálogo.');
    }
}