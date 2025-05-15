@extends('layouts.app')

@section('title', 'Ventas')

@section('content')

<div x-data="ventasApp()" class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- Encabezado --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <x-heroicon-o-clipboard-document-list class="w-7 h-7 text-blue-600 dark:text-blue-400" />
            Ventas Registradas
        </h1>

        <a href="{{ route('ventas.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
            <x-heroicon-o-plus class="w-5 h-5" />
            Nueva Venta
        </a>
    </div>

    {{-- Mensajes de éxito o error --}}
    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded-md">
            <div class="flex items-center">
                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                <span class="font-medium text-green-700 dark:text-green-400">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-md">
            <div class="flex items-center">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-500 mr-2" />
                <span class="font-medium text-red-700 dark:text-red-400">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Tabla --}}
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold"># Boleta</th>
                        <th class="px-6 py-3 text-left font-semibold">Fecha</th>
                        <th class="px-6 py-3 text-left font-semibold">Cliente</th>
                        <th class="px-6 py-3 text-left font-semibold">Total</th>
                        <th class="px-6 py-3 text-left font-semibold">Entregado por</th>
                        <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($ventas as $venta)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $venta->boleta_numero }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $venta->cliente }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">Q {{ number_format($venta->detalles->sum('total'), 2) }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $venta->entregado_por }}</td>
                            <td class="px-6 py-3 text-right">
                                <button @click="verDetalle({{ $venta->id }})" 
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition" 
                                    title="Ver Detalle">
                                    <x-heroicon-o-eye class="w-5 h-5" />
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                No hay ventas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Paginación --}}
        <div class="px-6 py-3">
            {{ $ventas->links() }}
        </div>
    </div>

    {{-- Modal de detalle de venta --}}
    <div x-show="modalDetalle" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         @keydown.escape.window="cerrarModalDetalle()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div x-show="modalDetalle" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @click.away="cerrarModalDetalle()"
             class="relative w-full max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-y-auto max-h-[90vh] p-6 space-y-6">

            {{-- Botón cerrar --}}
            <button @click="cerrarModalDetalle()"
                    class="absolute top-4 right-4 text-gray-500 dark:text-gray-400 hover:text-red-600 transition">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>

            {{-- Título --}}
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <x-heroicon-o-document-text class="w-6 h-6 text-blue-500" />
                Detalle de Venta <span x-text="detalleVenta.boleta_numero" class="ml-2"></span>
            </h2>

            {{-- Spinner de carga --}}
            <div x-show="cargando" class="flex justify-center py-8">
                <svg class="animate-spin h-10 w-10 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            {{-- Contenido del detalle --}}
            <div x-show="!cargando">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Información General</h3>
                        <div class="mt-2 space-y-2">
                            <p class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Fecha:</span> 
                                <span x-text="formatearFecha(detalleVenta.fecha)" class="font-medium text-gray-900 dark:text-white"></span>
                            </p>
                            <p class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Cliente:</span> 
                                <span x-text="detalleVenta.cliente" class="font-medium text-gray-900 dark:text-white"></span>
                            </p>
                            <p class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Documento:</span> 
                                <span x-text="detalleVenta.cliente_documento || '-'" class="font-medium text-gray-900 dark:text-white"></span>
                            </p>
                            <p class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Teléfono:</span> 
                                <span x-text="detalleVenta.cliente_telefono || '-'" class="font-medium text-gray-900 dark:text-white"></span>
                            </p>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Datos de Entrega</h3>
                        <div class="mt-2 space-y-2">
                            <p class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Vendedor:</span> 
                                <span x-text="detalleVenta.user ? detalleVenta.user.name : '-'" class="font-medium text-gray-900 dark:text-white"></span>
                            </p>
                            <p class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Entregado por:</span> 
                                <span x-text="detalleVenta.entregado_por || '-'" class="font-medium text-gray-900 dark:text-white"></span>
                            </p>
                            <p class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Fecha registro:</span> 
                                <span x-text="formatearFechaCompleta(detalleVenta.created_at)" class="font-medium text-gray-900 dark:text-white"></span>
                            </p>
                            <p class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Observaciones:</span> 
                                <span x-text="detalleVenta.observaciones || 'Sin observaciones'" class="font-medium text-gray-900 dark:text-white"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2 mb-3">
                    <x-heroicon-o-list-bullet class="w-5 h-5 text-green-500" /> Detalle de Productos
                </h3>

                <div class="overflow-x-auto bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descripción</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo Árbol</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Medida</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unidad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio Unit.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="(detalle, index) in detalleVenta.detalles" :key="index">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100" x-text="detalle.descripcion"></td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100" x-text="detalle.tipo_arbol"></td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100" x-text="detalle.medida"></td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100" x-text="detalle.unidad"></td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100" x-text="detalle.cantidad"></td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100" x-text="'Q ' + parseFloat(detalle.valor_unitario).toFixed(2)"></td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100" x-text="'Q ' + parseFloat(detalle.total).toFixed(2)"></td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">Total:</td>
                                <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-white" x-text="'Q ' + calcularTotalDetalle()"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
    /* Estilos base para todos los inputs */
    .input-form {
        width: 100%;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        border: 1px solid #e5e7eb;
        background-color: #ffffff;
        color: #111827;
        transition: all 150ms ease;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .input-form:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
    }

    /* Modo oscuro */
    html.dark .input-form {
        background-color: #374151;
        color: #f9fafb;
        border-color: #4b5563;
    }

    html.dark .input-form:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.25);
    }

    /* Placeholder */
    .input-form::placeholder {
        color: #9ca3af;
    }

    html.dark .input-form::placeholder {
        color: #d1d5db;
    }

    /* Ocultar elementos con Alpine.js */
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function ventasApp() {
        return {
            modalDetalle: false,
            cargando: false,
            detalleVenta: {},
            
            verDetalle(id) {
                this.modalDetalle = true;
                this.cargando = true;
                document.body.classList.add('overflow-hidden');
                
                // Cargar los datos de la venta mediante AJAX
                fetch(`/ventas/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.detalleVenta = data;
                    this.cargando = false;
                })
                .catch(error => {
                    console.error('Error cargando detalles:', error);
                    this.cargando = false;
                });
            },
            
            cerrarModalDetalle() {
                this.modalDetalle = false;
                document.body.classList.remove('overflow-hidden');
            },
            
            formatearFecha(fecha) {
                if (!fecha) return '-';
                const date = new Date(fecha);
                return date.toLocaleDateString('es-GT');
            },
            
            formatearFechaCompleta(fecha) {
                if (!fecha) return '-';
                const date = new Date(fecha);
                return date.toLocaleDateString('es-GT') + ' ' + date.toLocaleTimeString('es-GT');
            },
            
            calcularTotalDetalle() {
                if (!this.detalleVenta.detalles) return '0.00';
                
                const total = this.detalleVenta.detalles.reduce((sum, detalle) => {
                    return sum + parseFloat(detalle.total || 0);
                }, 0);
                
                return total.toFixed(2);
            }
        }
    }
</script>
@endpush

@endsection
