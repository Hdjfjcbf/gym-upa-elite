<?php
session_start();
require_once 'config.php'; // Asegúrate de que este archivo tenga la conexión a TiDB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    $correo = trim($_POST['correo']);
    $pass = $_POST['password'];

    try {
        // Buscamos si el correo existe
        $query = "SELECT * FROM usuarios WHERE correo = :correo LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si el correo existe, verificamos la contraseña
        if ($usuario && password_verify($pass, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            header("Location: ../index.php"); // Pa' dentro si todo está bien
            exit();
        } else {
            // SI NO EXISTE EL CORREO O LA PASS ESTÁ MAL, REBOTA AQUÍ:
            header("Location: ../app/views/login.php?error=1");
            exit();
        }

    } catch (PDOException $e) {
        // Si falla la base de datos, también mandamos error
        header("Location: ../app/views/login.php?error=1");
        exit();
    }
}