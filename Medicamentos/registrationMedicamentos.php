    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
    require_once("conexion.php"); //Inclusión requerida el archivo de conexión

    $db = new Conexion();
    $con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos
        
    $response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta
    

    $Id_Usuario = $_GET["Id_Usuario"];
    $Id_Medicamento= "";//Este y primero se tomanm de los adapter
    $Id_MedicamentotipoAux= "";
    $Nombre_Medicamento= $_GET['Nombre_Medicamento'];//Este y los dos siguinetes se toman por teclado por parte del usuario
    $TipoMediFK= $_GET['TipoMediFK'];
    $Dosis_Indi_Medi= $_GET['Dosis_Indi_Medi'];
    $Razon_de_Toma= $_GET['Razon_de_Toma'];
    $Fecha_Inicio_Med= $_GET['Fecha_Inicio_Med'];
    $Fecha_Final_Med= $_GET['Fecha_Final_Med'];
    if (($Fecha_Final_Med == "") || ($Fecha_Final_Med == null)) {
        $Fecha_Final_Med= "no tiene";
    }
    $Fecha_Regis_Cro= $_GET['Fecha_Regis_Cro'];
 

    
    //Select Para saber si ya el nombre de medicamentos se encuentra en la base datos
    $querys = $con->prepare("SELECT * FROM  medicamentos  where  Nombre_Medicamento = ?");		
    $querys->execute(array($Nombre_Medicamento));

    if ($querys->rowCount() > 0) {
        //Al existir se trae todos lo campos, pero sólo se usaraá el ID de ese medicamento
        $result = $querys->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $Id_Medicamento = $row['Id_Medicamento'];
            $Id_MedicamentotipoAux = $row['TipoMediFK'];
        }

        $querysThree = $con->prepare("INSERT INTO usuamedica (`Id_UsuarioFK`, `Id_MedicamentoFK`, `Dosis_Indi_Medi`, `Razon_de_Toma`,`Fecha_Inicio_Med`,`Fecha_Final_Med`,`Fecha_Regis_Medicamento` ) VALUES (?,?,?,?,?,?,?)");		 
        $querysThree->execute(array($Id_Usuario,$Id_Medicamento,$Dosis_Indi_Medi,$Razon_de_Toma,$Fecha_Inicio_Med,$Fecha_Final_Med,$Fecha_Final_Med));
        if ($querysThree) {
            header('Content-Type: application/json');
            $response["process"] = "DatosuserMedicamentos_Registered1";
            $response["message"] = "Datos de medicamentos ya almacenado en la BD, pero registrado para este usaurio";
            echo (json_encode($response));
        }else{
            header('Content-Type: application/json');
            $response["process"] = "DatosuserMedicamentos_NOTRegistered1";
            $response["message"] = "Datos de medicamentos NO registrados en la BD";
            echo (json_encode($response));
        }
    }else {
        $querysfour = $con->prepare("INSERT INTO medicamentos (`Nombre_Medicamento`, `TipoMediFK`) VALUES (?,?)");		 
        $querysfour->execute(array($Nombre_Medicamento,$TipoMediFK));
        if ($querysfour) {
            $queryAxuliarOne = $con->prepare("SELECT Id_Medicamento FROM medicamentos ORDER BY Id_Medicamento DESC LIMIT 1;");		
            $queryAxuliarOne->execute();
            
            if ($queryAxuliarOne) {
                $result = $queryAxuliarOne->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $Id_Medicamento= $row['Id_Medicamento'];  
                }
                $querysFive = $con->prepare("INSERT INTO usuamedica (`Id_UsuarioFK`, `Id_MedicamentoFK`, `Dosis_Indi_Medi`, `Razon_de_Toma`,`Fecha_Inicio_Med`,`Fecha_Final_Med`,`Fecha_Regis_Medicamento` ) VALUES (?,?,?,?,?,?,?)");		 
                $querysFive->execute(array($Id_Usuario,$Id_Medicamento,$Dosis_Indi_Medi,$Razon_de_Toma,$Fecha_Inicio_Med,$Fecha_Final_Med,$Fecha_Final_Med));
                 if ($querysFive) {
                    header('Content-Type: application/json');
                    $response["process"] = "DatosuserMedicamentos_Registered2";
                    $response["message"] = "Datos de medicamentos registrados en la BD";
                    echo (json_encode($response));
                }else{
                    header('Content-Type: application/json');
                    $response["process"] = "DatosuserMedicamentos_NOTRegistered2";
                    $response["message"] = "Datos de medicamentos NO registrados en la BD";
                    echo (json_encode($response));
                }
            }
        }else{
            header('Content-Type: application/json');
            $response["process"] = "DatosuserMediamentosNew_NOTRegistered0";
            $response["message"] = "Datos de nuevo medicamentos no registrados en la BD";
            echo (json_encode($response));
        }
    }

    // http://localhost/ApisTesis/Medicamentos/registrationMedicamentos.php?Id_Usuario=7&Nombre_Medicamento=Simvastatina&TipoMediFK=1&Dosis_Indi_Medi=Una tableta cada noche&Razon_de_Toma=La tomo para ayudar a mantener mi colesterol bajo control&Fecha_Inicio_Med=2022/08/09&Fecha_Final_Med=&Fecha_Regis_Cro=2024/01/03  <<<<< LINK DEL API
 



    // http://localhost/ApisTesis/Afecciones/registrationAfeccion.php?Id_Usuario=4&Nombre_enf=Apendicitis&Id_TipoEnfer=2&Fecha_Inicio=2024/03/20&Fecha_Finalización=2024/05/03&DescripcionPropia_enf=Esta se me presentó con un dolor intenso en la parte inferior derecha del abdomen, acompañado de náuseas y fiebre, pero luego de la operación y la medicacón pertienente me curé&Fecha_Regis_Cro=2024/05/06  <<<<< LINK DEL API  
    