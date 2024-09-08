<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$cedula_Usuario= $_GET['cedula_Usuario'];
$contrasena = $_GET['contrasena'];

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querys = $con->prepare("SELECT  *  from  usuariospacientes  where Cedula_DNI = ? AND Password= ?");
$querys->execute(array($cedula_Usuario,$contrasena ));
   

if ($querys) {
    $result = $querys->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Id_Usuario"] = $row['Id_Usuario'];
        $stuff["NombreC_U"] = $row['NombreC_U'];
        $stuff["Apellido_CU"] = $row['Apellido_CU'];
        $stuff["Cedula_DNI"] = $row['Cedula_DNI'];
        $stuff["E_mail"] = $row['E_mail'];
        array_push($response, $stuff);
    }
    if($response != null){
        $stuff = array();
        $stuff["process"] = "Datos_de_user_encontrados";
        $stuff["message"] = "Datos de este usuario encontrado";
        array_push($response, $stuff);
        header('Content-Type: application/json');
        echo (json_encode($response));
    }else{
        $response["process"]= "Datos_de_user_No_encontrados";
        $response["message"] = "Datos de este usuario No encontrado";
        header('Content-Type: application/json');
        echo (json_encode($response));
    }
} 

//  http://localhost/ApisTesis/Login BDT/login.php?cedula_Usuario=8-962-3421&contrasena=12345  <<<<< LINK DEL API 

?>