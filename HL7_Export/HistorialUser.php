<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ E_DEPRECATED); //Estas Líneas son para el tema del manejo de errores en la pantalla o más bien consola por medio de informes y demás 
require_once("conexion.php"); //Inclusión requerida el archivo de conexión

$db = new Conexion();
$con = $db->conectar(); //Estas líneas son para establecer la conexión por medio de la instanciación a ese archivo de conexión con los permisos



$Id_Usuario= $_GET['Id_Usuario'];

$response = array();//se crea un arreglo vacío para almacenar los resultados de la consulta
$datosFinales = array();

//Datos básicos del User----------------------------------------------------------------------------------------------------------------------------
$querysDatosUser = $con->prepare("SELECT  *  from  usuariospacientes  where Id_Usuario = ? ");
$querysDatosUser->execute(array($Id_Usuario));
   

if ($querysDatosUser) {
    $result = $querysDatosUser->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Nombre_User"] = $row['NombreC_U'] . " " . $row['Según_nombre']. " " . $row['Apellido_CU']. " " . $row['SegundoAC_U'];
        $stuff["Cedula_DNI"] = $row['Cedula_DNI'];
        $stuff["Fecha_Nacimiento"] = $row['Fecha_Nacimiento'];
        $stuff["Edad"] = $row['Edad'];
        $stuff["Sexo"] = $row['Sexo'];
        $stuff["Tipo_De_Sangre"] = $row['Tipo_D_Sangre'];
        $stuff["E_mail"] = $row['E_mail'];
        array_push($response, $stuff);
    }
    $datosFinales["Datos_Usuario_Paciente"]= $response;
} 


//Datos básicos de observaciones de Usuario--------------------------------------------------------------------------------------------------------------------------------------------------------------
$response = array();
$querysSignoBasicos = $con->prepare("SELECT b.Peso, b.Altura  FROM usuariospacientes a  INNER JOIN datosuserspaciente b ON (b.Id_UsuarioFK= a.Id_Usuario) && (a.Id_Usuario =?);");
$querysSignoBasicos->execute(array($Id_Usuario)); 
if ($querysSignoBasicos) {
    $result = $querysSignoBasicos->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Peso"] = $row['Peso'];
        $stuff["Altura"] = $row['Altura'];
        array_push($response, $stuff);
    }
    $datosFinales["Observaciones"]= $response;
} 


//Datos básicos de alergías de Usuario--------------------------------------------------------------------------------------------------------------------------------------------------------------
$response = array();
$querysAlergia = $con->prepare("SELECT d.Id_Usuario, a.Id_Alergia,a.Nombre_Alergia,c.descripcion_TipoAler,b.DecripcionPropia_Alerg,b.Fecha_Regis_Aler FROM alergias a INNER JOIN usuaalergia b ON  (b.Id_AlergiaFK = a.Id_Alergia)  INNER JOIN tipoalergia c ON (c.Id_TipoAler = a.TipoAlerFK) INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (d.Id_Usuario = ?);");
$querysAlergia->execute(array($Id_Usuario)); 
  
if ($querysAlergia->rowCount() > 0) {
    $result = $querysAlergia->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Nombre_Alergia"] = $row['Nombre_Alergia'];
        $stuff["descripcion_TipoAler"] = $row['descripcion_TipoAler'];
        $stuff["DecripcionPropia_Alerg"] = $row['DecripcionPropia_Alerg'];
        $stuff["Fecha_Regis_Aler"] = $row['Fecha_Regis_Aler'];
        array_push($response, $stuff);
    }  

    $datosFinales["Alergias"]= $response;
}
 

//Datos básicos de Afecciones de Usuario--------------------------------------------------------------------------------------------------------------------------------------------------------------
$response = array();
$querysAfecciones = $con->prepare("SELECT d.Id_Usuario, a.Id_Enfermedad,b.Id_UserEnfermedad ,a.Nombre_enf,c.descripcion_TipEnfer,b.Fecha_Inicio,b.Fecha_Finalización,b.DescripcionPropia_enf,b.Fecha_Regis_Cro
FROM enfermedades a INNER JOIN usuaenfermedad b ON  (b.Id_EnfermedadFK = a.Id_Enfermedad) INNER JOIN tipoenfermedad c ON (c.Id_TipoEnfer = a.TipoEnfermedadFK) && (b.Id_EnfermedadFK = a.Id_Enfermedad) INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (d.Id_Usuario = ?);");
$querysAfecciones->execute(array($Id_Usuario)); 
  
if ($querysAfecciones->rowCount() > 0) {
    $result = $querysAfecciones->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Nombre_enf"] = $row['Nombre_enf'];
        $stuff["descripcion_TipEnfer"] = $row['descripcion_TipEnfer'];
        $stuff["Fecha_Inicio"] = $row['Fecha_Inicio'];
        $stuff["Fecha_Finalización"] = $row['Fecha_Finalización'];
        $stuff["DescripcionPropia_enf"] = $row['DescripcionPropia_enf'];
        $stuff["Fecha_Regis_Cro"] = $row['Fecha_Regis_Cro'];
        array_push($response, $stuff);
    }  

    $datosFinales["Afecciones"]= $response;
}


