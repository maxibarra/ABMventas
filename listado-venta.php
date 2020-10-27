<?php
include_once "config.php";


include_once "entidades/venta.php";
include_once "entidades/producto.php";
include_once "entidades/cliente.php";




$venta = new Venta();
$aVentas = $venta->cargarGrilla();


include_once "header.php";


?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Listado de Ventas</h1>
    <div class="row">
        <div class="col-6 mb-3">
            <a href="venta-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
        </div>
    </div>
    <table class="table table-hover border">
        <tr>

            <th>Fecha</th>
            <th>Cantidad</th>
            <th>Producto</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
        <tr>
            <?php foreach ($aVentas as  $venta) : ?>
                <td><?php echo date_format(date_create($venta->fecha), 'd/m/Y H:m'); ?></td>
                <td><?php echo $venta->cantidad ?></td>
                <td><?php echo $venta->nombre_producto; ?></a></td>
                <td><?php echo $venta->nombre_cliente; ?></a></td>
                <td>$<?php echo $venta->preciounitario; ?></td>
                <td><a href="venta-formulario.php?id=<?php echo $venta->idventa; ?>"><i class="fas fa-search"></i></a></td>
        </tr>
    <?php endforeach; ?>
    </table>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php include_once "footer.php"; ?>