<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

if (!isset($_SESSION['username'])) {
    if (!empty($_GET)) {
        $_SESSION['form_data'] = $_GET;
    }
    header("Location: /inicio-sesion?mensaje=debe_iniciar_sesion");
    exit();
}

if (isset($_SESSION['form_data'])) {
    $_GET = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}

require_once __DIR__ . '/../config/db.php';

$titulo = isset($_GET['titulo']) ? trim($_GET['titulo']) : '';

try {
    // Buscar Albumes
    $query_albumes = "SELECT IdAlbum, Titulo, Descripcion, Portada FROM Albumes WHERE 1=1";
    $params_album = [];
    if (!empty($titulo)) {
        $query_albumes .= " AND LOWER(Titulo) LIKE :titulo_a";
        $params_album[':titulo_a'] = '%' . strtolower($titulo) . '%';
    }
    $query_albumes .= " ORDER BY Titulo ASC";
    $stmt_album = $pdo->prepare($query_albumes);
    $stmt_album->execute($params_album);
    $albumes = $stmt_album->fetchAll(PDO::FETCH_ASSOC);

    if (empty($fotos) && empty($albumes)) {
        header("Location: /formulario-busqueda-error");
        exit();
    }
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

$title = 'Resultados de Búsqueda';
include __DIR__ . '/../templates/header.php';
?>

<section>
    <h2>Resultados de Álbumes</h2>
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

</section>


<?php include __DIR__ . '/../templates/footer.php'; ?>