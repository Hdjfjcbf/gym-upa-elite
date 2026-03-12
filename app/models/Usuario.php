<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Aquí crearemos la función para registrar usuarios más adelante
    public function registrar() {
        // Por ahora lo dejamos listo para el siguiente paso
        return true;
    }
}