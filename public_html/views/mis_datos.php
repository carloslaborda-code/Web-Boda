<?php
session_start();
if (!defined('FROM_INDEX')) { header("HTTP/1.0 403 Forbidden"); exit("Acceso directo no permitido."); }
if (!isset($_SESSION['username'])) { header("Location: /daw/miweb_xampp/inicio-sesion"); exit(); }

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Recuperar errores y datos ingresados anteriormente
$errores = $_SESSION['form_errors'] ?? [];
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_data']);

try {
    // Obtener datos del usuario autenticado
    $query = "SELECT NomUsuario FROM Usuarios WHERE NomUsuario = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $_SESSION['username']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Error: Usuario no encontrado.");
    }


    // Preparar datos para el formulario
    $username = $usuario['NomUsuario'];
    $modo_edicion = true; 
    $action_url = "/daw/miweb_xampp/procesar-mis-datos"; 
} catch (PDOException $e) {
    die("Error al cargar los datos: " . $e->getMessage());
}

$title = "Mis Datos";
include __DIR__ . '/../templates/header.php';
?>

<section class="mis-datos-contenedor">
    <h2>Mis Datos</h2>
    <?php if (isset($_SESSION['flashdata'])): ?>
    <p class="mensaje-exito"><?= htmlspecialchars($_SESSION['flashdata'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['flashdata']); ?></p>

<?php endif; ?>

    <?php 
        include __DIR__ . '/../partials/formulario_usuario.php'; 
    ?>
</section>



<?php include __DIR__ . '/../templates/footer.php'; ?>
