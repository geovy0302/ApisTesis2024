<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario = $_GET["Id_Usuario"];
$Id_Alergia= $_GET['Id_Alergia'];//Este y primero se tomanm de los adapter
$Nombre_Alergia= $_GET['Nombre_Alergia'];//Este y los dos siguinetes se toman por teclado por parte del usuario
$Id_TipoAlergia= $_GET['Id_TipoAlergia'];
$DecripcionPropia_Alerg= $_GET['DecripcionPropia_Alerg'];

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("UPDATE alergias SET Nombre_Alergia=? , TipoAlerFK=?  where Id_Alergia=?");
$querysito->execute(array($Nombre_Alergia, $Id_TipoAlergia,$Id_Alergia));
   
if ($querysito) {
    $queryTwo = $con->prepare("UPDATE usuaalergia SET DecripcionPropia_Alerg=? where (Id_UsuarioFK=?) && (Id_AlergiaFK=?);");
    $queryTwo->execute(array($DecripcionPropia_Alerg,$Id_Usuario, $Id_Alergia));
    if ($queryTwo) {
        header('Content-Type: application/json');
        $response["process"] = "Datos_de_NombreAlergia_Update";
        $response["message"] = "Datos de este alergia actualizados con éxito";
        echo (json_encode($response));
    }else{
        header('Content-Type: application/json');
        $response["process"] = "Datos_de_NombreAlergia_Not_Update";
        $response["message"] = "Error en actualizar esta alergia para este usuario por consulta";
        echo (json_encode($response));
    }
}else {
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_NombreAlergia_Not_Update";
    $response["message"] = "Error en actualizar esta alergia para este usuario porque no cargo la consulta";
    echo (json_encode($response));
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/Alergias/ModifiAlergia.php?Id_Usuario=6&Id_Alergia=3&Nombre_Alergia=Aspirina &Id_TipoAlergia=2&DecripcionPropia_Alerg=La alergia a este medicamento en ocasiones me suele dar  con una erspecie de sudoración y angustía excesiva.
?>