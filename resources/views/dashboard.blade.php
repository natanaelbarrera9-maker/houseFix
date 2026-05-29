<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                {{ __('House Fix - Control de Tienda') }}
            </h2>
            <span class="text-sm text-slate-500 bg-white px-3 py-1 rounded-full shadow-sm border border-slate-200">
                {{ now()->isoFormat('D [de] MMMM, YYYY') }}
            </span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p class="font-bold">Éxito</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif
    </div>

    <div class="py-8 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- TARJETAS DE RESUMEN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Ventas Hoy --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Ventas Hoy</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $salesTodayCount }} <span class="text-sm font-normal text-slate-400">arts.</span></p>
                        </div>
                    </div>
                </div>

                {{-- Ingresos Hoy --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-emerald-50 text-emerald-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Ingresos Hoy</p>
                            <p class="text-2xl font-bold text-slate-800">${{ number_format($incomeToday, 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Stock Crítico --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-50 text-red-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Stock Crítico</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $lowStock }}</p>
                        </div>
                    </div>
                </div>

                {{-- Personal --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Personal</p>
                            <p class="text-2xl font-bold text-slate-800">{{ $activeStaff }} <span class="text-sm font-normal text-slate-400">/ {{ $totalStaff }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- BOTONES DE ACCESO RÁPIDO --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Punto de Venta</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        
                        {{-- 1. Botón NUEVA VENTA (Ruta POS) --}}
                        <a href="{{ route('pos.index') }}" class="flex flex-col items-center justify-center p-4 bg-slate-50 border-2 border-slate-100 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 group cursor-pointer">
                            <svg class="w-8 h-8 text-slate-400 group-hover:text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span class="text-sm font-semibold text-slate-600 group-hover:text-indigo-700">Nueva Venta</span>
                        </a>
                        
                        {{-- 2. Botón INVENTARIO (Ruta Inventory) --}}
                        <a href="{{ route('inventory.index') }}" class="flex flex-col items-center justify-center p-4 bg-slate-50 border-2 border-slate-100 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 group cursor-pointer">
                            <svg class="w-8 h-8 text-slate-400 group-hover:text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <span class="text-sm font-semibold text-slate-600 group-hover:text-indigo-700">Inventario</span>
                        </a>

                 {{-- 3. Botón CORTE / PAGOS (Corregido) --}}
<a href="{{ route('cash_cuts.index') }}" class="flex flex-col items-center justify-center p-4 bg-slate-50 border-2 border-slate-100 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 group cursor-pointer">
    <svg class="w-8 h-8 text-slate-400 group-hover:text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
    <span class="text-sm font-semibold text-slate-600 group-hover:text-indigo-700">Corte / Pagos</span>
</a>

                        {{-- 4. Botón ASISTENCIA (Ruta Attendance) --}}
                         <a href="{{ route('attendance.index') }}" class="flex flex-col items-center justify-center p-4 bg-slate-50 border-2 border-slate-100 rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition duration-200 group cursor-pointer">
                            <svg class="w-8 h-8 text-slate-400 group-hover:text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm font-semibold text-slate-600 group-hover:text-indigo-700">Pase de Lista</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TABLA DE VENTAS RECIENTES --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Últimas Ventas Registradas</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-500">
                            <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b">
                                <tr>
                                    <th class="px-6 py-3">Producto</th>
                                    <th class="px-6 py-3">Cantidad</th>
                                    <th class="px-6 py-3">Vendedor</th>
                                    <th class="px-6 py-3">Hora</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentActivity as $movement)
                                <tr class="bg-white hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        {{ $movement->product_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-red-600 font-bold">-{{ $movement->quantity }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $movement->user_name }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ \Carbon\Carbon::parse($movement->created_at)->format('H:i') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500 italic">
                                        No se han registrado ventas hoy.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>