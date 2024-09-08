<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario = $_GET["Id_Usuario"];
$Id_Cita= $_GET['Id_Cita']; 
$Descrip_Consul= $_GET['Descrip_Consul'];//Este y primero se tomanm de los adapter
$Diagnóstico= $_GET['Diagnóstico'];//Este y los dos siguinetes se toman por teclado por parte del usuario
$Detalle_Receta= $_GET['Detalle_Receta'];
$Detalle_de_Examen= $_GET['Detalle_de_Examen'];
$FechadeCita= $_GET['FechadeCita'];
$Fecha_RegistrCi= $_GET['Fecha_RegistrCi'];
$SignoVital_Presion= $_GET['SignoVital_Presion'];
$SignoVital_Temperatura= $_GET['SignoVital_Temperatura'];
$SignoVital_Peso= $_GET['SignoVital_Peso'];
$SignoVital_Altura= $_GET['SignoVital_Altura'];


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$queryTwo = $con->prepare("UPDATE citasmedicas SET Descrip_Consul=?, Diagnóstico=?, Detalle_Receta=?, Detalle_de_Examen=?, FechadeCita=?, Fecha_RegistrCi=?, SignoVital_Presion=?, SignoVital_Temperatura=?, SignoVital_Peso=?, SignoVital_Altura=?  where (Id_UsuarioFK=?) && (Id_Cita=?);");
$queryTwo->execute(array($Descrip_Consul,$Diagnóstico,$Detalle_Receta,$Detalle_de_Examen,$FechadeCita, $Fecha_RegistrCi,$SignoVital_Presion,$SignoVital_Temperatura,$SignoVital_Peso,$SignoVital_Altura,$Id_Usuario,$Id_Cita ));
   

if ($queryTwo) {
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_cita_Medica_Update";
    $response["message"] = "Datos de esta cita Médica actualizados con éxito";
    echo (json_encode($response));
}else {
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_cita_Medica_Not_Update";
    $response["message"] = "Error en actualizar los datos más relevantes de esta cita médica para este usuario, porque no cargó la consulta";
    echo (json_encode($response));
}

?>