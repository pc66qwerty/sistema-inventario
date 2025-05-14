@extends('layouts.app')

@section('title', 'Panel de Control')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    
    <!-- Encabezado -->
    <div class="flex items-center gap-2">
        <x-heroicon-o-chart-bar class="w-7 h-7 text-blue-600 dark:text-blue-400" />
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Panel de Control</h1>
    </div>

    <!-- Tarjetas resumen -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Total de ventas -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Ventas</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            Q {{ number_format(\App\Models\DetalleVenta::sum('total'), 2) }}
                        </h3>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                        <x-heroicon-o-currency-dollar class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <a href="{{ route('ventas.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                        <span>Ver todas las ventas</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Productos registrados -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Productos</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ \App\Models\Producto::count() }}
                        </h3>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg">
                        <x-heroicon-o-cube class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <a href="{{ route('inventario.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                        <span>Ver inventario</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Productos con stock bajo -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock Bajo</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ \App\Models\Producto::where('stock', '>', 0)->where('stock', '<=', 10)->count() }}
                        </h3>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-2 rounded-lg">
                        <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <a href="{{ route('inventario.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                        <span>Ver productos críticos</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Usuarios -->
        @if(auth()->user()->role === 'jefe')
        <div class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Usuarios</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ \App\Models\User::count() }}
                        </h3>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 p-2 rounded-lg">
                        <x-heroicon-o-users class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                    </div>
                </div>
                <div class="mt-4 text-sm">
                    <a href="{{ route('usuarios.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                        <span>Administrar usuarios</span>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Secciones Principal y Secundaria -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sección Principal - Gráfico de Ventas -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <x-heroicon-o-presentation-chart-bar class="w-5 h-5 text-blue-500" />
                Ventas Recientes
            </h2>
            <div class="h-64">
                <canvas id="ventasChart"></canvas>
            </div>
        </div>

        <!-- Sección Secundaria - Accesos Rápidos y Resumen -->
        <div class="space-y-6">
            <!-- Accesos Rápidos -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                    <x-heroicon-o-bolt class="w-5 h-5 text-yellow-500" />
                    Accesos Rápidos
                </h2>

                <div class="flex flex-col gap-3">
                    @if(auth()->user()->hasAccessTo('ventas'))
                    <a href="{{ route('ventas.create') }}" class="inline-flex items-center justify-between gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-plus class="w-5 h-5" />
                            <span>Nueva Venta</span>
                        </div>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                    @endif

                    @if(auth()->user()->hasAccessTo('inventario'))
                    <a href="{{ route('inventario.create') }}" class="inline-flex items-center justify-between gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-plus class="w-5 h-5" />
                            <span>Nuevo Producto</span>
                        </div>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                    @endif

                    @if(auth()->user()->hasAccessTo('jefe'))
                    <a href="{{ route('reportes.index') }}" class="inline-flex items-center justify-between gap-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg shadow transition">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-document-chart-bar class="w-5 h-5" />
                            <span>Ver Reportes</span>
                        </div>
                        <x-heroicon-o-arrow-right class="w-4 h-4" />
                    </a>
                    @endif
                </div>
            </div>

            <!-- Últimas Ventas -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                    <x-heroicon-o-receipt-percent class="w-5 h-5 text-blue-500" />
                    Últimas Ventas
                </h2>

                <div class="space-y-4">
                    @forelse(\App\Models\Venta::with('detalles')->latest()->take(4)->get() as $venta)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-3 last:border-0 last:pb-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Boleta: {{ $venta->boleta_numero }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $venta->cliente }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900 dark:text-white">
                                    Q {{ number_format($venta->detalles->sum('total'), 2) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 dark:text-gray-400">No hay ventas recientes</p>
                    @endforelse

                    <div class="pt-2">
                        <a href="{{ route('ventas.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm flex items-center justify-center gap-1">
                            <span>Ver todas las ventas</span>
                            <x-heroicon-o-arrow-right class="w-4 h-4" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos Recientes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <x-heroicon-o-cube class="w-5 h-5 text-green-500" />
            Productos Recientes
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo Árbol</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse(\App\Models\Producto::latest()->take(5)->get() as $producto)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $producto->nombre }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $producto->tipo_arbol }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <span class="px-2 py-1 text-xs rounded-full font-medium
                                @if($producto->stock <= 0) 
                                    bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                @elseif($producto->stock > 0 && $producto->stock <= 10)
                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @else
                                    bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @endif
                            ">
                                {{ $producto->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            Q {{ number_format($producto->precio_unitario, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('inventario.edit', $producto->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-500">
                                Editar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                            No hay productos recientes
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('inventario.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm inline-flex items-center gap-1">
                <span>Ver todos los productos</span>
                <x-heroicon-o-arrow-right class="w-4 h-4" />
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ventasChart').getContext('2d');
        
        // Datos de ejemplo - Puedes reemplazar esto con datos reales de PHP
        const fechas = {!! json_encode(
            \App\Models\Venta::select('fecha')
                ->orderBy('fecha', 'desc')
                ->take(7)
                ->get()
                ->map(function($venta) { 
                    return \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y'); 
                })
                ->reverse()
                ->values()
        ) !!};
        
        const totales = {!! json_encode(
            \App\Models\Venta::select('id', 'fecha')
                ->with('detalles')
                ->orderBy('fecha', 'desc')
                ->take(7)
                ->get()
                ->map(function($venta) { 
                    return $venta->detalles->sum('total'); 
                })
                ->reverse()
                ->values()
        ) !!};
        
        // Detectar si estamos en modo oscuro
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#f3f4f6' : '#1f2937';
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: fechas,
                datasets: [{
                    label: 'Ventas (Q)',
                    data: totales,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Q ' + new Intl.NumberFormat('es-GT').format(context.parsed.y.toFixed(2));
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor,
                            callback: function(value) {
                                return 'Q ' + value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection