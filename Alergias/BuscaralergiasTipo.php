<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario= $_GET['Id_Usuario'];
$Id_TipoAlergia= $_GET['Id_TipoAlergia'];

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta


//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysAmarre = $con->prepare("SELECT d.Id_Usuario, a.Id_Alergia,a.Nombre_Alergia,c.descripcion_TipoAler,b.DecripcionPropia_Alerg,b.Fecha_Regis_Aler 
FROM alergias a INNER JOIN usuaalergia b ON  (b.Id_AlergiaFK = a.Id_Alergia) INNER JOIN tipoalergia c ON (c.Id_TipoAler = a.TipoAlerFK) && (b.Id_AlergiaFK = a.Id_Alergia) && (a.TipoAlerFK =?)INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (b.Id_UsuarioFK = ?);");
$querysAmarre->execute(array($Id_TipoAlergia,$Id_Usuario)); 
//SELECT a.Nombre_Alergia,c.descripcion_TipoAler,b.DecripcionPropia_Alerg,b.Fecha_Regis_Aler  FROM alergias a INNER JOIN usuaalergia b ON  (b.Id_AlergiaFK = a.Id_Alergia)  INNER JOIN tipoalergia c ON (c.Id_TipoAler = a.TipoAlerFK) INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (d.Id_Usuario = 6);
   

if ($querysAmarre->rowCount() > 0) {
    $result = $querysAmarre->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Id_Usuario"] = $row['Id_Usuario'];
        $stuff["Id_Alergia"] = $row['Id_Alergia'];
        $stuff["Nombre_Alergia"] = $row['Nombre_Alergia'];
        $stuff["descripcion_TipoAler"] = $row['descripcion_TipoAler'];
        $stuff["DecripcionPropia_Alerg"] = $row['DecripcionPropia_Alerg'];
        $stuff["Fecha_Regis_Aler"] = $row['Fecha_Regis_Aler'];
        array_push($response, $stuff);
    }
    $stuff = array();
    $stuff["process"] = "Datos_de_alergia_encontrados";
    $stuff["message"] = "Alergias de este tipo encontradas";
    array_push($response, $stuff);
    header('Content-Type: application/json');
    echo (json_encode($response));
}else{
    $response["process"] = "Datos_de_alergia_no_encontrados";
    $response["message"] = "Error No hay alergias de este tipo registradas";
    header('Content-Type: application/json');
    echo (json_encode($response));
} 

//  http://localhost/ApisTesis/Alergias/BuscaralergiasTipo.php?Id_Usuario=6&Id_TipoAlergia=3 <<<<< LINK DEL API 
?>