<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario = $_GET["Id_Usuario"];
$NuevaPassword = $_GET["NuevaPassword"];



$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("UPDATE usuarios SET Password=?  where Id_Usuario=?");
$querysito->execute(array($NuevaPassword, $Id_Usuario));
   
if ($querysito) {
    header('Content-Type: application/json');
    $response["success"] = "Moficación Password Realizada";
    echo (json_encode($response));
}else {
    header('Content-Type: application/json');
    $response["success"] = "Error";
    echo (json_encode($response));
}
//  http://localhost/ApisTesis/PerfilUser/ModifiContra.php?Id_Usuario=1&NuevaPassword=54321   