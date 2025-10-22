<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Inicia la sesión si no se ha iniciado ya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header("Location: /inicio-sesion?mensaje=debe_iniciar_sesion");
    exit();
}

// Verificar si hay datos del álbum en la sesión
if (!isset($_SESSION['album_creado'])) {
    die("No se encontraron los datos del álbum. Por favor, regrese y cree un álbum nuevamente.");
}

// Obtener los datos del álbum desde la sesión
$titulo = htmlspecialchars($_SESSION['album_creado']['titulo']);
$descripcion = htmlspecialchars($_SESSION['album_creado']['descripcion']);

// Limpiar los datos de la sesión para evitar mostrar información duplicada
unset($_SESSION['album_creado']);

// Configurar el título de la página
$title = 'Confirmación de Creación del Álbum';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <h2>Confirmación de Creación del Álbum</h2>
    <p><strong>Título del Álbum:</strong> <?php echo $titulo; ?></p>
    <p><strong>Descripción:</strong> <?php echo $descripcion; ?></p>
    <p>¡El álbum ha sido creado exitosamente!</p>

    <!-- Enlace para agregar la primera fotografía -->
    <p>
        <a href="/anadir-foto" class="boton-agregar-foto">
            Agregar la primera fotografía al álbum
        </a>
    </p>
    <a href="/" class="boton-volver">Volver a Inicio</a>
</main>

<?php
include __DIR__ . '/../templates/footer.php';
?>
