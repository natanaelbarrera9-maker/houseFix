<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'House Fix') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        {{-- CAMBIO PRINCIPAL: Fondo oscuro (bg-slate-900) con un patrón sutil --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-900 relative">
            
            {{-- Fondo decorativo (opcional, para que no sea plano) --}}
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 24px 24px;"></div>

            {{-- LOGO (Ahora con texto blanco para contrastar) --}}
            <div class="mb-8 z-10 relative">
                <a href="/" class="flex items-center gap-4 justify-center">
                    {{-- Icono --}}
                    <div class="bg-indigo-600 p-3 rounded-xl text-white shadow-lg shadow-indigo-500/50 ring-4 ring-indigo-900/50">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    {{-- Texto --}}
                    <div class="flex flex-col">
                        <span class="font-black text-4xl text-white tracking-tighter leading-none drop-shadow-md">House Fix</span>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Panel de Control</span>
                    </div>
                </a>
            </div>

            {{-- TARJETA (Se mantiene blanca para legibilidad, pero flota mejor sobre lo oscuro) --}}
            <div class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white shadow-2xl sm:rounded-2xl border border-slate-700/50 relative overflow-hidden z-10">
                
                {{-- Barra superior de color --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                
                {{ $slot }}
            </div>

            {{-- Pie de página oscuro --}}
            <div class="mt-8 text-center text-xs text-slate-500 font-medium z-10">
                &copy; {{ date('Y') }} House Fix. Sistema Seguro.
            </div>
        </div>
    </body>
</html>