<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Iniciamos la session

session_start();

class Config {
  const BBDD_HOST = "167.114.86.210"; //127.0.0.1
  const BBDD_USUARIO = "curso_abmventas"; //root
  const BBDD_CLAVE = "43MV.9877"; // vacio
  const BBDD_NOMBRE = "curso_abmventas"; //abmventas
}


if($_POST){
  if(isset($_POST["btnCerrar"])){ /* Analizamos si es la accion del boton cerrar */
    header("location:login.php");
      session_destroy();  
  }
}








?>

