<?php

session_set_cookie_params([
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/db.php'; // Asegúrate de tener acceso a la base de datos

$estilo = '/css/style.css'; // Estilo por defecto

// Verificar si hay una sesión activa o si se debe recordar al usuario
if (isset($_SESSION['username'])) {
    // Usuario autenticado por sesión
    try {
        // Recuperar el estilo del usuario desde la base de datos
        $query = "SELECT Estilo FROM Usuarios WHERE NomUsuario = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':username' => $_SESSION['username']]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado && !empty($resultado['Estilo'])) {
            $estilo_id = $resultado['Estilo'];
            $_SESSION['estilo'] = $estilo_id;

            // Recuperar la ruta del CSS correspondiente al IdEstilo
            $query = "SELECT Fichero FROM Estilos WHERE IdEstilo = :id_estilo";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id_estilo' => $estilo_id]);
            $resultado_estilo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado_estilo && !empty($resultado_estilo['Fichero'])) {
                $estilo = '/css/' . $resultado_estilo['Fichero'];
            }
        }
    } catch (PDOException $e) {
        die("Error al recuperar el estilo del usuario: " . $e->getMessage());
    }
} elseif (isset($_COOKIE['estilo'])) {
    // Leer la cookie "estilo" si no hay sesión ni autenticación automática
    $estilo_id = $_COOKIE['estilo'];

    // Recuperar la ruta del CSS correspondiente al IdEstilo
    try {
        $query = "SELECT Fichero FROM Estilos WHERE IdEstilo = :id_estilo";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id_estilo' => $estilo_id]);
        $resultado_estilo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado_estilo && !empty($resultado_estilo['Fichero'])) {
            $estilo = '/css/' . $resultado_estilo['Fichero'];
        }
    } catch (PDOException $e) {
        die("Error al recuperar el estilo de la cookie: " . $e->getMessage());
    }
}

date_default_timezone_set('Europe/Madrid');
$hora_actual = (int) date('H');
$nombre_usuario = isset($_SESSION['username']) ? $_SESSION['username'] : (isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : 'Usuario');

if ($hora_actual >= 6 && $hora_actual < 12) {
    $saludo = "Buenos días, " . htmlspecialchars($nombre_usuario);
} elseif ($hora_actual >= 12 && $hora_actual < 16) {
    $saludo = "Hola, " . htmlspecialchars($nombre_usuario);
} elseif ($hora_actual >= 16 && $hora_actual < 20) {
    $saludo = "Buenas tardes, " . htmlspecialchars($nombre_usuario);
} else {
    $saludo = "Buenas noches, " . htmlspecialchars($nombre_usuario);
}
// Cabeceras de seguridad globales (fallback por PHP)
if (!headers_sent()) {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=(), fullscreen=(self)");
    header("Cross-Origin-Resource-Policy: same-site");
    header("Cross-Origin-Embedder-Policy: require-corp");
    header("Cross-Origin-Opener-Policy: same-origin");
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");
    header("Set-Cookie: securetoken=1; HttpOnly; Secure; SameSite=Strict", false);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Boda Marta & Luis', ENT_QUOTES, 'UTF-8') ?></title>

    <link rel="icon" type="image/x-icon" href="/image/icon.ico">


    <!-- Siempre cargar el estilo base -->
   <link rel="stylesheet" href="/css/style.css?v=15">



    <!-- Cargar solo el estilo personalizado si es válido y existe -->
<?php
if ($estilo !== '/css/style.css' && file_exists($_SERVER['DOCUMENT_ROOT'] . $estilo)) {
    echo '<link rel="stylesheet" href="' . htmlspecialchars($estilo) . '">';
}
?>
    <!-- Botón hamburguesa (colócalo dentro del <header>) -->


    
    
    
</head>
<body>
    <!-- Encabezado del sitio web -->
    <header>
        <h1>Boda Marta & Luis</h1>
        <?php if (isset($_COOKIE['usuario'])): ?>
            <p><?php echo $saludo; ?>.</p>
        <?php elseif (isset($_SESSION['username'])): ?>
            <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <?php endif; ?>
        <h3>¡Disfruta de las fotos!</h3>
        <!-- Botón hamburguesa solo visible en móviles -->
  <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">&#9776;</button>
        <nav>
            <ul id="menu-principal" class="menu-principal">
                <a href="/"><i class="fas fa-home icono-menu"></i>Inicio</a>
                <?php if (!isset($_SESSION['username'])): ?>   
                    <a href="/inicio-sesion"><i class="fas fa-sign-in-alt icono-menu"></i>Inicio de sesión</a>
                    <?php if (!isset($_COOKIE['usuario']))?>
                        <a href="/registro"><i class="fas fa-user-plus icono-menu"></i>Registro</a>
                <?php else: ?>
                    <a href="/perfil-usuario"><i class="fas fa-user icono-menu"></i>Mi Perfil</a>
                    <a href="/logout"><i class="fas fa-sign-out-alt icono-menu"></i>Cerrar sesión</a>
                    <!-- Nuevo enlace a "Añadir Foto a Álbum" -->
                    <a href="/anadir-foto"><i class="fas fa-upload icono-menu"></i>Subir Foto</a>
                <?php endif; ?>
               <!-- Formulario de búsqueda rápida -->
                <form class="buscador-rapido" action="/resultado-busqueda" method="get">
                    <input type="text" id="search" name="titulo" placeholder="Buscar albumes..." required>
                    <button type="submit">Buscar</button>
                </form>
            </ul>
        </nav>
       
<script>
  function goBack() {
    // Si hay una página anterior en el historial, volver
    if (document.referrer) {
        window.history.back();
    } else {
        // Si no, redirige a una página predeterminada, por ejemplo la página de inicio
        window.location.href = '/'; // Aquí puedes cambiar la URL si lo deseas
    }
  }
</script>


        
        
    </header>
    <!-- Mínimo JavaScript necesario para togglear el menú -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu-principal');
    toggle.addEventListener('click', () => {
  menu.classList.toggle('activo');
});

  });
</script>
