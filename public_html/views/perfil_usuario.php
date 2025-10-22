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

$username = $_SESSION['username'];

try {
    $query = "
        SELECT NomUsuario, FRegistro 
        FROM Usuarios 
        WHERE NomUsuario = :username
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $username]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Error: Usuario no encontrado.");
    }
} catch (PDOException $e) {
    die("Error al cargar los datos del usuario: " . $e->getMessage());
}

$title = 'Perfil Usuario';
include __DIR__ . '/../templates/header.php';
?>



<?php if (isset($_SESSION['primera_vez']) && !isset($_COOKIE['no_mostrar_bienvenida'])): ?>
<div id="modal-bienvenida" class="modal">
  <div class="modal-content">
    <span id="cerrar-modal" class="cerrar">&times;</span>
    <h2>¡Bienvenid@ a la Galería de Fotos de Marta & Luis!</h2>
<p>
Esta web ha sido creada con mucho cariño para reunir <strong>todas las fotos de nuestra boda</strong> en un único lugar. 
<br><br>
Todos los usuarios pueden ver, subir imágenes y crear recuerdos juntos. ¡Queremos que esta sea una experiencia especial y colaborativa!
</p>

<p>
🔔 <strong>Importante:</strong> <br>
- Una vez subida una foto, <u>no podrá ser eliminada</u>. Por favor, asegúrate antes de subirla. <br>
- No disponemos de sistema de recuperación de cuenta. Si olvidas tu contraseña, tendrás que crear una nueva cuenta con un nombre distinto.
</p>

<p>
📩 <strong>¿Problemas o dudas?</strong><br>
Para cualquier incidencia, error en la página o si necesitas eliminar alguna foto no deseada, puedes contactarme directamente por <strong>Correo</strong> al:<br> 
 <a href="mailto:bodamartayluis12345@gmail.com">
      <i class="fas fa-envelope"></i> Enviar correo
    </a>
</p>

<p><strong>Gracias por formar parte de este recuerdo tan especial ❤️</strong></p>
<button id="no-mostrar-mas">No volver a mostrar esto</button>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modal-bienvenida");
    const cerrar = document.getElementById("cerrar-modal");
    const noMostrar = document.getElementById("no-mostrar-mas");

    modal.style.display = "block";

    cerrar.onclick = () => modal.style.display = "none";
    window.onclick = e => { if (e.target == modal) modal.style.display = "none"; };

    noMostrar.onclick = () => {
        document.cookie = "no_mostrar_bienvenida=1; path=/; max-age=" + (60 * 60 * 24 * 180);
        modal.style.display = "none";
    };
});
</script>
<style>
.modal {
  display: none;
  position: fixed; z-index: 9999;
  padding-top: 100px; left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.7);
}
.modal-content {
  background: white;
  margin: auto; padding: 20px;
  border-radius: 10px;
  width: 80%; max-width: 500px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.4);
  text-align: center;
}
.cerrar {
  float: right; font-size: 24px;
  font-weight: bold; cursor: pointer;
}
#no-mostrar-mas {
  margin-top: 20px; padding: 10px 20px;
  background-color: #d48c9e; border: none;
  border-radius: 5px; color: white;
  cursor: pointer;
}
#no-mostrar-mas:hover {
  background-color: #b86c80;
}
</style>
<?php unset($_SESSION['primera_vez']); endif; ?>




<main>
<section class="perfil-container">
    <div class="perfil-header">
        <h2>Perfil de Usuario</h2>
    </div>
    <div class="perfil-datos">
        <p><strong>Nombre de Usuario:</strong> <?= htmlspecialchars($usuario['NomUsuario']) ?></p>
        <p><strong>Fecha de Incorporación:</strong> <?= htmlspecialchars($usuario['FRegistro']) ?></p>
    </div>
    <div class="perfil-opciones">
        <a href="/albumes"><i class="fas fa-image"></i>Ver Álbumes</a>
        <a href="/fotos-todas"><i class="fas fa-camera-retro"></i>Todas las Fotos</a>
        <?php if (isset($_SESSION['id_usuario']) && $_SESSION['id_usuario'] == 36): ?>
        <a href="/crear-album"><i class="fas fa-plus-circle"></i>Crear Álbum</a>
        <?php endif; ?>
        <a href="/mis-datos"><i class="fas fa-user-cog"></i>Modificar Mis Datos</a>
    </div>
</section>

</main>


<?php include __DIR__ . '/../templates/footer.php'; ?>
