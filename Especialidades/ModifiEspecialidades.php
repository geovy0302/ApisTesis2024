<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
ob_start(); // Inicia el buffer de salida
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Especialidad = $_POST["Id_Especialidad"];
$Nombre_Especialidad= $_POST['Nombre_Especialidad'];//Este y primero se tomanm de los adapter


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("UPDATE especialidades_medi SET Nombre_Especialidad=?  where Id_Especialidad=?");
$querysito->execute(array($Nombre_Especialidad,$Id_Especialidad));
   
if ($querysito) {
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_Especialiadad_Update";
    $response["message"] = "Datos de especialidad actualizados con éxito";
    header('Content-Type: application/json');
    echo (json_encode($response));
    exit();
}else {
    $response["process"] = "Datos_de_Especialiadad_No_Update";
    $response["message"] = "Error en intentar modificar los datos de esta especialidad";
    header('Content-Type: application/json');
    echo (json_encode($response));
    exit();
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/Especialidades/ModifiEspecialidades.php?Id_Especialidad=11&Nombre_Especialidad=Medicina Internasss
