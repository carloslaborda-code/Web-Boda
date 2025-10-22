<?php
session_start();

if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

$title = 'Registro de Usuario';
include __DIR__ . '/../templates/header.php';

$form_data = $_SESSION['form_data'] ?? [];
$errores = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data'], $_SESSION['form_errors']);
?>

<main>
<section class="registro-container">

        <h2>Registro</h2>
        <form action="/daw/miweb_xampp/procesar-registro" method="post">
            <div>
                <label for="username">Nombre de usuario:</label>
                <input type="text" id="username" name="username" required value="<?= htmlspecialchars($form_data['username'] ?? '') ?>">
                <span class="error"><?= htmlspecialchars($errores['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>

            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
                <span class="error"><?= htmlspecialchars($errores['password'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>

            </div>
            <div>
                <label for="confirm_password">Repetir contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <span class="error"><?= htmlspecialchars($errores['confirm_password'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>

            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <button type="submit">Registrarse</button>
        </form>
    </section>
</main>

<?php include __DIR__ . '/../templates/footer.php'; ?>
