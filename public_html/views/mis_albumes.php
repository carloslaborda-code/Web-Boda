<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: /inicio-sesion");
    exit();
}

require_once __DIR__ . '/../config/db.php';

try {
    $query_albumes = "SELECT IdAlbum, Titulo, Descripcion, Portada FROM Albumes";
    $stmt_albumes = $pdo->query($query_albumes);
    $albumes = $stmt_albumes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los álbumes: " . $e->getMessage());
}

$title = 'Álbumes disponibles';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section>
        <h2>Álbumes disponibles</h2>
        <?php if (!empty($albumes)): ?>
            <ul class="lista-albumes">
                <?php foreach ($albumes as $album): ?>
                    <li class="item-album">
                        <h3><?= htmlspecialchars($album['Titulo']) ?></h3>
                        <a href="/ver-album-privada?id=<?= htmlspecialchars($album['IdAlbum']) ?>">
                        <img class="imagen-portada grande" 
     src="/image/albums/<?= htmlspecialchars($album['Portada']) ?>" 
     alt="Portada del álbum <?= htmlspecialchars($album['Titulo']) ?>">



        
</a>
<p><?= htmlspecialchars($album['Descripcion']) ?></p>
<a href="/ver-album-privada?id=<?= htmlspecialchars($album['IdAlbum']) ?>">Ver álbum</a>

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No hay álbumes disponibles.</p>
        <?php endif; ?>
    </section>
</main>


<?php include __DIR__ . '/../templates/footer.php'; ?>
