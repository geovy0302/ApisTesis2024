<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos

$Id_Especialidad= "";


$Nombre_Medico= $_GET['Nombre_Medico'];//Este y primero se tomanm de los adapter
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

    $querysOne = $con->prepare("INSERT INTO lista_de_medicos (`Nombre_Medico`,`Id_EspecialidadFK` ) VALUES (?,?)");		
    $querysOne->execute(array($Nombre_Medico, $Id_Especialidad));

    if ($querysOne) {
        header('Content-Type: application/json');
        $response["process"] = "Datos_de_Especialiadad_registered";
        $response["message"] = "Médico registrado con éxito";
        echo (json_encode($response));
    }else {
        header('Content-Type: application/json');
        $response["process"] = "Datos_de_Medico_No_registered";
        $response["message"] = "Hubo un error en el registrado de este nuevo médico";
        echo (json_encode($response));
    }
}else {
    header('Content-Type: application/json');
    $response["process"] = "Datos_de_Medico_No_registered";
    $response["message"] = "Esta especialidad no se encuentra en nuestra BD, por lo que si quieres puedes agregarla desde opción de menú para las especialidades en la aplicación";
    echo (json_encode($response));
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/Medicos/RegistrarMedico.php?Nombre_Medico=Jesús Castillo&Nombre_Especialidad=fantasma
?>