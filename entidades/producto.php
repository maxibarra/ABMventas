<?php

class Producto 
{
    private $idproducto;
    private $nombre;
    private $fk_idtipoproducto;
    private $cantidad;
    private $precio;
    private $descripcion;
    private $imagen;

    public function __construct(){
        $this->cantidad= 0;
        $this->precio= 0.0;

    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarFormulario($request){
        $this->idproducto = isset($request["id"])? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"])? $request["txtNombre"] : "";
        $this->fk_idtipoproducto = isset($request["lstTipoProducto"])? $request["lstTipoProducto"]: "";
        $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"]: "";
        $this->precio = isset($request["txtPrecio"])? $request["txtPrecio"] : "";
        $this->descripcion = isset($request["txtDescripcion"])? $request["txtDescripcion"] :"";
        $this->imagen = isset($request["txtImagen"])? $request["txtImagen"] :"";
    }

    public function insertar(){
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        //Arma la query
        $sql = "INSERT INTO productos(
                    nombre, 
                    cantidad, 
                    precio, 
                    descripcion,
                    imagen,
                    fk_idtipoproducto
                ) VALUES (
                  
                    '". $this->nombre ."', 
                    ". $this->cantidad .", 
                    ". $this->precio .", 
                    '". $this->descripcion ."',
                    '". $this->imagen ."',
                    ". $this->fk_idtipoproducto ."
                );";
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        //Obtiene el id generado por la inserción
        $this->idproducto = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }

    public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE productos SET
                nombre ='".$this->nombre."',
                cantidad = ".$this->cantidad.",
                precio =".$this->precio.",
                descripcion ='".$this->descripcion."',
                imagen ='".$this->imagen."',
                fk_idtipoproducto ='".$this->fk_idtipoproducto."'

                WHERE idproducto = " .$this->idproducto;
          
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM productos WHERE idproducto = " .$this->idproducto;
        //Ejecuta la query
        if (!$mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idproducto, 
                        nombre, 
                        fk_idtipoproducto, 
                        cantidad, 
                        precio, 
                        descripcion,
                        imagen
                FROM productos 
                WHERE idproducto = " . $this->idproducto;
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }

        //Convierte el resultado en un array asociativo
        if($fila = $resultado->fetch_assoc()){
            $this->idproducto = $fila["idproducto"];
            $this->nombre = $fila["nombre"];
            $this->fk_idtipoproducto = $fila["fk_idtipoproducto"];
            $this->cantidad = $fila["cantidad"];
            $this->precio = $fila["precio"];
            $this->descripcion = $fila["descripcion"];
            $this->imagen = $fila["imagen"];
        }  
        $mysqli->close();

    }
    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idproducto, 
                        nombre, 
                        fk_idtipoproducto, 
                        cantidad, 
                        precio, 
                        descripcion,
                        imagen
                FROM productos"; 
           
        if (!$resultado = $mysqli->query($sql)) {
            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
        }
         $aResultado = array();
        //Convierte el resultado en un array asociativo
        if($resultado){
           while ($fila = $resultado->fetch_assoc()){
               $entidadAux = new Producto();
               $entidadAux->idproducto = $fila["idproducto"];
               $entidadAux->nombre = $fila["nombre"];
               $entidadAux->fk_idtipoproducto = $fila["fk_idtipoproducto"];
               $entidadAux->cantidad = $fila["cantidad"];
               $entidadAux->precio = $fila["precio"];
               $entidadAux->descripcion = $fila["descripcion"];
               $entidadAux->imagen = $fila["imagen"];
              $aResultado[]=$entidadAux;
        }  
        $mysqli->close();

        }
        return $aResultado;
 
    }


    public function fBuscarPrecio(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT idproducto, 
                        precio, 
                        
                FROM productos
                WHERE fk_idproducto = $idproducto
                ORDER BY idproducto DESC";
            $resultado = $mysqli->query($sql);
            while ($fila = $resultado->fetch_assoc()) {
                $preciounitario = $fila["precio"];
            }

            return $preciounitario;
    }

}
?>