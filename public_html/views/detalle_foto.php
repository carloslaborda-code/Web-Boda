<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    $_SESSION['flashdata'] = 'Debes estar registrado o iniciar sesión para ver los detalles de una foto.';
    header("Location: /inicio-sesion");
    exit();
}

require_once __DIR__ . '/../config/db.php';

$foto_id = isset($_GET['id']) && ctype_digit($_GET['id']) ? intval($_GET['id']) : 0;
if ($foto_id === 0) {
    die("ID de la foto no válido.");
}

function obtenerFoto($pdo, $id) {
    $query = "SELECT f.Fichero, f.Usuario, f.Album, a.Titulo AS AlbumTitulo, a.IdAlbum AS AlbumId
              FROM Fotos f
              LEFT JOIN Albumes a ON f.Album = a.IdAlbum
              WHERE f.IdFoto = :id";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$foto = obtenerFoto($pdo, $foto_id);
if (!$foto) {
    header("HTTP/1.0 404 Not Found");
    echo "Foto no encontrada.";
    exit();
}

$modo = $_GET['modo'] ?? 'global';
$foto_anterior_id = null;
$foto_siguiente_id = null;

try {
    if ($modo === 'album' && isset($foto['AlbumId'])) {
        $stmtAnt = $pdo->prepare("SELECT IdFoto FROM Fotos WHERE IdFoto < :id AND Album = :albumId ORDER BY IdFoto DESC LIMIT 1");
        $stmtSig = $pdo->prepare("SELECT IdFoto FROM Fotos WHERE IdFoto > :id AND Album = :albumId ORDER BY IdFoto ASC LIMIT 1");
        $stmtAnt->execute([':id' => $foto_id, ':albumId' => $foto['AlbumId']]);
        $stmtSig->execute([':id' => $foto_id, ':albumId' => $foto['AlbumId']]);
    } else {
        $stmtAnt = $pdo->prepare("SELECT IdFoto FROM Fotos WHERE IdFoto > :id ORDER BY IdFoto ASC LIMIT 1");
        $stmtSig = $pdo->prepare("SELECT IdFoto FROM Fotos WHERE IdFoto < :id ORDER BY IdFoto DESC LIMIT 1");
        $stmtAnt->execute([':id' => $foto_id]);
        $stmtSig->execute([':id' => $foto_id]);
    }

    $foto_anterior_id = $stmtAnt->fetchColumn();
    $foto_siguiente_id = $stmtSig->fetchColumn();
} catch (PDOException $e) {
    die("Error al obtener navegación: " . $e->getMessage());
}

$title = 'Detalle de Foto';
include __DIR__ . '/../templates/header.php';
?>

<main class="detalle-foto-main">
    <div class="detalle-foto-cabecera" id="info-album">
        <?php if (!empty($foto['AlbumId']) && $foto['AlbumTitulo'] !== 'default'): ?>
            <a href="/ver-album-privada?id=<?= htmlspecialchars($foto['AlbumId']) ?>" class="boton-ver-album">
                <i class="fas fa-images"></i> Ver álbum: <?= htmlspecialchars($foto['AlbumTitulo']) ?>
            </a>
        <?php elseif ($foto['AlbumTitulo'] === 'default' || empty($foto['AlbumId'])): ?>
            <p class="sin-album">Esta foto no pertenece a ningún álbum.</p>
        <?php endif; ?>
    </div>

    <div class="detalle-foto-contenedor lateral-nav">
        <?php if ($foto_anterior_id): ?>
           <button class="boton-navegacion lateral anterior"
 data-id="<?= htmlspecialchars($foto_anterior_id, ENT_QUOTES, 'UTF-8') ?>"
 data-modo="<?= htmlspecialchars($modo, ENT_QUOTES, 'UTF-8') ?>"
 data-album="<?= htmlspecialchars($foto['AlbumId'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
Siguiente ➡
</button>

        <?php endif; ?>

        <div class="detalle-foto-recuadro fade-container" id="foto-container">
            <img id="foto-detalle" src="/image/<?= htmlspecialchars($foto['AlbumTitulo'] ?? 'default') ?>/<?= htmlspecialchars($foto['Fichero']) ?>" 
                 alt="Foto subida" class="foto-detalle-grande">
        </div>

        <?php if ($foto_siguiente_id): ?>
            <button class="boton-navegacion lateral siguiente"
 data-id="<?= htmlspecialchars($foto_siguiente_id, ENT_QUOTES, 'UTF-8') ?>"
 data-modo="<?= htmlspecialchars($modo, ENT_QUOTES, 'UTF-8') ?>"
 data-album="<?= htmlspecialchars($foto['AlbumId'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
⬅ Anterior
</button>

        <?php endif; ?>
    </div>
</main>

<style>
.fade-out {
    animation: fadeOut 0.2s ease forwards;
}
.fade-in {
    animation: fadeIn 0.2s ease forwards;
}
@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>


<script>
function asignarEventosNavegacion() {
    document.querySelectorAll('.boton-navegacion').forEach(boton => {
        boton.addEventListener('click', function () {
            const id = this.dataset.id;
            const modo = this.dataset.modo;
            const album = this.dataset.album;

            const contenedor = document.getElementById('foto-container');
            contenedor.classList.remove('fade-in');
            contenedor.classList.add('fade-out');

            setTimeout(() => {
                fetch(`detalle-foto?id=${id}&modo=${modo}${modo === 'album' ? `&album=${album}` : ''}`)
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const nuevaImagen = doc.querySelector('#foto-detalle');
                        document.getElementById('foto-detalle').src = nuevaImagen.src;

                        const nuevaCabecera = doc.querySelector('#info-album');
                        document.getElementById('info-album').innerHTML = nuevaCabecera.innerHTML;

                        const nuevoAnterior = doc.querySelector('.boton-navegacion.anterior');
                        const nuevoSiguiente = doc.querySelector('.boton-navegacion.siguiente');

                        const contenedorBtns = document.querySelector('.detalle-foto-contenedor');

                        const botonAntActual = contenedorBtns.querySelector('.boton-navegacion.anterior');
                        if (botonAntActual) botonAntActual.remove();
                        if (nuevoAnterior) contenedorBtns.insertBefore(nuevoAnterior, document.getElementById('foto-container'));

                        const botonSigActual = contenedorBtns.querySelector('.boton-navegacion.siguiente');
                        if (botonSigActual) botonSigActual.remove();
                        if (nuevoSiguiente) contenedorBtns.appendChild(nuevoSiguiente);

                        contenedor.classList.remove('fade-out');
                        contenedor.classList.add('fade-in');

                        asignarEventosNavegacion();
                    });
            }, 200);
        });
    });
}

asignarEventosNavegacion();
</script>

<?php 
include __DIR__ . '/../templates/footer.php';
?>
