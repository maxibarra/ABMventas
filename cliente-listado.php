<?php
include_once "config.php";
include_once "entidades/cliente.php";

$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

include_once "header.php";


?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Listado de Clientes</h1>
    <div class="row">
        <div class="col-12 mb-3">
            <a href="cliente-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
        </div>
    </div>
    <table class="table table-hover border">
        <tr>
            
            <th>Nombre</th>
            <th>Fecha Nac</th>
            <th>Telefono</th>
            <th>Cuit</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
        <tr>
            <?php foreach ($aClientes as $cliente) : ?>
                <td><?php echo $cliente->nombre; ?></td>
                <td><?php echo date_format(date_create($cliente->fecha_nac),"d/m/Y");?></td>
                <td><?php echo $cliente->telefono; ?></td>
                <td><?php echo $cliente->cuit; ?></td>
                <td><?php echo $cliente->correo; ?></td>
                <td><a href="cliente-formulario.php?id=<?php echo $cliente->idcliente; ?>"><i class="fas fa-search"></i></a></td>
        </tr>
            <?php endforeach; ?>
    </table>




</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php include_once "footer.php"; ?>