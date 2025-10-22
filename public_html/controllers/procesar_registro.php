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

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../partials/validaciones.php';

// Obtener y limpiar los datos del formulario
$username = isset($_POST['username']) ? limpiar_input($_POST['username']) : '';
$password = isset($_POST['password']) ? limpiar_input($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? limpiar_input($_POST['confirm_password']) : '';
$errores = [];

// Validaciones
if ($error_username = validar_username($username)) {
    $errores['username'] = $error_username;
}
if ($error_password = validar_password($password)) {
    $errores['password'] = $error_password;
}
if ($password !== $confirm_password) {
    $errores['confirm_password'] = 'Las contraseñas no coinciden.';
}

if (!empty($errores)) {
    $_SESSION['form_errors'] = $errores;
    $_SESSION['form_data'] = $_POST;
    header("Location: /daw/miweb_xampp/registro");
    exit();
}

// Insertar usuario
try {
    $pdo->beginTransaction();

    $query_user = "
        INSERT INTO Usuarios (NomUsuario, Clave, FRegistro, Estilo)
        VALUES (:username, :password, NOW(), 6)";
    $stmt_user = $pdo->prepare($query_user);
    $stmt_user->execute([
        ':username' => $username,
        ':password' => password_hash($password, PASSWORD_BCRYPT)
    ]);

    // Obtener ID del nuevo usuario
    $id_nuevo_usuario = $pdo->lastInsertId();

    $pdo->commit();
    session_regenerate_id(true);

    // Guardar datos de sesión
    $_SESSION['id_usuario'] = $id_nuevo_usuario;
    $_SESSION['username'] = $username;
    $_SESSION['autenticado'] = true;
    $_SESSION['primera_vez'] = true;
    $_SESSION['estilo'] = '/daw/miweb_xampp/css/style.css'; // o el estilo por defecto que prefieras

    header("Location: /daw/miweb_xampp/perfil-usuario");
    exit();
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Fallo en registro: " . $e->getMessage());
    $_SESSION['flashdata'] = '❌ No se pudo registrar al usuario. Inténtalo más tarde.';
    header("Location: /daw/miweb_xampp/registro");
    exit();
}

