<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
ob_start(); // Inicia el buffer de salida
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario = $_POST["Id_Usuario"];
$Id_MedicaToma = $_POST["Id_MedicaToma"];
$Id_Medicamento= $_POST['Id_Medicamento'];//Este y primero se tomanm de los adapter
$Nombre_Medicamento= $_POST['Nombre_Medicamento'];//Este y los dos siguinetes se toman por teclado por parte del usuario
$TipoMediFK= $_POST['TipoMediFK'];
$Dosis_Indi_Medi= $_POST['Dosis_Indi_Medi'];
$Razon_de_Toma= $_POST['Razon_de_Toma'];
$Fecha_Inicio_Med= $_POST['Fecha_Inicio_Med'];
$Fecha_Final_Med= $_POST['Fecha_Final_Med'];


$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("UPDATE medicamentos SET Nombre_Medicamento=? , TipoMediFK=?  where Id_Medicamento=?");
$querysito->execute(array($Nombre_Medicamento, $TipoMediFK,$Id_Medicamento));
   
if ($querysito) {
    $queryTwo = $con->prepare("UPDATE usuamedica SET Dosis_Indi_Medi=?, Razon_de_Toma=?,Fecha_Inicio_Med=?,Fecha_Final_Med=? where (Id_UsuarioFK=?) && (Id_MedicamentoFK=?) && (Id_MedicaToma=?);;");
    $queryTwo->execute(array($Dosis_Indi_Medi,$Razon_de_Toma,$Fecha_Inicio_Med,$Fecha_Final_Med, $Id_Usuario, $Id_Medicamento,$Id_MedicaToma));
    if ($queryTwo) {
        $response["process"] = "Datos_de_NombreMedicamentos_Update";
        $response["message"] = "Datos de esta medicamentos actualizados con éxito";
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }else{
        $response["process"] = "Datos_de_NombreMedicamento_Not_Update";
        $response["message"] = "Error en actualizar esta alergia para este usuario por consulta";
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }
}else {
    $response["process"] = "Datos_de_NombreMedicamento_Not_Update";
    $response["message"] = "Error en actualizar esta afeccion para este usuario porque no cargo la consulta";
    header('Content-Type: application/json');
    echo (json_encode($response));
    exit();
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/Medicamentos/ModifiMedicamentos.php?Id_Usuario=4&Id_Medicamento=2&Id_MedicaToma=1&Nombre_Medicamento=Acetaminofén Para&TipoMediFK=2&Dosis_Indi_Medi=Dos tabletas cada cuatro horas&Razon_de_Toma=Lo uso para aliviar dolores de cabeza y reducir la fiebre&Fecha_Inicio_Med=2023/10/03&Fecha_Final_Med=2023/10/06
