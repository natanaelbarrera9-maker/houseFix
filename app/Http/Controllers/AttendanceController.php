<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Muestra la Lista de Pase de Lista (Solo para Gerente).
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Si es EMPLEADO, lo bloqueamos visualmente.
        if (!$user->isAdmin()) {
            return view('attendance.employee_view');
        }

        // 2. Si es GERENTE, preparamos la lista.
        $employees = User::where('role_id', 2)
            ->with(['attendances' => function($query) {
                $query->whereDate('check_in', Carbon::today());
            }])
            ->get()
            ->each(fn($emp) => $emp->today_record = $emp->attendances->first());

        return view('attendance.index', compact('employees'));
    }

    /**
     * El Gerente guarda la asistencia de un empleado.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status'  => 'required|in:a_tiempo,retardo,falta',
        ]);

        $exists = Attendance::where('user_id', $request->user_id)
            ->whereDate('check_in', Carbon::today())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Ya se registró la asistencia de este empleado hoy.');
        }

        Attendance::create([
            'user_id'  => $request->user_id,
            'check_in' => Carbon::now(),
            'status'   => $request->status,
        ]);

        return back()->with('success', 'Asistencia registrada correctamente.');
    }

    /**
     * Genera y descarga el reporte semanal en Excel (CSV).
     */
    public function export()
    {
        if (!Auth::user()->isAdmin()) abort(403);

        $filename = 'Reporte_Asistencia_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Columnas del Excel
        $columns = ['Empleado', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            // Escribir cabecera (BOM para que Excel reconozca caracteres latinos como tildes)
            fputs($file, "\xEF\xBB\xBF"); 
            fputcsv($file, $columns);

            $employees = User::where('role_id', 2)->get();
            
            // Calcular inicio (Lunes) y fin (Domingo) de la semana actual
            $startOfWeek = Carbon::now()->startOfWeek(); 

            foreach ($employees as $emp) {
                $row = [$emp->name];

                // Recorrer los 7 días de la semana
                for ($i = 0; $i < 7; $i++) {
                    $currentDay = $startOfWeek->copy()->addDays($i);
                    
                    $record = Attendance::where('user_id', $emp->id)
                                ->whereDate('check_in', $currentDay)
                                ->first();

                    if ($record) {
                        if ($record->status == 'falta') {
                            $row[] = 'FALTA';
                        } else {
                            $time = Carbon::parse($record->check_in)->format('H:i');
                            // Si es retardo, agregamos una marca
                            $note = ($record->status == 'retardo') ? ' (Retardo)' : '';
                            $row[] = $time . $note;
                        }
                    } else {
                        $row[] = '-'; // No hay registro (quizás aún no pasa el día)
                    }
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}