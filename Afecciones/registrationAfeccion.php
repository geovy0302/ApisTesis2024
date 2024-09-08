    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
    require_once("conexion.php"); //Inclusión requerida el archivo de conexión

    $db = new Conexion();
    $con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos
        
    $response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta
    

    $Id_Usuario = $_GET["Id_Usuario"];
    $Id_Enfermedad= "";//Este y primero se tomanm de los adapter
    $Id_EnfermedadtipoAux= "";
    $Nombre_enf= $_GET['Nombre_enf'];//Este y los dos siguinetes se toman por teclado por parte del usuario
    $Id_TipoEnfer= $_GET['Id_TipoEnfer'];
    $Fecha_Inicio= $_GET['Fecha_Inicio'];
    $Fecha_Finalizacion= $_GET['Fecha_Finalización'];
    if (($Fecha_Finalizacion == "") || ($Fecha_Finalizacion == null)) {
        $Fecha_Finalizacion= "no tiene";
    }
    $DescripcionPropia_enf= $_GET['DescripcionPropia_enf'];
    $Fecha_Regis_Cro= $_GET['Fecha_Regis_Cro'];
 

    
    //Select Para saber si ya el nombre de alergia se encuentra en la base datos
    $querys = $con->prepare("SELECT * FROM  enfermedades  where  Nombre_enf = ?");		
    $querys->execute(array($Nombre_enf));

    if ($querys->rowCount() > 0) {
        //Al existir se trae todos lo campos, pero sólo se usaraá el ID de esa alergia
        $result = $querys->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $Id_Enfermedad = $row['Id_Enfermedad'];
            $Id_EnfermedadtipoAux = $row['TipoEnfermedadFK'];
        }

        //Select Para saber si la alrgia se encuentra asociada con el usaurio en cuestión en la base datos, para cumplir con el principio de que un usaario puede sufrir de esa alergia una vez y nos 2 y tres veces, eciatando datos redundantes. 
        $querysOne = $con->prepare("SELECT * FROM  usuaenfermedad  where  (Id_UsuarioFK = ?)&& (Id_EnfermedadFK = ?) ");		
        $querysOne->execute(array($Id_Usuario, $Id_Enfermedad));

        if ($querysOne->rowCount() > 0) {
            if ($Id_EnfermedadtipoAux == "1") {
                header('Content-Type: application/json');
                $response["process"] = "DatosuserAffection_cronicanoregistred";
                $response["message"] = "Este Usuario ya cuenta con esta afección crónica registrada";
                echo (json_encode($response));      
            }else{
                $querysTwo = $con->prepare("INSERT INTO usuaenfermedad (`Id_UsuarioFK`, `Id_EnfermedadFK`, `Fecha_Inicio`, `Fecha_Finalización`,`DescripcionPropia_enf`,`Fecha_Regis_Cro` ) VALUES (?,?,?,?,?,?)");		 
                $querysTwo->execute(array($Id_Usuario,$Id_Enfermedad,$Fecha_Inicio,$Fecha_Finalizacion,$DescripcionPropia_enf,$Fecha_Regis_Cro));
                if ($querysTwo) {
                    header('Content-Type: application/json');
                    $response["process"] = "DatosuserAfeccion_Registered1";
                    $response["message"] = "Datos de afeccióin registrados en la BD";
                    echo (json_encode($response));
                }else{
                    header('Content-Type: application/json');
                    $response["process"] = "DatosuserAlergia_NOTRegistered1";
                    $response["message"] = "Datos de afección NO registrados en la BD";
                    echo (json_encode($response));
                }
            }      
        }else{
            $querysThree = $con->prepare("INSERT INTO usuaenfermedad (`Id_UsuarioFK`, `Id_EnfermedadFK`, `Fecha_Inicio`, `Fecha_Finalización`,`DescripcionPropia_enf`,`Fecha_Regis_Cro` ) VALUES (?,?,?,?,?,?)");		 
            $querysThree->execute(array($Id_Usuario,$Id_Enfermedad,$Fecha_Inicio,$Fecha_Finalizacion,$DescripcionPropia_enf,$Fecha_Regis_Cro));
            if ($querysThree) {
                header('Content-Type: application/json');
                $response["process"] = "DatosuserAfeccion_Registered1";
                $response["message"] = "Datos de afeccióin registrados en la BD";
                echo (json_encode($response));
            }else{
                header('Content-Type: application/json');
                $response["process"] = "DatosuserAlergia_NOTRegistered1";
                $response["message"] = "Datos de afección NO registrados en la BD";
                echo (json_encode($response));
            }
        }    
    }else {
        $querysfour = $con->prepare("INSERT INTO enfermedades (`Nombre_enf`, `TipoEnfermedadFK`) VALUES (?,?)");		 
        $querysfour->execute(array($Nombre_enf,$Id_TipoEnfer));
        if ($querysfour) {
            $queryAxuliarOne = $con->prepare("SELECT Id_Enfermedad FROM enfermedades ORDER BY Id_Enfermedad DESC LIMIT 1;");		
            $queryAxuliarOne->execute();
            if ($queryAxuliarOne) {
                $result = $queryAxuliarOne->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $Id_Enfermedad= $row['Id_Enfermedad'];  
                }

                $querysFive = $con->prepare("INSERT INTO usuaenfermedad (`Id_UsuarioFK`, `Id_EnfermedadFK`, `Fecha_Inicio`, `Fecha_Finalización`,`DescripcionPropia_enf`,`Fecha_Regis_Cro` ) VALUES (?,?,?,?,?,?)");		 
                $querysFive->execute(array($Id_Usuario,$Id_Enfermedad,$Fecha_Inicio,$Fecha_Finalizacion,$DescripcionPropia_enf,$Fecha_Regis_Cro));
                if ($querysFive) {
                    header('Content-Type: application/json');
                    $response["process"] = "DatosuserANew_Registered2";
                    $response["message"] = "Datos de nueva afección registrada en la BD";
                    echo (json_encode($response));
                }else{
                    header('Content-Type: application/json');
                    $response["process"] = "DatosuserAlergiaNew_NOTRegistered2";
                    $response["message"] = "Datos de afección NO registrados en la BD";
                    echo (json_encode($response));
                }
            }
        }else{
            header('Content-Type: application/json');
            $response["process"] = "DatosuserAfeccionNew_NOTRegistered0";
            $response["message"] = "Datos de nueva alergia no registrados en la BD";
            echo (json_encode($response));
        }
    }

    // http://localhost/ApisTesis/Afecciones/registrationAfeccion.php?Id_Usuario=4&Nombre_enf=Asma&Id_TipoEnfer=1&Fecha_Inicio=2014/01/03&Fecha_Finalización="Sigue sin tener"&DescripcionPropia_enf=descripción de prueba&Fecha_Regis_Cro=2024/01/03  <<<<< LINK DEL API
    // http://localhost/ApisTesis/Afecciones/registrationAfeccion.php?Id_Usuario=4&Nombre_enf=Gripe&Id_TipoEnfer=2&Fecha_Inicio=2024/05/29&Fecha_Finalización=2024/06/04&DescripcionPropia_enf=Volví a sufrir de girpe con fuerte dolores de cabeza, fiebre altas  y los escalofríos debdio a que me rucié con agua lluvia&Fecha_Regis_Cro=2024/05/10  <<<<< LINK DEL API  



    // http://localhost/ApisTesis/Afecciones/registrationAfeccion.php?Id_Usuario=4&Nombre_enf=Apendicitis&Id_TipoEnfer=2&Fecha_Inicio=2024/03/20&Fecha_Finalización=2024/05/03&DescripcionPropia_enf=Esta se me presentó con un dolor intenso en la parte inferior derecha del abdomen, acompañado de náuseas y fiebre, pero luego de la operación y la medicacón pertienente me curé&Fecha_Regis_Cro=2024/05/06  <<<<< LINK DEL API  
    ?>
	