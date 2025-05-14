<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- Logo --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-mark class="block h-9 w-auto" />
                </a>
            </div>

            {{-- Menú principal --}}
            <div class="hidden sm:flex sm:items-center sm:ml-10 space-x-8">
                @if(auth()->user()->hasAccessTo('ventas'))
                    <x-nav-link href="{{ route('ventas.index') }}" :active="request()->routeIs('ventas.*')">
                        <x-heroicon-o-receipt-refund class="w-5 h-5" />
                        Ventas
                    </x-nav-link>
                @endif

                @if(auth()->user()->hasAccessTo('inventario'))
                    <x-nav-link href="{{ route('inventario.index') }}" :active="request()->routeIs('inventario.*')">
                        <x-heroicon-o-cube class="w-5 h-5" />
                        Inventario
                    </x-nav-link>
                @endif

                @if(auth()->user()->hasAccessTo('usuarios'))
                    <x-nav-link href="{{ route('usuarios.index') }}" :active="request()->routeIs('usuarios.*')">
                        <x-heroicon-o-user class="w-5 h-5" />
                        Usuarios
                    </x-nav-link>
                @endif
            </div>

            {{-- Opciones de usuario --}}
            <div class="hidden sm:flex sm:items-center gap-4">
                {{-- Modo claro/oscuro --}}
                <button
                    x-data="{ modo: document.documentElement.classList.contains('dark') ? 'oscuro' : 'claro' }"
                    @click="
                        modo = (modo === 'oscuro') ? 'claro' : 'oscuro';
                        localStorage.setItem('modo', modo);
                        document.documentElement.classList.toggle('dark');
                    "
                    x-text="modo === 'oscuro' ? '🌞 Claro' : '🌙 Oscuro'"
                    class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md px-3 py-2 transition"
                ></button>

                {{-- Usuario logueado --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 bg-white dark:bg-gray-800 rounded-md transition">
                            {{ Auth::user()->name }}
                            <x-heroicon-o-chevron-down class="w-4 h-4 ml-2" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('profile.show') }}">
                            Perfil
                        </x-dropdown-link>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                API Tokens
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Menú móvil --}}
            <div class="sm:hidden">
                <button @click="open = !open" class="p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Menú responsive --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->hasAccessTo('ventas'))
                <x-responsive-nav-link href="{{ route('ventas.index') }}" :active="request()->routeIs('ventas.*')">
                    🧾 Ventas
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasAccessTo('inventario'))
                <x-responsive-nav-link href="{{ route('inventario.index') }}" :active="request()->routeIs('inventario.*')">
                    📦 Inventario
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasAccessTo('usuarios'))
                <x-responsive-nav-link href="{{ route('usuarios.index') }}" :active="request()->routeIs('usuarios.*')">
                    👤 Usuarios
                </x-responsive-nav-link>
            @endif
        </div>

        {{-- Info usuario + logout --}}
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}">
                    Perfil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
