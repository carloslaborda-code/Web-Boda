<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

$title = 'Página Principal';
include __DIR__ . '/../templates/header.php';

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

$imagenes_por_pagina = 6;
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina_actual - 1) * $imagenes_por_pagina;

try {
    $total_query = "SELECT COUNT(*) FROM Fotos";
    $total_stmt = $pdo->query($total_query);
    $total_imagenes = $total_stmt->fetchColumn();
} catch (PDOException $e) {
    die("Error al contar imágenes: " . $e->getMessage());
}

try {
    $query = "SELECT f.IdFoto, f.Fichero, a.Titulo AS AlbumTitulo 
              FROM Fotos f
              LEFT JOIN Albumes a ON f.Album = a.IdAlbum
              ORDER BY f.FRegistro DESC
              LIMIT :limite OFFSET :offset";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':limite', $imagenes_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

$total_paginas = ceil($total_imagenes / $imagenes_por_pagina);
$pagina_anterior = ($pagina_actual > 1) ? $pagina_actual - 1 : null;
$pagina_siguiente = ($pagina_actual < $total_paginas) ? $pagina_actual + 1 : null;

?>

<!-- Sección de todas las fotos -->
<section>
    
    <h2 class="titulo-seccion">Todas las fotos subidas</h2>
   <p class="indicador-pagina">Estás en la página <?= htmlspecialchars($pagina_actual, ENT_QUOTES, 'UTF-8') ?> de <?= htmlspecialchars($total_paginas, ENT_QUOTES, 'UTF-8') ?></p>

    <ul class="galeria-principal galeria-imagenes">
        <?php foreach ($fotos as $foto): ?>
            <li class="foto-item">
                <a href="/detalle-foto?id=<?= htmlspecialchars($foto['IdFoto']); ?>&modo=global">
                    <img src="/image/<?= htmlspecialchars($foto['AlbumTitulo'] ?? 'default'); ?>/<?= htmlspecialchars($foto['Fichero']); ?>" alt="foto">
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Controles de paginación -->
   <div class="navegacion-botones">
  <?php if ($pagina_anterior): ?>
    <a class="boton-navegacion" href="?pagina=<?= htmlspecialchars($pagina_anterior, ENT_QUOTES, 'UTF-8') ?>">Anterior</a>
  <?php endif; ?>

  <?php if ($pagina_siguiente): ?>
    <a class="boton-navegacion" href="?pagina=<?= htmlspecialchars($pagina_siguiente, ENT_QUOTES, 'UTF-8') ?>">Siguiente</a>
  <?php endif; ?>
</div>


</section>

<?php include __DIR__ . '/../templates/footer.php'; ?>
