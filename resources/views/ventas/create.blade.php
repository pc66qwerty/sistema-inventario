@extends('layouts.app')
@section('title', 'Nueva Venta')
@section('content')
<div x-data="ventaForm()" x-init="() => { console.log('Formulario de venta inicializado'); }" class="max-w-7xl mx-auto px-4 py-6 space-y-6">
{{-- Encabezado --}}
<div class="flex items-center gap-2">
    <x-heroicon-o-document-plus class="w-7 h-7 text-green-600 dark:text-green-400" />
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Registrar Nueva Venta</h1>
</div>

{{-- Mensajes de error --}}
@if ($errors->any())
    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-md">
        <div class="flex items-center mb-1">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-500 mr-2" />
            <span class="font-medium text-red-700 dark:text-red-400">Hay errores en el formulario:</span>
        </div>
        <ul class="ml-5 list-disc text-red-700 dark:text-red-400">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Formulario --}}
<form method="POST" action="{{ route('ventas.store') }}">
    @csrf
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-6 space-y-6">
        
        {{-- Datos principales de la venta --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Número de boleta (generado automáticamente) --}}
            <div>
                <label for="boleta_numero" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nº Boleta <span class="text-xs text-gray-500">(generado automáticamente)</span>
                </label>
                <input type="text" id="boleta_numero" name="boleta_numero" value="{{ $boletaNumero }}" readonly
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-3 py-2 text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            
            {{-- Fecha --}}
            <div>
                <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            
            {{-- Cliente (con autocompletado) --}}
            <div x-data="clienteAutocomplete()" class="relative">
                <div class="flex justify-between items-center mb-1">
                    <label for="cliente_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                    <a href="{{ route('clientes.index') }}" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 flex items-center">
                        <x-heroicon-o-user-plus class="w-3 h-3 mr-1" />
                        Gestionar Clientes
                    </a>
                </div>
                <input type="text" id="cliente_nombre" name="cliente_nombre" required
                       x-model="query"
                       @input="buscarClientes"
                       @focus="mostrarSugerencias = true"
                       @click.away="mostrarSugerencias = false"
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                       placeholder="Nombre del cliente">
                
                {{-- Campos ocultos para información del cliente --}}
                <input type="hidden" id="cliente_documento" name="cliente_documento" x-model="clienteDocumento">
                <input type="hidden" id="cliente_telefono" name="cliente_telefono" x-model="clienteTelefono">
                <input type="hidden" id="cliente_direccion" name="cliente_direccion" x-model="clienteDireccion">
                       
                {{-- Sugerencias de autocompletado --}}
                <div x-show="mostrarSugerencias && sugerencias.length > 0" 
                     class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg">
                    <ul class="max-h-60 overflow-auto py-1">
                        <template x-for="(cliente, index) in sugerencias" :key="index">
                            <li @click="seleccionarCliente(cliente)"
                                class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300">
                                <div x-text="cliente.nombre" class="font-medium"></div>
                                <div x-show="cliente.documento" class="text-xs text-gray-500 dark:text-gray-400" x-text="'Doc: ' + cliente.documento"></div>
                            </li>
                        </template>
                    </ul>
                </div>
                
                {{-- Información del cliente seleccionado --}}
                <div x-show="clienteDocumento || clienteTelefono" class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <div x-show="clienteDocumento" class="flex">
                        <span class="font-medium mr-2">Documento:</span>
                        <span x-text="clienteDocumento"></span>
                    </div>
                    <div x-show="clienteTelefono" class="flex">
                        <span class="font-medium mr-2">Teléfono:</span>
                        <span x-text="clienteTelefono"></span>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Detalle de productos --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <x-heroicon-o-cube class="w-5 h-5 text-green-500" />
                Detalle de Productos
            </h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Producto</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo Árbol</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Medida</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unidad</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio Unit.</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="productos-container">
                        <template x-for="(detalle, index) in detalles" :key="'detalle_'+index">
                            <tr>
                                <td class="px-3 py-2">
                                    <div class="relative" x-data="{ mostrarProductos: false }">
                                        <input type="text" x-model="detalle.descripcion" :name="'detalles['+index+'][descripcion]'" 
                                               @focus="mostrarProductos = true" @click.away="mostrarProductos = false"
                                               class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                        <input type="hidden" x-model="detalle.producto_id" :name="'detalles['+index+'][producto_id]'">
                                        
                                        <!-- Lista de productos disponibles -->
                                        <div x-show="mostrarProductos" class="absolute z-10 w-full bg-white dark:bg-gray-700 mt-1 rounded-md shadow-lg max-h-60 overflow-auto">
                                            <ul>
                                                @foreach($productos as $producto)
                                                <li @click="seleccionarProducto(index, {{ $producto->id }}, '{{ $producto->nombre }}', '{{ $producto->tipo_arbol }}', '{{ $producto->medida }}', '{{ $producto->unidad }}', {{ $producto->precio_unitario }})" 
                                                    class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer text-sm flex justify-between">
                                                    <span>{{ $producto->nombre }}</span>
                                                    <span class="text-gray-500 dark:text-gray-400">({{ $producto->stock }} disponibles)</span>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" x-model="detalle.tipo_arbol" :name="'detalles['+index+'][tipo_arbol]'" 
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" x-model="detalle.medida" :name="'detalles['+index+'][medida]'" 
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" x-model="detalle.unidad" :name="'detalles['+index+'][unidad]'" 
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" x-model="detalle.cantidad" :name="'detalles['+index+'][cantidad]'" min="1" 
                                           @input="calcularTotal(index)"
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" x-model="detalle.valor_unitario" :name="'detalles['+index+'][valor_unitario]'" step="0.01" min="0" 
                                           @input="calcularTotal(index)"
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" x-model="detalle.total" :name="'detalles['+index+'][total]'" readonly
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                                </td>
                                <td class="px-3 py-2">
                                    <button type="button" @click="eliminarDetalle(index)" :disabled="detalles.length === 1"
                                            class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                                        <x-heroicon-o-minus class="h-4 w-4" />
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 flex justify-between">
                <button type="button" @click.prevent="agregarDetalle()" 
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <x-heroicon-o-plus class="h-4 w-4 mr-1" /> Agregar producto
                </button>
                
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total venta:</div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white" x-text="'Q ' + totalVenta.toFixed(2)"></div>
                </div>
            </div>
        </div>
        
        {{-- Datos adicionales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="entregado_por" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Entregado por</label>
                <input type="text" id="entregado_por" name="entregado_por" value="{{ Auth::user()->name }}" 
                       class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                       placeholder="Persona que entrega el producto">
            </div>
            
            <div>
                <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="2"
                          class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                          placeholder="Observaciones adicionales"></textarea>
            </div>
        </div>
    </div>
    
    {{-- Botones de acción --}}
    <div class="mt-6 flex justify-end gap-3">
        <a href="{{ route('ventas.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Cancelar
        </a>
        
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <x-heroicon-o-check class="h-5 w-5 mr-2" /> Guardar venta
        </button>
    </div>
</form>
</div>
@endsection
@push('scripts')
<script>
    function ventaForm() {
        return {
            detalles: [
                {
                    producto_id: null,
                    descripcion: '',
                    tipo_arbol: '',
                    medida: '',
                    unidad: '',
                    cantidad: 1,
                    valor_unitario: 0,
                    total: 0
                }
            ],
            get totalVenta() {
                return this.detalles.reduce((sum, detalle) => {
                    return sum + (parseFloat(detalle.total) || 0);
                }, 0);
            },
            agregarDetalle() {
                this.detalles.push({
                    producto_id: null,
                    descripcion: '',
                    tipo_arbol: '',
                    medida: '',
                    unidad: '',
                    cantidad: 1,
                    valor_unitario: 0,
                    total: 0
                });
                // Forzar a Alpine a actualizar la vista
                this.$nextTick(() => {
                    // Esto garantiza que Alpine haya actualizado la vista 
                    // antes de intentar interactuar con el nuevo detalle
                    console.log('Producto agregado. Total de productos:', this.detalles.length);
                });
            },
            eliminarDetalle(index) {
                if (this.detalles.length > 1) {
                    this.detalles.splice(index, 1);
                }
            },
            calcularTotal(index) {
                const detalle = this.detalles[index];
                const cantidad = parseFloat(detalle.cantidad) || 0;
                const valorUnitario = parseFloat(detalle.valor_unitario) || 0;
                this.detalles[index].total = (cantidad * valorUnitario).toFixed(2);
            },
            seleccionarProducto(index, id, nombre, tipoArbol, medida, unidad, precio) {
                this.detalles[index].producto_id = id;
                this.detalles[index].descripcion = nombre;
                this.detalles[index].tipo_arbol = tipoArbol;
                this.detalles[index].medida = medida;
                this.detalles[index].unidad = unidad;
                this.detalles[index].valor_unitario = precio;
                this.calcularTotal(index);
            }
        }
    }
    
    function clienteAutocomplete() {
        return {
            query: '',
            sugerencias: [],
            mostrarSugerencias: false,
            clienteId: null,
            clienteDocumento: '',
            clienteTelefono: '',
            clienteDireccion: '',
            timeout: null,
            
            init() {
                // Cargar clientes recientes al iniciar
                this.buscarClientes();
            },
            
            buscarClientes() {
                // Limpiar el timeout anterior para evitar múltiples llamadas
                clearTimeout(this.timeout);
                
                // Debounce: esperar a que el usuario deje de escribir
                this.timeout = setTimeout(() => {
                    fetch(`/ventas/buscar-clientes?query=${this.query}`)
                        .then(response => response.json())
                        .then(data => {
                            this.sugerencias = data;
                            this.mostrarSugerencias = data.length > 0;
                        })
                        .catch(error => {
                            console.error('Error buscando clientes:', error);
                        });
                }, 300);
            },
            
            seleccionarCliente(cliente) {
                // Actualizamos todos los campos relacionados con el cliente
                this.query = cliente.nombre;
                this.clienteId = cliente.id;
                this.clienteDocumento = cliente.documento || '';
                this.clienteTelefono = cliente.telefono || '';
                this.clienteDireccion = cliente.direccion || '';
                
                // Actualizar los campos ocultos del formulario
                document.getElementById('cliente_nombre').value = cliente.nombre;
                document.getElementById('cliente_documento').value = cliente.documento || '';
                document.getElementById('cliente_telefono').value = cliente.telefono || '';
                document.getElementById('cliente_direccion').value = cliente.direccion || '';
                
                this.mostrarSugerencias = false;
                
                console.log("Cliente seleccionado:", cliente);
            }
        }
    }
</script>
@endpush