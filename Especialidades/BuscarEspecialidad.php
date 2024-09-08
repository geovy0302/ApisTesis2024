<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos




$Nombre_Especialidad= $_GET['Nombre_Especialidad'];//Este y primero se tomanm de los adapter


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("SELECT * FROM  especialidades_medi  where  Nombre_Especialidad = ?");
$querysito->execute(array($Nombre_Especialidad));
   
if ($querysito->rowCount() > 0) {
    $result = $querysito->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Id_Especialidad"] = $row['Id_Especialidad'];
        $stuff["Nombre_Especialidad"] = $row['Nombre_Especialidad'];
        array_push($response, $stuff);
    }
    if($response != null){
        $stuff = array();
        $stuff["process"] = "Datos_de_Especialidad_encontrados";
        $stuff["message"] = "Datos de especialidad encontrados";
        array_push($response, $stuff);
        header('Content-Type: application/json');
        echo (json_encode($response));
    }else{
        $response["process"] = "Datos_de_Especialidades_No_encontrados";
        $response["message"] = "Datos de especialidad no encontrados";
        header('Content-Type: application/json');
        echo (json_encode($response));
    }
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/Especialidades/BuscarEspecialidad.php?Nombre_Especialidad=Medicina Interna
?>