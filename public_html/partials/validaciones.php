<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

function limpiar_input($data) {
    return trim(stripslashes($data)); // sin htmlspecialchars
}


function validar_username($username) {
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9]{2,14}$/', $username)) {
        return 'El nombre de usuario debe tener entre 3 y 15 caracteres, comenzar con una letra y solo contener letras y números(No puede contener espacios en blanco).';
    }
    return '';
}

function validar_password($password) {
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d_-]{6,15}$/', $password)) {
        return 'La contraseña debe tener entre 6 y 15 caracteres, contener al menos una mayúscula, una minúscula y un número. Solo se permiten letras, números, guiones y guiones bajos.';
    }
    return '';
}

function validar_username_existente(PDO $pdo, string $username): string {
    $query = "SELECT COUNT(*) FROM Usuarios WHERE NomUsuario = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $username]);
    $existe = $stmt->fetchColumn();
    if ($existe > 0) {
        return 'El nombre de usuario ya está en uso.';
    }
    return '';
}

?>
