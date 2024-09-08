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
$querysAmarre = $con->prepare("SELECT e.Id_Usuario,a.Id_Hospitalizacion,b.Nombre_CentroHos, b.ProvinciaUbicacion,c.Nombre_Medico,d.Nombre_Especialidad,a.Motivo_Hos,a.Descripcion_Hosp,a.FechaIngresoHsp,a.FechaISalidaHsp,a.Fecha_RegistrHospit
FROM  hospitalizaciones a INNER JOIN centros_de_atencio b ON  (b.Id_CentroHosp = a.Id_CentroAtencionFK)  INNER JOIN lista_de_medicos c ON (c.Id_medico = a.Id_Medico_FK) INNER JOIN especialidades_medi d ON (d.Id_Especialidad = c.Id_EspecialidadFK) INNER JOIN usuariospacientes e ON (e.Id_Usuario = a.Id_UsuarioFK) && (e.Id_Usuario = ?);");
$querysAmarre->execute(array($Id_Usuario)); 
//SELECT a.Nombre_Alergia,c.descripcion_TipoAler,b.DecripcionPropia_Alerg,b.Fecha_Regis_Aler  FROM alergias a INNER JOIN usuaalergia b ON  (b.Id_AlergiaFK = a.Id_Alergia)  INNER JOIN tipoalergia c ON (c.Id_TipoAler = a.TipoAlerFK) INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (d.Id_Usuario = 6);
   

if ($querysAmarre->rowCount() > 0) {
    $result = $querysAmarre->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Id_Usuario"] = $row['Id_Usuario'];
        $stuff["Id_Hospitalizacion"] = $row['Id_Hospitalizacion'];
        $stuff["Nombre_CentroHos"] = $row['Nombre_CentroHos'];
        $stuff["ProvinciaUbicacion"] = $row['ProvinciaUbicacion'];
        $stuff["Nombre_Medico"] = $row['Nombre_Medico'];
        $stuff["Nombre_Especialidad"] = $row['Nombre_Especialidad'];
        $stuff["Motivo_Hos"] = $row['Motivo_Hos'];
        $stuff["Descripcion_Hosp"] = $row['Descripcion_Hosp'];
        $stuff["FechaIngresoHsp"] = $row['FechaIngresoHsp'];
        $stuff["FechaISalidaHsp"] = $row['FechaISalidaHsp'];
        $stuff["Fecha_RegistrHospit"] = $row['Fecha_RegistrHospit'];
        array_push($response, $stuff);
    }
    if($response != null){
        $stuff = array();
        $stuff["process"] = "Datos_de_Hospitalizaciones_encontrados";
        $stuff["message"] = "Listado de Hospitalizaciones encontrados";
        array_push($response, $stuff);
        header('Content-Type: application/json');
        echo (json_encode($response));
    }else{
        $response["process"] = "Datos_de_Hospitalizaciones_no_encontrados";
        $response["message"] = "Error en intentar encontar los datos de las Hospitalizaciones de este usuario";
        header('Content-Type: application/json');
        echo (json_encode($response));
    }
}else{
    $response["process"] = "Datos_de_hospitalizaciones_no_encontrados";
    $response["message"] = "Error, este usuario no cuenta con hospitalizaciones registradas";
    header('Content-Type: application/json');
    echo (json_encode($response));
} 
//  http://localhost/ApisTesis/Hospitalizaciones/ListadosdeHospitalizacionesUser.php?Id_Usuario=4 <<<<< LINK DEL API 
?>