@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">
    
    {{-- Encabezado --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <x-heroicon-o-users class="w-7 h-7 text-blue-600 dark:text-blue-400" />
            Gestión de Clientes
        </h1>

        <a href="{{ route('clientes.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow transition">
            <x-heroicon-o-plus class="w-5 h-5" />
            Nuevo Cliente
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
                        <th class="px-6 py-3 text-left font-semibold">Nombre</th>
                        <th class="px-6 py-3 text-left font-semibold">Documento</th>
                        <th class="px-6 py-3 text-left font-semibold">Teléfono</th>
                        <th class="px-6 py-3 text-left font-semibold">Dirección</th>
                        <th class="px-6 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($clientes as $cliente)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $cliente->nombre }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $cliente->documento ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $cliente->telefono ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ $cliente->direccion ?? '-' }}</td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('clientes.edit', $cliente) }}" 
                                       class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 transition" 
                                       title="Editar">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" 
                                          onsubmit="return confirm('¿Está seguro de que desea eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition" 
                                                title="Eliminar">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                No hay clientes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Paginación --}}
        <div class="px-6 py-3">
            {{ $clientes->links() }}
        </div>
    </div>

</div>
@endsection