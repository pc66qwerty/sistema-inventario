<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Mostrar la página de reportes
     */
    public function index(Request $request)
    {
        // Obtener fechas del request o usar valores por defecto (último mes)
        $fechaHasta = $request->input('fecha_hasta', Carbon::now()->format('Y-m-d'));
        $fechaDesde = $request->input('fecha_desde', Carbon::now()->subMonth()->format('Y-m-d'));

        // Validar y formatear las fechas
        $fechaDesde = $this->validarFecha($fechaDesde);
        $fechaHasta = $this->validarFecha($fechaHasta);

        // Consultar ventas agrupadas por día
        $ventasPorDia = $this->obtenerVentasPorDia($fechaDesde, $fechaHasta);

        // Preparar datos para el gráfico
        $labels = $ventasPorDia->pluck('fecha_formateada');
        $totales = $ventasPorDia->pluck('total');

        // Calcular estadísticas
        $totalVentas = $ventasPorDia->sum('total');
        $promedioVenta = $ventasPorDia->avg('total');
        $cantidadVentas = Venta::whereBetween('fecha', [$fechaDesde, $fechaHasta])->count();
        
        // Calcular productos más vendidos
        $productosPopulares = $this->obtenerProductosPopulares($fechaDesde, $fechaHasta);

        return view('reportes.index', compact(
            'fechaDesde',
            'fechaHasta',
            'labels',
            'totales',
            'totalVentas',
            'promedioVenta',
            'cantidadVentas',
            'productosPopulares',
            'ventasPorDia'
        ));
    }

    /**
     * Exportar reporte a PDF
     */
    public function exportarPDF(Request $request)
    {
        // Obtener fechas del request o usar valores por defecto
        $fechaHasta = $request->input('fecha_hasta', Carbon::now()->format('Y-m-d'));
        $fechaDesde = $request->input('fecha_desde', Carbon::now()->subMonth()->format('Y-m-d'));

        // Validar y formatear las fechas
        $fechaDesde = $this->validarFecha($fechaDesde);
        $fechaHasta = $this->validarFecha($fechaHasta);

        // Consultar ventas agrupadas por día
        $ventas = $this->obtenerVentasPorDia($fechaDesde, $fechaHasta);
        
        // Calcular totales
        $totalGeneral = $ventas->sum('total');
        
        // Calcular productos más vendidos
        $productosPopulares = $this->obtenerProductosPopulares($fechaDesde, $fechaHasta);

        // Formatear fechas para mostrar
        $fechaDesdeFormato = Carbon::parse($fechaDesde)->format('d/m/Y');
        $fechaHastaFormato = Carbon::parse($fechaHasta)->format('d/m/Y');

        // Crear PDF
        $pdf = PDF::loadView('reportes.pdf', compact(
            'ventas', 
            'fechaDesdeFormato', 
            'fechaHastaFormato', 
            'totalGeneral',
            'productosPopulares'
        ));

        // Nombre del archivo
        $nombreArchivo = 'reporte_ventas_' . Carbon::now()->format('Y_m_d_His') . '.pdf';

        // Descargar PDF
        return $pdf->download($nombreArchivo);
    }

    /**
     * Validar y formatear fecha
     */
    private function validarFecha($fecha)
    {
        try {
            return Carbon::parse($fecha)->format('Y-m-d');
        } catch (\Exception $e) {
            return Carbon::now()->format('Y-m-d');
        }
    }

    /**
     * Obtener ventas agrupadas por día
     */
    private function obtenerVentasPorDia($fechaDesde, $fechaHasta)
    {
        $ventasPorDia = DB::table('ventas')
            ->join('detalle_ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->select(
                'ventas.fecha',
                DB::raw('SUM(detalle_ventas.total) as total'),
                DB::raw('COUNT(DISTINCT ventas.id) as cantidad')
            )
            ->whereBetween('ventas.fecha', [$fechaDesde, $fechaHasta])
            ->groupBy('ventas.fecha')
            ->orderBy('ventas.fecha')
            ->get();

        // Añadir fecha formateada para mostrar
        $ventasPorDia->map(function ($venta) {
            $venta->fecha_formateada = Carbon::parse($venta->fecha)->format('d/m/Y');
            return $venta;
        });

        return $ventasPorDia;
    }

    /**
     * Obtener productos más vendidos
     */
    private function obtenerProductosPopulares($fechaDesde, $fechaHasta)
    {
        return DB::table('detalle_ventas')
            ->join('ventas', 'ventas.id', '=', 'detalle_ventas.venta_id')
            ->select(
                'detalle_ventas.descripcion',
                'detalle_ventas.tipo_arbol',
                DB::raw('SUM(detalle_ventas.cantidad) as cantidad_total'),
                DB::raw('SUM(detalle_ventas.total) as total_vendido')
            )
            ->whereBetween('ventas.fecha', [$fechaDesde, $fechaHasta])
            ->groupBy('detalle_ventas.descripcion', 'detalle_ventas.tipo_arbol')
            ->orderBy('cantidad_total', 'desc')
            ->limit(5)
            ->get();
    }
}