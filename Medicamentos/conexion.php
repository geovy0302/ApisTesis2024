<?php
class Conexion {
    private $hostname = "192.185.45.80";
    private $user = "webbusi2_Geovany";
    private $pass = "\$Geovany24";
    private $db = "webbusi2_ehealth";
    private $charset = "utf8";

    function conectar() {
        try {
            $dsn = "mysql:host=" . $this->hostname . ";dbname=" . $this->db . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $pdo = new PDO($dsn, $this->user, $this->pass, $options);
            return $pdo;

        } catch (PDOException $e) {
            // En lugar de usar echo, podemos registrar el error en un log
            error_log("Error en la Conexión a la Base de datos: " . $e->getMessage(), 0);
            return null; // Retorna null si ocurre un error de conexión
        }
    }
}


