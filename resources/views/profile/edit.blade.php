<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Mi Perfil Profesional') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tarjeta de Identificación --}}
            <div class="bg-white shadow-lg sm:rounded-2xl overflow-hidden border border-slate-200">
                
                {{-- Encabezado Visual --}}
                <div class="bg-slate-900 h-32 relative">
                    <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>

                {{-- Contenido del Perfil --}}
                <div class="px-8 pb-8 relative">
                    
                    {{-- Avatar / Foto (Iniciales) --}}
                    <div class="relative -mt-16 mb-6">
                        <div class="h-32 w-32 rounded-full bg-white p-2 shadow-xl inline-block">
                            <div class="h-full w-full rounded-full bg-slate-800 flex items-center justify-center text-4xl font-bold text-white border-4 border-slate-100">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                        {{-- Indicador de Estado --}}
                        <div class="absolute bottom-2 left-24 bg-emerald-500 h-6 w-6 rounded-full border-4 border-white shadow-sm" title="Usuario Activo"></div>
                    </div>

                    {{-- Datos Principales --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end border-b border-slate-100 pb-6 mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">{{ Auth::user()->name }}</h1>
                            <p class="text-slate-500 font-medium flex items-center gap-2 mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            @if(Auth::user()->role_id === 1)
                                <span class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold tracking-wide shadow-sm">
                                    GERENCIA
                                </span>
                            @else
                                <span class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold tracking-wide shadow-sm">
                                    PERSONAL OPERATIVO
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Grilla de Detalles --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        
                        {{-- ID de Empleado --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">ID de Colaborador</span>
                            <div class="text-lg font-mono text-slate-700 font-semibold">
                                #{{ str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>

                        {{-- Fecha de Ingreso --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Fecha de Registro</span>
                            <div class="text-lg text-slate-700 font-semibold">
                                {{ \Carbon\Carbon::parse(Auth::user()->created_at)->isoFormat('D [de] MMMM, YYYY') }}
                            </div>
                        </div>

                        {{-- Última Actualización --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Última Actividad</span>
                            <div class="text-lg text-slate-700 font-semibold">
                                Hoy, {{ now()->format('H:i A') }}
                            </div>
                        </div>

                        {{-- Estado de Cuenta --}}
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Estado de Cuenta</span>
                                <div class="text-lg text-emerald-600 font-bold">
                                    Verificado
                                </div>
                            </div>
                            <svg class="w-8 h-8 text-emerald-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>

                    {{-- Botón de Acción --}}
                    <div class="mt-8 border-t border-slate-100 pt-6 flex justify-end">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-slate-500 hover:text-red-600 font-medium text-sm transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>