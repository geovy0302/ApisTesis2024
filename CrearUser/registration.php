    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
    require_once("conexion.php"); //Inclusión requerida el archivo de conexión

    $db = new Conexion();
    $con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos
        
    $response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta
    //http://localhost/ApisTesis/CrearUser/registration.php?IdUserprincipal=0&IdUserTipoRe=0&Nombre_Com=José&Según_nombre=Antonio&Apellido_CU=Mendoza&SegundoAC_U=Castillero&Sexo= M&Dirección=Panamá, Bocas Del Toro, Isla Perico&Edad=24&Fecha_Nacimiento=2000/04/03&Cedula_DNI=1-963-789&Tipo_D_Sangre= AB (+)&E_mail=usuario@dominio.com&Password=12345&TipoUserFK=1&Peso=220 lb&Altura= 1.70 m
    //http://localhost/ApisTesis/CrearUser/registration.php?IdUserprincipal=3&IdUserTipoRe=1&Nombre_Com=María&Según_nombre=Antonia&Apellido_CU=Torrijo&SegundoAC_U=Ellington&Sexo=F&Dirección=Panamá, Los Santos&Edad=70&Fecha_Nacimiento=1954/06/04&Cedula_DNI=9-156-187&Tipo_D_Sangre=O%20(+)&E_mail=imac@gmail.com&Password=12345&TipoUserFK=2&Peso=%20200&Altura=1.63

    /* INSERT INTO `usuariospacientes` (`Id_Usuario`, `NombreC_U`, `Según_nombre`, `Apellido_CU`,`SegundoAC_U`, `Sexo`, `Dirección`, `Edad`, `Fecha_Nacimiento`, `Cedula_DNI`, `Tipo_D_Sangre`, `E_mail`, `Password`, `TipoUserFK`) VALUES (NULL, 'Geovanny ', '', 'Castillero','Monerp', 'M', 'Panamá,Chiriquí, David  \r\n', '20', '2004/02/03', '8-962-3421', 'O (+)', 'asxa@gmail.com\r\n', '12345', '1'); */
    $IdUserPrincipal = $_POST["IdUserprincipal"];//Esta variable debe ser extraída del usuario principal cuando vaya a regustar un usuario dependiente
    $IdUserTipoRe = $_POST["IdUserTipoRe"];//Esta variable debe será asignada desde la pantAlla para saber si esta ren registar nuevo uusario o en la pantalla de usuarios dependientes
    $Nombre_Com = $_POST["Nombre_Com"];
    $Según_nombre = $_POST["Según_nombre"];
    $Apellido_CU = $_POST["Apellido_CU"];
    $SegundoAC_U= $_POST["SegundoAC_U"];
    $Sexo= $_POST["Sexo"];
    $Dirección= $_POST["Dirección"];
    $Edad= $_POST["Edad"];
    $Fecha_Nacimiento= $_POST["Fecha_Nacimiento"];
    $Cedula_DNI= $_POST["Cedula_DNI"];
    $Tipo_D_Sangre= $_POST["Tipo_D_Sangre"];
    $E_mail= $_POST["E_mail"];
    $Password= $_POST["Password"];
    $TipoUserFK= $_POST["TipoUserFK"];
    $TipoUserFK = addslashes($TipoUserFK);// Escapar caracteres especiales
    $Peso= $_POST["Peso"];
    $Altura= $_POST["Altura"];
    $IdLastUser= "0"; //Variable que será llenada desde la consulta para saber el ID de útimo usauario agreagado 
    
    
    
    $querys = $con->prepare("SELECT * FROM  usuariospacientes  where  Cedula_DNI = ?");		
    $querys->execute(array($Cedula_DNI));

    if ($querys->rowCount() > 0) {
        $result = $querys->fetchAll(PDO::FETCH_ASSOC);
        if($result != null){
            header('Content-Type: application/json');
            $response["process"] = "Existing_User";
            $response["message"] = "Ya existe un usuario con este dato dato único(cédula) en nuestro sistema";
            echo (json_encode($response));
        }else{
            //Este If controla el registro para aquellos usuarios principales únicamente en la tabla de usuariospacientes y la de datosuserspaciente
            if($IdUserTipoRe == "0"){
                $querysOne = $con->prepare("INSERT INTO usuariospacientes (`NombreC_U`, `Según_nombre`, `Apellido_CU`,`SegundoAC_U`, `Sexo`, `Dirección`, `Edad`, `Fecha_Nacimiento`, `Cedula_DNI`, `Tipo_D_Sangre`, `E_mail`, `Password`, `TipoUserFK`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");		
                $querysOne->execute(array($Nombre_Com,$Según_nombre,$Apellido_CU,$SegundoAC_U,$Sexo,$Dirección,$Edad,$Fecha_Nacimiento,$Cedula_DNI,$Tipo_D_Sangre,$E_mail,$Password, $TipoUserFK));
                if ($querysOne) {
                    $queryAxuliar = $con->prepare("SELECT Id_Usuario FROM usuariospacientes ORDER BY Id_Usuario DESC LIMIT 1;");		
                    $queryAxuliar->execute();
                    if ($queryAxuliar->rowCount() > 0) {
                        $result = $queryAxuliar->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) {
                            $IdLastUser= $row['Id_Usuario'];  
                        }

                        $querysTwo = $con->prepare("INSERT INTO datosuserspaciente (`Id_UsuarioFK`, `Peso`, `Altura`) VALUES (?,?,?)");		
                        $querysTwo->execute(array($IdLastUser,$Peso,$Altura));
                        if ($querysTwo) {
                            header('Content-Type: application/json');
                            $response["process"] = "Principal_User_Registered";
                            $response["message"] = "Usuario Principal Registrado con Éxito en las dos tablas pertinentes";
                            echo (json_encode($response));
                        }
                    }else{
                        $response["process"] = "Error_Principal_User_Registration_A";
                        $response["message"] = "Erro al traer el último usuario para agregar el usuario principal a las 2 tablas";
                        header('Content-Type: application/json');
                        echo (json_encode($response)); 
                    }
                }else{
                    header('Content-Type: application/json');
                    $response["process"] = "Error_Principal_User_Registration_B";
                    $response["message"] = "Usuario No registrado para Tipo principal";
                    echo (json_encode($response));
                }
            }else{
                //Este sección controla el registro para aquellos usuarios dependientes en la tabla de usuariospacientes, en la de datosuserspaciente y en la de userdependencia
                $querysThree = $con->prepare("INSERT INTO usuariospacientes (`NombreC_U`, `Según_nombre`, `Apellido_CU`,`SegundoAC_U`, `Sexo`, `Dirección`, `Edad`, `Fecha_Nacimiento`, `Cedula_DNI`, `Tipo_D_Sangre`, `E_mail`, `Password`, `TipoUserFK`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");		
                $querysThree->execute(array($Nombre_Com,$Según_nombre,$Apellido_CU,$SegundoAC_U,$Sexo,$Dirección,$Edad,$Fecha_Nacimiento,$Cedula_DNI,$Tipo_D_Sangre,$E_mail,$Password, $TipoUserFK));
                if ($querysThree) {
                    $queryAxuliarDos = $con->prepare("SELECT Id_Usuario FROM usuariospacientes ORDER BY Id_Usuario DESC LIMIT 1;");		
                    $queryAxuliarDos->execute();
                    if ($queryAxuliarDos->rowCount() > 0) {
                        $result = $queryAxuliarDos->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) {
                            $IdLastUser= $row['Id_Usuario'];  
                        }

                        $querysFour = $con->prepare("INSERT INTO datosuserspaciente (`Id_UsuarioFK`, `Peso`, `Altura`) VALUES (?,?,?)");		
                        $querysFour->execute(array($IdLastUser,$Peso,$Altura));
                        if ($querysFour) {
                            $querysFive = $con->prepare("INSERT INTO userdependencia (`Id_UserPrinciFK`, `Id_UserDepenFK`) VALUES (?,?)");		
                            $querysFive->execute(array($IdUserPrincipal,$IdLastUser));
                            if ($querysFive) {
                                header('Content-Type: application/json');
                                $response["process"] = "Second_User_Registered";
                                $response["message"] = "Usuario Dependiente Registrado con Éxito";
                                echo (json_encode($response));
                            } 
                        }
                    }else{
                        $response["process"] = "Error_Principal_User_Registration_C";
                        $response["message"] = "Erro al traer el último usuario para agregar el usuario dependiente";
                        header('Content-Type: application/json');
                        echo (json_encode($response)); 
                    }
                    
                }else{
                    header('Content-Type: application/json');
                    $response["process"] = "Error_Principal_User_Registration_D";
                    $response["message"] = "Usuario Dependiente No registrado en las 3 tablas pertinentemente";
                    echo (json_encode($response));
                }
            }
        }
    }else {
        header('Content-Type: application/json');
        $response["process"] = "success 3";
        $response["message"] = "Error, no se pudo registrar dicho usuario";    
        echo(json_encode($result));
    }

    // http://localhost/ApisTesis/CrearUser/registration.php?Nombre_Com=Jesús Guerra&Sexo_User=M&Direccion_User=Panamá&Edad=21&Fecha_Nacimiento=2004-05-12&TipoSan_User=O+&cedula_User=09-581-367&Email_User=Jesus@gmail.com&Password_User=20205&Fecha_RegistroUser=2024-02-19 <<<<< LINK DEL API 
    ?>
	