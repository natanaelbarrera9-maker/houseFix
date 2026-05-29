<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Taller de Reparaciones') }}
            </h2>
            <a href="{{ route('services.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                + Nuevo Ingreso
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50 min-h-screen overflow-x-auto">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensajes flash --}}
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex gap-6 min-w-[1000px]">
                
                {{-- COLUMNA 1: POR REVISAR --}}
                <div class="w-1/3 flex flex-col gap-4">
                    <div class="flex items-center justify-between bg-white px-4 py-3 rounded shadow-sm border-t-4 border-slate-500">
                        <h3 class="font-bold text-slate-700 uppercase tracking-wide text-sm">Por Revisar</h3>
                        <span class="bg-slate-100 text-slate-600 text-xs font-bold px-2 py-1 rounded-full">{{ $columns['pendientes']->count() }}</span>
                    </div>
                    <div class="flex flex-col gap-3">
                        @foreach($columns['pendientes'] as $service)
                            @include('services.partials.card', ['service' => $service, 'next_status' => 'revision', 'btn_text' => 'Iniciar Revisión', 'btn_color' => 'blue'])
                        @endforeach
                    </div>
                </div>

                {{-- COLUMNA 2: EN PROCESO --}}
                <div class="w-1/3 flex flex-col gap-4">
                    <div class="flex items-center justify-between bg-white px-4 py-3 rounded shadow-sm border-t-4 border-blue-500">
                        <h3 class="font-bold text-blue-700 uppercase tracking-wide text-sm">En Reparación</h3>
                        <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2 py-1 rounded-full">{{ $columns['en_proceso']->count() }}</span>
                    </div>
                    <div class="flex flex-col gap-3">
                        @foreach($columns['en_proceso'] as $service)
                            @include('services.partials.card', ['service' => $service, 'next_status' => 'reparado', 'btn_text' => 'Finalizar Reparación', 'btn_color' => 'emerald'])
                        @endforeach
                    </div>
                </div>

                {{-- COLUMNA 3: LISTOS --}}
                <div class="w-1/3 flex flex-col gap-4">
                    <div class="flex items-center justify-between bg-white px-4 py-3 rounded shadow-sm border-t-4 border-emerald-500">
                        <h3 class="font-bold text-emerald-700 uppercase tracking-wide text-sm">Listos para Entrega</h3>
                        <span class="bg-emerald-50 text-emerald-600 text-xs font-bold px-2 py-1 rounded-full">{{ $columns['listos']->count() }}</span>
                    </div>
                    <div class="flex flex-col gap-3">
                        @foreach($columns['listos'] as $service)
                             @include('services.partials.card', ['service' => $service, 'next_status' => 'entregado', 'btn_text' => 'Entregar al Cliente', 'btn_color' => 'slate'])
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>