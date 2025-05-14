<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
         $ventas = Venta::with('detalles')->get(); // Asegúrate de usar with('detalles') si aplicás relaciones
    return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        return view('ventas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'boleta_numero' => 'required|unique:ventas',
            'fecha' => 'required|date',
            'cliente' => 'required|string',
            'entregado_por' => 'nullable|string',
            'observaciones' => 'nullable|string',

            'descripcion.*' => 'required|string',
            'tipo_arbol.*' => 'required|string',
            'medida.*' => 'required|string',
            'unidad.*' => 'required|string',
            'cantidad.*' => 'required|integer|min:1',
            'valor_unitario.*' => 'required|numeric|min:0',
            'total.*' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $venta = Venta::create($request->only([
                'boleta_numero', 'fecha', 'cliente', 'entregado_por', 'observaciones'
            ]));

            foreach ($request->descripcion as $i => $desc) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'descripcion' => $desc,
                    'tipo_arbol' => $request->tipo_arbol[$i],
                    'medida' => $request->medida[$i],
                    'unidad' => $request->unidad[$i],
                    'cantidad' => $request->cantidad[$i],
                    'valor_unitario' => $request->valor_unitario[$i],
                    'total' => $request->total[$i],
                ]);
            }
        });

        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente.');
    }
}
