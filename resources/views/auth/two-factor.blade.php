<x-guest-layout>
    <div class="text-center mb-8">
        {{-- Icono de Candado --}}
        <div class="bg-indigo-100 text-indigo-600 p-4 rounded-full inline-flex items-center justify-center mb-4 shadow-sm animate-pulse">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Verificación de Seguridad</h2>
        <p class="text-slate-500 text-sm mt-3 leading-relaxed">
            Tu cuenta de Gerente está protegida. Hemos enviado un código a: <br>
            <span class="font-bold text-slate-700 bg-slate-100 px-3 py-1 rounded-full mt-2 inline-block border border-slate-200">{{ auth()->user()->email }}</span>
        </p>
    </div>

    {{-- Mensajes de Éxito o Error --}}
    @if (session('success'))
        <div class="mb-6 font-medium text-sm text-green-700 bg-green-50 p-3 rounded-lg border border-green-100 text-center flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 font-medium text-sm text-red-700 bg-red-50 p-3 rounded-lg border border-red-100 text-center flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verify.store') }}">
        @csrf

        <div class="mb-6 relative">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 text-center">Ingresa el Código de 6 Dígitos</label>
            
            {{-- Input Gigante y Centrado --}}
            <input id="two_factor_code" 
                   type="text" 
                   name="two_factor_code" 
                   required 
                   autofocus 
                   maxlength="6"
                   autocomplete="one-time-code"
                   class="block w-full text-center text-4xl font-black text-indigo-600 tracking-[0.5em] rounded-xl border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-16 placeholder-slate-200"
                   placeholder="000000" />
        </div>

        <div class="flex flex-col gap-4">
            {{-- Botón Principal --}}
            <button type="submit" class="w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-lg shadow-lg transition transform active:scale-95 uppercase tracking-wide text-sm flex justify-center items-center gap-2">
                Verificar y Entrar
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </button>
            
            <div class="flex justify-between items-center mt-4 border-t border-slate-100 pt-4">
                {{-- Reenviar --}}
                <a href="{{ route('verify.resend') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-3 py-2 rounded transition">
                    ¿No llegó? Reenviar código
                </a>
                
                {{-- Salir --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-slate-400 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded transition">
                        Cancelar
                    </button>
                </form>
            </div>
        </div>
    </form>
</x-guest-layout>