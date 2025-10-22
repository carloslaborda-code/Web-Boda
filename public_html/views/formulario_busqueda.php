<?php

session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Consulta a la base de datos para obtener los países
try {
    $query = "SELECT IdPais, NomPais FROM Paises ORDER BY NomPais";
    $stmt = $pdo->query($query);
    $paises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los países: " . $e->getMessage());
}

$title = 'Formulario de Búsqueda';
include __DIR__ . '/../templates/header.php';
?>

<section>
    <h2>Formulario de Búsqueda</h2>
    
    <form class="formulario-busqueda" action="/resultado-busqueda" method="get">
        <label for="titulo">Título de la Foto:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($_GET['titulo'] ?? ''); ?>">
        <br><br>
        <button type="submit">Buscar</button>
    </form>
</section>



<?php 
include __DIR__ . '/../templates/footer.php';
?>
