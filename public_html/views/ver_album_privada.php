<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: /daw/miweb_xampp/inicio-sesion");
    exit();
}

require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/../partials/album_datos_comun.php';

$album_id = isset($_GET['id']) && ctype_digit($_GET['id']) ? intval($_GET['id']) : 0;
if ($album_id === 0) {
    die("ID de álbum no válido.");
}

try {
    $datos_album = obtenerDatosAlbum($pdo, $album_id);
} catch (Exception $e) {
    die($e->getMessage());
}

// PAGINACIÓN
$imagenes_por_pagina = 6;
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina_actual - 1) * $imagenes_por_pagina;

$stmt_total = $pdo->prepare("SELECT COUNT(*) FROM Fotos WHERE Album = :album");
$stmt_total->bindValue(':album', $album_id, PDO::PARAM_INT);
$stmt_total->execute();
$total_imagenes = $stmt_total->fetchColumn();

$total_paginas = ceil($total_imagenes / $imagenes_por_pagina);
$pagina_anterior = ($pagina_actual > 1) ? $pagina_actual - 1 : null;
$pagina_siguiente = ($pagina_actual < $total_paginas) ? $pagina_actual + 1 : null;

$stmt_fotos = $pdo->prepare("
    SELECT IdFoto, Fichero 
    FROM Fotos 
    WHERE Album = :album 
    ORDER BY FRegistro DESC 
    LIMIT :limite OFFSET :offset
");
$stmt_fotos->bindValue(':album', $album_id, PDO::PARAM_INT);
$stmt_fotos->bindValue(':limite', $imagenes_por_pagina, PDO::PARAM_INT);
$stmt_fotos->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt_fotos->execute();
$fotos_paginadas = $stmt_fotos->fetchAll(PDO::FETCH_ASSOC);

// NAVEGACIÓN ENTRE ÁLBUMES
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
    die("Error al obtener navegación entre álbumes: " . $e->getMessage());
}

$title = 'Ver Álbum (Privada)';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <div class="album-navegacion-lateral">
        <?php if ($album_anterior_id): ?>
            <button class="boton-album-lateral anterior"
 data-id="<?= htmlspecialchars($album_anterior_id, ENT_QUOTES, 'UTF-8') ?>">
 ⬅ Álbum anterior
</button>

        <?php endif; ?>

        <section class="album-privado-info" id="album-info" data-album-id="<?= $album_id ?>">
            <h2 class="titulo-album"><?= htmlspecialchars($datos_album['titulo']); ?></h2>
            <p><strong>Número total de fotos:</strong> <?= $total_imagenes; ?></p>

            <a class="boton-anadir-foto" href="/daw/miweb_xampp/anadir-foto?id=<?= htmlspecialchars($album_id); ?>">
                <i class="fas fa-plus-circle"></i> Añadir foto
            </a>
        </section>

        <?php if ($album_siguiente_id): ?>
            <button class="boton-album-lateral siguiente"
 data-id="<?= htmlspecialchars($album_siguiente_id, ENT_QUOTES, 'UTF-8') ?>">
 Álbum siguiente ➡
</button>

        <?php endif; ?>
    </div>

    <section id="galeria-album">
        <h3 class="fotos-del-album-titulo">Fotos del Álbum</h3>

        <p class="indicador-pagina">Estás en la página <?= $pagina_actual ?> de <?= $total_paginas ?></p>

        <div class="fotos-lista">
            <?php if (!empty($fotos_paginadas)): ?>
                <?php foreach ($fotos_paginadas as $foto): ?>
                    <div class="foto-item">
                        <a href="/daw/miweb_xampp/detalle-foto?id=<?= htmlspecialchars($foto['IdFoto']); ?>&modo=album&album=<?= $album_id; ?>">
                            <img src="/image/<?= htmlspecialchars($datos_album['titulo']); ?>/<?= htmlspecialchars($foto['Fichero']); ?>"
                                 alt="Foto del álbum">
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="sin-fotos">No hay fotos en este álbum.</p>
            <?php endif; ?>
        </div>

        <div class="navegacion-botones">
            <?php if ($pagina_anterior): ?>
                <a class="boton-navegacion"
 href="?id=<?= htmlspecialchars($album_id, ENT_QUOTES, 'UTF-8') ?>&pagina=<?= htmlspecialchars($pagina_anterior, ENT_QUOTES, 'UTF-8') ?>">
Anterior</a>

            <?php endif; ?>
            <?php if ($pagina_siguiente): ?>
                <a class="boton-navegacion" href="?id=<?= htmlspecialchars($album_id, ENT_QUOTES, 'UTF-8') ?>&pagina=<?= htmlspecialchars($pagina_siguiente, ENT_QUOTES, 'UTF-8') ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    </section>
</main>


<script>
function cargarAlbum(id) {
    const info = document.querySelector('#album-info');
    const galeria = document.querySelector('#galeria-album');
    info.classList.add('fade-out');
    galeria.classList.add('fade-out');

    fetch(`/daw/miweb_xampp/ver-album-privada?id=${id}`)
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const nuevoInfo = doc.querySelector('#album-info');
            const nuevaGaleria = doc.querySelector('#galeria-album');
            const nuevaAnterior = doc.querySelector('.boton-album-lateral.anterior');
            const nuevaSiguiente = doc.querySelector('.boton-album-lateral.siguiente');

            setTimeout(() => {
                document.querySelector('#album-info').replaceWith(nuevoInfo);
                document.querySelector('#galeria-album').replaceWith(nuevaGaleria);

                const contenedor = document.querySelector('.album-navegacion-lateral');

                document.querySelector('.boton-album-lateral.anterior')?.remove();
                if (nuevaAnterior) contenedor.insertBefore(nuevaAnterior, contenedor.firstElementChild);

                document.querySelector('.boton-album-lateral.siguiente')?.remove();
                if (nuevaSiguiente) contenedor.appendChild(nuevaSiguiente);

                asignarEventosAlbum();
            }, 200);
        });
}

function asignarEventosAlbum() {
    document.querySelectorAll('.boton-album-lateral').forEach(btn => {
        btn.onclick = () => {
            const id = btn.dataset.id;
            if (id) cargarAlbum(id);
        };
    });
}

asignarEventosAlbum();
</script>

<style>
.fade-out {
    animation: fadeOut 0.3s ease forwards;
}

@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}
</style>

<?php include __DIR__ . '/../templates/footer.php'; ?>
