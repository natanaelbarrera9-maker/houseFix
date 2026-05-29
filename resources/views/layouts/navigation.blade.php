<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::user()->role_id === 1 ? route('dashboard') : route('pos.index') }}" class="flex items-center gap-2">
                        <div class="bg-indigo-600 p-1.5 rounded text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="font-bold text-xl text-white tracking-tight">House Fix</span>
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center">
                    
                    {{-- SOLO GERENTE (ID 1) --}}
                    @if(Auth::user()->role_id === 1)
                        <a href="{{ route('dashboard') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out
                           {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            {{ __('Menu Principal') }}
                        </a>
                    @endif

                    {{-- TODOS --}}
                    <a href="{{ route('inventory.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out
                       {{ request()->routeIs('inventory.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        {{ __('Inventario') }}
                    </a>

                    <a href="{{ route('pos.index') }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out
                       {{ request()->routeIs('pos.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        {{ __('Punto de Venta') }}
                    </a>
                    
                    {{-- HERRAMIENTAS GERENCIALES --}}
                    @if(Auth::user()->role_id === 1)
                        <a href="{{ route('attendance.index') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out
                           {{ request()->routeIs('attendance.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            {{ __('Pase de Lista') }}
                        </a>

                        <a href="{{ route('services.index') }}" 
                           class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out
                           {{ request()->routeIs('services.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            {{ __('Taller') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-300 bg-slate-800 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- OPCIÓN NUEVA: REGISTRAR EMPLEADO --}}
                        @if(Auth::user()->role_id === 1)
                            <x-dropdown-link :href="route('employees.create')">
                                {{ __('Registrar Nuevo Empleado') }}
                            </x-dropdown-link>
                            <div class="border-t border-slate-100"></div>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mi Perfil') }}
                        </x-dropdown-link>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-800 border-t border-slate-700">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->role_id === 1)
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-slate-300 hover:text-white hover:bg-slate-700">
                    {{ __('Menu Principal') }}
                </x-responsive-nav-link>
            @endif
            
            <x-responsive-nav-link :href="route('inventory.index')" :active="request()->routeIs('inventory.*')" class="text-slate-300 hover:text-white hover:bg-slate-700">
                {{ __('Inventario') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')" class="text-slate-300 hover:text-white hover:bg-slate-700">
                {{ __('Punto de Venta') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role_id === 1)
                <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')" class="text-slate-300 hover:text-white hover:bg-slate-700">
                    {{ __('Pase de Lista') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')" class="text-slate-300 hover:text-white hover:bg-slate-700">
                    {{ __('Taller') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-slate-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if(Auth::user()->role_id === 1)
                    <x-responsive-nav-link :href="route('employees.create')" class="text-slate-300 hover:text-white hover:bg-slate-700">
                        {{ __('Registrar Empleado') }}
                    </x-responsive-nav-link>
                @endif

                <x-responsive-nav-link :href="route('profile.edit')" class="text-slate-300 hover:text-white hover:bg-slate-700">
                    {{ __('Mi Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="text-slate-300 hover:text-white hover:bg-slate-700"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>