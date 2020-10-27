<?php
include_once "config.php";

include_once "entidades/tipo_producto.php";

$tipoProducto = new TipoProducto();
$array_tipoproductos = $tipoProducto->obtenerTodos();
include_once "header.php";


?>
<!-- Begin Page Content -->
<form action="" method="POST">
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Tipo de Productos</h1>
        <div class="row">
            <div class="col-12 mb-3">
                <a href="tipoproductos-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
            </div>
        </div>
        <table class="table table-hover border">
            <tr>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
            <tr>
                <?php foreach ($array_tipoproductos as $tipoProducto):?>
                    <td><?php echo $tipoProducto->nombre;?></td>
                    <td><a href="tipoproductos-formulario.php?id=<?php echo $tipoProducto->idtipoproducto;?>"><i class="fas fa-search"></i></a></td>
            </tr>
        <?php endforeach; ?>
        </table>




    </div>
    <!-- /.container-fluid -->
</form>
</div>
<!-- End of Main Content -->

<?php include_once "footer.php"; ?>