@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <x-heroicon-o-users class="w-7 h-7 text-purple-600 dark:text-purple-400" />
            Gestión de Usuarios
        </h1>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" 
                       id="busqueda" 
                       placeholder="Buscar usuario..." 
                       class="w-full pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-400 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm transition-colors">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                </div>
            </div>
            
            <a href="{{ route('usuarios.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow transition w-full sm:w-auto">
                <x-heroicon-o-plus class="w-5 h-5" />
                Nuevo Usuario
            </a>
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
                        <th class="px-6 py-3 text-left font-semibold">Email</th>
                        <th class="px-6 py-3 text-left font-semibold">Rol</th>
                        <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="tabla-usuarios">
                    @forelse ($usuarios as $usuario)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-100 font-medium">{{ $usuario->name }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-100">{{ $usuario->email }}</td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-100">
                                <x-user-role-badge :role="$usuario->role" />
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="#" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition" title="Editar usuario">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </a>
                                    @if(auth()->id() !== $usuario->id)
                                        <form action="#" method="POST"
                                              class="inline-block" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition" title="Eliminar usuario">
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center py-6">
                                    <x-heroicon-o-users class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" />
                                    <span>No hay usuarios registrados.</span>
                                    <a href="{{ route('usuarios.create') }}" class="mt-3 text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 transition">
                                        Crear un usuario
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Búsqueda simple en JavaScript vanilla
    document.addEventListener('DOMContentLoaded', function() {
        const inputBusqueda = document.getElementById('busqueda');
        
        if (inputBusqueda) {
            inputBusqueda.addEventListener('keyup', function() {
                const terminoBusqueda = this.value.toLowerCase();
                const filas = document.querySelectorAll('#tabla-usuarios tr');
                
                filas.forEach(function(fila) {
                    if (fila.querySelector('td')) {  // Verificar que no sea una fila de "no hay usuarios"
                        const nombre = fila.cells[0].textContent.toLowerCase();
                        const email = fila.cells[1].textContent.toLowerCase();
                        const rol = fila.cells[2].textContent.toLowerCase();
                        
                        if (nombre.includes(terminoBusqueda) || 
                            email.includes(terminoBusqueda) || 
                            rol.includes(terminoBusqueda)) {
                            fila.style.display = '';
                        } else {
                            fila.style.display = 'none';
                        }
                    }
                });
                
                // Mostrar mensaje si no hay resultados
                let hayResultados = false;
                filas.forEach(function(fila) {
                    if (fila.style.display !== 'none' && fila.querySelector('td')) {
                        hayResultados = true;
                    }
                });
                
                // Si no hay resultados y estamos buscando algo, mostrar mensaje
                const mensajeNoResultados = document.getElementById('mensaje-no-resultados');
                if (!hayResultados && terminoBusqueda) {
                    if (!mensajeNoResultados) {
                        const tbody = document.getElementById('tabla-usuarios');
                        const tr = document.createElement('tr');
                        tr.id = 'mensaje-no-resultados';
                        tr.innerHTML = `
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center py-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <span>No se encontraron usuarios que coincidan con la búsqueda.</span>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    }
                } else if (mensajeNoResultados) {
                    mensajeNoResultados.remove();
                }
            });
        }
    });
</script>
@endpush

@endsection