<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos




$Nombre_CentroHos= $_GET['Nombre_CentroHos'];//Este y primero se tomanm de los adapter
$ProvinciaUbicacion= $_GET['ProvinciaUbicacion'];


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("SELECT * FROM  centros_de_atencio  where  Nombre_CentroHos = ?");
$querysito->execute(array($Nombre_CentroHos));
   
if ($querysito->rowCount() > 0) {
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_Nombre_CentroHos_existing";
    $response["message"] = "Centro Hospitalario ya existente";
    echo (json_encode($response));
}else {
    $querysOne = $con->prepare("INSERT INTO centros_de_atencio (`Nombre_CentroHos`, `ProvinciaUbicacion`) VALUES (?,?)");		
    $querysOne->execute(array($Nombre_CentroHos,$ProvinciaUbicacion));

    if ($querysOne) {
        header('Content-Type: application/json');
        $response["process"] = "Datos_de_Nombre_CentroHos__registered";
        $response["message"] = "Centro Hospitalario registrada con éxito";
        echo (json_encode($response));
    }else {
    }
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/CentrosHospitalarios/RegistrarCentroMed.php?Nombre_CentroHos=Clínica "Dra. Mónica Ríos"&ProvinciaUbicacion=Chiriquí
?>