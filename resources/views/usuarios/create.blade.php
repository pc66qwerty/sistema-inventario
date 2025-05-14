@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
    
    {{-- Encabezado --}}
    <div class="flex items-center gap-2">
        <x-heroicon-o-user-plus class="w-7 h-7 text-purple-600 dark:text-purple-400" />
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Crear Nuevo Usuario</h1>
    </div>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg dark:bg-red-900/20 dark:text-red-400 dark:border-red-500">
            <div class="flex items-center mb-2">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 mr-2" />
                <span class="font-medium">Por favor corrige los siguientes errores:</span>
            </div>
            <ul class="ml-5 list-disc space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario --}}
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden p-6">
        <form method="POST" action="{{ route('usuarios.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre Completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                           placeholder="Ingrese el nombre del usuario" required>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                           placeholder="ejemplo@correo.com" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Contraseña --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña</label>
                    <input type="password" id="password" name="password"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                           placeholder="********" required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">La contraseña debe tener al menos 6 caracteres</p>
                </div>

                {{-- Confirmar Contraseña --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                           placeholder="********" required>
                </div>
            </div>

            {{-- Rol --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol de Usuario</label>
                <select id="role" name="role"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                        required>
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Seleccione un rol</option>
                    <option value="jefe" {{ old('role') == 'jefe' ? 'selected' : '' }}>Jefe (Acceso completo)</option>
                    <option value="inventario" {{ old('role') == 'inventario' ? 'selected' : '' }}>Usuario de Inventario</option>
                    <option value="vendedor" {{ old('role') == 'vendedor' ? 'selected' : '' }}>Usuario Vendedor</option>
                </select>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    <span class="font-medium">Jefe:</span> Acceso completo a todas las funciones del sistema.
                    <br>
                    <span class="font-medium">Usuario de Inventario:</span> Gestión de productos y control de stock.
                    <br>
                    <span class="font-medium">Usuario Vendedor:</span> Registro de ventas y consultas.
                </p>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('usuarios.index') }}"
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow transition-colors">
                    <x-heroicon-o-user-plus class="w-5 h-5" />
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>

    {{-- Ayuda --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 text-blue-700 dark:text-blue-400 p-4 rounded-lg">
        <div class="flex items-center mb-1">
            <x-heroicon-o-information-circle class="w-5 h-5 mr-2" />
            <span class="font-medium">Información</span>
        </div>
        <p class="text-sm">Los usuarios creados podrán acceder al sistema utilizando el correo electrónico y la contraseña proporcionados en este formulario. Asegúrate de asignar el rol correcto según las necesidades del usuario.</p>
    </div>

</div>

@endsection

