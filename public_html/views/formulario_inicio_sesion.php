<?php 
if (!defined('FROM_INDEX')) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso directo no permitido.");
}

$title = 'Acceso de Usuario';
include __DIR__ . '/../templates/header.php';
?>

<main>
    <section class="login-container">
        <h2>Acceso de Usuario</h2>

        <!-- Mostrar mensaje flash si existe -->
        <?php if (isset($_SESSION['flashdata'])): ?>
            <p class="error" style="color: red; font-weight: bold;"><?php echo $_SESSION['flashdata']; unset($_SESSION['flashdata']); ?></p>
        <?php endif; ?>

        <form onsubmit="return validarFormulario()" action="/control-acceso" method="post" class="formulario-inicio-sesion">
    <label for="username">Nombre de Usuario:</label>
    <input type="text" id="username" name="username" value="<?php echo isset($_COOKIE['usuario']) ? htmlspecialchars($_COOKIE['usuario']) : ''; ?>">
    <br><br>
    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password">
    <br><br>
    <div class="recordarme-container">
        <input type="checkbox" name="recordarme" id="recordarme" <?php echo isset($_COOKIE['usuario']) ? 'checked' : ''; ?>>
        <label for="recordarme">Recordarme</label>
    </div>
    <br>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <button type="submit" class="btn-iniciar-sesion">Iniciar Sesión</button>
</form>


    </section>
</main>

<script>
    function validarFormulario() {
        var usuario = document.getElementById('username').value.trim();
        var contraseña = document.getElementById('password').value.trim();

        if (usuario === "" || contraseña === "") {
            alert("Por favor, rellena ambos campos.");
            return false;
        }
        return true;
    }
</script>



<?php 
include __DIR__ . '/../templates/footer.php';
?>
</body>
</html>
