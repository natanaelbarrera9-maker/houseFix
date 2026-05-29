<div class="bg-white p-4 rounded-lg shadow-sm border border-slate-200 hover:shadow-md transition">
    <div class="flex justify-between items-start mb-2">
        <span class="font-bold text-slate-800">#{{ str_pad($service->id, 4, '0', STR_PAD_LEFT) }}</span>
        <span class="text-xs text-slate-400">{{ $service->created_at->format('d M') }}</span>
    </div>
    
    <div class="mb-3">
        <h4 class="text-sm font-semibold text-slate-900">{{ $service->brand_model }}</h4>
        <p class="text-xs text-slate-500">{{ $service->device_type }}</p>
    </div>

    <div class="bg-slate-50 p-2 rounded text-xs text-slate-600 mb-3 border border-slate-100">
        {{ Str::limit($service->issue_description, 60) }}
    </div>

    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
        <div class="flex items-center gap-2">
            <div class="h-6 w-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                {{ substr($service->client->name, 0, 1) }}
            </div>
            <span class="text-xs font-medium text-slate-600 truncate max-w-[80px]">{{ explode(' ', $service->client->name)[0] }}</span>
        </div>
<div class="flex justify-between items-start mb-2">
        <div class="flex items-center gap-2">
            <span class="font-bold text-slate-800">#{{ str_pad($service->id, 4, '0', STR_PAD_LEFT) }}</span>
            {{-- Botón para VER TICKET --}}
            <a href="{{ route('services.show', $service) }}" class="text-slate-400 hover:text-indigo-600 transition" title="Ver Ticket">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </a>
        </div>
        <span class="text-xs text-slate-400">{{ $service->created_at->format('d M') }}</span>
    </div>
        <form action="{{ route('services.updateStatus', $service) }}" method="POST">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $next_status }}">
            <button type="submit" class="text-xs bg-{{ $btn_color }}-100 text-{{ $btn_color }}-700 px-2 py-1 rounded hover:bg-{{ $btn_color }}-200 font-medium transition">
                {{ $btn_text }} &rarr;
            </button>
        </form>
    </div>
</div>
