<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Historial de Cortes') }}
            </h2>
            <a href="{{ route('cash_cuts.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-md font-bold text-xs uppercase tracking-widest hover:bg-emerald-700">
                + Nuevo Corte
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-100 border-b">
                            <tr>
                                <th class="px-6 py-3">Fecha</th>
                                <th class="px-6 py-3">Responsable</th>
                                <th class="px-6 py-3 text-right">Venta Sistema</th>
                                <th class="px-6 py-3 text-right">Gastos</th>
                                <th class="px-6 py-3 text-right">Contado (Real)</th>
                                <th class="px-6 py-3 text-center">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($cuts as $cut)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        {{ \Carbon\Carbon::parse($cut->created_at)->format('d/m/Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4">{{ $cut->user->name }}</td>
                                    <td class="px-6 py-4 text-right">${{ number_format($cut->sales_amount, 2) }}</td>
                                    <td class="px-6 py-4 text-right text-red-500">-${{ number_format($cut->expenses_amount, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-800">${{ number_format($cut->counted_amount, 2) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($cut->difference == 0)
                                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full font-bold text-xs">OK</span>
                                        @elseif($cut->difference > 0)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-bold text-xs">+${{ number_format($cut->difference, 2) }}</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full font-bold text-xs">-${{ number_format(abs($cut->difference), 2) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">No hay cortes registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $cuts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>w