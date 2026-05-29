<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-slate-100">
        <div class="bg-white p-8 rounded-xl shadow-lg text-center max-w-md">
            <div class="bg-indigo-100 p-4 rounded-full w-20 h-20 mx-auto flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Control de Asistencia</h2>
            <p class="text-slate-500">
                El pase de lista es gestionado exclusivamente por la Gerencia.
            </p>
            <p class="text-slate-400 text-sm mt-4">
                Tu asistencia de hoy será registrada por tu supervisor.
            </p>
            <a href="{{ route('pos.index') }}" class="mt-6 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Volver a la Caja
            </a>
        </div>
    </div>
</x-app-layout>