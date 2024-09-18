<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
ob_start(); // Inicia el buffer de salida
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

$Id_Usuario= $_POST['Id_Usuario'];

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysAmarre = $con->prepare("SELECT d.Id_Usuario, a.Id_Enfermedad,b.Id_UserEnfermedad ,a.Nombre_enf,c.descripcion_TipEnfer,b.Fecha_Inicio,b.Fecha_Finalización,b.DescripcionPropia_enf,b.Fecha_Regis_Cro
FROM enfermedades a INNER JOIN usuaenfermedad b ON  (b.Id_EnfermedadFK = a.Id_Enfermedad) INNER JOIN tipoenfermedad c ON (c.Id_TipoEnfer = a.TipoEnfermedadFK) && (b.Id_EnfermedadFK = a.Id_Enfermedad) INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (d.Id_Usuario = ?);");
$querysAmarre->execute(array($Id_Usuario)); 

   

if ($querysAmarre->rowCount() > 0) {
    $result = $querysAmarre->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Id_Usuario"] = $row['Id_Usuario'];
        $stuff["Id_UserEnfermedad"] = $row['Id_UserEnfermedad'];
        $stuff["Id_Enfermedad"] = $row['Id_Enfermedad'];
        $stuff["Nombre_enf"] = $row['Nombre_enf'];
        $stuff["descripcion_TipEnfer"] = $row['descripcion_TipEnfer'];
        $stuff["Fecha_Inicio"] = $row['Fecha_Inicio'];
        $stuff["Fecha_Finalización"] = $row['Fecha_Finalización'];
        $stuff["DescripcionPropia_enf"] = $row['DescripcionPropia_enf'];
        $stuff["Fecha_Regis_Cro"] = $row['Fecha_Regis_Cro'];
        array_push($response, $stuff);
    }
    if($response != null){
        $stuff = array();
        $stuff["process"] = "Datos_de_afeccion_encontrados";
        $stuff["message"] = "Afecciones de este tipo encontradas";
        array_push($response, $stuff);
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }else{
        $response["process"] = "Datos_de_afeccion_no_encontrados";
        $response["message"] = "Error en intentar encontar los datos de afección de este usuario";
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }
    
}else{
    $response["process"] = "Datos_de_afeccions_no_encontrados";
    $response["message"] = "Error, No hay afecciones de este tipo registradas para este usuario";
    header('Content-Type: application/json');
    echo (json_encode($response));
    exit();
} 

//  http://localhost/ApisTesis/Afecciones/ListadosdeAfeccioneUser.php?Id_Usuario=4 <<<<< LINK DEL API


 