<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
ob_start(); // Inicia el buffer de salida
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario = $_POST["Id_Usuario"];
$Id_UserEnfermedad= $_POST['Id_UserEnfermedad']; 
$Id_Enfermedad= $_POST['Id_Enfermedad'];//Este y primero se tomanm de los adapter
$Nombre_enf= $_POST['Nombre_enf'];//Este y los dos siguinetes se toman por teclado por parte del usuario
$Id_TipoEnfer= $_POST['Id_TipoEnfer'];
$Fecha_Inicio= $_POST['Fecha_Inicio'];
$Fecha_Finalizacion= $_POST['Fecha_Finalización'];
$DescripcionPropia_enf= $_POST['DescripcionPropia_enf'];


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("UPDATE enfermedades SET Nombre_enf=? , TipoEnfermedadFK=?  where Id_Enfermedad=?");
$querysito->execute(array($Nombre_enf, $Id_TipoEnfer,$Id_Enfermedad));
   
if ($querysito) {
    $queryTwo = $con->prepare("UPDATE usuaenfermedad SET Fecha_Inicio=?, Fecha_Finalización=?, DescripcionPropia_enf=? where (Id_UsuarioFK=?) && (Id_EnfermedadFK=?) && (Id_UserEnfermedad=?);");
    $queryTwo->execute(array($Fecha_Inicio,$Fecha_Finalizacion,$DescripcionPropia_enf,$Id_Usuario, $Id_Enfermedad,$Id_UserEnfermedad));
    if ($queryTwo) {
        header('Content-Type: application/json');
        $response["process"] = "Datos_de_NombreAfeccion_Update";
        $response["message"] = "Datos de esta afección actualizados con éxito";
        echo (json_encode($response));
        exit();
    }else{
        header('Content-Type: application/json');
        $response["process"] = "Datos_de_NombreAfeccion_Not_Update";
        $response["message"] = "Error en actualizar esta alergia para este usuario por consulta";
        echo (json_encode($response));
        exit();
    }
}else {
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_NombreAfeccion_Not_Update";
    $response["message"] = "Error en actualizar esta afeccion para este usuario porque no cargo la consulta";
    echo (json_encode($response));
    exit();
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/Afecciones/ModifiAfecciones.php?Id_Usuario=4&Id_UserEnfermedad=6&Id_Enfermedad=1&Nombre_enf=Gripe&Id_TipoEnfer=2&Fecha_Inicio=2015/01/03&Fecha_Finalización="Sigue sin tener"&DescripcionPropia_enf=Vuelvo a sufrir de girpe con fuerte dolores de cabeza, fiebre altas y los escalofríos debdio a que me rucié con agua lluvia
