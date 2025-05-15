<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['cliente', 'detalles', 'user'])
                  ->orderBy('created_at', 'desc')
                  ->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        // Generar número de boleta automáticamente
        $ultimaBoleta = Venta::orderBy('id', 'desc')->first();
        $siguienteNumero = $ultimaBoleta ? (int)preg_replace('/[^0-9]/', '', $ultimaBoleta->boleta_numero) + 1 : 1;
        $boletaNumero = 'B-' . str_pad($siguienteNumero, 4, '0', STR_PAD_LEFT);
        
        // Obtener productos disponibles para la venta (solo los que tienen stock)
        $productos = Producto::where('stock', '>', 0)->get();
        
        return view('ventas.create', compact('boletaNumero', 'productos'));
    }

    public function store(Request $request)
    {
        // Validar datos básicos
        $request->validate([
            'boleta_numero' => 'required|unique:ventas',
            'fecha' => 'required|date',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_documento' => 'nullable|string|max:50',
            'cliente_telefono' => 'nullable|string|max:50',
            'cliente_direccion' => 'nullable|string|max:255',
            'entregado_por' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'nullable|exists:productos,id',
            'detalles.*.descripcion' => 'required|string',
            'detalles.*.tipo_arbol' => 'required|string',
            'detalles.*.medida' => 'required|string',
            'detalles.*.unidad' => 'required|string',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.valor_unitario' => 'required|numeric|min:0',
            'detalles.*.total' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            // Buscar o crear cliente
            $cliente = Cliente::firstOrCreate(
                ['documento' => $request->cliente_documento],
                [
                    'nombre' => $request->cliente_nombre,
                    'telefono' => $request->cliente_telefono,
                    'direccion' => $request->cliente_direccion,
                ]
            );

            // Crear la venta
            $venta = Venta::create([
                'boleta_numero' => $request->boleta_numero,
                'fecha' => $request->fecha,
                'cliente' => $cliente->nombre, // Agregar el nombre del cliente para compatibilidad
                'cliente_id' => $cliente->id,
                'user_id' => Auth::id(),
                'entregado_por' => $request->entregado_por ?? Auth::user()->name,
                'observaciones' => $request->observaciones
            ]);

            // Procesar cada detalle de la venta
            foreach ($request->detalles as $detalle) {
                // Calcular el total correcto (cantidad * valor_unitario)
                $cantidad = (int)$detalle['cantidad'];
                $valorUnitario = (float)$detalle['valor_unitario'];
                $total = $cantidad * $valorUnitario;
                
                $nuevoDetalle = [
                    'venta_id' => $venta->id,
                    'descripcion' => $detalle['descripcion'],
                    'tipo_arbol' => $detalle['tipo_arbol'],
                    'medida' => $detalle['medida'],
                    'unidad' => $detalle['unidad'],
                    'cantidad' => $cantidad,
                    'valor_unitario' => $valorUnitario,
                    'total' => $total,
                ];
                
                // Añadir producto_id si existe
                if (!empty($detalle['producto_id'])) {
                    $nuevoDetalle['producto_id'] = $detalle['producto_id'];
                    
                    // Actualizar stock del producto
                    $producto = Producto::find($detalle['producto_id']);
                    if ($producto) {
                        $producto->stock -= $cantidad;
                        $producto->save();
                    }
                }
                
                DetalleVenta::create($nuevoDetalle);
            }
        });

        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente.');
    }
    
    /**
     * Buscar clientes para autocompletado
     */
    public function buscarClientes(Request $request)
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
    
    
    /**
     * Mostrar detalles de una venta específica (para el modal)
     */
    public function show(Venta $venta)
    {
        // Cargar los detalles de la venta
        $venta->load(['detalles.producto', 'cliente', 'user']);
        
        if (request()->ajax()) {
            return response()->json($venta);
        }
        
        return view('ventas.show', compact('venta'));
    }
}