<?php
// 1. URL base actualizada para tu nuevo dominio en InfinityFree
define('URL_BASE', 'http://gym-upa-elite.rf.gd/');

// 2. Datos de la BD (TiDB Cloud) - Estos se quedan igual
define('DB_HOST', 'gateway01.us-east-1.prod.aws.tidbcloud.com');
define('DB_NAME', 'gym_upa');
define('DB_USER', '3SieYje7GssaPHQ.root');
define('DB_PASS', 'Zw2ydUncgV1ECIiX'); 
define('DB_PORT', '4000');

// 3. Credenciales de Correo
define('MAIL_USER', 'gymupacamaleones@gmail.com');
define('MAIL_PASS', 'itho vlol emqv gjnh'); 

// 4. Clase de Conexión PDO con SSL
class Database {
    public $conn;
    public function getConnection() {
        $this->conn = null;
        try {
            // El __DIR__ asegura que busque el cacert.pem en la misma carpeta api/
            $options = array(
                PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/cacert.pem',                
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);

        } catch(PDOException $e) {
            // En producción es mejor no mostrar el error completo, pero para pruebas déjalo así
            die("Error de conexión: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>