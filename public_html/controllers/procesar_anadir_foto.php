<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

session_start();
require_once __DIR__ . '/../config/db.php';

$errores = [];
$album = $_POST['album'] ?? null;
$IdUsuario = $_SESSION['id_usuario'] ?? null;

// Establecer carpeta por defecto
$carpeta_destino = 'default';

if (!empty($album) && $album !== 'default') {
    if (ctype_digit($album)) {
        $album = (int)$album;
        $stmt = $pdo->prepare("SELECT Titulo FROM Albumes WHERE IdAlbum = :id");
        $stmt->execute([':id' => $album]);
        $nombre_album = $stmt->fetchColumn();

        if ($nombre_album && is_dir(__DIR__ . '/../image/' . $nombre_album)) {
            $carpeta_destino = $nombre_album;
        } else {
            $album = null;
        }
    } else {
        $album = null;
    }
} else {
    $album = null;
}

if (!isset($_FILES['fichero']) || empty($_FILES['fichero']['name'][0])) {
    $errores[] = "Debes seleccionar al menos una imagen.";
}

if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    header("Location: /daw/miweb_xampp/anadir-foto");
    exit();
}

$stmt_count = $pdo->prepare("SELECT COUNT(*) FROM Fotos WHERE Usuario = :usuario");
$stmt_count->execute([':usuario' => $IdUsuario]);
$total_actual = (int)$stmt_count->fetchColumn();
$nuevas = count($_FILES['fichero']['name']);
$limite_maximo = 250;

if ($total_actual >= $limite_maximo) {
    $_SESSION['errores'] = ["Ya has alcanzado el límite de 100 fotos permitidas."];
    header("Location: /daw/miweb_xampp/anadir-foto");
    exit();
}

if (($total_actual + $nuevas) > $limite_maximo) {
    $_SESSION['errores'] = ["Solo puedes subir " . ($limite_maximo - $total_actual) . " fotos más."];
    header("Location: /daw/miweb_xampp/anadir-foto");
    exit();
}

try {
    $pdo->beginTransaction();

    foreach ($_FILES['fichero']['tmp_name'] as $index => $tmp_name) {
        if ($_FILES['fichero']['error'][$index] === UPLOAD_ERR_OK) {
            $original_name = $_FILES['fichero']['name'][$index];
$extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
$mime_type = mime_content_type($tmp_name);

// Validar MIME y extensión combinados
$extensiones_validas = [
    'image/jpeg' => ['jpg', 'jpeg'],
    'image/png'  => ['png'],
    'image/gif'  => ['gif']
];

if (!array_key_exists($mime_type, $extensiones_validas) || !in_array($extension, $extensiones_validas[$mime_type])) {
    $errores[] = "Tipo o extensión no permitida en: $original_name.";
    continue;
}

// Limitar tamaño máximo a 5 MB
if ($_FILES['fichero']['size'][$index] > 5 * 1024 * 1024) {
    $errores[] = "El archivo $original_name excede el límite de 5 MB.";
    continue;
}

// Renombrar extensión a estándar si se desea (opcional)
// $extension = $extensiones_validas[$mime_type][0];


            $nombre_archivo = uniqid('foto_', true) . '.' . $extension;
            $destino = __DIR__ . '/../image/' . $carpeta_destino . '/' . $nombre_archivo;

            if (move_uploaded_file($tmp_name, $destino)) {
                $stmt = $pdo->prepare("INSERT INTO Fotos (Album, Fichero, Usuario) VALUES (:album, :fichero, :usuario)");
                $stmt->execute([
                    ':album' => $album,
                    ':fichero' => $nombre_archivo,
                    ':usuario' => $IdUsuario
                ]);
            } else {
                $errores[] = "No se pudo guardar $original_name.";
            }
        }
    }

    $pdo->commit();

    if (!empty($errores)) {
        $_SESSION['errores'] = $errores;
    }

    header("Location: /daw/miweb_xampp/");

    exit();
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Error al subir las fotos: " . $e->getMessage());
}