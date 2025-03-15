<?php
session_start();
require 'app/class/DB.php';

use App\Crud\DB;

$conn = new DB();
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    if (isset($_POST['registrar'])) {
        $resultado = $conn->registrar_usuario($usuario, $password);
        $mensaje = $resultado === true ? "Usuario registrado con éxito." : $resultado;
    } elseif (isset($_POST['login'])) {
        if ($conn->validar_usuario($usuario, $password)) {
            $_SESSION['usuario'] = $usuario;
            header("Location: sitio.php");
            exit();
        } else {
            $mensaje = "Credenciales incorrectas.";
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
</head>
<body>

<h2>Login</h2>
<form method="POST">
    <label>Usuario:</label>
    <input type="text" name="usuario" required>
    <br>
    <label>Contraseña:</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit" name="login">Iniciar Sesión</button>
</form>

<h2>Registro</h2>
<form method="POST">
    <label>Usuario:</label>
    <input type="text" name="usuario" required>
    <br>
    <label>Contraseña:</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit" name="registrar">Registrarse</button>
</form>

<?php if (!empty($mensaje)): ?>
    <p style="color:red;"><?= $mensaje ?></p>
<?php endif; ?>

</body>
</html>

