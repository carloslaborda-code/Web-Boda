<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

require_once __DIR__ . '/../config/db.php';

// Lista de estilos disponibles (IdEstilo => Nombre)
$estilos_disponibles = [
    1 => 'Alto Contraste',
    2 => 'Contraste y Letra Grande',
    3 => 'Letra Grande',
    4 => 'Modo Impreso',
    5 => 'Modo Noche',
    6 => 'Estilo Base'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_estilo_id = $_POST['estilo'] ?? '';

    // Validar el estilo seleccionado
    if (array_key_exists($nuevo_estilo_id, $estilos_disponibles)) {
        if (isset($_SESSION['username'])) {
            // Guardar el estilo en la base de datos para el usuario autenticado
            try {
                $query = "UPDATE Usuarios SET Estilo = :nuevo_estilo WHERE NomUsuario = :username";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    ':nuevo_estilo' => $nuevo_estilo_id,
                    ':username' => $_SESSION['username']
                ]);

                // Actualizar el estilo en la sesión
                $_SESSION['estilo'] = $nuevo_estilo_id;

                // Redirigir al usuario a la página principal con el nuevo estilo aplicado
                header('Location: /');
                exit();
            } catch (PDOException $e) {
                $error = 'Error al actualizar el estilo en la base de datos: ' . $e->getMessage();
            }
        } else {
            // Guardar el estilo en la cookie si el usuario no está autenticado
            setcookie('estilo', $nuevo_estilo_id, time() + (90 * 24 * 60 * 60), '/');
            header('Location: /');
            exit();
        }
    } else {
        $error = 'El estilo seleccionado no es válido.';
    }
}

$title = 'Configurar Estilo';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section>
        <h2>Configurar Estilo</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="/configurar">
            <label for="estilo">Selecciona un estilo:</label>
            <select id="estilo" name="estilo">
                <?php foreach ($estilos_disponibles as $id => $nombre): ?>
                    <option value="<?php echo htmlspecialchars($id); ?>" 
                        <?php echo (($_SESSION['estilo'] ?? $_COOKIE['estilo'] ?? '') == $id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($nombre); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Guardar</button>
        </form>
    </section>
</main>



<?php include __DIR__ . '/../templates/footer.php'; ?>
