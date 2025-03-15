<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Manejo de logout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<h1>Panel de Administración</h1>

<!-- Botones de navegación -->
<div>
    <form action="sitio.php" method="post">
        <div>
            <p>Conectado como <strong><?= $_SESSION['usuario'] ?></strong></p>
            <button class="btn btn-logout" type="submit" name="logout">Cerrar sesión</button>
        </div>
        <hr/>
        <a class="btn btn-create" href="productos.php">Productos</a>
        <a class="btn btn-edit" href="tiendas.php">Tiendas</a>
        <a class="btn btn-delete" href="usuarios.php">Usuarios</a>
        <a class="btn btn-create" href="stock.php">Stock</a>
    </form>
</div>

<!-- Contenido principal -->
<div id="content">
    <p>Selecciona una opción para gestionar los elementos de la tienda.</p>
</div>

</body>
</html>
