@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
    
    {{-- Encabezado --}}
    <div class="flex items-center gap-2">
        <x-heroicon-o-pencil-square class="w-7 h-7 text-purple-600 dark:text-purple-400" />
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Usuario</h1>
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
        <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre Completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $usuario->name) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                           placeholder="Ingrese el nombre del usuario" required>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $usuario->email) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                           placeholder="ejemplo@correo.com" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Contraseña --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nueva Contraseña <span class="text-xs font-normal text-gray-500">(opcional)</span></label>
                    <input type="password" id="password" name="password"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                           placeholder="Dejar en blanco para mantener la actual">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">La contraseña debe tener al menos 6 caracteres</p>
                </div>

                {{-- Confirmar Contraseña --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                           placeholder="Dejar en blanco para mantener la actual">
                </div>
            </div>

            {{-- Rol --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol de Usuario</label>
                <select id="role" name="role"
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors"
                        required>
                    <option value="jefe" {{ old('role', $usuario->role) == 'jefe' ? 'selected' : '' }}>Jefe (Acceso completo)</option>
                    <option value="inventario" {{ old('role', $usuario->role) == 'inventario' ? 'selected' : '' }}>Usuario de Inventario</option>
                    <option value="vendedor" {{ old('role', $usuario->role) == 'vendedor' ? 'selected' : '' }}>Usuario Vendedor</option>
                </select>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    <span class="font-medium">Jefe:</span> Acceso completo a todas las funciones del sistema.
                    <br>
                    <span class="font-medium">Usuario de Inventario:</span> Gestión de productos y control de stock.
                    <br>
                    <span class="font-medium">Usuario Vendedor:</span> Registro de ventas y consultas.
                </p>
            </div>

            {{-- Estado de la cuenta (opcional) --}}
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="active" value="1" class="h-4 w-4 rounded text-purple-600 focus:ring-purple-500 border-gray-300" 
                           {{ old('active', $usuario->active ?? true) ? 'checked' : '' }}>
                    <span>Cuenta activa</span>
                </label>
                <p class="mt-1 ml-6 text-xs text-gray-500 dark:text-gray-400">
                    Si esta opción está desactivada, el usuario no podrá iniciar sesión en el sistema.
                </p>
            </div>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                @if(Auth::user()->id !== $usuario->id)
                    <button type="button"
                            data-modal-target="deleteUserModal"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow sm:order-last">
                        <x-heroicon-o-trash class="w-5 h-5" />
                        Eliminar Usuario
                    </button>
                @endif
                
                <a href="{{ route('usuarios.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    Cancelar
                </a>
                
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow transition-colors">
                    <x-heroicon-o-check class="w-5 h-5" />
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    {{-- Otras Secciones Relacionadas --}}
    @if(Auth::user()->id !== $usuario->id)
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <x-heroicon-o-shield-exclamation class="w-5 h-5 text-amber-500" />
                Acciones Adicionales
            </h2>
            
            {{-- Botón para Resetear Contraseña (enviar email) --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    Envía un correo electrónico al usuario con un enlace para restablecer su contraseña.
                </p>
                <form action="{{ route('password.email') }}" method="POST" class="flex justify-start">
                    @csrf
                    <input type="hidden" name="email" value="{{ $usuario->email }}">
                    <button type="submit" class="inline-flex items-center gap-2 text-sm text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300">
                        <x-heroicon-o-envelope class="w-4 h-4" />
                        Enviar enlace de recuperación
                    </button>
                </form>
            </div>
            
            {{-- Botón para Forzar Cierre de Sesión en todos los dispositivos --}}
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    Cierra sesión de este usuario en todos los dispositivos donde esté conectado.
                </p>
                <form action="{{ route('usuarios.logout-everywhere', $usuario->id) }}" method="POST" class="flex justify-start">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                        <x-heroicon-o-lock-closed class="w-4 h-4" />
                        Forzar cierre de sesión
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Última actividad --}}
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <x-heroicon-o-clock class="w-5 h-5 text-blue-500" />
            Información de la Cuenta
        </h2>
        
        <div class="space-y-3">
            <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">Fecha de creación:</span>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $usuario->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            
            <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">Última actualización:</span>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $usuario->updated_at->format('d/m/Y H:i:s') }}</span>
            </div>
            
            @if($usuario->last_login_at)
            <div class="flex justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">Último inicio de sesión:</span>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($usuario->last_login_at)->format('d/m/Y H:i:s') }}</span>
            </div>
            @endif
            
            @if($usuario->last_active_at)
            <div class="flex justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">Última actividad:</span>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($usuario->last_active_at)->format('d/m/Y H:i:s') }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal de confirmación para eliminar usuario --}}
<div id="deleteUserModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <div class="p-6 text-center">
                <x-heroicon-o-exclamation-triangle class="mx-auto mb-4 text-red-500 w-12 h-12" />
                <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-white">¿Estás seguro de eliminar este usuario?</h3>
                <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">
                    Esta acción no se puede deshacer. Se eliminarán todos los datos asociados a este usuario del sistema.
                </p>
                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                        <x-heroicon-o-trash class="w-5 h-5" />
                        Sí, eliminar usuario
                    </button>
                    <button type="button" data-modal-hide="deleteUserModal" class="mt-3 sm:mt-0 sm:ml-3 px-5 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg">
                        Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Manejador para el modal - Requiere Flowbite u otra librería similar
    document.addEventListener('DOMContentLoaded', function() {
        // Si estás usando Tailwind/AlpineJS sin librería adicional, necesitarás implementar la lógica del modal aquí
        const modalTriggers = document.querySelectorAll('[data-modal-target]');
        const modalCloses = document.querySelectorAll('[data-modal-hide]');
        
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                const modalId = trigger.getAttribute('data-modal-target');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            });
        });
        
        modalCloses.forEach(close => {
            close.addEventListener('click', () => {
                const modalId = close.getAttribute('data-modal-hide');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });
    });
</script>
@endpush

@endsection