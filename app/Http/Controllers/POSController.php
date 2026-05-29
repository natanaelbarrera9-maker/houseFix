<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class POSController extends Controller
{
    public function index()
    {
        // CORRECCIÓN: Quitamos 'sku' para que no falle con la base de datos actual
        $products = Inventory::where('is_active', true)
            ->where('quantity', '>', 0)
            ->get(['id', 'name', 'price', 'quantity']); 

        return view('pos.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:inventories,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->cart as $item) {
                    $product = Inventory::lockForUpdate()->find($item['id']);

                    if ($product->quantity >= $item['quantity']) {
                        $product->decrement('quantity', $item['quantity']);

                        InventoryMovement::create([
                            'inventory_id' => $product->id,
                            'user_id'      => Auth::id(),
                            'type'         => 'salida',
                            'quantity'     => $item['quantity'],
                            'reason'       => 'Venta POS #' . time(),
                        ]);
                    } else {
                        throw new Exception("Stock insuficiente para: " . $product->name);
                    }
                }
            });

            return response()->json(['success' => true, 'message' => 'Venta registrada correctamente']);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}