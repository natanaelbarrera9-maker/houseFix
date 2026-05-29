<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail; // Necesario para enviar correo
use App\Mail\TwoFactorCode;          // Necesario para el formato del correo

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista de inicio de sesión.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * PROCESO DE LOGIN (Aquí está la magia del 2FA)
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Validar credenciales (Usuario y Contraseña)
        $request->authenticate();

        // 2. Regenerar sesión por seguridad
        $request->session()->regenerate();

        $user = Auth::user();

        // --- LÓGICA DE DOS PASOS (2FA) ---
        
        // CASO A: Es GERENTE (Role ID 1) -> OBLIGATORIO 2FA
        if ($user->role_id === 1) {
            
            // a) Generamos el código numérico y lo guardamos en la BD
            $user->generateTwoFactorCode();

            // b) Enviamos el correo (dentro de un try/catch para que no falle si no hay internet)
            try {
                Mail::to($user->email)->send(new TwoFactorCode($user->two_factor_code));
            } catch (\Exception $e) {
                // Si el correo falla, podríamos mostrar un error, 
                // pero por ahora dejamos que continúe para que al menos vea la pantalla de código.
                // \Log::error("Error enviando correo 2FA: " . $e->getMessage());
            }

            // c) LO MÁS IMPORTANTE: No lo mandamos al Dashboard, lo mandamos a VERIFICAR
            return redirect()->route('verify.index');
        }

        // CASO B: Es EMPLEADO (Role ID 2) -> Entra directo (Sin código)
        // Esto es para que no pierdan tiempo en la caja.
        return redirect()->intended(route('pos.index'));
    }

    /**
     * Cerrar sesión.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Si el usuario tenía un código pendiente, lo limpiamos al salir
        if (Auth::check()) {
            Auth::user()->resetTwoFactorCode();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}