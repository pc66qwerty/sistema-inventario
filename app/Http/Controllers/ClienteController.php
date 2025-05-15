<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Mostrar un listado de los clientes.
     */
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Mostrar el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Almacenar un nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'nullable|string|max:50|unique:clientes,documento',
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Mostrar los datos de un cliente específico.
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Mostrar el formulario para editar un cliente.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar un cliente específico en la base de datos.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'documento' => 'nullable|string|max:50|unique:clientes,documento,'.$cliente->id,
            'telefono' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Eliminar un cliente específico de la base de datos.
     */
    public function destroy(Cliente $cliente)
    {
        // Verificar si el cliente tiene ventas asociadas
        if ($cliente->ventas()->count() > 0) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede eliminar el cliente porque tiene ventas asociadas.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }

    /**
     * Buscar clientes para autocompletado (API)
     */
    public function buscar(Request $request)
    {
        $query = $request->input('query', '');
        
        // Si la consulta está vacía, devolver los 10 clientes más recientes
        if (empty($query)) {
            $clientes = Cliente::orderBy('created_at', 'desc')
                        ->take(10)
                        ->get(['id', 'nombre', 'documento', 'telefono', 'direccion']);
        } else {
            // Buscar por nombre o documento
            $clientes = Cliente::where('nombre', 'LIKE', "%{$query}%")
                        ->orWhere('documento', 'LIKE', "%{$query}%")
                        ->take(10)
                        ->get(['id', 'nombre', 'documento', 'telefono', 'direccion']);
        }
        
        return response()->json($clientes);
    }
}