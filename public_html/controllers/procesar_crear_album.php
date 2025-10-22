<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['flashdata'] = '⚠️ Error de seguridad en el formulario. Recarga la página e inténtalo de nuevo.';
    header("Location: /daw/miweb_xampp/registro");
    exit();
}
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] != 36) {
    header("HTTP/1.0 403 Forbidden");
    exit("Solo el administrador puede crear álbumes.");
}

if (!isset($_SESSION['username'])) {
    header("Location: /daw/miweb_xampp/inicio-sesion?mensaje=debe_iniciar_sesion");
    exit();
}

require_once __DIR__ . '/../config/db.php';

$titulo = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

if (empty($titulo)) {
    die("Por favor, completa el campo titulo.");
}

// Manejar la subida de portada
$portada = 'default-album.jpg';

if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['portada']['tmp_name'];
    $ext = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
    $nombre_final = uniqid("album_") . '.' . $ext;

    $carpeta_destino = __DIR__ . '/../image/albums/';
    $ruta = $carpeta_destino . $nombre_final;

    if (move_uploaded_file($tmp, $ruta)) {
        $portada = $nombre_final;
    } else {
        die("No se pudo guardar la imagen en $ruta");
    }
}

try {
    $query_insert_album = "
        INSERT INTO Albumes (Titulo, Descripcion, Portada)
        VALUES (:titulo, :descripcion, :portada)
    ";
    $stmt = $pdo->prepare($query_insert_album);
    $stmt->execute([
        ':titulo' => $titulo,
        ':descripcion' => $descripcion,
        ':portada' => $portada
    ]);

    // Crear carpeta para el álbum usando el Titulo
    $titulo_carpeta = preg_replace('/[^a-zA-Z0-9_-]/', '_', $titulo);
    $ruta_carpeta = __DIR__ . '/../image/' . $titulo_carpeta;
    if (!is_dir($ruta_carpeta)) {
        mkdir($ruta_carpeta, 0775, true);
    }

    $_SESSION['album_creado'] = [
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'portada' => $portada
    ];

    header("Location: /daw/miweb_xampp/respuesta-creacion-album");
    exit();
} catch (PDOException $e) {
    die("Error al crear el álbum: " . $e->getMessage());
}
