@php use Carbon\Carbon; @endphp
    <!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Folio:</th>
        <td class="text-right">{{ $Venta->folio }}</td>
    </tr>
    <tr>
        <th>Cliente</th>
        <td class="text-right">{{ $Venta->cliente->nombre }}</td>
    </tr>
    <tr>
        <th>Atendió:</th>
        <td class="text-right">{{ $Venta->user->name }}</td>
    </tr>
    <tr>
        <th>Fecha:</th>
        <td class="text-right">{{ Carbon::parse($Venta->fecha)->format('d-m-Y H:i:s') }}</td>
    </tr>
    </thead>
</table>
<table class="table table-bordered table-striped">
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
                <th class='text-right' colspan='$colspan1'>T.Débito:</th>
                <td>$%s</td>
            </tr>
        ", number_format($TarjetaDebito, 2));
    }
    if ($Venta->tarjeta_credito > 0) {
        $TarjetaCredito = $Venta->tarjeta_credito;
        $TotalPagado += $TarjetaCredito;
        $FormasDePago .= sprintf("
            <tr>
                <th class='text-right' colspan='$colspan1'>T.Credito:</th>
                <td>$%s</td>
            </tr>
        ", number_format($TarjetaCredito, 2));
    }
    if ($Venta->transferencia > 0) {
        $Transferencia = $Venta->transferencia;
        $TotalPagado += $Transferencia;
        $FormasDePago .= sprintf("
            <tr>
                <th class='text-right' colspan='$colspan1'>Transferencia:</th>
                <td>$%s</td>
            </tr>
        ", number_format($Transferencia, 2));
    }
    if ($Venta->deposito > 0) {
        $Deposito = $Venta->deposito;
        $TotalPagado += $Deposito;
        $FormasDePago .= sprintf("
            <tr>
                <th class='text-right' colspan='$colspan1'>Depósito:</th>
                <td>$%s</td>
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
                <th class='text-right' colspan='$colspan1'>Efectivo:</th>
                <td>$%s</td>
            </tr>
            <tr>
                <th class='text-right' colspan='$colspan1'>Pago con:</th>
                <td>$%s</td>
            </tr>
            <tr>
                <th class='text-right' colspan='$colspan1'>Cambio:</th>
                <td>$%s</td>
            </tr>
        ", number_format($Efectivo, 2), number_format($PagoCon, 2), number_format($Cambio, 2));
    }
    ?>
    <tr>
        <th>#</th>
        <th>Producto</th>
        <th>Precio</th>
        <th>Cant.</th>
        <th>Total</th>
    </tr>
    @foreach($Venta->venta_detalle as $item)
            <?php
            $Precio = $item->precio;
            $Cantidad = $item->cantidad;
            $Total = $Precio * $Cantidad;
            ?>
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->producto->nombre ?? ''}}</td>
            <td>${{ number_format($Precio, 2) }}</td>
            <td>{{ $Cantidad }}</td>
            <td>${{ number_format($Total, 2) }}</td>
        </tr>
    @endforeach
    <tr>
        <th class="text-right" colspan="{{ $colspan1 }}">TOTAL:</th>
        <td>${{ number_format($Venta->total, 2) }}</td>
    </tr>
    <?= $FormasDePago ?>
    <tr>
        <th class="text-right" colspan="{{ $colspan1 }}">TOTAL PAGADO:</th>
        <td>${{ number_format($TotalPagado, 2) }}</td>
    </tr>

    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>

</body>
</html>
