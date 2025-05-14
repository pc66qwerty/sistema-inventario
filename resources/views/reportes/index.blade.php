@extends('layouts.app')

@section('title', 'Reportes de Ventas')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- Encabezado --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <x-heroicon-o-chart-bar class="w-7 h-7 text-blue-600 dark:text-blue-400" />
            Reporte de Ventas por Fecha
        </h1>

        <a href="{{ route('reportes.pdf', ['fecha_desde' => request('fecha_desde'), 'fecha_hasta' => request('fecha_hasta')]) }}"
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow transition">
            <x-heroicon-o-printer class="w-5 h-5" />
            Exportar PDF
        </a>
    </div>

    {{-- Filtros --}}
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden p-6">
        <form method="GET" action="{{ route('reportes.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="fecha_desde" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Desde</label>
                <input id="fecha_desde" type="date" name="fecha_desde" value="{{ $fechaDesde }}" 
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
            </div>
            <div>
                <label for="fecha_hasta" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Hasta</label>
                <input id="fecha_hasta" type="date" name="fecha_hasta" value="{{ $fechaHasta }}" 
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
            </div>
            <div>
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
                    <x-heroicon-o-funnel class="w-5 h-5" />
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    {{-- Estadísticas Rápidas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Ventas</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Q {{ number_format($totalVentas ?? 0, 2) }}</h3>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                        <x-heroicon-o-banknotes class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cantidad de Ventas</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $cantidadVentas ?? 0 }}</h3>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg">
                        <x-heroicon-o-document-text class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Promedio por Venta</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Q {{ number_format($promedioVenta ?? 0, 2) }}</h3>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-2 rounded-lg">
                        <x-heroicon-o-calculator class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráfico --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <x-heroicon-o-presentation-chart-bar class="w-5 h-5 text-blue-500" />
                Ventas por Día
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Periodo: {{ \Carbon\Carbon::parse($fechaDesde)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($fechaHasta)->format('d/m/Y') }}
            </div>
        </div>
        <div class="w-full h-80">
            <canvas id="ventasChart"></canvas>
        </div>
    </div>
    
    {{-- Tabla Top Productos --}}
    @if(isset($productosPopulares) && count($productosPopulares) > 0)
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <x-heroicon-o-fire class="w-5 h-5 text-orange-500" />
                Top 5 Productos Más Vendidos
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">#</th>
                        <th class="px-6 py-3 text-left font-semibold">Producto</th>
                        <th class="px-6 py-3 text-left font-semibold">Tipo Árbol</th>
                        <th class="px-6 py-3 text-left font-semibold">Cantidad</th>
                        <th class="px-6 py-3 text-left font-semibold">Total Vendido</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($productosPopulares as $index => $producto)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100 font-medium">{{ $producto->descripcion }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $producto->tipo_arbol }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $producto->cantidad_total }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">Q {{ number_format($producto->total_vendido, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Tabla de Ventas Diarias --}}
    @if(isset($ventasPorDia) && count($ventasPorDia) > 0)
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <x-heroicon-o-calendar class="w-5 h-5 text-purple-500" />
                Ventas por Día
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Fecha</th>
                        <th class="px-6 py-3 text-left font-semibold">Cantidad de Ventas</th>
                        <th class="px-6 py-3 text-left font-semibold">Total Vendido</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($ventasPorDia as $venta)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $venta->fecha_formateada }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $venta->cantidad }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">Q {{ number_format($venta->total, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-100 dark:bg-gray-600 font-semibold">
                        <td class="px-6 py-3 text-gray-800 dark:text-gray-100">TOTAL</td>
                        <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $ventasPorDia->sum('cantidad') }}</td>
                        <td class="px-6 py-3 text-gray-800 dark:text-gray-100">Q {{ number_format($ventasPorDia->sum('total'), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    html.dark input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ventasChart').getContext('2d');
        
        // Detectar si estamos en modo oscuro para ajustar los colores
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#f3f4f6' : '#1f2937';
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
        
        const ventasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Total de ventas (Q)',
                    data: {!! json_encode($totales) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
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
        
        // Añadir botón para alternar entre gráfico de barras y líneas si es necesario
        const toggleButton = document.getElementById('toggleChart');
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                ventasChart.config.type = ventasChart.config.type === 'bar' ? 'line' : 'bar';
                ventasChart.update();
            });
        }
    });
</script>
@endpush

@endsection