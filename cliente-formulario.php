<?php
include_once "config.php";

include_once "entidades/cliente.php";
include_once "entidades/provincias.php";
include_once "entidades/localidad.php";
include_once "entidades/domicilio.php";



$aMsg = ["mensaje" => "", "codigo" => ""];

$cliente = new Cliente();
$cliente->cargarFormulario($_REQUEST);

if ($_POST) {
  if (isset($_POST["btnGuardar"])) {
    if (isset($_GET["id"]) && $_GET["id"] > 0) {
      //Actualizo un cliente existente
      $cliente->actualizar();
      

      $aMsg = ["mensaje" => "Cliente modificado con éxito.", "codigo" => "success"];
      
    }else {
      //Es nuevo
      $cliente->insertar();

      $aMsg = ["mensaje" => "Cliente cargado con éxito.", "codigo" => "success"];
 
    }
    if (isset($_POST["txtTipo"])) {
      $domicilio = new Domicilio();
      $domicilio->eliminarPorCliente($cliente->idcliente);
      for ($i = 0; $i < count($_POST["txtTipo"]); $i++) {
        $domicilio->fk_idcliente = $cliente->idcliente;
        $domicilio->fk_tipo = $_POST["txtTipo"][$i];
        $domicilio->fk_idlocalidad = $_POST["txtLocalidad"][$i];
        $domicilio->domicilio = $_POST["txtDomicilio"][$i];
        $domicilio->insertar();
      }
    } 
  } else if (isset($_POST["btnBorrar"])) {
    $domicilio = new Domicilio();
    $domicilio->eliminarPorCliente($cliente->idcliente);
    $cliente->eliminar();

    $aMsg = ["mensaje" => "Cliente eliminado con éxito.", "codigo" => "danger"];
     //header('location:cliente-formulario.php');
  } 

}

if (isset($_GET["do"]) && $_GET["do"] == "buscarLocalidad" && $_GET["id"] && $_GET["id"] > 0) {
  $idProvincia = $_GET["id"];
  $localidad = new Localidad();
  $aLocalidad = $localidad->obtenerPorProvincia($idProvincia);
  echo json_encode($aLocalidad);
  exit;
} else if (isset($_GET["id"]) && $_GET["id"] > 0) {
  $cliente->obtenerPorId();
}


if (isset($_GET["do"]) && $_GET["do"] == "cargarGrilla") {
  $idcliente = $_GET['idcliente'];
  $request = $_REQUEST;


  $entidad = new Domicilio();
  $aDomicilio = $entidad->obtenerFiltrado($idcliente);


  $data = array();

  if (count($aDomicilio) > 0)
    $count = 0;
  for ($i = 0; $i < count($aDomicilio); $i++) {
    $row = array();
    $row[] = $aDomicilio[$i]->tipo;
    $row[] = $aDomicilio[$i]->provincia;
    $row[] = $aDomicilio[$i]->localidad;
    $row[] = $aDomicilio[$i]->domicilio;
    $count++;
    $data[] = $row;
  }

  $json_data = array(
    "draw" => isset($request['draw']) ? intval($request['draw']) : 0,
    "recordsTotal" => count($aDomicilio), //cantidad total de registros sin paginar
    "recordsFiltered" => count($aDomicilio), //cantidad total de registros en la paginacion
    "data" => $data
  );
  echo json_encode($json_data);
  exit;
}

$provincia = new Provincia();
$aProvincias = $provincia->obtenerTodos();


include_once  "header.php";


?>


<form  method="POST">
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Clientes</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generar Reporte</a>
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

                <div class="col-12 mb-3 form-group">
                    <a href="cliente-listado.php" class="btn btn-primary mr-2">Listado</a>
                    <a href="cliente-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                    <button type="submit" class="btn btn-success mr-2" id="btnGuardar"
                        name="btnGuardar">Guardar</button>
                    <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>

                </div>



            </div>
        
        <div class="row">
            <div class="col-6 form-group">
                <label for="txtNombre">Nombre:</label>
                <input type="text" required="" class="form-control" name="txtNombre" id="txtNombre"
                    value="<?php echo $cliente->nombre ?>">
            </div>
            <div class="col-6 form-group">
                <label for="txtCuit">Cuit:</label>
                <input type="text" required="" class="form-control" name="txtCuit" id="txtCuit"
                    value="<?php echo $cliente->cuit ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-6 form-group">
                <label for="txtTelefono">Telefono:</label>
                <input type="number" required="" class="form-control" name="txtTelefono" id="txtTelefono"
                    value="<?php echo $cliente->telefono ?>">
            </div>
            <div class="col-6 form-group">
                <label for="txtCorreo">Correo:</label>
                <input type="email" class="form-control" name="txtCorreo" id="txtCorreo"
                    value="<?php echo $cliente->correo ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-6  form-group">
                <label for="">Fecha De Nacimiento:</label>
                <input type="date" class="form-control" name="txtFechaNac" id="txtFechaNac"
                    value="<?php echo date_format(date_create($cliente->fecha_nac),"Y-m-d");?>">
            </div>
        </div>
