blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            color: #333;
        }
        .header { 
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .header h1 {
            margin: 0;
            color: #2563eb;
            font-size: 22px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-box {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }
        .info-box p {
            margin: 5px 0;
        }
        .info-box .label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
            margin-bottom: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f3f4f6;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .total-row {
            font-weight: bold;
            background-color: #e5e7eb;
        }
        .section-title {
            color: #2563eb;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>REPORTE DE VENTAS</h1>
        <p>SisMaderera</p>
    </div>

    <div class="info-box">
        <p><span class="label">Periodo de Reporte:</span> {{ $fechaDesdeFormato }} al {{ $fechaHastaFormato }}</p>
        <p><span class="label">Total General:</span> Q {{ number_format($totalGeneral, 2) }}</p>
        <p><span class="label">Generado el:</span> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="section-title">Ventas por Día</div>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Total (Q)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $venta)
                <tr>
                    <td>{{ $venta->fecha_formateada }}</td>
                    <td>{{ $venta->cantidad }}</td>
                    <td>Q {{ number_format($venta->total, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTAL</td>
                <td>{{ $ventas->sum('cantidad') }}</td>
                <td>Q {{ number_format($totalGeneral, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if(isset($productosPopulares) && count($productosPopulares) > 0)
    <div class="section-title">Productos Más Vendidos</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Tipo Árbol</th>
                <th>Cantidad</th>
                <th>Total (Q)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productosPopulares as $index => $producto)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $producto->descripcion }}</td>
                    <td>{{ $producto->tipo_arbol }}</td>
                    <td>{{ $producto->cantidad_total }}</td>
                    <td>Q {{ number_format($producto->total_vendido, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Este reporte fue generado automáticamente por SisMaderera.</p>
        <p>© {{ date('Y') }} - Todos los derechos reservados</p>
    </div>

</body>
</html>
