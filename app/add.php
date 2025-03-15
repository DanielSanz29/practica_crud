<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

require 'app/class/DB.php';
use App\Crud\DB;

$conn = new DB();
$tabla = $_GET['tabla'] ?? 'productos'; // Tabla por defecto
$mensaje = '';

// Obtener los campos de la tabla seleccionada
$campos = $conn->get_campos($tabla);

// Procesar agregar registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $datos = [];
    foreach ($campos as $campo) {
        if ($campo !== "id") { // No insertar el ID si es autoincremental
            $datos[$campo] = $_POST[$campo] ?? '';
        }
    }

    if ($conn->add_fila($tabla, $datos)) {
        $mensaje = "Registro agregado con éxito.";
    } else {
        $mensaje = "Error al agregar el registro.";
    }
}

// Procesar eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    if ($conn->borrar_fila($tabla, $id)) {
        $mensaje = "Registro eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar el registro.";
    }
}

// Obtener registros actualizados
$registros = $conn->get_filas($tabla);
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de <?= ucfirst($tabla) ?></title>
    <script>
        function confirmarEliminacion(id) {
            if (confirm("¿Seguro que quieres eliminar este registro?")) {
                document.getElementById("deleteForm-" + id).submit();
            }
        }
    </script>
</head>
<body>

<h1>Gestión de <?= ucfirst($tabla) ?></h1>

<!-- Mostrar mensaje -->
<?php if (!empty($mensaje)): ?>
    <p style="color:red;"><?= htmlspecialchars($mensaje) ?></p>
<?php endif; ?>

<!-- Formulario de agregar -->
<form method="POST">
    <?php foreach ($campos as $campo): ?>
        <?php if ($campo !== "id"): ?>
            <label><?= ucfirst($campo) ?>:</label>
            <input type="text" name="<?= $campo ?>" required>
            <br>
        <?php endif; ?>
    <?php endforeach; ?>
    <button type="submit" name="agregar">Añadir Registro</button>
</form>

<!-- Tabla de registros -->
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
                <form id="deleteForm-<?= $fila['id'] ?>" method="POST">
                    <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                    <button type="button" onclick="confirmarEliminacion(<?= $fila['id'] ?>)">Eliminar</button>
                    <input type="hidden" name="eliminar" value="1">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="sitio.php">Volver al panel</a>

</body>
</html>
