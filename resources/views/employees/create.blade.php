<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Registrar Nuevo Colaborador') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-8 border border-slate-200">
                
                <div class="text-center mb-6">
                    <div class="bg-indigo-100 p-3 rounded-full inline-block text-indigo-600 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Alta de Personal</h3>
                    <p class="text-sm text-slate-500">El usuario tendrá acceso limitado (Caja y Taller).</p>
                </div>

                <form method="POST" action="{{ route('employees.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nombre Completo</label>
                        <input type="text" name="name" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej. Juan Pérez">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico (Usuario)</label>
                        <input type="email" name="email" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="empleado@housefix.com">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                        <input type="password" name="password" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-900 underline mr-4">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition transform active:scale-95">
                            Registrar Empleado
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>