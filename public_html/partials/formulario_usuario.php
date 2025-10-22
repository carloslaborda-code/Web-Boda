<?php if (!defined('FROM_INDEX')) { header("HTTP/1.0 403 Forbidden"); exit("Acceso directo no permitido."); } ?>

<form class="formulario-registro" action="<?php echo htmlspecialchars($action_url); ?>" method="post" enctype="multipart/form-data" novalidate>
    <label for="username">Nombre de Usuario:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" <?php echo $modo_edicion ? 'readonly' : ''; ?>>
    <?php if (!empty($errores['username'])): ?>
        <p class="error" style="color: red;"><?php echo $errores['username']; ?></p>
    <?php endif; ?>

    <?php if ($modo_edicion): ?>
        <!-- Solo en la página "Mis Datos" se muestra la contraseña actual para validar cambios -->
        <label for="current_password">Contraseña Actual:</label>
        <input type="password" id="current_password" name="current_password" required>
        <?php if (!empty($errores['current_password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['current_password']; ?></p>
        <?php endif; ?>

        <!-- Campos de "Nueva Contraseña" y "Repetir Nueva Contraseña" para modificar la contraseña -->
        <label for="password">Nueva Contraseña:</label>
        <input type="password" id="password" name="password">
        <?php if (!empty($errores['password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['password']; ?></p>
        <?php endif; ?>

        <label for="confirm_password">Repetir Nueva Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <?php if (!empty($errores['confirm_password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['confirm_password']; ?></p>
        <?php endif; ?>
    <?php else: ?>
        <!-- Solo en la página de registro se muestra "Contraseña" y "Repetir Contraseña" -->
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <?php if (!empty($errores['password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['password']; ?></p>
        <?php endif; ?>

        <label for="confirm_password">Repetir Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <?php if (!empty($errores['confirm_password'])): ?>
            <p class="error" style="color: red;"><?php echo $errores['confirm_password']; ?></p>
        <?php endif; ?>
    <?php endif; ?>
    <button type="submit"><?php echo $modo_edicion ? 'Guardar Cambios' : 'Registrarse'; ?></button>
</form>
