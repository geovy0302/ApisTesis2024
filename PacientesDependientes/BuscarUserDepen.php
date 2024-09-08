<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_UsuarioPrincipal = $_GET['Id_UsuarioPrincipal'];
$Cedula_DNI= $_GET['Cedula_DNI'];
$Id_Pacientedependiente= "";

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
/* $querysAmarre = $con->prepare("SELECT a.NombreC_U,a.Según_nombre,a.Apellido_CU,a.SegundoAC_U,a.Sexo,a.Dirección,a.Edad,a.Fecha_Nacimiento,a.Cedula_DNI,a.Tipo_D_Sangre,a.E_mail,a.Password, 
 b.Peso, b.Altura  FROM usuariospacientes a INNER JOIN datosuserspaciente b ON (a.Id_Usuario = b.Id_UsuarioFK) && (a.Cedula_DNI =?);
$querysAmarre->execute(array($Cedula_DNI));  */
$querys = $con->prepare("SELECT Id_Usuario FROM  usuariospacientes  where  Cedula_DNI = ?");		
$querys->execute(array($Cedula_DNI));

if ($querys->rowCount() > 0) {
    $result = $querys->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $Id_Pacientedependiente= $row['Id_Usuario'];  
    }
        
    $querysTwo = $con->prepare("SELECT * FROM  userdependencia  where  (Id_UserPrinciFK = ?) && (Id_UserDepenFK = ?)");		
    $querysTwo->execute(array($Id_UsuarioPrincipal,$Id_Pacientedependiente));
    if ($querysTwo->rowCount() > 0) {
        $querysAmarre = $con->prepare("SELECT a.NombreC_U,a.Según_nombre,a.Apellido_CU,a.SegundoAC_U,a.Sexo,a.Dirección,a.Edad,a.Fecha_Nacimiento,a.Cedula_DNI,a.Tipo_D_Sangre,a.E_mail,a.Password, 
        b.Peso, b.Altura  FROM usuariospacientes a INNER JOIN datosuserspaciente b ON (a.Id_Usuario = b.Id_UsuarioFK) && (a.Cedula_DNI =?);");
        $querysAmarre->execute(array($Cedula_DNI));
        if ($querysAmarre) {
            $result = $querysAmarre->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $stuffo = array();
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
                $stuff["process"] = "Datos_de_Usuario_Dependiente_encontrados";
                $stuff["message"] = "Usuario Dependiente encontrado";
                array_push($response, $stuff);
                header('Content-Type: application/json');
                echo (json_encode($response));
            }else{
                $response["process"] = "Datos_de_Cedula_vinculada_no_encontrados";
                $response["message"] = "La cédula existe en la base de datos, pero los datos no cargaron";
                header('Content-Type: application/json');
                echo (json_encode($response));
            }
        }     
    }else{
        $response["process"] = "Cedulanovinculada";
        $response["message"] = "La cédula existe en la base de datos, pero no es un paciente dependentiente para este usario principal";
        header('Content-Type: application/json');
        echo (json_encode($response));
    }
                  
}else {
    header('Content-Type: application/json');
    $response["process"] = "Errordecedulaenbasedatos";
    $response["message"] = "Error, no se encuentra esta cédula en la base datos";    
    echo(json_encode($response));
}


   



/* http://localhost/ApisTesis/PacientesDependientes/BuscarUserDepen.php?Id_UsuarioPrincipal=4&&Cedula_DNI=9-156-187 <<<<< LINK DEL API   */
?>