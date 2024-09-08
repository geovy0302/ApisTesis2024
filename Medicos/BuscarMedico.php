<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos

$Nombre_Especialidad= $_GET['Nombre_Especialidad'];//Este y primero se tomanm de los adapter
$Id_Especialidad= "";


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta


//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("SELECT Id_Especialidad FROM  especialidades_medi  where  Nombre_Especialidad = ?");
$querysito->execute(array($Nombre_Especialidad));
   
if ($querysito->rowCount() > 0) {
    $result = $querysito->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    foreach ($result as $row) {
        $Id_Especialidad = $row['Id_Especialidad'];
    }

    if(($Id_Especialidad != null)&&($Id_Especialidad != "")){
        $queryOne = $con->prepare("SELECT a.Id_medico, a.Nombre_Medico, b.Nombre_Especialidad FROM lista_de_medicos a INNER JOIN especialidades_medi b ON (a.Id_EspecialidadFK = b.Id_Especialidad)&& (b.Id_Especialidad = ?);");
        $queryOne->execute(array($Id_Especialidad));
        if ($queryOne->rowCount() > 0) {
            $result = $queryOne->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $stuff["Id_medico"] = $row['Id_medico'];
                $stuff["Nombre_Medico"] = $row['Nombre_Medico'];
                $stuff["Nombre_Especialidad"] = $row['Nombre_Especialidad'];
                array_push($response, $stuff);
            }
            $stuff = array();
            $stuff["process"] = "Datos_de_Medicos_encontrados";
            $stuff["message"] = "Médicos encontrados en esta especialidad";
            array_push($response, $stuff);
            header('Content-Type: application/json');
            echo (json_encode($response));
        }else{
            $response["process"] = "Datos_de_Medicos_No_encontrados";
            $response["message"] = "No hay médicos registrados en esta especialidad";
            header('Content-Type: application/json');
            echo (json_encode($response));
        }
    }
}else{
    $response["process"] = "Datos_de_Especialidad_No_encontrados";
    $response["message"] = "No hay médicos en esta especialidad porque la misma no se encuentra registrada en nuestra BD";
    header('Content-Type: application/json');
    echo (json_encode($response));
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/Medicos/BuscarMedico.php?Nombre_Especialidad=bruja
?>