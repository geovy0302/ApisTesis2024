<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos

$ProvinciaUbicacion= $_GET['ProvinciaUbicacion'];//Este y primero se tomanm de los adapter


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("SELECT * FROM  centros_de_atencio  where  ProvinciaUbicacion = ?");
$querysito->execute(array($ProvinciaUbicacion));
   
if ($querysito->rowCount() > 0) {
    $result = $querysito->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Id_CentroHosp"] = $row['Id_CentroHosp'];
        $stuff["Nombre_CentroHos"] = $row['Nombre_CentroHos'];
        $stuff["ProvinciaUbicacion"] = $row['ProvinciaUbicacion'];
        array_push($response, $stuff);
    }
    if($response != null){
        $stuff = array();
        $stuff["process"] = "Datos_de_CentroMedicos_encontrados";
        $stuff["message"] = "Centro Medico encontrado";
        array_push($response, $stuff);
        header('Content-Type: application/json');
        echo (json_encode($response));
    }else{
        $response["process"] = "Datos_de_Centros_Medicos_No_encontrados";
        $response["message"] = "Error, datos de Centros Medicos no encontrados";
        header('Content-Type: application/json');
        echo (json_encode($response));
    }
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/CentrosHospitalarios/BuscarCentroMed.php?ProvinciaUbicacion=Chiriquí
?>