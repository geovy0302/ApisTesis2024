<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
ob_start(); // Inicia el buffer de salida
require_once("conexion.php"); //Inclusión requerida el archivo de conexión
    
    $db = new Conexion();
    $con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos
    

    $response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta
    
    $Id_tips = $_GET['Id_tips'];
  
    $querys = $con->prepare("SELECT Descripcion_Tip FROM  mensaje_tips  where  Id_Mensaje_Tips = ?");		
	$querys->execute(array($Id_tips));
   

    if ($querys) {
        $result = $querys->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $stuff = array();
            $stuff["Descripcion_Tip"] = $row['Descripcion_Tip'];
            array_push($response, $stuff);
        }
        if($response != null){
            $stuff = array();
            $stuff["process"] = "Datos_de_tips_encontrados";
            $stuff["message"] = "Datos de tips encontrado";
            array_push($response, $stuff);
            header('Content-Type: application/json');
            echo (json_encode($response));
            exit();
        }else{

            $response["process"]= "Datos_de_tips_no_encontrados";
            $response["message"] = "Datos de tips no encontrado";
            header('Content-Type: application/json');
            echo (json_encode($response));
            exit();
        }
    } 

    //  http://localhost/ApisTesis/Tips/traerTips.php?Id_tips=16  <<<<< LINK DEL API 
