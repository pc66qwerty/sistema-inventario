<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <x-icons.plus-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
            Nuevo Producto
        </h2>
    </x-slot>

  <div class="min-h-[calc(100vh-8rem)] flex items-center justify-center px-4">
    <div class="w-full max-w-6xl backdrop-blur-xl bg-white/80 dark:bg-gray-900/70 border border-gray-200 dark:border-white/10 rounded-3xl p-10 shadow-2xl space-y-10 transition-all duration-300">
            </div>
</div>
{{-- Aquí va todo tu formulario --}}

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg dark:bg-red-500/20 dark:text-red-300 shadow-md">
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

        <form method="POST" action="{{ route('inventario.store') }}"
              class="space-y-6 bg-white dark:bg-gray-900 p-8 rounded-2xl shadow-2xl ring-1 ring-gray-200 dark:ring-gray-700">
            @csrf

            @foreach ([
                'nombre' => 'Nombre',
                'tipo_arbol' => 'Tipo de árbol',
                'medida' => 'Medida',
                'predio' => 'Predio',
                'unidad' => 'Unidad',
            ] as $field => $label)
                <div>
                    <label for="{{ $field }}" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $label }}</label>
                    <input type="text" name="{{ $field }}" id="{{ $field }}" value="{{ old($field) }}"
                           placeholder="Ingrese {{ strtolower($label) }}"
                           class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                           required>
                </div>
            @endforeach

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="stock" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Stock inicial</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                           placeholder="Ej: 50"
                           class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                           required>
                </div>

                <div>
                    <label for="precio_unitario" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Precio Unitario</label>
                    <input type="number" step="0.01" name="precio_unitario" id="precio_unitario" value="{{ old('precio_unitario') }}"
                           placeholder="Ej: 10.50"
                           class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                           required>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-2.5 rounded-lg transition-all duration-300 shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <x-icons.check-circle class="w-5 h-5" />
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
