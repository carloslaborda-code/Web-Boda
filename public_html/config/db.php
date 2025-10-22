<?php
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(403);
    exit("Acceso prohibido");
}

// ✅ Corrige la ruta a dos niveles por encima
$env = parse_ini_file(__DIR__ . '/../../secure/db.env');

// 🧪 Test temporal (puedes quitarlo si todo va bien)
// var_dump($env); exit;

if (!$env) {
    die("❌ No se pudo cargar el archivo db.env. Revisa la ruta.");
}

define('DB_HOST', $env['DB_HOST']);
define('DB_NAME', $env['DB_NAME']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
