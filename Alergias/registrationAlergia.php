    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
    require_once("conexion.php"); //Inclusión requerida el archivo de conexión

    $db = new Conexion();
    $con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos
        
    $response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta
    

    $Id_Usuario = $_GET["Id_Usuario"];
    $Id_Alergia= "";//Este y primero se tomanm de los adapter
    $Nombre_Alergia= $_GET['Nombre_Alergia'];//Este y los dos siguinetes se toman por teclado por parte del usuario
    $Id_TipoAlergia= $_GET['Id_TipoAlergia'];
    $DecripcionPropia_Alerg= $_GET['DecripcionPropia_Alerg'];
    $Fecha_Regis_Aler= $_GET['Fecha_Regis_Aler'];
 

    
    //Select Para saber si ya el nombre de alergia se encuentra en la base datos
    $querys = $con->prepare("SELECT * FROM  alergias  where  Nombre_Alergia = ?");		
    $querys->execute(array($Nombre_Alergia));

    if ($querys->rowCount() > 0) {
        //Al existir se trae todos lo campos, pero sólo se usaraá el ID de esa alergia
        $result = $querys->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $Id_Alergia = $row['Id_Alergia'];
        }

        //Select Para saber si la alrgia se encuentra asociada con el usaurio en cuestión en la base datos, para cumplir con el principio de que un usaario puede sufrir de esa alergia una vez y nos 2 y tres veces, eciatando datos redundantes. 
        $querysOne = $con->prepare("SELECT * FROM  usuaalergia  where  (Id_UsuarioFK = ?)&& (Id_AlergiaFK = ?) ");		
        $querysOne->execute(array($Id_Usuario, $Id_Alergia));

        if ($querysOne->rowCount() > 0) {
            header('Content-Type: application/json');
            $response["process"] = "DatosuserAlergia_Found";
            $response["message"] = "Este Usuario ya cuenta con esa alergia registrada";
            echo (json_encode($response));      
        }else{
            $querysTwo = $con->prepare("INSERT INTO usuaalergia (`Id_UsuarioFK`, `Id_AlergiaFK`, `DecripcionPropia_Alerg`, `Fecha_Regis_Aler`) VALUES (?,?,?,?)");		 
            $querysTwo->execute(array($Id_Usuario,$Id_Alergia,$DecripcionPropia_Alerg,$Fecha_Regis_Aler));
            if ($querysTwo) {
                header('Content-Type: application/json');
                $response["process"] = "DatosuserAlergia_Registered1";
                $response["message"] = "Datos de alergia registrada en la BD";
                echo (json_encode($response));
            }else{
                header('Content-Type: application/json');
                $response["process"] = "DatosuserAlergia_NOTRegistered1";
                $response["message"] = "Datos de alergia NO registrados en la BD";
                echo (json_encode($response));
            }
        }    
    }else {
        $querysThree = $con->prepare("INSERT INTO alergias (`Nombre_Alergia`, `TipoAlerFK`) VALUES (?,?)");		 
        $querysThree->execute(array($Nombre_Alergia,$Id_TipoAlergia));
        if ($querysThree) {
            $queryAxuliarDos = $con->prepare("SELECT Id_Alergia FROM alergias ORDER BY Id_Alergia DESC LIMIT 1;");		
            $queryAxuliarDos->execute();
            if ($queryAxuliarDos) {
                $result = $queryAxuliarDos->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $Id_Alergia= $row['Id_Alergia'];  
                }

                $querysFour = $con->prepare("INSERT INTO usuaalergia (`Id_UsuarioFK`, `Id_AlergiaFK`, `DecripcionPropia_Alerg`, `Fecha_Regis_Aler`) VALUES (?,?,?,?)");		 
                $querysFour->execute(array($Id_Usuario,$Id_Alergia,$DecripcionPropia_Alerg,$Fecha_Regis_Aler));
                if ($querysFour) {
                    header('Content-Type: application/json');
                    $response["process"] = "DatosuserAlergiaNew_Registered2";
                    $response["message"] = "Datos de alergia registrada en la BD";
                    echo (json_encode($response));
                }else{
                    header('Content-Type: application/json');
                    $response["process"] = "DatosuserAlergiaNew_NOTRegistered2";
                    $response["message"] = "Datos de alergia NO registrados en la BD";
                    echo (json_encode($response));
                }
            }
        }else{
            header('Content-Type: application/json');
            $response["process"] = "DatosuserAlergia_NOTRegistered0";
            $response["message"] = "Datos de alergia NO registrados en la BD";
            echo (json_encode($response));
        }
    }

    // http://localhost/ApisTesis/Alergias/registrationAlergia.php?Id_Usuario=6&&Nombre_Alergia=Papos&Id_TipoAlergia=3 &DecripcionPropia_Alerg= La alergia al papo me provoca estornudos constantes, con picazón en los ojos y congestión nasal.&Fecha_Regis_Aler=2024/05/09   <<<<< LINK DEL API 
    ?>
	