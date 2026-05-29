<x-app-layout>
    {{-- Inyección de estilos específicos para el módulo POS --}}
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
    @endpush

    {{-- Contenedor Principal POS --}}
    <div x-data="posSystem(
            {{ json_encode($products) }}, 
            '{{ route('pos.store') }}', 
            '{{ csrf_token() }}'
         )" 
         class="flex pos-container !bg-slate-100 !text-slate-800 h-[calc(100vh-65px)] overflow-hidden">
        
        {{-- COLUMNA IZQUIERDA: Catálogo y Buscador --}}
        <div class="w-full md:w-2/3 flex flex-col border-r border-slate-200 !bg-slate-100">
            
            {{-- Componente de Búsqueda --}}
            <div class="p-4 bg-white border-b border-slate-200 shadow-sm z-10">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input x-model="search" 
                           type="text" 
                           class="w-full py-3 pl-10 pr-4 text-slate-700 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" 
                           placeholder="Escanear código o buscar producto..." 
                           autofocus>
                </div>
            </div>

            {{-- Grid de Productos (Renderizado dinámico con Alpine.js) --}}
            <div class="flex-1 overflow-y-auto p-4 custom-scroll">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)" 
                             class="product-card bg-white p-4 rounded-xl shadow-sm border border-slate-200 cursor-pointer hover:border-indigo-500 h-32 flex flex-col justify-between group transition-all duration-200">
                            
                            {{-- Información del Item --}}
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm leading-tight line-clamp-2" x-text="product.name"></h3>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-xs text-slate-500" x-text="'Stock: ' + product.quantity"></span>
                                    {{-- Manejo seguro de SKU por si viene nulo --}}
                                    <span class="text-xs font-mono text-slate-400" x-text="product.sku || ''"></span>
                                </div>
                            </div>

                            {{-- Precio y Botón de Acción --}}
                            <div class="flex justify-between items-end mt-2">
                                <span class="font-bold text-indigo-700 text-lg" x-text="'$' + Number(product.price).toFixed(2)"></span>
                                <div class="bg-indigo-50 text-indigo-600 p-1.5 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Estado Vacío (Empty State) --}}
                <div x-show="filteredProducts.length === 0" class="flex flex-col items-center justify-center h-64 text-slate-400">
                    <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p>No se encontraron coincidencias en el inventario.</p>
                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA: Ticket de Venta --}}
        <div class="w-full md:w-1/3 bg-white flex flex-col shadow-2xl z-20 border-l border-slate-200">
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h2 class="font-bold text-xl text-slate-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Ticket Actual
                </h2>
                <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full font-bold" x-text="cart.length + ' items'"></span>
            </div>

            {{-- Listado de Items en Carrito --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scroll bg-white">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex items-center justify-between bg-white border border-slate-100 p-3 rounded-lg shadow-sm hover:border-indigo-100 transition">
                        <div class="flex-1 overflow-hidden">
                            <h4 class="font-medium text-slate-800 text-sm truncate" x-text="item.name"></h4>
                            <div class="flex items-center text-xs text-slate-500 mt-1">
                                <span x-text="'$' + Number(item.price).toFixed(2)"></span>
                                <span class="mx-2 text-slate-300">|</span>
                                <span x-text="'Cant: ' + item.quantity"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-slate-800" x-text="'$' + (item.price * item.quantity).toFixed(2)"></span>
                            {{-- Botón Eliminar Item --}}
                            <button @click="removeFromCart(index)" class="text-slate-400 hover:text-red-500 transition p-1 rounded-full hover:bg-red-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>

                {{-- Estado Vacío del Carrito --}}
                <template x-if="cart.length === 0">
                    <div class="flex flex-col items-center justify-center h-full text-slate-400 opacity-50 space-y-2">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <p class="text-sm">El carrito está vacío</p>
                    </div>
                </template>
            </div>

            {{-- Footer con Totales y Checkout --}}
            <div class="p-6 bg-slate-50 border-t border-slate-200">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-slate-500 font-medium">Total a Pagar</span>
                    <span class="text-3xl font-extrabold text-slate-900" x-text="'$' + total.toFixed(2)"></span>
                </div>
                
                {{-- Botón de Procesar Pago --}}
                <button @click="processSale()" 
                        :disabled="cart.length === 0 || processing"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold py-4 rounded-xl shadow-lg transition duration-200 flex justify-center items-center group">
                    <span x-show="!processing" class="flex items-center">
                        Completar Venta
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </span>
                    <span x-show="processing" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Procesando...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Inyección de lógica JS --}}
    @push('scripts')
        {{-- ¡IMPORTANTE! Cargamos SweetAlert2 ANTES que nuestro script --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('js/pos.js') }}"></script>
    @endpush
</x-app-layout>