//Datos básicos de medicamentos de Usuario--------------------------------------------------------------------------------------------------------------------------------------------------------------
$response = array();
$querysMedicamentos = $con->prepare("SELECT d.Id_Usuario,b.Id_MedicaToma, a.Id_Medicamento,a.Nombre_Medicamento,c.descripcion_TipMed,b.Dosis_Indi_Medi,b.Razon_de_Toma,b.Fecha_Inicio_Med,b.Fecha_Final_Med,b.Fecha_Regis_Medicamento 
FROM medicamentos a INNER JOIN usuamedica b ON  (b.Id_MedicamentoFK = a.Id_Medicamento)  INNER JOIN tipo_de_medicamento c ON (c.Id_TipoMedicamento = a.TipoMediFK) INNER JOIN usuariospacientes d ON (d.Id_Usuario = b.Id_UsuarioFK) && (d.Id_Usuario = ?);");
$querysMedicamentos->execute(array($Id_Usuario));  
  
if ($querysMedicamentos->rowCount() > 0) {
    $result = $querysMedicamentos->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Nombre_Medicamento"] = $row['Nombre_Medicamento'];
        $stuff["descripcion_TipMed"] = $row['descripcion_TipMed'];
        $stuff["Dosis_Indi_Medi"] = $row['Dosis_Indi_Medi'];
        $stuff["Razon_de_Toma"] = $row['Razon_de_Toma'];
        $stuff["Fecha_Inicio"] = $row['Fecha_Inicio_Med'];
        $stuff["Fecha_Finalización"] = $row['Fecha_Final_Med'];
        $stuff["DescripcionPropia_enf"] = $row['Fecha_Regis_Medicamento'];
        array_push($response, $stuff);
    }  

    $datosFinales["Medicamentos"]= $response;
}

//Datos básicos de hospitalizaviones de Usuario--------------------------------------------------------------------------------------------------------------------------------------------------------------
$response = array();
$querysHospitalizaciones = $con->prepare("SELECT e.Id_Usuario,a.Id_Hospitalizacion,b.Nombre_CentroHos, b.ProvinciaUbicacion,c.Nombre_Medico,d.Nombre_Especialidad,a.Motivo_Hos,a.Descripcion_Hosp,a.FechaIngresoHsp,a.FechaISalidaHsp,a.Fecha_RegistrHospit
FROM  hospitalizaciones a INNER JOIN centros_de_atencio b ON  (b.Id_CentroHosp = a.Id_CentroAtencionFK)  INNER JOIN lista_de_medicos c ON (c.Id_medico = a.Id_Medico_FK) INNER JOIN especialidades_medi d ON (d.Id_Especialidad = c.Id_EspecialidadFK) INNER JOIN usuariospacientes e ON (e.Id_Usuario = a.Id_UsuarioFK) && (e.Id_Usuario = ?);");
$querysHospitalizaciones->execute(array($Id_Usuario)); 
  
if ($querysHospitalizaciones->rowCount() > 0) {
    $result = $querysHospitalizaciones->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($result as $row) {
        $stuff = array();
        $stuff["Nombre_CentroHos"] = $row['Nombre_CentroHos'];
        $stuff["ProvinciaUbicacion"] = $row['ProvinciaUbicacion'];
        $stuff["Nombre_Medico"] = $row['Nombre_Medico'];
        $stuff["Nombre_Especialidad"] = $row['Nombre_Especialidad'];
        $stuff["Motivo_Hos"] = $row['Motivo_Hos'];
        $stuff["Descripcion_Hosp"] = $row['Descripcion_Hosp'];
        $stuff["FechaIngresoHsp"] = $row['FechaIngresoHsp'];
        $stuff["FechaISalidaHsp"] = $row['FechaISalidaHsp'];
        $stuff["Fecha_RegistrHospit"] = $row['Fecha_RegistrHospit'];
        array_push($response, $stuff);
    }  

    $datosFinales["Ingresos_hospitalarios"]= $response;
    header('Content-Type: application/json');
    echo (json_encode($datosFinales));
}





// http://localhost/ApisTesis/HL7_Export/HistorialUser.php?Id_Usuario=4









?>