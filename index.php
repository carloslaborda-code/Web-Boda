<?php
// index.php
// Define una constante llamada 'FROM_INDEX' con el valor true.
// Esta constante se utiliza para verificar que los archivos incluidos o requeridos sean accedidos solo desde este archivo (index.php),
// y no directamente, como medida de seguridad.
define('FROM_INDEX', true);

// Obtiene la ruta de la URI de la solicitud actual (sin los parámetros de la consulta).
// $_SERVER['REQUEST_URI'] contiene la URI completa (por ejemplo, "/daw/miweb_xampp/registro").
// parse_url() extrae solo la parte del path, ignorando la consulta (?).
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Limpia la URI eliminando la parte '/daw/miweb_xampp' de la ruta y los caracteres de barra iniciales o finales.
$request_uri = trim(str_replace('/daw/miweb_xampp', '', $request_uri), '/');

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

// Si existe, incluye el archivo correspondiente.
if (array_key_exists($request_uri, $routes)) {
    require $routes[$request_uri];
} else {
    header("HTTP/1.0 404 Not Found");
    include 'views/404.php';
}
