<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos

$Id_Especialidad= "";


$Nombre_Medico= $_GET['Nombre_Medico'];//Este y primero se tomanm de los adapter
$Id_Medico= $_GET['Id_Medico'];
$Nombre_Especialidad= $_GET['Nombre_Especialidad'];//Este y primero se tomanm de los adapter


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("SELECT * FROM  especialidades_medi  where  Nombre_Especialidad = ?");
$querysito->execute(array($Nombre_Especialidad));
   
if ($querysito->rowCount() > 0) {
    header('Content-Type: application/json');
    $result = $querysito->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $Id_Especialidad = $row['Id_Especialidad'];
    }

    $querysito = $con->prepare("UPDATE lista_de_medicos SET Nombre_Medico=? , Id_EspecialidadFK=? where Id_Medico=?");
    $querysito->execute(array($Nombre_Medico,$Id_Especialidad,$Id_Medico));
    if ($querysito) {
        header('Content-Type: application/json');
        $response["process"] = "Datos_de_NombreMexico_Update";
        $response["message"] = "Datos de este médico actualizados con éxito";
        echo (json_encode($response));
    }else{
        header('Content-Type: application/json');
        $response["process"] = "Datos_de_Medicos_No_Update";
        $response["message"] = "Error en intentar modificar los datos de este doctor";
        echo (json_encode($response));
    }
}else{
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_Medico_No_registered";
    $response["message"] = "Esta especialidad no se encuentra en nuestra BD, por lo que si quieres puedes agregarla desde opción de menú para las especialidades en la aplicación";
    echo (json_encode($response));
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/Medicos/ModificarMedico.php?Nombre_Medico=Jesús Castillero&Id_Medico=11&Nombre_Especialidad=fantasma
?>