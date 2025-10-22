<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Conexión a la base de datos y código común
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../partials/album_datos_comun.php';

// Validar ID del álbum
$album_id = isset($_GET['id']) && ctype_digit($_GET['id']) ? intval($_GET['id']) : 0;
if ($album_id === 0) {
    die("ID de álbum no válido.");
}

// Obtener datos del álbum
try {
    $datos_album = obtenerDatosAlbum($pdo, $album_id);
} catch (Exception $e) {
    die($e->getMessage());
}
// Navegación entre álbumes
$album_anterior_id = null;
$album_siguiente_id = null;

try {
    $stmtAnt = $pdo->prepare("SELECT IdAlbum FROM Albumes WHERE IdAlbum < :id ORDER BY IdAlbum DESC LIMIT 1");
    $stmtAnt->execute([':id' => $album_id]);
    $album_anterior_id = $stmtAnt->fetchColumn();

    $stmtSig = $pdo->prepare("SELECT IdAlbum FROM Albumes WHERE IdAlbum > :id ORDER BY IdAlbum ASC LIMIT 1");
    $stmtSig->execute([':id' => $album_id]);
    $album_siguiente_id = $stmtSig->fetchColumn();
} catch (PDOException $e) {
    die("Error al obtener la navegación entre álbumes: " . $e->getMessage());
}

$title = 'Ver Álbum (Pública Restringida)';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section>
        <h2>Álbum: <?php echo htmlspecialchars($datos_album['titulo']); ?></h2>
        <p><strong>Número total de fotos:</strong> <?php echo $datos_album['numero_fotos']; ?></p>
    </section>

    <section>
        <h3>Fotos del Álbum</h3>
        <div class="fotos-lista">
            <?php if (!empty($datos_album['fotos'])): ?>
                <?php foreach ($datos_album['fotos'] as $foto): ?>
                    <div class="foto-item">
                        <a href="/daw/miweb_xampp/detalle-foto?id=<?php echo htmlspecialchars($foto['IdFoto']); ?>">
                            <img src="/daw/miweb_xampp/image/<?php echo htmlspecialchars($foto['Fichero']); ?>" 
                                alt="<?php echo htmlspecialchars($foto['FotoTitulo']); ?>">
                        </a>
                        <p><strong>Título:</strong> <?php echo htmlspecialchars($foto['FotoTitulo']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay fotos en este álbum.</p>
            <?php endif; ?>
        </div>
    </section>
</main>



<?php 
include __DIR__ . '/../templates/footer.php';
?>
