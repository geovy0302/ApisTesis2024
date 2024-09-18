<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
ob_start(); // Inicia el buffer de salida
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario= $_POST['Id_Usuario'];

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta


//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysAmarre = $con->prepare("SELECT d.Id_Usuario, a.Id_Alergia,a.Nombre_Alergia,c.descripcion_TipoAler,b.DecripcionPropia_Alerg,b.Fecha_Regis_Aler FROM alergias a INNER JOIN usuaalergia b ON  (b.Id_AlergiaFK = a.Id_Alergia)  INNER JOIN tipoalergia c ON (c.Id_TipoAler = a.TipoAlerFK) INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (d.Id_Usuario = ?);");
$querysAmarre->execute(array($Id_Usuario)); 
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
    if($response != null){
        $stuff = array();
        $stuff["process"] = "Datos_de_alergia_encontrados";
        $stuff["message"] = "Listados de Alergias encontradas";
        array_push($response, $stuff);
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }else{
        $response["process"] = "Datos_de_alergia_no_encontrados";
        $response["message"] = "Error en intentar encontar los datos de las alergías de este usuario";
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }
}else{
    $response["process"] = "Datos_de_alergia_no_encontrados";
    $response["message"] = "Error, este usuario no cuenta con alergias registradas";
    header('Content-Type: application/json');
    echo (json_encode($response));
    exit();
} 

//  http://localhost/ApisTesis/Alergias/ListadosdeAlergiaUser.php?Id_Usuario=4 <<<<< LINK DEL API 
