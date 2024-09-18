<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
ob_start(); // Inicia el buffer de salida
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario= $_POST['Id_Usuario'];
$FechaInicio= $_POST['FechaInicio'];
$FechaFinal= $_POST['FechaFinal'];

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta


//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysAmarre = $con->prepare("SELECT e.Id_Usuario,a.Id_Cita,b.Nombre_CentroHos, b.ProvinciaUbicacion,c.Nombre_Medico,d.Nombre_Especialidad,a.Descrip_Consul,a.Diagnóstico,a.Detalle_Receta,a.FechadeCita,a.Fecha_RegistrCi,a.SignoVital_Presion,a.SignoVital_Temperatura,a.SignoVital_Peso,a.SignoVital_Altura
FROM  citasmedicas a  
INNER JOIN centros_de_atencio b ON  (b.Id_CentroHosp = a.Id_CentroAteFK)  
INNER JOIN lista_de_medicos c ON (c.Id_medico = a.Id_MedicoFK) 
INNER JOIN especialidades_medi d ON (d.Id_Especialidad = c.Id_EspecialidadFK) 
INNER JOIN usuariospacientes e ON (e.Id_Usuario = a.Id_UsuarioFK) && (e.Id_Usuario = ?)
WHERE (a.FechadeCita BETWEEN ? and ?);");
$querysAmarre->execute(array( $Id_Usuario, $FechaInicio, $FechaFinal)); 

   

if ($querysAmarre->rowCount() > 0) {
    $result = $querysAmarre->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Id_Usuario"] = $row['Id_Usuario'];
        $stuff["Id_Cita"] = $row['Id_Cita'];
        $stuff["Nombre_CentroHos"] = $row['Nombre_CentroHos'];
        $stuff["ProvinciaUbicacion"] = $row['ProvinciaUbicacion'];
        $stuff["Nombre_Medico"] = $row['Nombre_Medico'];
        $stuff["Nombre_Especialidad"] = $row['Nombre_Especialidad'];
        $stuff["Descrip_Consul"] = $row['Descrip_Consul'];
        $stuff["Diagnóstico"] = $row['Diagnóstico'];
        $stuff["Detalle_Receta"] = $row['Detalle_Receta'];
        $stuff["FechadeCita"] = $row['FechadeCita'];
        $stuff["SignoVital_Presion"] = $row['SignoVital_Presion'];
        $stuff["SignoVital_Temperatura"] = $row['SignoVital_Temperatura'];
        $stuff["SignoVital_Peso"] = $row['SignoVital_Peso'];
        $stuff["SignoVital_Altura"] = $row['SignoVital_Altura'];
        array_push($response, $stuff);
    }
    if($response != null){
        $stuff = array();
        $stuff["process"] = "Datos_de_Citas_Medicas_encontrados";
        $stuff["message"] = "Citas Médicas encontradas en ese rango de fechas";
        array_push($response, $stuff);
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }else{
        $response["process"] = "Datos_de_Citas_Medicas_no_encontrados";
        $response["message"] = "Error en intentar encontar los datos de las citas médicas de este usuario en ese rango de fecha";
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }
}else{
    $response["process"] = "Datos_de_medicamentos_no_encontrados";
    $response["message"] = "Error, este usuario no cuenta con citas médicas registradas en ese rango de fecha";
    header('Content-Type: application/json');
    echo (json_encode($response));
    exit();
} 
//  http://localhost/ApisTesis/CitasMedicas/BuscarCitasMediUser.php?Id_Usuario=4 <<<<< LINK DEL API 
