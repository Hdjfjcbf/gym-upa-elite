<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    $correo = trim($_POST['correo']);

    try {
        $query = "SELECT id FROM usuarios WHERE correo = :correo LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Aquí iría la lógica de enviar el correo con PHPMailer
            // Por ahora, simulamos el éxito:
            header("Location: ../app/views/recuperar.php?status=sent");
        } else {
            header("Location: ../app/views/recuperar.php?status=notfound");
        }
    } catch (PDOException $e) {
        header("Location: ../app/views/recuperar.php?status=error");
    }
}