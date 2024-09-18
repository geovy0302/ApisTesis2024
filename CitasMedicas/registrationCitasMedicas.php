    <?php
   //ini_set('display_errors', 1);
    //ini_set('display_startup_errors', 1);
    //error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
    ob_start(); // Inicia el buffer de salida
    require_once("conexion.php"); //Inclusión requerida el archivo de conexión

    $db = new Conexion();
    $con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos
        
    $response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta
    

    $Id_Usuario = $_POST["Id_Usuario"];
    $Nombre_CentroHos = $_POST["Nombre_CentroHos"];
    $Nombre_Especialidad = $_POST['Nombre_Especialidad'];
    $Nombre_Medico = $_POST['Nombre_Medico'];
    $Descrip_Consul= $_POST['Descrip_Consul'];
    $Diagnóstico = $_POST['Diagnóstico'];
    $Detalle_Receta = $_POST['Detalle_Receta'];
    $Detalle_de_Examen = $_POST['Detalle_de_Examen'];
    $FechadeCita = $_POST['FechadeCita'];
    $Fecha_RegistrCi = $_POST['Fecha_RegistrCi'];
    $SignoVital_Presion = $_POST['SignoVital_Presion'];
    $SignoVital_Temperatura = $_POST['SignoVital_Temperatura'];
    $SignoVital_Peso = $_POST['SignoVital_Peso'];
    $SignoVital_Altura = $_POST['SignoVital_Altura'];
    
    $Id_cenntroMedico= "";
    $Id_medico= "";

    

    //Select Para saber si ya el nombre del Centro Médico se encuentra en la base datos
    $querys = $con->prepare("SELECT * FROM  centros_de_atencio  where  Nombre_CentroHos = ?");		
    $querys->execute(array($Nombre_CentroHos));

    if ($querys->rowCount() > 0) {
        //Al existir se trae todos lo campos, pero sólo se usaraá el ID de ese Hospital
        $result = $querys->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $Id_cenntroMedico = $row['Id_CentroHosp'];
        }

        //Select Para saber si la cita médicas se encuentra asociada con el usaurio en cuestión en la base datos.
        $querysOne = $con->prepare("SELECT * FROM  lista_de_medicos  where  Nombre_Medico = ?");		
        $querysOne->execute(array($Nombre_Medico));

        if ($querysOne->rowCount() > 0) {
            //Al existir se trae todos lo campos, pero sólo se usaraá el ID de ese Hospital
            $result = $querysOne->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $Id_medico = $row['Id_medico'];
            }
    
            //Select Para saber si la cita médicas se encuentra asociada con el usaurio en cuestión en la base datos.
            $querysTwo = $con->prepare("INSERT INTO citasmedicas (`Id_UsuarioFK`,`Id_CentroAteFK`,`Id_MedicoFK`, `Descrip_Consul`,`Diagnóstico`,`Detalle_Receta`,`Detalle_de_Examen`,`FechadeCita`,`Fecha_RegistrCi`,`SignoVital_Presion`,`SignoVital_Temperatura`,`SignoVital_Peso`,`SignoVital_Altura` ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");		
            $querysTwo->execute(array($Id_Usuario,$Id_cenntroMedico,$Id_medico,$Descrip_Consul,$Diagnóstico,$Detalle_Receta,$Detalle_de_Examen,$FechadeCita,$Fecha_RegistrCi,$SignoVital_Presion,$SignoVital_Temperatura,$SignoVital_Peso,$SignoVital_Altura));

            if ($querysTwo) {
                $response["process"] = "DatosuserCitas_Registered";
                $response["message"] = "Datos de Citas Médicas registrados en la BD";
                header('Content-Type: application/json');
                echo (json_encode($response));
                exit();
            }else{
                $response["process"] = "DatosuserCitasMedicas_NOTRegistered";
                $response["message"] = "Datos de citas médicas NO registrados en la BD";
                header('Content-Type: application/json');
                echo (json_encode($response));
                exit();
            }
        }else {
            $response["process"] = "DatosuserCitasMedicas_NOTRegistered";
            $response["message"] = "Datos de citas medicas no registrados en la BD, debido a que el doctor no existe";
            header('Content-Type: application/json');
            echo (json_encode($response));
            exit();
        }
    }else {
        $response["process"] = "DatosuserCitasMedicas_NOTRegistered";
        $response["message"] = "Datos de citas medicas no registrados en la BD, debido a que al centro médico no existe";
        header('Content-Type: application/json');
        echo (json_encode($response));
        exit();
    }
	