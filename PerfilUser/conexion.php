<?php

class Conexion{
    private $hostname= "192.185.45.80"; 
   private $user = "webbusi2_Geovany";
   private $pass = "\$Geovany24";
   private $db = "webbusi2_ehealth";
   private $charsates="utf8"; 

   function conectar(){

    try{
        $conexione = "mysql:host=".$this->hostname."; dbname=".$this->db."; charset=".$this->charsates;
        $options= [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false, 
        ]; 

        $pdo = new PDO($conexione, $this->user, $this->pass ,$options);
        return $pdo; 

    } catch(PDOException $e){

        echo "Error en la Conexión a la Base de datos ". $e->getMessage();
        exit; 
    }

   }
}
?>

