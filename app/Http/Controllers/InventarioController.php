<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventarioController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        // Si hay un mensaje en la URL, pasarlo como variable de sesión
        if (request()->has('success')) {
            session()->flash('success', request('success'));
        }
        return view('inventario.index', compact('productos'));
    }

    public function create()
    {
        return view('inventario.create');
    }

    public function store(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'tipo_arbol' => 'required',
            'medida' => 'required',
            'predio' => 'required',
            'unidad' => 'required',
            'stock' => 'required|integer|min:0',
            'precio_unitario' => 'required|numeric|min:0',
        ]);

        // Si la validación falla, devolver errores en formato JSON para AJAX
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Crear el producto
        $producto = Producto::create($request->all());

        // Respuesta según el tipo de solicitud
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto agregado correctamente.',
                'producto' => $producto
            ]);
        }

        return redirect()->route('inventario.index')->with('success', 'Producto agregado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return view('inventario.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'tipo_arbol' => 'required',
            'medida' => 'required',
            'predio' => 'required',
            'unidad' => 'required',
            'stock' => 'required|integer|min:0',
            'precio_unitario' => 'required|numeric|min:0',
        ]);

        // Si la validación falla, devolver errores en formato JSON para AJAX
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Actualizar el producto
        $producto->update($request->only([
            'nombre',
            'tipo_arbol',
            'medida',
            'predio',
            'unidad',
            'stock',
            'precio_unitario',
        ]));

        // Respuesta según el tipo de solicitud
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado correctamente.',
                'producto' => $producto
            ]);
        }

        return redirect()->route('inventario.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente.'
            ]);
        }

        return redirect()->route('inventario.index')->with('success', 'Producto eliminado correctamente.');
    }
}