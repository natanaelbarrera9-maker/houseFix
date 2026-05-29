<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CashCutController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TwoFactorController; // <--- Nuevo para 2FA
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- RUTA DE INICIO ---
Route::get('/', function () {
    if (Auth::check()) {
        // Si es Gerente (1) va al Dashboard, si es Empleado (2) va al Punto de Venta
        return (Auth::user()->role_id === 1) ? redirect()->route('dashboard') : redirect()->route('pos.index');
    }
    return view('welcome');
});

// --- RUTAS PROTEGIDAS (Requieren Login) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. SEGURIDAD: VERIFICACIÓN DE DOS PASOS (2FA)
    Route::get('verify/two-factor', [TwoFactorController::class, 'index'])->name('verify.index');
    Route::post('verify/two-factor', [TwoFactorController::class, 'store'])->name('verify.store');
    Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
    
    // 2. PERFIL DE USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 3. MÓDULOS OPERATIVOS
    // Punto de Venta (Caja)
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/store', [POSController::class, 'store'])->name('pos.store');

    // Asistencia (Pase de Lista)
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');

    // Inventario
    Route::resource('inventory', InventoryController::class);

    // Taller y Servicios
    Route::resource('services', ServiceController::class);
    Route::patch('/services/{service}/status', [ServiceController::class, 'updateStatus'])->name('services.updateStatus');

    // Corte y Pagos (Cierre de Caja)
    Route::resource('cash_cuts', CashCutController::class)->only(['index', 'create', 'store']);

    // 4. GESTIÓN DE PERSONAL (SOLO GERENTE)
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');

    // 5. DASHBOARD GERENCIAL
    // Usamos la función directa para evitar conflictos con middlewares
    Route::get('/dashboard', function () {
        
        // Seguridad: Si no es Gerente (ID 1), lo mandamos a la caja
        if (Auth::user()->role_id !== 1) { 
            return redirect()->route('pos.index'); 
        }

        // Éxito: Mostramos el panel
        return app(DashboardController::class)->index();

    })->name('dashboard');

});

require __DIR__.'/auth.php';