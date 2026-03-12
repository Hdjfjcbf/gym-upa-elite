<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $pass = $_POST['password'];

    // Validar dominio en servidor
    if (!str_ends_with($correo, '@upatlacomulco.edu.mx')) {
        header("Location: ../app/views/registro.php?error=domain");
        exit();
    }

    try {
        // Verificar si el correo ya existe
        $check = $db->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $check->execute([$correo]);
        
        if ($check->rowCount() > 0) {
            header("Location: ../app/views/registro.php?error=exists");
            exit();
        }

        // Encriptar contraseña para que el login pueda usar password_verify
        $pass_hash = password_hash($pass, PASSWORD_BCRYPT);

        $query = "INSERT INTO usuarios (nombre, correo, password) VALUES (:nom, :corr, :pass)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nom', $nombre);
        $stmt->bindParam(':corr', $correo);
        $stmt->bindParam(':pass', $pass_hash);

        if ($stmt->execute()) {
            // Registro exitoso, mandarlo al login azul
            header("Location: ../app/views/login.php?success=registered");
        } else {
            header("Location: ../app/views/registro.php?error=1");
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}