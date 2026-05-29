<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Registrar Nuevo Producto') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-slate-200">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-lg font-medium text-slate-900">Detalles del Artículo</h3>
                    <p class="mt-1 text-sm text-slate-500">Completa la información para dar de alta en el sistema.</p>
                </div>

                <form method="POST" action="{{ route('inventory.store') }}" class="p-6 space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Nombre del Producto *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                               class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-slate-700">Precio de Venta ($) *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="price" id="price" step="0.01" value="{{ old('price') }}" required
                                       class="block w-full pl-7 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                            </div>
                            @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-slate-700">Stock Inicial *</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" required
                                   class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                            @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">Descripción (Opcional)</label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3"
                                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-slate-300 rounded-lg p-3"
                                      placeholder="Detalles técnicos, código de barras, ubicación...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('inventory.index') }}" class="bg-white py-2 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>