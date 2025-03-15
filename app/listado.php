<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require 'app/class/DB.php';
use App\Crud\DB;

$conn = new DB();
$tabla = $_GET['tabla'] ?? 'productos'; // Tabla por defecto: productos
$registros = $conn->get_filas($tabla);

// Manejo de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    $mensaje = $conn->borrar_fila($tabla, $id);
    header("Location: listado.php?tabla=$tabla&mensaje=" . urlencode($mensaje));
    exit();
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de <?= ucfirst($tabla) ?></title>
</head>
<body>

<h1>Listado de <?= ucfirst($tabla) ?></h1>

<!-- Mostrar mensaje si existe -->
<?php if (!empty($_GET['mensaje'])): ?>
    <p style="color:red;"><?= htmlspecialchars($_GET['mensaje']) ?></p>
<?php endif; ?>

<!-- Selección de tabla -->
<form method="GET" action="listado.php">
    <label>Seleccionar tabla:</label>
    <select name="tabla" onchange="this.form.submit()">
        <option value="productos" <?= $tabla == 'productos' ? 'selected' : '' ?>>Productos</option>
        <option value="tiendas" <?= $tabla == 'tiendas' ? 'selected' : '' ?>>Tiendas</option>
        <option value="usuarios" <?= $tabla == 'usuarios' ? 'selected' : '' ?>>Usuarios</option>
        <option value="stock" <?= $tabla == 'stock' ? 'selected' : '' ?>>Stock</option>
    </select>
</form>

<table border="1">
    <tr>
        <?php if (!empty($registros)): ?>
            <?php foreach (array_keys($registros[0]) as $columna): ?>
                <th><?= ucfirst($columna) ?></th>
            <?php endforeach; ?>
            <th>Acciones</th>
        <?php else: ?>
            <th>No hay registros</th>
        <?php endif; ?>
    </tr>

    <?php foreach ($registros as $fila): ?>
        <tr>
            <?php foreach ($fila as $valor): ?>
                <td><?= htmlspecialchars($valor) ?></td>
            <?php endforeach; ?>
            <td>
                <form method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este registro?');">
                    <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                    <button type="submit" name="eliminar">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="sitio.php">Volver al panel</a>

</body>
</html>
