<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                    {{ __('Control de Asistencia Diario') }}
                </h2>
                <span class="text-sm text-slate-500 font-medium">
                    {{ now()->isoFormat('D [de] MMMM, YYYY') }}
                </span>
            </div>
            
            {{-- BOTÓN DESCARGAR EXCEL --}}
            <a href="{{ route('attendance.export') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:border-emerald-900 focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Descargar Reporte Semanal
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alertas del Sistema --}}
            @if(session('success'))
                <div class="mb-6 flex items-center p-4 text-sm text-emerald-800 border border-emerald-200 rounded-lg bg-emerald-50" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>
                    <span class="font-medium">Operación exitosa:</span>&nbsp;{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 flex items-center p-4 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/></svg>
                    <span class="font-medium">Atención:</span>&nbsp;{{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm ring-1 ring-black ring-opacity-5 sm:rounded-lg overflow-hidden">
                {{-- Encabezado de la Tabla --}}
                <div class="px-6 py-5 border-b border-slate-200 bg-white flex justify-between items-center">
                    <div>
                        <h3 class="text-base font-semibold leading-6 text-slate-900">Listado de Personal</h3>
                        <p class="mt-1 text-sm text-slate-500">Gestione la asistencia del día para los empleados activos.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">
                            Total: {{ $employees->count() }}
                        </span>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Colaborador</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado Actual</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Registrar Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($employees as $emp)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    {{-- Columna Empleado --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm border border-indigo-200">
                                                    {{ substr($emp->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900">{{ $emp->name }}</div>
                                                <div class="text-xs text-slate-500">{{ $emp->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- Columna Estado --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($emp->today_record)
                                            @if($emp->today_record->status == 'a_tiempo')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    PRESENTE ({{ \Carbon\Carbon::parse($emp->today_record->check_in)->format('H:i') }})
                                                </span>
                                            @elseif($emp->today_record->status == 'retardo')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    RETARDO ({{ \Carbon\Carbon::parse($emp->today_record->check_in)->format('H:i') }})
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    FALTA
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Columna Acciones --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if(!$emp->today_record)
                                            <div class="flex justify-center items-center gap-3">
                                                {{-- Botón ASISTENCIA --}}
                                                <form action="{{ route('attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $emp->id }}">
                                                    <input type="hidden" name="status" value="a_tiempo">
                                                    <button type="submit" class="group flex flex-col items-center justify-center p-2 rounded-lg hover:bg-emerald-50 transition-all duration-200" title="Marcar Asistencia">
                                                        <div class="p-2 rounded-full bg-white border border-slate-200 text-slate-400 group-hover:border-emerald-500 group-hover:text-emerald-600 shadow-sm transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        </div>
                                                        <span class="text-[10px] font-medium text-slate-400 group-hover:text-emerald-700 mt-1 uppercase tracking-wide">Asistencia</span>
                                                    </button>
                                                </form>

                                                {{-- Botón RETARDO --}}
                                                <form action="{{ route('attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $emp->id }}">
                                                    <input type="hidden" name="status" value="retardo">
                                                    <button type="submit" class="group flex flex-col items-center justify-center p-2 rounded-lg hover:bg-amber-50 transition-all duration-200" title="Marcar Retardo">
                                                        <div class="p-2 rounded-full bg-white border border-slate-200 text-slate-400 group-hover:border-amber-500 group-hover:text-amber-600 shadow-sm transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                        <span class="text-[10px] font-medium text-slate-400 group-hover:text-amber-700 mt-1 uppercase tracking-wide">Retardo</span>
                                                    </button>
                                                </form>

                                                {{-- Botón FALTA --}}
                                                <form action="{{ route('attendance.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $emp->id }}">
                                                    <input type="hidden" name="status" value="falta">
                                                    <button type="submit" class="group flex flex-col items-center justify-center p-2 rounded-lg hover:bg-rose-50 transition-all duration-200" title="Marcar Falta">
                                                        <div class="p-2 rounded-full bg-white border border-slate-200 text-slate-400 group-hover:border-rose-500 group-hover:text-rose-600 shadow-sm transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </div>
                                                        <span class="text-[10px] font-medium text-slate-400 group-hover:text-rose-700 mt-1 uppercase tracking-wide">Falta</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="flex justify-center">
                                                <span class="inline-flex items-center rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-400 ring-1 ring-inset ring-slate-500/10">
                                                    Registrado
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-500">
                                            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            <p class="text-base font-medium">No se encontraron empleados activos</p>
                                            <p class="text-sm mt-1">Asegúrese de que existan usuarios con el rol de Empleado (ID 2).</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>