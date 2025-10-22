<?php

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Eliminar cookies solo si existían para recordar al usuario
if (isset($_COOKIE['usuario']) && isset($_COOKIE['password'])) {
    setcookie('usuario', '', time() - 3600, "/");
    setcookie('password', '', time() - 3600, "/");
    setcookie('ultima_visita', '', time() - 3600, "/");
}

// Cerrar sesión
session_start();
session_unset();
session_destroy();

header("Location: /daw/miweb_xampp/"); // Redirigir a la página principal
exit();
?>
