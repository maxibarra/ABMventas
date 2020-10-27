<?php
include_once ("config.php");

include_once ("entidades/tipo_producto.php");


$tipoProducto = new TipoProducto();
$tipoProducto->cargarFormulario($_REQUEST);

if($_POST){
    if(isset($_POST["btnGuardar"])){
        if(isset($_GET["id"]) && $_GET["id"] > ""){
              //Actualizo un cliente existente
              $tipoProducto->actualizar();
        } else {
            //Es nuevo
            $tipoProducto->insertar();
        }
    } else if(isset($_POST["btnBorrar"])){
        $tipoProducto->eliminar();
    }
} 
if(isset($_GET["id"]) && $_GET["id"] > 0){
    $tipoProducto->obtenerPorId();
}
$TipoProducto = new TipoProducto();
$aTipoProductos= $TipoProducto->obtenerTodos();

include_once  "header.php";
?>



<!-- Begin Page Content -->
<div class="container-fluid">
  <form action="" method="POST">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Tipo de Productos</h1>
      <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- Content Row -->
    <div class="row">
      <div class="col-12 mb-3">
        <a href="tipoproductos-listado.php" class="btn btn-primary mr-2">Listado</a>
        <a href="tipoproductos-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
        <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
        <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
      </div>


    </div>
    <div class="row">
      <div class="col-6 form-group">
        <label for="txtNombre">Nombre:</label>
        <input type="text" required="" class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $tipoProducto->nombre?>">
      </div>
  </form>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<script>
  ClassicEditor
    .create(document.querySelector('#txtDescripcion'))
    .catch(error => {
      console.error(error);
    });
</script>
<style>
  .ck.ck-editor {
    height: 600px;
  }
</style>


<?php
include_once "footer.php"
?>
</body>

</html>