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

$IdUsuario = $_SESSION['id_usuario'];
$limite_fotos = 250;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM Fotos WHERE Usuario = :usuario");
$stmt->execute([':usuario' => $IdUsuario]);
$total_usuario = (int)$stmt->fetchColumn();
$fotos_restantes = $limite_fotos - $total_usuario;

try {
    $query_albumes = "SELECT a.IdAlbum, a.Titulo FROM Albumes a";
    $stmt = $pdo->query($query_albumes);
    $albumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($albumes)) {
        throw new Exception("No tienes álbumes creados.");
    }
} catch (PDOException $e) {
    die("Error al cargar los álbumes: " . $e->getMessage());
}

$album_preseleccionado = isset($_GET['id']) && ctype_digit($_GET['id']) ? (int)$_GET['id'] : null;
$album_nombre = null;

if ($album_preseleccionado) {
    foreach ($albumes as $album) {
        if ((int)$album['IdAlbum'] === $album_preseleccionado) {
            $album_nombre = $album['Titulo'];
            break;
        }
    }
}

$title = 'Añadir Foto a Álbum';
include __DIR__ . '/../templates/header.php';
?>

<main>
<section class="anadir-foto-contenedor">
        <h2>Añadir Foto</h2>

        <?php if ($album_nombre): ?>
    <p class="album-preseleccionado"><strong>Álbum seleccionado:</strong> <?= htmlspecialchars($album_nombre) ?></p>
<?php endif; ?>

        <?php if (isset($_SESSION['errores'])): ?>
            <ul class="errores">
                <?php foreach ($_SESSION['errores'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
                <?php unset($_SESSION['errores']); ?>
            </ul>
        <?php endif; ?>

        <form action="/procesar-anadir-foto" method="post" enctype="multipart/form-data">
    <div>
        <label for="fichero" class="custom-file-upload">Seleccionar imagen/imagenes</label>
        <input type="file" id="fichero" name="fichero[]" accept="image/*" multiple required class="input-file">
        <p id="contador-archivos" style="margin-top: 10px; text-align: center; font-weight: bold; color: var(--color-texto);">0 imágenes seleccionadas.</p>
        <button type="button" id="limpiar-seleccion" class="boton-limpiar">Limpiar selección</button>

    </div>

            <?php if (!$album_preseleccionado): ?>
            <div>
                <label for="album">Seleccionar álbum:</label>
                <select id="album" name="album">
                    <option value="default">Subir sin álbum</option>
                    <?php foreach ($albumes as $album): ?>
                        <option value="<?= htmlspecialchars($album['IdAlbum']) ?>">
                            <?= htmlspecialchars($album['Titulo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php else: ?>
                <input type="hidden" name="album" value="<?= htmlspecialchars($album_preseleccionado, ENT_QUOTES, 'UTF-8') ?>">

            <?php endif; ?>

            <?php if ($fotos_restantes <= 0): ?>
               <p><strong>Te quedan <?= htmlspecialchars($fotos_restantes, ENT_QUOTES, 'UTF-8') ?> fotos disponibles para subir.</strong></p>

            <?php else: ?>
                <p><strong>Te quedan <?= $fotos_restantes ?> fotos disponibles para subir.</strong></p>
            <?php endif; ?>

            <button type="submit" class="boton-volver" <?= $fotos_restantes <= 0 ? 'disabled' : '' ?>>Añadir Foto</button>

        </form>
    </section>
</main>
<script>
document.getElementById('fichero').addEventListener('change', function () {
    const cantidad = this.files.length;
    const texto = cantidad === 1 ? '1 imagen seleccionada.' : `${cantidad} imágenes seleccionadas.`;
    const contador = document.getElementById('contador-archivos');
    contador.textContent = texto;
    contador.style.color = cantidad > 0 ? 'green' : 'var(--color-texto)';
});

document.getElementById('limpiar-seleccion').addEventListener('click', function () {
    const input = document.getElementById('fichero');
    input.value = '';  // Limpiar selección
    const contador = document.getElementById('contador-archivos');
    contador.textContent = '0 imágenes seleccionadas.';
    contador.style.color = 'var(--color-texto)';
});
</script>




<?php include __DIR__ . '/../templates/footer.php'; ?>
