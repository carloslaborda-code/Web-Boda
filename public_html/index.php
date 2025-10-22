<?php
// index.php - Router principal seguro
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

define('FROM_INDEX', true);

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = trim(str_replace('/daw/miweb_xampp', '', $request_uri), '/');

// Validar solo rutas seguras (letras, números, guiones, barra y guión bajo)
if (!preg_match('/^[a-zA-Z0-9\-\/_]*$/', $request_uri)) {
    error_log("Intento de ruta maliciosa: $request_uri - IP: " . $_SERVER['REMOTE_ADDR']);
    header("HTTP/1.0 400 Bad Request");
    exit("Solicitud inválida.");
}
$routes = [
    '' => 'views/pagina_principal.php',
    'fotos-todas' => 'views/pagina_principal.php',
    'registro' => 'views/registro.php',
    'inicio-sesion' => 'views/formulario_inicio_sesion.php',
    'detalle-foto' => 'views/detalle_foto.php',
    'perfil-usuario' => 'views/perfil_usuario.php',
    'procesar-registro' => 'controllers/procesar_registro.php',
    'procesar-crear-album' => 'controllers/procesar_crear_album.php',
    'control-acceso' => __DIR__ . '/controllers/control_acceso.php',
    'declaracion-accesibilidad' => 'views/declaracion_accesibilidad.php',
    'formulario-busqueda' => 'views/formulario_busqueda.php',
    'formulario-busqueda-error' => 'views/pagina_resultado_busqueda_sinnada.php',
    'resultado-busqueda' => 'views/resultado_busqueda.php',
    'crear-album' => 'views/crear_album.php',
    'respuesta-solicitar-album' => 'views/respuesta_solicitar_album.php',
    'respuesta-creacion-album' => 'views/confirmacion_creacion_album.php',
    'respuesta-anadir-foto' => 'views/confirmacion_anadir_foto.php',
    'ver-album' => 'views/ver_album.php',
    'configurar' => 'views/configurar.php',
    'mis-datos' => 'views/mis_datos.php',
    'albumes'=>'views/mis_albumes.php',
    'fotos'=>'views/mis_fotos.php',
    'ver-album-privada'=>'views/ver_album_privada.php',
    'ver-album-comun'=>'views/ver_album_comun.php',
    'anadir-foto'=>'views/anadir_foto.php',
    'procesar-anadir-foto' => 'controllers/procesar_anadir_foto.php', // Nueva ruta para procesar añadir foto
    'procesar-mis-datos' => 'controllers/procesar_mis_datos.php',
    'logout' => 'controllers/logout.php',
    'procesar-baja' => 'controllers/procesar_baja.php',
    'darse-baja' => 'views/darse_baja.php',
];
if (array_key_exists($request_uri, $routes)) {
    require $routes[$request_uri];
} else {
    error_log("404 - Ruta no encontrada: $request_uri - IP: " . $_SERVER['REMOTE_ADDR']);
    header("HTTP/1.0 404 Not Found");
    include 'views/404.php';
}
