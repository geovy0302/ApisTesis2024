<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario = $_POST["Id_Usuario"];
$NombreC_U = $_POST["NombreC_U"];
$Según_nombre = $_POST["Según_nombre"];
$Apellido_CU = $_POST["Apellido_CU"];
$SegundoAC_U = $_POST["SegundoAC_U"];
$Sexo = $_POST["Sexo"];
$Dirección = $_POST["Dirección"];
$Edad = $_POST["Edad"];
$Fecha_Nacimiento = $_POST["Fecha_Nacimiento"];
$Cedula_DNI = $_POST["Cedula_DNI"];
$Tipo_D_Sangre = $_POST["Tipo_D_Sangre"];
$E_mail = $_POST["E_mail"];
$Altura = $_POST["Altura"];
$Peso = $_POST["Peso"];



$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta

//En las siguinetes líneas se realiza la consulta y se ralizan acciones dependiendo de los datos obtenidos 
$querysito = $con->prepare("UPDATE usuariospacientes SET NombreC_U=? , Según_nombre=?, Apellido_CU=?, SegundoAC_U=?, Sexo=?, Dirección=?, Edad=?, Fecha_Nacimiento=?,Cedula_DNI=?, Tipo_D_Sangre=?,E_mail=? where Id_Usuario=?");
$querysito->execute(array($NombreC_U,$Según_nombre,$Apellido_CU,$SegundoAC_U,$Sexo,$Dirección,$Edad,$Fecha_Nacimiento, $Cedula_DNI,$Tipo_D_Sangre,$E_mail, $Id_Usuario));
   
if ($querysito) {
    $queryTwo = $con->prepare("UPDATE datosuserspaciente SET Peso=?,Altura=? where Id_UsuarioFK=?");
    $queryTwo->execute(array($Peso,$Altura,$Id_Usuario));
    if ($queryTwo) {
        header('Content-Type: application/json');
        $response["process"] = "DatosuserDependiente_updatesss";
        $response["message"] = "Los Datos del usuario escogido han sido actulizados con éxito";
        echo (json_encode($response));
    }else{
        header('Content-Type: application/json');
        $response["process"] = "ErrorupdatedatospacienteQueryComplete";
        $response["message"] = "Error en intentar actualizar los datos del usuario en todas las tablas";
        echo (json_encode($response));
    }
}else {
    header('Content-Type: application/json');
    $response["process"] = "Errorupdatedatospaciente";
    $response["message"] = "Error en intentar actualizar los datos del usuario";
    echo (json_encode($response));
}

// LINK DEL API ↓↓↓↓↓↓↓↓↓↓↓↓↓
//  http://localhost/ApisTesis/PacientesDependientes/ModifiUserDePen.php?Id_Usuario=4&NombreC_U=Geovanny&Según_nombre=Alonso&Apellido_CU=Castillero&SegundoAC_U=Polanco&Sexo=M&Dirección=Panamá, Chiriquí, Gualaca&Edad=22&Fecha_Nacimiento=2001/02/02&Cedula_DNI=8-962-3421&Tipo_D_Sangre=O(+)&&E_mail=geovanny@gmail.com&Peso=220&Altura=1.81 
?>