@php use
 Carbon\Carbon;
 $imagen = '';
 if($Venta->tienda->imagen){
     $imagen = \Illuminate\Support\Facades\Storage::url('public/tiendas/' . $Venta->tienda->imagen);
 }
@endphp
    <!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <style>
        body {
            font-size: 9px;
        }

        #tablaEncabezado td:nth-child(1) {
            font-weight: bold;
        }

        #tablaConceptos tbody tr:first-child {
            font-weight: bold;
        }

        #tablaConceptos tfoot td:nth-child(1) {
            font-weight: bold;
        }

        @media print {
            .printEl {
                max-width: 165px;
                align-content: center;
            }
        }
    </style>
</head>
<body>
<table width="100%" style="margin: 5% auto">
    <tr>
        <td align="center">
            <img src="{{ $imagen }}" height="100" width="100%"><br>
        </td>
    </tr>
    <tr>
        <td align="center">
            {{ $Venta->tienda->nombre ?? '' }}
        </td>
    </tr>
    <tr>
        <td align="center">
            {{ $Venta->tienda->domicilio ?? '' }}
        </td>
    </tr>
    <tr>
        <td align="center">
            {{ $Venta->tienda->encabezado_ticket ?? '' }}
        </td>
    </tr>
</table>
<hr style="border-top: 1px solid black;">
<table id="tablaEncabezado" width="100%" style="margin: 5% auto">
    <thead>
    <tr>
        <td>Folio:</td>
        <td align="right">{{ $Venta->folio }}</td>
    </tr>
    <tr>
        <td>Cliente</td>
        <td align="right">{{ $Venta->cliente->nombre }}</td>
    </tr>
    <tr>
        <td>Atendió:</td>
        <td align="right">{{ $Venta->user->name }}</td>
    </tr>
    <tr>
        <td>Fecha:</td>
        <td align="right">{{ Carbon::parse($Venta->fecha)->format('d-m-Y H:i:s') }}</td>
    </tr>
    </thead>
</table>
<hr style="border-top: 1px solid black;">
<table id="tablaConceptos" width="100%" style="margin: 5% auto">
    <tbody>
    <?php
    $FormasDePago = '';
    $TotalPagado = 0;
    $colspan1 = 4; //TOTAL Y FORMAS DE PAGO
    if ($Venta->tarjeta_debito > 0) {
        $TarjetaDebito = $Venta->tarjeta_debito;
        $TotalPagado += $TarjetaDebito;
        $FormasDePago .= sprintf("
            <tr>
                <td align='right' colspan='$colspan1'>T.Débito:</td>
                <td align='right'>$%s</td>
            </tr>
        ", number_format($TarjetaDebito, 2));
    }
    if ($Venta->tarjeta_credito > 0) {
        $TarjetaCredito = $Venta->tarjeta_credito;
        $TotalPagado += $TarjetaCredito;
        $FormasDePago .= sprintf("
            <tr>
                <td align='right' colspan='$colspan1'>T.Crédito:</td>
                <td align='right'>$%s</td>
            </tr>
        ", number_format($TarjetaCredito, 2));
    }
    if ($Venta->transferencia > 0) {
        $Transferencia = $Venta->transferencia;
        $TotalPagado += $Transferencia;
        $FormasDePago .= sprintf("
            <tr>
                <td align='right' colspan='$colspan1'>Transferencia:</td>
                <td align='right'>$%s</td>
            </tr>
        ", number_format($Transferencia, 2));
    }
    if ($Venta->deposito > 0) {
        $Deposito = $Venta->deposito;
        $TotalPagado += $Deposito;
        $FormasDePago .= sprintf("
            <tr>
                <td align='right' colspan='$colspan1'>Depósito:</td>
                <td align='right'>$%s</td>
            </tr>
        ", number_format($Deposito, 2));
    }
    if ($Venta->efectivo > 0) {
        $Efectivo = $Venta->efectivo;
        $PagoCon = $Venta->pago_con;
        $Cambio = $PagoCon - $Efectivo;
        $TotalPagado += $Efectivo;
        $FormasDePago .= sprintf("
            <tr>
                <td align='right' colspan='$colspan1'>Efectivo:</td>
                <td align='right'>$%s</td>
            </tr>
            <tr>
                <td align='right' colspan='$colspan1'>Pago con:</td>
                <td align='right'>$%s</td>
            </tr>
            <tr>
                <td align='right' colspan='$colspan1'>Cambio:</td>
                <td align='right'>$%s</td>
            </tr>
        ", number_format($Efectivo, 2), number_format($PagoCon, 2), number_format($Cambio, 2));
    }
    ?>
    <tr>
        <td align='center'>#</td>
        <td align='center'>Producto</td>
        <td align='center'>Precio</td>
        <td align='center'>Cant.</td>
        <td align="right">Total</td>
    </tr>
    @foreach($Venta->venta_detalle as $item)
            <?php
            $Precio = $item->precio;
            $Cantidad = $item->cantidad;
            $Total = $Precio * $Cantidad;
            ?>
        <tr>
            <td align='center'>{{ $loop->iteration }}</td>
            <td>{{ $item->producto->nombre ?? ''}}</td>
            <td align='center'>${{ number_format($Precio, 2) }}</td>
            <td align='center'>{{ $Cantidad }}</td>
            <td align="right">${{ number_format($Total, 2) }}</td>
        </tr>
    @endforeach
    <tfoot>
    <tr>
        <td colspan="{{ $colspan1 + 1 }}">
            <hr style="border-top: 1px solid black;">
        </td>
    </tr>
    <tr>
        <td align="right" colspan="{{ $colspan1 }}">TOTAL:</td>
        <td align="right">${{ number_format($Venta->total, 2) }}</td>
    </tr>
    <?= $FormasDePago ?>
    <tr>
        <td align="right" colspan="{{ $colspan1 }}">TOTAL PAGADO:</td>
        <td align="right">${{ number_format($TotalPagado, 2) }}</td>
    </tr>

    </tbody>
    </tfoot>
</table>
<hr style="border-top: 1px solid black;">
<footer>
    {{ $Venta->tienda->pie_ticket ?? '' }}
</footer>

<script type="text/javascript">
    window.onload = function () {
        window.print();
        window.close();
    };
</script>

</body>
</html>
