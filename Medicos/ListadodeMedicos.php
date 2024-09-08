<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
    require_once("conexion.php"); //Inclusión requerida el archivo de conexión
    
    $db = new Conexion();
    $con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos
    

    $response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta
    
    
    $query = $con->prepare("SELECT a.Id_medico,a.Nombre_Medico, b.Nombre_Especialidad FROM lista_de_medicos a INNER JOIN especialidades_medi b ON (a.Id_EspecialidadFK = b.Id_Especialidad) ; ");	
	$query->execute();
   

    if ($query) {
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $stuff = array();
            $stuff["Id_medico"] = $row['Id_medico'];
            $stuff["Nombre_Medico"] = $row['Nombre_Medico'];
            $stuff["Nombre_Especialidad"] = $row['Nombre_Especialidad'];
            array_push($response, $stuff);
        }
        if($response != null){
            $stuff = array();
            $stuff["process"] = "Datos_de_Medicos_encontrados";
            $stuff["message"] = "Médicos encontrados ";
            array_push($response, $stuff);
            header('Content-Type: application/json');
            echo (json_encode($response));
        }else{
            $response["success"] = "Medicos_No_Encontrado";
            $stuff["message"] = "Médicos No encontrados ";
            header('Content-Type: application/json');
            echo (json_encode($response));
        }
    } 
    //  http://localhost/ApisTesis/Medicos/ListadodeMedicos.php?  <<<<< LINK DEL API 
?>