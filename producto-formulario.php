<?php
include_once "config.php";

include_once "entidades/producto.php";
include_once "entidades/tipo_producto.php";

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);

$tipoProducto = new TipoProducto();
$aTipoProductos = $tipoProducto->obtenerTodos();


$aMsg = ["mensaje" => "", "codigo" => ""];

if ($_POST) {
  if (isset($_POST["btnGuardar"])) {
    if (isset($_GET["id"]) && $_GET["id"] > 0) {
      $productoAux = new Producto();
      $productoAux->idproducto = $_GET["id"];
      $productoAux->obtenerPorId();
      $imagenAnterior = $productoAux ->imagen;
        
         if ($_FILES["txtImagen"]["error"] === UPLOAD_ERR_OK) {
           
        $nombreAleatorio = date("Ymdhmsi");
        $archivo_tmp = $_FILES["txtImagen"]["tmp_name"];
        $nombreArchivo = $_FILES["txtImagen"]["name"];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreImagen = $nombreAleatorio . $extension;
        move_uploaded_file($archivo_tmp, "archivos/$nombreImagen");
      } 
      if ($imagenAnterior != ""){
        unlink("archivos/$imagenAnterior");
      }
    
      $producto->imagen=$nombreImagen;

      $producto->actualizar();

      $aMsg = ["mensaje" => "Producto modificado con éxito.", "codigo" => "success"];
    } else {
      //Es nuevo

      if ($_FILES["txtImagen"]["error"] === UPLOAD_ERR_OK) {
        $nombreAleatorio = date("Ymdhmsi");
        $archivo_tmp = $_FILES["txtImagen"]["tmp_name"];
        $nombreArchivo = $_FILES["txtImagen"]["name"];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreImagen = $nombreAleatorio . $extension;
        move_uploaded_file($archivo_tmp, "archivos/$nombreImagen");
      }
        $producto->imagen = $nombreImagen;
      
      $producto->insertar();

      $aMsg = ["mensaje" => "Producto cargado con éxito.", "codigo" => "success"];
    }
  } else if (isset($_POST["btnBorrar"])) {
    $producto->eliminar();

    $aMsg = ["mensaje" => "Producto eliminado con éxito.", "codigo" => "danger"];
  
  }
 
}

if (isset($_GET["id"]) && $_GET["id"] > 0) {
  $producto->obtenerPorId();
}







include_once  "header.php";
?>



<!-- Begin Page Content -->
<div class="container-fluid">
<form action="" method="POST" enctype="multipart/form-data">
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Productos</h1>
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
      <a href="producto-listado.php" class="btn btn-primary mr-2">Listado</a>
      <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
      <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
      <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
    </div>

  </div>

  
    <div class="row">
      <div class="col-6 form-group">
        <label for="txtNombre">Nombre:</label>
        <input type="text" required="" class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $producto->nombre; ?>">
      </div>
      <div class="col-6 form-group">
        <label for="txtNombre">Tipo de producto:</label>
        <select name="lstTipoProducto" id="lstTipoProducto" class="form-control">
          <option  disabled selected> Seleccionar </option>
          <?php foreach ($aTipoProductos as $tipo) : ?>
            <?php if ($producto->fk_idtipoproducto == $tipo->idtipoproducto) : ?>
              <option selected value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
            <?php else : ?>
              <option value="<?php echo $tipo->idtipoproducto; ?>"><?php echo $tipo->nombre; ?></option>
            <?php endif; ?>
          <?php endforeach; ?>

        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-6 form-group">
        <label for="txtCantidad">Cantidad:</label>
        <input type="number" required="" class="form-control" name="txtCantidad" id="txtCantidad" value="<?php echo $producto->cantidad ?>">
      </div>
      <div class="col-6 form-group">
        <label for="txtPrecio">Precio:</label>
        <input type="text" class="form-control" name="txtPrecio" id="txtPrecio" value="<?php echo $producto->precio ?>">
      </div>
    </div>
    <div class="col-6 form-group">
      <label for="txtImagen">Imagen del Producto:</label>
      <input type="file" class="form-control" name="txtImagen" id="txtImagen" value="<?php echo $producto->imagen;?>">
    </div>
    <div class="row">
      <div class="col-12 form-group">
        <label for="txtCorreo">Descripción:</label>
        <textarea type="text" name="txtDescripcion" id="txtDescripcion"> <?php echo $producto->descripcion; ?> </textarea>
      </div>
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