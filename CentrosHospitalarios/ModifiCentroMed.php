<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_CentroHosp = $_GET["Id_CentroHosp"];
$Nombre_CentroHos= $_GET['Nombre_CentroHos'];//Este y primero se tomanm de los adapter
$ProvinciaUbicacion= $_GET['ProvinciaUbicacion'];


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("UPDATE centros_de_atencio SET Nombre_CentroHos=?, ProvinciaUbicacion=?  where Id_CentroHosp=?");
$querysito->execute(array($Nombre_CentroHos,$ProvinciaUbicacion,$Id_CentroHosp ));
   
if ($querysito) {
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_CentroAtencion_Update";
    $response["message"] = "Datos de Centro de Atención actualizados con éxito";
    echo (json_encode($response));
}else {
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_CentroAtencion_No_Update";
    $response["message"] = "Error en intentar modificar los datos de este centro médico";
    echo (json_encode($response));
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/CentrosHospitalarios/ModifiCentroMed.php?Id_CentroHosp=11&Nombre_CentroHos=Hospital Chiriquí&ProvinciaUbicacion=Chiriquí
?>