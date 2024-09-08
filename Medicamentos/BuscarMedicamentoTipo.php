<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario= $_GET['Id_Usuario'];
$Id_TipoEnfer= $_GET['Id_TipoEnfer'];

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta


//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysAmarre = $con->prepare("SELECT d.Id_Usuario, a.Id_Medicamento,a.Nombre_Medicamento,c.descripcion_TipMed,b.Dosis_Indi_Medi,b.Razon_de_Toma,b.Fecha_Inicio_Med,b.Fecha_Final_Med, b.Fecha_Regis_Medicamento
FROM medicamentos a INNER JOIN usuamedica b ON  (b.Id_MedicamentoFK = a.Id_Medicamento) INNER JOIN tipo_de_medicamento c ON (c.Id_TipoMedicamento = a.TipoMediFK) && (a.TipoMediFK =?)INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (b.Id_UsuarioFK = ?);");
$querysAmarre->execute(array($Id_TipoEnfer,$Id_Usuario)); 
//SELECT a.Nombre_Alergia,c.descripcion_TipoAler,b.DecripcionPropia_Alerg,b.Fecha_Regis_Aler  FROM alergias a INNER JOIN usuaalergia b ON  (b.Id_AlergiaFK = a.Id_Alergia)  INNER JOIN tipoalergia c ON (c.Id_TipoAler = a.TipoAlerFK) INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (d.Id_Usuario = 6);
//&& (b.Id_MedicamentoFK = a.Id_Medicamento) 

if ($querysAmarre->rowCount() > 0) {
    $result = $querysAmarre->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Id_Usuario"] = $row['Id_Usuario'];
        $stuff["Id_Enfermedad"] = $row['Id_Medicamento'];
        $stuff["Nombre_enf"] = $row['Nombre_Medicamento'];
        $stuff["descripcion_TipEnfer"] = $row['descripcion_TipMed'];
        $stuff["Dosis_Indi_Medi"] = $row['Dosis_Indi_Medi'];
        $stuff["Razon_de_Toma"] = $row['Razon_de_Toma'];
        $stuff["Fecha_Inicio"] = $row['Fecha_Inicio_Med'];
        $stuff["Fecha_Finalización"] = $row['Fecha_Final_Med'];
        $stuff["Fecha_Regis_Medicamento"] = $row['Fecha_Regis_Medicamento'];
        array_push($response, $stuff);
    }
    $stuff = array();
    $stuff["process"] = "Datos_de_medicamento_encontrados";
    $stuff["message"] = "Medicamentos de este tipo encontradas";
    array_push($response, $stuff);
    header('Content-Type: application/json');
    echo (json_encode($response));
}else{
    $response["process"] = "Datos_de_medicamentos_no_encontrados";
    $response["message"] = "Error, No hay medicamentos de este tipo registrados para este usuario";
    header('Content-Type: application/json');
    echo (json_encode($response));
} 

//  http://localhost/ApisTesis/Medicamentos/BuscarMedicamentoTipo.php?Id_Usuario=4&Id_TipoEnfer=1 <<<<< LINK DEL API 
?>