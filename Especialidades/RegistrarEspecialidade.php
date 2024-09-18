<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
ob_start(); // Inicia el buffer de salida
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos

$Nombre_Especialidad= $_POST['Nombre_Especialidad'];//Este y primero se tomanm de los adapter


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("SELECT * FROM  especialidades_medi  where  Nombre_Especialidad = ?");
$querysito->execute(array($Nombre_Especialidad));
   
if ($querysito->rowCount() > 0) {
    $response["process"] = "Datos_de_Especialiadad_existing";
    $response["message"] = "Especialidad ya existente";
    header('Content-Type: application/json');
    echo (json_encode($response));
    exit();
}else {
    $querysOne = $con->prepare("INSERT INTO especialidades_medi (`Nombre_Especialidad`) VALUES (?)");		
    $querysOne->execute(array($Nombre_Especialidad));

    if ($querysOne) {
        $response["process"] = "Datos_de_Especialiadad_registered";
        $response["message"] = "Especialidad registrada con éxito";
        echo (json_encode($response));
        exit();
    }else {
        $response["process"] = "Datos_de_Especialidades_No_registered";
        $response["message"] = "Datos de especialidad no registrados";
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/Especialidades/RegistrarEspecialidade.php?Nombre_Especialidad=Medicina Interna
