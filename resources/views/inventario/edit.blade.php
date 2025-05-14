<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <x-icons.pencil-square class="w-6 h-6 text-indigo-600 dark:text-indigo-300" />
            Editar Producto
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 shadow-2xl rounded-2xl p-8 mt-10 ring-1 ring-gray-200 dark:ring-gray-700">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-500/20 dark:text-red-300 shadow">
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <x-icons.exclamation-triangle class="w-4 h-4" />
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inventario.update', $producto->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @foreach ([
                'nombre' => 'Nombre',
                'tipo_arbol' => 'Tipo de Árbol',
                'medida' => 'Medida',
                'predio' => 'Predio',
                'unidad' => 'Unidad',
            ] as $field => $label)
                <div>
                    <label for="{{ $field }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $label }}</label>
                    <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $producto->$field) }}"
                           placeholder="Ingrese {{ strtolower($label) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           required>
                </div>
            @endforeach

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="stock" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Stock</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $producto->stock) }}"
                           placeholder="Ej: 100"
                           class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           required>
                </div>

                <div>
                    <label for="precio_unitario" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Precio Unitario</label>
                    <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" value="{{ old('precio_unitario', $producto->precio_unitario) }}"
                           placeholder="Ej: 25.75"
                           class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           required>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('inventario.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-md transition font-medium">
                    <x-icons.arrow-left class="w-4 h-4" />
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md transition-all duration-300 shadow-md focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <x-icons.check-badge class="w-5 h-5" />
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
