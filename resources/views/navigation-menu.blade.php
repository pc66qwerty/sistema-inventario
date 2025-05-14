<nav x-data="{ open: false }"
     class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            {{-- Logo --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <x-application-mark class="block h-9 w-auto" />
                    <span class="font-semibold text-gray-900 dark:text-white text-lg hidden md:block">SisMaderera</span>
                </a>
            </div>

            {{-- Links Principales --}}
            <div class="hidden space-x-4 md:flex md:ml-6">
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    <x-heroicon-o-home class="w-5 h-5 me-1" />
                    <span>Inicio</span>
                </x-nav-link>

                <x-nav-link href="{{ route('ventas.index') }}" :active="request()->routeIs('ventas.*')">
                    <x-heroicon-o-shopping-cart class="w-5 h-5 me-1" />
                    <span>Ventas</span>
                </x-nav-link>

                <x-nav-link href="{{ route('inventario.index') }}" :active="request()->routeIs('inventario.*')">
                    <x-heroicon-o-cube class="w-5 h-5 me-1" />
                    <span>Inventario</span>
                </x-nav-link>

                <x-nav-link href="{{ route('reportes.index') }}" :active="request()->routeIs('reportes.*')">
                    <x-heroicon-o-chart-bar class="w-5 h-5 me-1" />
                    <span>Reportes</span>
                </x-nav-link>

                @if(Auth::user()->hasAccessTo('usuarios') || Auth::user()->role === 'jefe')
                    <x-nav-link href="{{ route('usuarios.index') }}" :active="request()->routeIs('usuarios.*')">
                        <x-heroicon-o-users class="w-5 h-5 me-1" />
                        <span>Usuarios</span>
                    </x-nav-link>
                @endif
            </div>

            {{-- Dark Mode + Usuario --}}
            <div class="hidden sm:flex sm:items-center gap-3">
                {{-- Botón modo claro/oscuro --}}
                <button
                    x-data="{ modo: document.documentElement.classList.contains('dark') ? 'oscuro' : 'claro' }"
                    @click="
                        modo = (modo === 'oscuro') ? 'claro' : 'oscuro';
                        localStorage.setItem('modo', modo);
                        document.documentElement.classList.toggle('dark');
                    "
                    class="inline-flex items-center justify-center text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md p-2 transition focus:outline-none focus:ring focus:ring-blue-500"
                >
                    <x-heroicon-o-sun class="w-5 h-5 hidden dark:block" />
                    <x-heroicon-o-moon class="w-5 h-5 block dark:hidden" />
                </button>


                {{-- Dropdown de usuario --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 bg-white dark:bg-gray-800 rounded-md transition">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden md:block">
                                    <span>{{ Auth::user()->name }}</span>
                                    <span class="text-xs block text-gray-500 dark:text-gray-400">{{ ucfirst(Auth::user()->role) }}</span>
                                </div>
                            </div>
                            <x-heroicon-o-chevron-down class="w-4 h-4 ml-2" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('profile.show') }}" class="flex items-center gap-2">
                            <x-heroicon-o-user-circle class="w-5 h-5" />
                            <span>Mi Perfil</span>
                        </x-dropdown-link>

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                            <x-dropdown-link href="{{ route('api-tokens.index') }}" class="flex items-center gap-2">
                                <x-heroicon-o-key class="w-5 h-5" />
                                <span>API Tokens</span>
                            </x-dropdown-link>
                        @endif

                        <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="flex items-center gap-2">
                                <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                                <span>Cerrar sesión</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburguesa Mobile --}}
            <div class="sm:hidden">
                <button @click="open = !open"
                        aria-label="Menú"
                        aria-expanded="false"
                        class="p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-white focus:outline-none transition">
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

    {{-- Menú Responsive --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="flex items-center gap-2">
                <x-heroicon-o-home class="w-5 h-5" />
                <span>Inicio</span>
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('ventas.index') }}" :active="request()->routeIs('ventas.*')" class="flex items-center gap-2">
                <x-heroicon-o-shopping-cart class="w-5 h-5" />
                <span>Ventas</span>
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('inventario.index') }}" :active="request()->routeIs('inventario.*')" class="flex items-center gap-2">
                <x-heroicon-o-cube class="w-5 h-5" />
                <span>Inventario</span>
            </x-responsive-nav-link>

            <x-responsive-nav-link href="{{ route('reportes.index') }}" :active="request()->routeIs('reportes.*')" class="flex items-center gap-2">
                <x-heroicon-o-chart-bar class="w-5 h-5" />
                <span>Reportes</span>
            </x-responsive-nav-link>

            @if(Auth::user()->hasAccessTo('usuarios') || Auth::user()->role === 'jefe')
                <x-responsive-nav-link href="{{ route('usuarios.index') }}" :active="request()->routeIs('usuarios.*')" class="flex items-center gap-2">
                    <x-heroicon-o-users class="w-5 h-5" />
                    <span>Usuarios</span>
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center px-4 py-2">
                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 font-medium text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link href="{{ route('profile.show') }}" class="flex items-center gap-2">
                    <x-heroicon-o-user-circle class="w-5 h-5" />
                    <span>Mi Perfil</span>
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" class="flex items-center gap-2">
                        <x-heroicon-o-key class="w-5 h-5" />
                        <span>API Tokens</span>
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="flex items-center gap-2">
                        <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                        <span>Cerrar sesión</span>
                    </x-responsive-nav-link>
                </form>

                {{-- Toggle modo oscuro en mobile --}}
                <div class="px-4 pt-2">
                    <button
                        @click="
                            const actual = document.documentElement.classList.contains('dark') ? 'oscuro' : 'claro';
                            const nuevo = actual === 'oscuro' ? 'claro' : 'oscuro';
                            localStorage.setItem('modo', nuevo);
                            document.documentElement.classList.toggle('dark');
                        "
                        class="w-full flex items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md px-4 py-2 transition"
                    >
                        <x-heroicon-o-sun class="w-5 h-5 hidden dark:block" />
                        <x-heroicon-o-moon class="w-5 h-5 block dark:hidden" />
                        <span x-text="document.documentElement.classList.contains('dark') ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>