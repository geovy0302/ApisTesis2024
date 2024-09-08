<?php

class Conexion{
    private $hostname= "localhost"; 
   private $user = "admingeo";
   private $pass = "TesisFinal2024";
   private $db = "mymedirec";
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

