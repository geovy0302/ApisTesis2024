<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
    require_once("conexion.php"); //Inclusión requerida el archivo de conexión
    
    $db = new Conexion();
    $con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos
    

    $response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta
    
    $query = $con->prepare("SELECT * FROM  especialidades_medi ");		
	$query->execute();
   

    if ($query) {
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $response["process"] = "Datos_de_Especialidades_encontrados";
        foreach ($result as $row) {
            $stuff = array();
            $stuff["Id_Especialidad"] = $row['Id_Especialidad'];
            $stuff["Nombre_Especialidad"] = $row['Nombre_Especialidad'];
            array_push($response, $stuff);
        }
        if($response != null){
            $stuff = array();
            $stuff["process"] = "Datos_de_Especialidad_encontrados";
            $stuff["message"] = "Datos de especialidad encontrados";
            header('Content-Type: application/json');
            echo (json_encode($response));
        }else{
            $response["process"] = "Datos_de_Especialidades_No_encontrados";
            $response["message"] = "Datos de especialidad no encontrados";
            header('Content-Type: application/json');
            echo (json_encode($response));
        }
    } 

    //  http://localhost/ApisTesis/Especialidades/ListadodeEspecialidad.php?  <<<<< LINK DEL API 
?>