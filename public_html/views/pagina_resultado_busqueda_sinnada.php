<?php
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

// perfil_usuario.php

$title = 'Pagina no hay resultados';
include __DIR__ . '/../templates/header.php';
?>

    <!-- Sección que muestra que no se encontraron resultados -->
    <section>
        <h2>No se encontraron albumes que coincidan con tu búsqueda</h2>
        <p>Lo sentimos, no hemos encontrado ninguna album que coincida con los criterios de búsqueda.</p>
        <p>Los albumes disponibles son Ceremonia, Coctel, Comida, Tardeo y Fiesta</p>
    </section>
    

<!-- Pie de página -->
<?php 
include __DIR__ . '/../templates/footer.php';
?>
   

</body>
</html>