</form>
<!-- /.container-fluid -->
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <button type="button" class="btn btn-primary" data-toggle="modal"
                    data-target="#exampleModal">Agregar</button>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Domilicio</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="">
                                    <div class="row">
                                        <div class="col-12 form-group">
                                            <label for="lstTipo">Tipo:</label>
                                            <select name="lstTipo" id="lstTipo" class="form-control">
                                                <option disabled selected>Seleccionar</option>
                                                <option value="1"> Personal </option>
                                                <option value="2"> Laboral </option>
                                                <option value="3"> Comercial </option>
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="lstProvincia">Provincia:</label>
                                            <select name="lstProvincia" id="lstProvincia" onchange="fBuscarLocalidad();"
                                                class="form-control">
                                                <option disabled selected> Seleccionar </option>
                                                <?php foreach ($aProvincias as $provincia) : ?>

                                                <option value="<?php echo $provincia->idprovincia; ?>">
                                                    <?php echo $provincia->nombre; ?></option>

                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 form-group">
                                            <label for="lstLocalidad">Localidad:</label>
                                            <select name="lstLocalidad" id="lstLocalidad" class="form-control">
                                                <option value="" disabled selected> Seleccionar </option>
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="txtDireccion">Direccion:</label>
                                            <input type="text" required="" class="form-control" name="txtDireccion"
                                                id="txtDireccion" value="">
                                        </div>
                                    </div>
                                </form>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="fAgregarDomicilio()">Agregar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="panel-body">
                    <table id="grilla" class="display" style="width:98%">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Provincia</th>
                                <th>Localidad</th>
                                <th>Dirección</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End of Main Content -->
<script>
window.onload = function() {
    var idcliente = '<?php echo isset($cliente) && $cliente->idcliente > 0 ? $cliente->idcliente : 0 ?>';

    var dataTable = $('#grilla').DataTable({
        "processing": true,
        "serverSide": false,
        "bFilter": false,
        "bInfo": true,
        "bSearchable": false,
        "paging": false,
        "pageLength": 25,
        "order": [
            [0, "asc"]
        ],
        "ajax": "cliente-formulario.php?do=cargarGrilla&idcliente=" + idcliente
    });
}

function fBuscarLocalidad() {
    idProvincia = $("#lstProvincia option:selected").val();
    $.ajax({
        type: "GET",
        url: "cliente-formulario.php?do=buscarLocalidad",
        data: {
            id: idProvincia
        },
        async: true,
        dataType: "json",
        success: function(respuesta) {
            $("#lstLocalidad option").remove();
            $("<option>", {
                value: 0,
                text: "Seleccionar",
                disabled: true,
                selected: true
            }).appendTo("#lstLocalidad");

            for (i = 0; i < respuesta.length; i++) {
                $("<option>", {
                    value: respuesta[i]["idlocalidad"],
                    text: respuesta[i]["nombre"]
                }).appendTo("#lstLocalidad");
            }
            $("#lstLocalidad").prop("selectedIndex", "0");
        }
    });
}

function fAgregarDomicilio() {
    var grilla = $('#grilla').DataTable();
    grilla.row.add([
        $("#lstTipo option:selected").text() + "<input type='hidden' name='txtTipo[]' value='" + $(
            "#lstTipo option:selected").val() + "'>",
        $("#lstProvincia option:selected").text() + "<input type='hidden' name='txtProvincia[]' value='" + $(
            "#lstProvincia option:selected").val() + "'>",
        $("#lstLocalidad option:selected").text() + "<input type='hidden' name='txtLocalidad[]' value='" + $(
            "#lstLocalidad option:selected").val() + "'>",
        $("#txtDireccion").val() + "<input type='hidden' name='txtDomicilio[]' value='" + $("#txtDireccion")
        .val() + "'>"
    ]).draw();
    $('#modalDomicilio').modal('toggle');
    limpiarFormulario();
}


function limpiarFormulario() {
    $("#lstTipo").val("");
    $("#lstProvincia").val("");
    $("#lstLocalidad").val("");
    $("#txtDireccion").val("");
}
</script>


<?php
  include_once "footer.php"
  ?>
</body>

</html>