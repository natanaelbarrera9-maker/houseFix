<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Muestra el formulario para registrar empleado.
     */
    public function create()
    {
        // Seguridad: Solo gerente
        if (Auth::user()->role_id !== 1) { abort(403); }

        return view('employees.create');
    }

    /**
     * Guarda el nuevo empleado en la base de datos.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role_id !== 1) { abort(403); }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, 
        ]);

        return redirect()->route('dashboard')->with('success', 'Empleado registrado exitosamente.');
    }
}