<x-app-layout>
    {{-- Incluimos la librería html2pdf directamente desde CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Detalle de Orden') }} #{{ str_pad($service->id, 4, '0', STR_PAD_LEFT) }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('services.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-md font-bold text-xs uppercase tracking-widest hover:bg-slate-300">
                    Volver
                </a>
                
                {{-- BOTÓN IMPRIMIR (Nativo) --}}
                <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-md font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-sm">
                    Imprimir
                </button>

                {{-- BOTÓN DESCARGAR PDF (Nuevo) --}}
                <button onclick="generatePDF()" class="px-4 py-2 bg-red-600 text-white rounded-md font-bold text-xs uppercase tracking-widest hover:bg-red-700 shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Descargar PDF
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ÁREA DEL DOCUMENTO (Lo que se convertirá a PDF) --}}
            <div id="ticket-content" class="bg-white shadow-lg sm:rounded-lg overflow-hidden border border-slate-200 print:shadow-none print:border-none relative">
                
                {{-- Encabezado del Ticket --}}
                <div class="p-8 border-b border-slate-100 flex justify-between items-start">
                    <div>
                        {{-- Logo / Nombre --}}
                        <div class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                            <div class="bg-indigo-600 text-white p-1 rounded">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            House Fix
                        </div>
                        <p class="text-slate-500 text-sm mt-1">Centro de Reparación Especializado</p>
                        <p class="text-slate-400 text-xs mt-4">
                            Fecha de Recepción: <br>
                            <span class="text-slate-700 font-bold text-base">{{ \Carbon\Carbon::parse($service->received_date)->format('d/m/Y - h:i A') }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="bg-slate-100 text-slate-600 px-4 py-2 rounded-lg mb-2 inline-block">
                            <span class="block text-xs uppercase font-bold text-slate-400">Folio de Orden</span>
                            <span class="text-2xl font-mono font-bold text-slate-900">#{{ str_pad($service->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $service->status_color }}">
                                {{ $service->status_label }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Cuerpo del Documento --}}
                <div class="p-8 grid grid-cols-2 gap-8">
                    
                    {{-- Datos del Cliente --}}
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-b border-slate-100 pb-1">Cliente</h3>
                        <p class="font-bold text-slate-800 text-lg">{{ $service->client->name }}</p>
                        <p class="text-slate-600 flex items-center gap-2 mt-1">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $service->client->phone }}
                        </p>
                        <p class="text-slate-500 text-sm mt-1">{{ $service->client->email }}</p>
                    </div>

                    {{-- Datos del Equipo --}}
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-b border-slate-100 pb-1">Dispositivo</h3>
                        <p class="font-bold text-slate-800 text-lg">{{ $service->brand_model }}</p>
                        <p class="text-slate-600 text-sm bg-slate-50 inline-block px-2 py-1 rounded mt-1 border border-slate-100">
                            Tipo: {{ $service->device_type }}
                        </p>
                    </div>

                    {{-- Descripción de la Falla --}}
                    <div class="col-span-2 mt-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-b border-slate-100 pb-1">Reporte de Falla / Solicitud</h3>
                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-100 text-slate-700 italic">
                            "{{ $service->issue_description }}"
                        </div>
                    </div>

                    {{-- Diagnóstico (Si existe) --}}
                    @if($service->diagnosis)
                        <div class="col-span-2 mt-2">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-b border-slate-100 pb-1">Diagnóstico Técnico</h3>
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-blue-800">
                                {{ $service->diagnosis }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Pie de Página / Firmas --}}
                <div class="bg-slate-50 p-8 border-t border-slate-200 mt-8 print:bg-white print:mt-16">
                    <div class="grid grid-cols-2 gap-16 mt-8">
                        <div class="text-center">
                            <div class="border-b border-slate-400 w-full mb-2"></div>
                            <span class="text-xs text-slate-500 uppercase font-bold">Firma del Cliente</span>
                            <p class="text-[10px] text-slate-400 mt-1 leading-tight">
                                Acepto los términos y condiciones. House Fix no se hace responsable por equipos no reclamados después de 30 días.
                            </p>
                        </div>
                        <div class="text-center">
                            <div class="border-b border-slate-400 w-full mb-2"></div>
                            <span class="text-xs text-slate-500 uppercase font-bold">Recibido Por (House Fix)</span>
                            <p class="text-xs text-slate-600 mt-1 font-medium">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-center no-print">
                <p class="text-slate-400 text-sm">Este documento es un comprobante interno y para el cliente.</p>
            </div>
        </div>
    </div>

    {{-- Estilos para Impresión --}}
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .min-h-screen { min-height: auto; }
            .shadow-lg { box-shadow: none !important; }
            .border { border: 1px solid #ddd !important; }
        }
    </style>

    {{-- Script para generar PDF --}}
    <script>
        function generatePDF() {
            // Seleccionamos el elemento exacto del ticket
            const element = document.getElementById('ticket-content');
            
            // Opciones de configuración
            const opt = {
                margin:       0.3, // Márgenes pequeños
                filename:     'Orden_Servicio_{{ $service->id }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 }, // Alta resolución
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Generar y Descargar
            html2pdf().set(opt).from(element).save();
        }
    </script>
</x-app-layout>