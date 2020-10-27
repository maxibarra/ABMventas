<?php
include_once "config.php";

include_once "entidades/venta.php";
include_once "entidades/producto.php";
include_once "entidades/cliente.php";




$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

$producto = new Producto();
$aProductos = $producto->obtenerTodos();

$venta = new Venta();
$venta->cargarFormulario($_REQUEST);

$aMsg = ["mensaje" => "", "codigo" => ""];

if ($_POST) {
    if (isset($_POST["btnGuardar"])) {
      if (isset($_GET["id"]) && $_GET["id"] > 0 ) {
        //Actualizo un cliente existente
        $venta->actualizar();
        $aMsg = ["mensaje" => "Venta actualizada con éxito.", "codigo" => "success"];
      } else {
        //Es nuevo
        $venta->insertar();
        $aMsg = ["mensaje" => "Venta cargada con éxito.", "codigo" => "success"];
      }
    } else if (isset($_POST["btnBorrar"])) {
      $venta->eliminar();

      $aMsg = ["mensaje" => "Venta eliminada con éxito.", "codigo" => "danger"];
    }
  }

  if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $venta->obtenerPorId();
}


  if (isset($_GET["do"]) && $_GET["do"] == "buscarProducto" && $_GET["id"] && $_GET["id"] > 0) {
    $idproducto = $_GET["id"];
    $producto = new Producto;
    $producto->idproducto = $idproducto;
    $producto->obtenerPorId($idproducto);
    echo json_encode($producto->precio);
    exit;
  }else if (isset($_GET["id"]) && $_GET["id"] > 0) {
      $venta->obtenerPorId();
  }


  
 


include_once  "header.php";
?>



<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ventas</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <?php if($aMsg["mensaje"] != ""): ?>
            
            <div class="row">
              <div class="col-12">
                <div class = "alert alert-<?php echo $aMsg["codigo"]; ?>" role="alert">
                  <?php echo $aMsg["mensaje"]; ?>
                </div>
              </div>
            </div>

            <?php endif;?>


    <!-- Content Row -->
    <div class="row">
        <div class="col-12 mb-3">
            <a href="listado-venta.php" class="btn btn-primary mr-2">Listado</a>
            <a href="venta-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
            <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
            <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
        </div>


    </div>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">

            <div class="col-6 form-group">
                <label for="txtFecha">Fecha:</label>
                <input type="date" required="" class="form-control" name="txtFecha" id="txtFecha" value="<?php echo date_format(date_create($venta->fecha), "Y-m-d"); ?>">
            </div>
            <div class="col-6 form-group">
                <label for="txtFecha">Hora:</label>
                <input type="time" required="" class="form-control" name="txtHora" id="txtHora" value="<?php echo date_format(date_create($venta->fecha), "H:i"); ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-group">
                <label for="lstCliente">Cliente:</label>
                <select required class="form-control" name="lstCliente" id="lstCliente">
                    <option disabled selected value="">Seleccionar</option>
                    <?php foreach ($aClientes as $cliente) :  ?>
                        <?php if ($cliente->idcliente == $venta->fk_idcliente) : ?>
                            <option selected value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                        <?php else : ?>
                            <option value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 form-group">
                <label for="lstProducto">Producto:</label>
                <select required class="form-control" name="lstProducto" id="lstProducto" onchange="fBuscarPrecio();">
                    <option disabled selected value="">Seleccionar</option>
                    <?php foreach ($aProductos as $producto) :  ?>
                        <?php if ($producto->idproducto == $venta->fk_idproducto) : ?>
                            <option selected value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                        <?php else : ?>
                            <option value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-group">
                <label for="txtPrecio">Precio Unitario:</label>
                <input type="text" class="form-control" name="txtPrecio" id="txtPrecio" onchange="fCalcularTotal();"  value="<?php echo $venta->preciounitario;?>">
            </div>
            <div class="col-6 form-group">
                <label for="txtCantidad">Cantidad:</label>
                <input type="text" required="" class="form-control" name="txtCantidad" id="txtCantidad" onchange="fCalcularTotal();" value="<?php echo $venta->cantidad; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-group">
                <label for="txtTotal">Total:</label>
                <input type="text" class="form-control" name="txtTotal" id="txtTotal" onchange="fCalcularTotal();" value="<?php echo $venta->total;?>">
            </div>
        </div>
    </form>
</div>

<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<script>


        function fBuscarPrecio(){
            var idproducto = $("#lstProducto option:selected").val();
            $.ajax({
                type: "GET",
                url: "venta-formulario.php?do=buscarProducto",
                data: { id:idproducto },
                async: true,
                dataType: "json",
                success: function (respuesta) {
                    $("#txtPrecio").val(respuesta);
                }
            });

        }

        function fCalcularTotal(){
            var precio = $('#txtPrecio').val();
            var cantidad = $('#txtCantidad').val();
            var resultado = precio * cantidad;
                  $("#txtTotal").val(resultado);
            
        }
</script>



<?php
include_once "footer.php"
?>
</body>

</html>