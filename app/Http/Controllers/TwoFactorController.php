<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    // Mostrar formulario
    public function index()
    {
        return view('auth.two-factor');
    }

    // Validar código
    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|integer',
        ]);

        $user = auth()->user();

        if ($request->two_factor_code == $user->two_factor_code) {
            
            // Verificar expiración
            if($user->two_factor_expires_at < now()) {
                $user->resetTwoFactorCode();
                auth()->logout();
                return redirect()->route('login')
                    ->with('error', 'El código ha expirado. Ingresa de nuevo.');
            }

            // ÉXITO: Limpiamos el código y dejamos pasar
            $user->resetTwoFactorCode();

            // Redirigir según rol
            return ($user->role_id === 1) 
                ? redirect()->route('dashboard') 
                : redirect()->route('pos.index');
        }

        return back()->with('error', 'El código ingresado es incorrecto.');
    }

    // Reenviar código
    public function resend()
    {
        $user = auth()->user();
        $user->generateTwoFactorCode();
        
        \Mail::to($user->email)->send(new \App\Mail\TwoFactorCode($user->two_factor_code));

        return back()->with('success', 'El código ha sido reenviado a tu correo: ' . $user->email);
    }
}