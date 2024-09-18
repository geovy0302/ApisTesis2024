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
$querysAmarre = $con->prepare("SELECT a.NombreC_U,a.Según_nombre,a.Apellido_CU,a.SegundoAC_U,a.Sexo,a.Dirección,a.Edad,a.Fecha_Nacimiento,a.Cedula_DNI,a.Tipo_D_Sangre,a.E_mail,a.Password, 
 b.Peso, b.Altura  FROM usuariospacientes a INNER JOIN datosuserspaciente b ON (a.Id_Usuario = b.Id_UsuarioFK) && (a.Id_Usuario =?); ");
$querysAmarre->execute(array($Id_Usuario)); 
   

if ($querysAmarre->rowCount() > 0) {
    $result = $querysAmarre->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["NombreC_U"] = $row['NombreC_U'];
        $stuff["Segun_nombre"] = $row['Según_nombre'];
        $stuff["Apellido_CU"] = $row['Apellido_CU'];
        $stuff["SegundoAC_U"] = $row['SegundoAC_U'];
        $stuff["Sexo"] = $row['Sexo'];
        $stuff["Dirección"] = $row['Dirección'];
        $stuff["Edad"] = $row['Edad'];
        $stuff["Fecha_Nacimiento"] = $row['Fecha_Nacimiento'];
        $stuff["Cedula_DNI"] = $row['Cedula_DNI'];
        $stuff["Tipo_D_Sangre"] = $row['Tipo_D_Sangre']; 
        $stuff["Peso"] = $row['Peso'];
        $stuff["Altura"] = $row['Altura'];
        array_push($response, $stuff);
    }
    if($response != null){
        $stuff = array();
        $stuff["process"] = "Datos_de_Usuario_Principal_encontrados";
        $stuff["message"] = "Usuario Principal encontrado";
        array_push($response, $stuff);
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }else{
        $response["process"] = "Error_finding_User_Profile";
        $response["message"] = "Error en intentar encontar los datos del usuario";
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }
} else{
    $response["process"] = "ErrordeiduserProfile";
    $response["message"] = "Error, no se encuentra esta ID en la base datos";
    header('Content-Type: application/json');
    echo (json_encode($response));
    exit();
}

//  http://localhost/ApisTesis/PerfilUser/UserFind.php?Id_Usuario=1 <<<<< LINK DEL API 
