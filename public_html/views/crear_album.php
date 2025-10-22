<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] != 36) {
    header("HTTP/1.0 403 Forbidden");
    exit("Solo el administrador puede crear álbumes.");
}

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header("Location: /inicio-sesion");
    exit();
}

$title = 'Crear Álbum';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section class="crear-album-contenedor">
        <h2>Crear nuevo álbum</h2>

        <form action="/procesar-crear-album" method="post" enctype="multipart/form-data">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="4" required></textarea>

            <label for="portada">Portada del Álbum:</label>
            <input type="file" id="portada" name="portada" accept="image/*" required>

            <button type="submit">Crear álbum</button>
        </form>
    </section>
</main>



<?php include __DIR__ . '/../templates/footer.php'; ?>
