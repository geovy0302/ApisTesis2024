<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario= $_GET['Id_Usuario'];

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta


//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysAmarre = $con->prepare("SELECT d.Id_Usuario,b.Id_MedicaToma, a.Id_Medicamento,a.Nombre_Medicamento,c.descripcion_TipMed,b.Dosis_Indi_Medi,b.Razon_de_Toma,b.Fecha_Inicio_Med,b.Fecha_Final_Med,b.Fecha_Regis_Medicamento 
FROM medicamentos a INNER JOIN usuamedica b ON  (b.Id_MedicamentoFK = a.Id_Medicamento)  INNER JOIN tipo_de_medicamento c ON (c.Id_TipoMedicamento = a.TipoMediFK) INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (d.Id_Usuario = ?);");
$querysAmarre->execute(array($Id_Usuario)); 

   

if ($querysAmarre->rowCount() > 0) {
    $result = $querysAmarre->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Id_Usuario"] = $row['Id_Usuario'];
        $stuff["Id_MedicaToma"] = $row['Id_MedicaToma'];
        $stuff["Id_Enfermedad"] = $row['Id_Medicamento'];
        $stuff["Nombre_Medicamento"] = $row['Nombre_Medicamento'];
        $stuff["descripcion_TipMed"] = $row['descripcion_TipMed'];
        $stuff["Dosis_Indi_Medi"] = $row['Dosis_Indi_Medi'];
        $stuff["Razon_de_Toma"] = $row['Razon_de_Toma'];
        $stuff["Fecha_Inicio"] = $row['Fecha_Inicio_Med'];
        $stuff["Fecha_Finalización"] = $row['Fecha_Final_Med'];
        $stuff["DescripcionPropia_enf"] = $row['Fecha_Regis_Medicamento'];
        array_push($response, $stuff);
    }
    if($response != null){
        $stuff = array();
        $stuff["process"] = "Datos_de_medicamentos_encontrados";
        $stuff["message"] = "Listados de Medicamentos encontrados";
        array_push($response, $stuff);
        header('Content-Type: application/json');
        echo (json_encode($response));
    }else{
        $response["process"] = "Datos_de_medicamentos_no_encontrados";
        $response["message"] = "Error en intentar encontar los datos de los medicamentos de este usuario";
        header('Content-Type: application/json');
        echo (json_encode($response));
    }
}else{
    $response["process"] = "Datos_de_medicamentos_no_encontrados";
    $response["message"] = "Error, este usuario no cuenta con medicamentos registradas";
    header('Content-Type: application/json');
    echo (json_encode($response));
} 
//  http://localhost/ApisTesis/Medicamentos/ListadosdeMedicamentosUser.php?Id_Usuario=4 <<<<< LINK DEL API 
?>