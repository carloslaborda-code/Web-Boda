<?php
session_start();
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['flashdata'] = '⚠️ Error de seguridad en el formulario. Recarga la página e inténtalo de nuevo.';
    header("Location: /daw/miweb_xampp/registro");
    exit();
}
if (!defined('FROM_INDEX')) { 
    header("HTTP/1.0 403 Forbidden"); 
    exit("Acceso directo no permitido."); 
}
if (!isset($_SESSION['username'])) { 
    header("Location: /daw/miweb_xampp/inicio-sesion"); 
    exit(); 
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../partials/validaciones.php';

$username = $_SESSION['username'];
$password = isset($_POST['password']) ? limpiar_input($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? limpiar_input($_POST['confirm_password']) : '';
$current_password = isset($_POST['current_password']) ? limpiar_input($_POST['current_password']) : '';

$errores = [];

// Verificar contraseña actual
if (empty($current_password)) {
    $errores['current_password'] = 'Debes introducir tu contraseña actual para confirmar los cambios.';
} else {
    try {
        $query = "SELECT Clave FROM Usuarios WHERE NomUsuario = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || !password_verify($current_password, $usuario['Clave'])) {
            $errores['current_password'] = 'La contraseña actual no es correcta.';
        }        
    } catch (PDOException $e) {
        die("Error al verificar la contraseña: " . $e->getMessage());
    }
}

// Validación de nueva contraseña
if (!empty($password)) {
    if ($error_password = validar_password($password)) {
        $errores['password'] = $error_password;
    }
    if ($password !== $confirm_password) {
        $errores['confirm_password'] = 'Las contraseñas no coinciden.';
    }
}

// Si hay errores, redirigir con errores y datos antiguos
if (!empty($errores)) {
    $_SESSION['form_errors'] = $errores;
    $_SESSION['form_data'] = $_POST;
    header("Location: /daw/miweb_xampp/mis-datos");
    exit();
}

// Actualizar contraseña
try {
    if (!empty($password)) {
        $query = "UPDATE Usuarios SET Clave = :password WHERE NomUsuario = :username";
        $params = [
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':username' => $username
        ];
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
    }

    $_SESSION['flashdata'] = '✅ Contraseña actualizada con éxito.';
    header("Location: /daw/miweb_xampp/mis-datos");
    exit();
} catch (PDOException $e) {
    die("Error al actualizar los datos: " . $e->getMessage());
}
?>
