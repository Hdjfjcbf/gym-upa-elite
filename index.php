<?php
session_start();

// Si NO hay sesión, mandamos al Login que está en views
if (!isset($_SESSION['usuario_id'])) {
    header("Location: app/views/login.php");
    exit();
}

// Si SI hay sesión, mostramos el Dashboard
// Aquí pegué el código de la imagen que me mostraste
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GYM UPA | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { background: #000; color: #fff; font-family: 'Inter', sans-serif; margin: 0; }
        .sidebar { width: 200px; position: fixed; height: 100%; border-right: 1px solid #333; padding: 20px; }
        .main-content { margin-left: 240px; padding: 40px; }
        .card-grid { display: flex; gap: 20px; margin-top: 30px; }
        .card { background: #111; border: 1px solid #333; padding: 25px; border-radius: 15px; flex: 1; }
        .btn-red { background: #ff0000; color: #fff; padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; }
        .user-tag { position: absolute; top: 20px; right: 20px; background: #111; padding: 5px 15px; border-radius: 20px; border: 1px solid #333; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>GYM UPA</h2>
        <p>Dashboard</p>
        <p>Mis Rutinas</p>
        <p>Dieta</p>
        <p>Asistencia</p>
        <p>Perfil</p>
    </div>

    <div class="user-tag">
        <?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?> 👤
    </div>

    <div class="main-content">
        <p style="color: red;">BIENVENIDO DE VUELTA</p>
        <h1>Hola, <?php echo $_SESSION['usuario_nombre'] ?? 'Camaleón'; ?></h1>

        <div class="card-grid">
            <div class="card">
                <h3>Entrenamiento</h3>
                <p>Accede a tus rutinas personalizadas.</p>
                <button class="btn-red">VER RUTINA</button>
            </div>
            <div class="card">
                <h3>Biblioteca</h3>
                <p>Explora la base de datos de ejercicios.</p>
                <button class="btn-red">EXPLORAR</button>
            </div>
            <div class="card">
                <h3>Estadísticas</h3>
                <p>Visualiza tus cambios físicos.</p>
                <button class="btn-red">VER GRÁFICAS</button>
            </div>
        </div>
        <br>
        <a href="api/logout.php" style="color: #444; text-decoration: none; font-size: 0.8rem;">Cerrar Sesión</a>
    </div>
</body>
</html>