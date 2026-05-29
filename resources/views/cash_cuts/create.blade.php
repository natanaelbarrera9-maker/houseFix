<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Realizar Corte de Caja') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-slate-200">
                <form action="{{ route('cash_cuts.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="text-center mb-8">
                        <p class="text-sm text-slate-500 uppercase tracking-widest font-bold">Fecha del Corte</p>
                        <h3 class="text-3xl font-black text-slate-800">{{ now()->isoFormat('D [de] MMMM, YYYY') }}</h3>
                    </div>

                    {{-- Sección: Sistema --}}
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-100 mb-6">
                        <h4 class="font-bold text-slate-700 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Cálculo del Sistema
                        </h4>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase">Ventas Totales</label>
                                <div class="text-xl font-bold text-slate-800">${{ number_format($salesToday, 2) }}</div>
                                <input type="hidden" name="sales_amount" value="{{ $salesToday }}">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase">Gastos / Pagos</label>
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">$</span>
                                    <input type="number" step="0.01" name="expenses_amount" value="0" class="pl-7 w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 font-bold text-red-600" placeholder="0.00">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">Ingresa pagos a proveedores o retiros.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Sección: Conteo Físico --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Efectivo Contado en Caja (Real)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 font-bold text-lg">$</span>
                            <input type="number" step="0.01" name="counted_amount" required class="pl-8 w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-2xl font-bold text-emerald-700 h-14" placeholder="0.00">
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Cuenta todo el dinero físico en la caja e ingrésalo aquí.</p>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Notas / Observaciones</label>
                        <textarea name="notes" rows="2" class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej. Faltaron 5 pesos por cambio..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white font-bold py-4 rounded-lg shadow-lg hover:bg-slate-800 transition transform active:scale-95 text-lg uppercase tracking-widest">
                        Cerrar Caja
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>