<?php


session_start();
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['flashdata'] = '⚠️ Error de seguridad en el formulario. Recarga la página e inténtalo de nuevo.';
    header("Location: /daw/miweb_xampp/inicio-sesion");
    exit();
}

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

require_once __DIR__ . '/../models/UserModel.php';

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
    $_SESSION['flashdata'] = 'El nombre de usuario tiene un formato inválido.';
    header("Location: /daw/miweb_xampp/inicio-sesion");
    exit();
}

if (empty($username) || empty($password)) {
    $_SESSION['flashdata'] = 'Por favor, rellena todos los campos.';
    header("Location: /daw/miweb_xampp/inicio-sesion");
    exit();
}

$userModel = new UserModel();
$usuario = $userModel->verificarCredenciales($username, $password);

if ($usuario) {
    session_regenerate_id(true);

    $_SESSION['username'] = $usuario['username'];
    $_SESSION['autenticado'] = true;
    $_SESSION['primera_vez'] = true;
    $_SESSION['id_usuario'] = $usuario['IdUsuario'];


    // Validar si se obtiene correctamente el estilo
    if (isset($usuario['estilo'])) {
        $_SESSION['estilo'] = $usuario['estilo'];
    } else {
        $_SESSION['estilo'] = '/daw/miweb_xampp/css/style.css'; // Estilo por defecto
    }

    // Opcional: Configurar cookies si el usuario selecciona "Recordarme"
    if (isset($_POST['recordarme'])) {
      $cookie_params = [
    'expires' => time() + (90 * 24 * 60 * 60),
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
];
setcookie('usuario', $usuario['username'], $cookie_params);
setcookie('ultima_visita', date('Y-m-d H:i:s'), $cookie_params);
setcookie('estilo', $_SESSION['estilo'], $cookie_params);

    }

    header("Location: /daw/miweb_xampp/perfil-usuario");
    exit();
} else {
    $_SESSION['flashdata'] = 'Credenciales incorrectas. Intente de nuevo.';
    header("Location: /daw/miweb_xampp/inicio-sesion");
    exit();
}

