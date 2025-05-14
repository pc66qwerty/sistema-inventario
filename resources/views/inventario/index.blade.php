@extends('layouts.app')

@section('title', 'Inventario')

@section('content')

<div x-data="inventarioApp()" class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <x-heroicon-o-cube class="w-7 h-7 text-blue-600 dark:text-blue-400" />
            Inventario de Productos
        </h1>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" 
                       x-model="busqueda" 
                       @input="filtrarProductos()"
                       placeholder="Buscar producto..." 
                       class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                </div>
            </div>
            
            <button @click="abrirModalCreacion()"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition w-full sm:w-auto">
                <x-heroicon-o-plus class="w-5 h-5" />
                Agregar Producto
            </button>
        </div>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md" role="alert">
        <div class="flex items-center">
            <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    {{-- Tabla --}}
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Nombre</th>
                        <th class="px-6 py-3 text-left font-semibold">Tipo árbol</th>
                        <th class="px-6 py-3 text-left font-semibold">Medida</th>
                        <th class="px-6 py-3 text-left font-semibold">Predio</th>
                        <th class="px-6 py-3 text-left font-semibold">Stock</th>
                        <th class="px-6 py-3 text-left font-semibold">Unidad</th>
                        <th class="px-6 py-3 text-left font-semibold">Precio Unitario</th>
                        <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($productos as $producto)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition 
                            @if($producto->stock <= 0) bg-red-50 dark:bg-red-900/20 @elseif($producto->stock > 0 && $producto->stock <= 10) bg-yellow-50 dark:bg-yellow-900/20 @endif">
                            <td class="px-6 py-3 text-gray-700 dark:text-gray-100">{{ $producto->nombre }}</td>
                            <td class="px-6 py-3 text-gray-700 dark:text-gray-100">{{ $producto->tipo_arbol }}</td>
                            <td class="px-6 py-3 text-gray-700 dark:text-gray-100">{{ $producto->medida }}</td>
                            <td class="px-6 py-3 text-gray-700 dark:text-gray-100">{{ $producto->predio }}</td>
                            <td class="px-6 py-3 text-gray-700 dark:text-gray-100">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($producto->stock <= 0) 
                                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @elseif($producto->stock > 0 && $producto->stock <= 10)
                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @else
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @endif">
                                    {{ $producto->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-700 dark:text-gray-100">Q {{ number_format((float) $producto->unidad, 2) }}</td>
                            <td class="px-6 py-3 text-gray-700 dark:text-gray-100">Q {{ number_format((float) $producto->precio_unitario, 2) }}</td>
                            <td class="px-6 py-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3">
                                    <button @click="abrirModalEdicion({{ $producto->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition" title="Editar producto">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </button>
                                    <button @click="confirmarEliminacion({{ $producto->id }}, '{{ $producto->nombre }}')" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition" title="Eliminar producto">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center py-6">
                                    <x-heroicon-o-cube class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" />
                                    <span>No hay productos registrados en el inventario.</span>
                                    <button @click="abrirModalCreacion()" class="mt-3 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition">
                                        Agregar un producto
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal de Creación/Edición de Producto --}}
    <div x-show="modalForm" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         @keydown.escape.window="cerrarModal()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div x-show="modalForm" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @click.away="cerrarModal()"
             class="relative w-full max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-y-auto max-h-[90vh] p-6">

            {{-- Botón cerrar --}}
            <button @click="cerrarModal()"
                    class="absolute top-4 right-4 text-gray-500 dark:text-gray-400 hover:text-red-600 transition">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>

            {{-- Título --}}
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-6">
                <span x-show="!productoId">
                    <x-heroicon-o-plus-circle class="w-6 h-6 text-green-500" />
                    Agregar Nuevo Producto
                </span>
                <span x-show="productoId">
                    <x-heroicon-o-pencil-square class="w-6 h-6 text-blue-500" />
                    Editar Producto
                </span>
            </h2>

            {{-- Errores --}}
            <div x-show="errores.length > 0" class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-500/20 dark:text-red-300 shadow-md">
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    <template x-for="error in errores" :key="error">
                        <li class="flex items-center gap-2">
                            <x-heroicon-o-exclamation-triangle class="w-4 h-4" />
                            <span x-text="error"></span>
                        </li>
                    </template>
                </ul>
            </div>

            {{-- Formulario --}}
            <form id="productoForm" @submit.prevent="guardarProducto" class="space-y-5">
                <input type="hidden" name="_token" x-bind:value="csrfToken">
                <template x-if="productoId">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                        <input type="text" id="nombre" name="nombre" x-model="form.nombre" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                    </div>
                    
                    <div>
                        <label for="tipo_arbol" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de árbol</label>
                        <input type="text" id="tipo_arbol" name="tipo_arbol" x-model="form.tipo_arbol" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="medida" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medida</label>
                        <input type="text" id="medida" name="medida" x-model="form.medida" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                    </div>
                    
                    <div>
                        <label for="predio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Predio</label>
                        <input type="text" id="predio" name="predio" x-model="form.predio" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="unidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unidad</label>
                        <input type="text" id="unidad" name="unidad" x-model="form.unidad" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                    </div>
                    
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock</label>
                        <input type="number" id="stock" name="stock" x-model="form.stock" min="0" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                    </div>
                    
                    <div>
                        <label for="precio_unitario" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Precio Unitario</label>
                        <input type="number" id="precio_unitario" name="precio_unitario" x-model="form.precio_unitario" step="0.01" min="0" required
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit"
                            :class="productoId ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700'"
                            class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-lg shadow transition">
                        <x-heroicon-o-check class="w-5 h-5" />
                        <span x-text="productoId ? 'Actualizar' : 'Guardar'"></span>
                    </button>
                    <button type="button" @click="cerrarModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal de Confirmación de Eliminación --}}
    <div x-show="modalEliminar" x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         @keydown.escape.window="modalEliminar = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div x-show="modalEliminar" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @click.away="modalEliminar = false"
             class="relative w-full max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden p-6">

            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                    <x-heroicon-o-exclamation-triangle class="h-6 w-6 text-red-600 dark:text-red-400" />
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">¿Eliminar producto?</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    ¿Estás seguro de eliminar el producto "<span class="font-semibold text-gray-700 dark:text-gray-300" x-text="productoNombre"></span>"? 
                    Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-center gap-3">
                    <form id="formEliminar" :action="'/inventario/' + productoId" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            Sí, eliminar
                        </button>
                    </form>
                    <button @click="modalEliminar = false" 
                            class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function inventarioApp() {
        return {
            productos: @json($productos),
            productosFiltrados: [],
            busqueda: '',
            modalForm: false,
            modalEliminar: false,
            productoId: null,
            productoNombre: '',
            errores: [],
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            form: {
                nombre: '',
                tipo_arbol: '',
                medida: '',
                predio: '',
                unidad: '',
                stock: 0,
                precio_unitario: 0
            },
            
            init() {
                this.productosFiltrados = this.productos;
            },
            
            filtrarProductos() {
                const busquedaLower = this.busqueda.toLowerCase().trim();
                
                if (busquedaLower === '') {
                    this.productosFiltrados = this.productos;
                } else {
                    this.productosFiltrados = this.productos.filter(producto => {
                        return producto.nombre.toLowerCase().includes(busquedaLower) || 
                               producto.tipo_arbol.toLowerCase().includes(busquedaLower) ||
                               producto.predio.toLowerCase().includes(busquedaLower);
                    });
                }
            },
            
            abrirModalCreacion() {
                this.productoId = null;
                this.form = {
                    nombre: '',
                    tipo_arbol: '',
                    medida: '',
                    predio: '',
                    unidad: '',
                    stock: 0,
                    precio_unitario: 0
                };
                this.errores = [];
                this.modalForm = true;
                document.body.classList.add('overflow-hidden');
            },
            
            abrirModalEdicion(id) {
                this.productoId = id;
                this.errores = [];
                
                // Buscar el producto por ID
                const producto = this.productos.find(p => p.id === id);
                
                if (producto) {
                    this.form = {
                        nombre: producto.nombre,
                        tipo_arbol: producto.tipo_arbol,
                        medida: producto.medida,
                        predio: producto.predio,
                        unidad: producto.unidad,
                        stock: producto.stock,
                        precio_unitario: producto.precio_unitario
                    };
                    
                    this.modalForm = true;
                    document.body.classList.add('overflow-hidden');
                }
            },
            
            cerrarModal() {
                this.modalForm = false;
                document.body.classList.remove('overflow-hidden');
            },
            
            confirmarEliminacion(id, nombre) {
                this.productoId = id;
                this.productoNombre = nombre;
                this.modalEliminar = true;
            },
            
            guardarProducto() {
                this.errores = [];
                
                // Validación básica
                if (!this.form.nombre) this.errores.push('El nombre es obligatorio');
                if (!this.form.tipo_arbol) this.errores.push('El tipo de árbol es obligatorio');
                if (!this.form.medida) this.errores.push('La medida es obligatoria');
                if (!this.form.predio) this.errores.push('El predio es obligatorio');
                if (!this.form.unidad) this.errores.push('La unidad es obligatoria');
                if (isNaN(this.form.stock) || this.form.stock < 0) this.errores.push('El stock debe ser un número positivo');
                if (isNaN(this.form.precio_unitario) || this.form.precio_unitario <= 0) this.errores.push('El precio unitario debe ser mayor que 0');
                
                if (this.errores.length > 0) return;
                
                // Enviar formulario
                const formData = new FormData();
                formData.append('_token', this.csrfToken);
                
                if (this.productoId) {
                    formData.append('_method', 'PUT');
                }
                
                Object.keys(this.form).forEach(key => {
                    formData.append(key, this.form[key]);
                });
                
                const url = this.productoId 
                    ? `/inventario/${this.productoId}` 
                    : '/inventario';
                
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            if (data.errors) {
                                Object.values(data.errors).forEach(messages => {
                                    messages.forEach(message => this.errores.push(message));
                                });
                                throw new Error('Validación fallida');
                            } else {
                                throw new Error('Error en el servidor');
                            }
                        });
                    }
                    return response;
                })
                .then(() => {
                    // Redirigir a la página de inventario con un mensaje de éxito
                    window.location.href = '/inventario?success=' + encodeURIComponent(
                        this.productoId ? 'Producto actualizado correctamente.' : 'Producto agregado correctamente.'
                    );
                })
                .catch(error => {
                    if (error.message !== 'Validación fallida') {
                        this.errores.push(error.message || 'Error al guardar el producto');
                    }
                });
            }
        }
    }
</script>
@endpush

@endsection