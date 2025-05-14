<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('modo') === 'oscuro' }"
      x-bind:class="{ 'dark': darkMode }"
      x-init="$watch('darkMode', val => localStorage.setItem('modo', val ? 'oscuro' : 'claro'))"
      x-cloak>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Sistema Inventario'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1f2937">
    <link rel="apple-touch-icon" href="/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- AlpineJS (si no está en app.js) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(() => console.log('✅ Service Worker registrado correctamente'))
                .catch(err => console.error('❌ Error al registrar SW:', err));
        }
    </script>

    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

    {{-- Banner superior de Jetstream --}}
    <x-banner />

    {{-- Menú de navegación (reemplaza por tu nav personalizado si usas @include) --}}
    @livewire('navigation-menu')

    {{-- Encabezado dinámico --}}
    @hasSection('header')
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @yield('header')
            </div>
        </header>
    @endif

    {{-- Contenido principal --}}
    <main>
        @yield('content')
    </main>

    {{-- Modales y scripts Livewire --}}
    @stack('modals')
    @livewireScripts

    {{-- Scripts adicionales --}}
    @stack('scripts')
</body>
</html>
