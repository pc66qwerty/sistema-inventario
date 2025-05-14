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

        <button @click="openModal()"
            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
            <x-heroicon-o-plus class="w-5 h-5" />
            Nueva Venta
        </button>
    </div>

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
                                <a href="#" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition" title="Ver Detalle">
                                    <x-heroicon-o-eye class="w-5 h-5" />
                                </a>
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
    </div>

    {{-- Modal --}}
    <div x-show="modal" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         @keydown.escape.window="closeModal()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div x-show="modal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @click.away="closeModal()"
             class="relative w-full max-w-5xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-y-auto max-h-[90vh] p-6 space-y-6">

            {{-- Botón cerrar --}}
            <button @click="closeModal()"
                    class="absolute top-4 right-4 text-gray-500 dark:text-gray-400 hover:text-red-600 transition">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>

            {{-- Título --}}
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <x-heroicon-o-document-text class="w-6 h-6 text-blue-500" />
                Registrar Nueva Venta
            </h2>

            {{-- Formulario --}}
            <form id="formVenta" @submit.prevent="submitForm" method="POST" action="{{ route('ventas.store') }}">
                @csrf

                {{-- Datos principales --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label for="boleta_numero" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">N° Boleta</label>
                        <input id="boleta_numero" name="boleta_numero" type="text" placeholder="N° Boleta" 
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors" required>
                    </div>
                    <div>
                        <label for="fecha" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Fecha</label>
                        <input id="fecha" name="fecha" type="date" value="{{ now()->format('Y-m-d') }}" 
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors" required>
                    </div>
                    <div class="sm:col-span-2 md:col-span-1">
                        <label for="cliente" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                        <input id="cliente" name="cliente" type="text" placeholder="Nombre del cliente" 
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors" required>
                    </div>
                </div>

                {{-- Detalle productos --}}
                <div x-data="productosManager()" x-init="init()" class="mb-6">

                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                        <x-heroicon-o-cube class="w-5 h-5 text-green-500" /> Detalle de Productos
                    </h3>

                    <!-- Cabeceras de tabla - Solo visibles en pantallas medianas y grandes -->
                    <div class="hidden md:grid md:grid-cols-8 gap-3 mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <div class="col-span-2">Descripción</div>
                        <div>Tipo árbol</div>
                        <div>Medida</div>
                        <div>Unidad</div>
                        <div>Cantidad</div>
                        <div>Precio</div>
                        <div class="text-center">Acciones</div>
                    </div>

                    <template x-for="(item, index) in items" :key="index">
                        <!-- Vista escritorio -->
                        <div class="hidden md:grid md:grid-cols-8 gap-3 mb-3 items-center">
                            <div class="col-span-2">
                                <input x-model="item.descripcion" :name="'descripcion[]'" class="input-form" placeholder="Descripción" required>
                            </div>
                            <div>
                                <input x-model="item.tipo_arbol" :name="'tipo_arbol[]'" class="input-form" placeholder="Tipo árbol" required>
                            </div>
                            <div>
                                <input x-model="item.medida" :name="'medida[]'" class="input-form" placeholder="Medida" required>
                            </div>
                            <div>
                                <input x-model="item.unidad" :name="'unidad[]'" class="input-form" placeholder="Unidad" required>
                            </div>
                            <div>
                                <input x-model="item.cantidad" type="number" min="1" :name="'cantidad[]'" @input="calcularTotal(index)" class="input-form" placeholder="Cantidad" required>
                            </div>
                            <div>
                                <input x-model="item.valor_unitario" type="number" step="0.01" min="0" :name="'valor_unitario[]'" @input="calcularTotal(index)" class="input-form" placeholder="Precio" required>
                            </div>
                            <div class="flex items-center justify-around">
                                <span x-text="formatCurrency(item.total || 0)" class="font-medium text-gray-800 dark:text-gray-200"></span>
                                <input type="hidden" x-model="item.total" :name="'total[]'" :value="item.total">
                                
                                <button type="button"
                                        @click="removerProducto(index)"
                                        :disabled="items.length === 1"
                                        class="bg-red-500 hover:bg-red-600 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-full p-1 transition"
                                        title="Eliminar producto">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                        
                        <!-- Vista móvil -->
                        <div class="md:hidden mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-medium text-gray-700 dark:text-gray-200">Producto #<span x-text="index + 1"></span></span>
                                <button type="button"
                                    @click="removerProducto(index)"
                                    :disabled="items.length === 1"
                                    class="bg-red-500 hover:bg-red-600 disabled:bg-gray-400 disabled:cursor-not-allowed text-white rounded-full p-1 transition"
                                    title="Eliminar producto">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </button>
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Descripción</label>
                                    <input x-model="item.descripcion" :name="'descripcion[]'" class="input-form" placeholder="Descripción" required>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tipo árbol</label>
                                        <input x-model="item.tipo_arbol" :name="'tipo_arbol[]'" class="input-form" placeholder="Tipo árbol" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Medida</label>
                                        <input x-model="item.medida" :name="'medida[]'" class="input-form" placeholder="Medida" required>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Unidad</label>
                                        <input x-model="item.unidad" :name="'unidad[]'" class="input-form" placeholder="Unidad" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Cantidad</label>
                                        <input x-model="item.cantidad" type="number" min="1" :name="'cantidad[]'" @input="calcularTotal(index)" class="input-form" placeholder="Cantidad" required>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Precio</label>
                                        <input x-model="item.valor_unitario" type="number" step="0.01" min="0" :name="'valor_unitario[]'" @input="calcularTotal(index)" class="input-form" placeholder="Precio" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Total</label>
                                        <div class="input-form bg-gray-100 dark:bg-gray-600 flex items-center" x-text="formatCurrency(item.total || 0)"></div>
                                        <input type="hidden" x-model="item.total" :name="'total[]'" :value="item.total">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 mt-4">
                        <button type="button" @click="agregarProducto()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-sm rounded-lg shadow inline-flex items-center gap-2 transition w-full sm:w-auto justify-center sm:justify-start">
                            <x-heroicon-o-plus class="w-4 h-4" /> Agregar Producto
                        </button>
                        
                        <div class="text-right w-full sm:w-auto">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Venta:</span>
                            <span x-text="formatCurrency(calcularTotalVenta())" class="ml-2 font-bold text-lg text-gray-900 dark:text-white"></span>
                        </div>
                    </div>
                </div>

                {{-- Datos adicionales --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="entregado_por" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Entregado por</label>
                        <input id="entregado_por" name="entregado_por" type="text" placeholder="Nombre de quien entrega" 
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                    </div>
                    <div>
                        <label for="observaciones" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Observaciones</label>
                        <input id="observaciones" name="observaciones" type="text" placeholder="Observaciones adicionales" 
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow inline-flex items-center justify-center gap-2 transition-colors">
                        <x-heroicon-o-check-circle class="w-5 h-5" />
                        Guardar
                    </button>
                    <button type="button" @click="resetearFormulario()"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg shadow transition-colors">
                        Limpiar
                    </button>
                    <button type="button" @click="closeModal()"
                            class="px-5 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        Cancelar
                    </button>
                </div>
            </form>
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

    /* Asegurar que los calendarios se vean bien en modo oscuro */
    html.dark input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
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
            modal: false,
            
            openModal() {
                this.modal = true;
                document.body.classList.add('overflow-hidden');

            },
            
            closeModal() {
                this.modal = false;
                document.body.classList.remove('overflow-hidden');
            },
            
            resetearFormulario() {
                document.getElementById('formVenta').reset();
                // Reiniciar los productos
                window.dispatchEvent(new CustomEvent('reset-productos'));
            },
            
            submitForm() {
                // Aquí puedes validar el formulario antes de enviarlo
                // También podrías usar AJAX para enviar los datos si lo prefieres
                document.getElementById('formVenta').submit();
            }
        }
    }
    
    function productosManager() {
        return {
            items: [{
                descripcion: '',
                tipo_arbol: '',
                medida: '',
                unidad: '',
                cantidad: 1,
                valor_unitario: 0,
                total: 0
            }],
            
            init() {
                window.addEventListener('reset-productos', () => {
                    this.items = [{
                        descripcion: '',
                        tipo_arbol: '',
                        medida: '',
                        unidad: '',
                        cantidad: 1,
                        valor_unitario: 0,
                        total: 0
                    }];
                });
            },
            
            agregarProducto() {
                this.items.push({
                    descripcion: '',
                    tipo_arbol: '',
                    medida: '',
                    unidad: '',
                    cantidad: 1,
                    valor_unitario: 0,
                    total: 0
                });
            },
            
            removerProducto(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },
            
            calcularTotal(index) {
                const item = this.items[index];
                const cantidad = parseFloat(item.cantidad) || 0;
                const precio = parseFloat(item.valor_unitario) || 0;
                this.items[index].total = cantidad * precio;
                return this.items[index].total;
            },
            
            calcularTotalVenta() {
                return this.items.reduce((sum, item) => {
                    return sum + (parseFloat(item.total) || 0);
                }, 0);
            },
            
            formatCurrency(value) {
                return 'Q ' + parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }
        }
    }
</script>
@endpush

@endsection