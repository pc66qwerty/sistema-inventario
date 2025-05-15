@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 space-y-6">
    
    {{-- Encabezado --}}
    <div class="flex items-center gap-2">
        <x-heroicon-o-pencil-square class="w-7 h-7 text-yellow-600 dark:text-yellow-400" />
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Cliente</h1>
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
    <form method="POST" action="{{ route('clientes.update', $cliente) }}">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-6 space-y-6">
            
            {{-- Datos del cliente --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nombre --}}
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre *</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                
                {{-- Documento --}}
                <div>
                    <label for="documento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Documento</label>
                    <input type="text" id="documento" name="documento" value="{{ old('documento', $cliente->documento) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                
                {{-- Teléfono --}}
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                
                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección</label>
                    <input type="text" id="direccion" name="direccion" value="{{ old('direccion', $cliente->direccion) }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="text-xs text-gray-500 dark:text-gray-400">
                * Campos obligatorios
            </div>
        </div>
        
        {{-- Botones de acción --}}
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('clientes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancelar
            </a>
            
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <x-heroicon-o-check class="h-5 w-5 mr-2" /> Actualizar Cliente
            </button>
        </div>
    </form>

</div>
@endsection