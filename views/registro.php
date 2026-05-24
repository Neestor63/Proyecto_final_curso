<!DOCTYPE html>
<!-- Vista de la página de registro de nuevos usuarios -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Fortafyt - <?php echo $texts['registro_titulo'] ?? 'Registro'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'dark-mode' : ''; ?>">
    <div class="auth-container">
        <h1 style="font-size: 3rem; margin-bottom: 5px;">FORTAFYT</h1>
        <h2 style="font-size: 1.2rem; font-weight: 400; color: var(--text-muted); margin-bottom: 30px;">
            <?php echo $texts['registro_titulo'] ?? 'REGISTRO'; ?>
        </h2>
        <?php if (isset($error_registro)): ?>
            <p style="color: var(--accent-red); margin-bottom: 15px; font-weight: 600;"><?php echo htmlspecialchars($error_registro); ?></p>
        <?php endif; ?>
        <form action="index.php?action=registrar" method="POST">
            <input type="text" name="nombre" placeholder="<?php echo $texts['nombre_placeholder'] ?? 'Nombre completo'; ?>" required>
            <input type="email" name="email" placeholder="<?php echo $texts['email_placeholder'] ?? 'Correo electrónico'; ?>" required>
            <input type="password" name="password" id="pass-input" placeholder="<?php echo $texts['password_placeholder'] ?? 'Contraseña'; ?>" minlength="6" required>
            <div id="pass-mensaje" style="font-size: 0.8rem; margin-top: -15px; margin-bottom: 15px; text-align: left; height: 15px;"></div>
            <button type="submit" class="btn-reservar btn-full"><?php echo $texts['btn_crear_cuenta'] ?? 'CREAR CUENTA'; ?></button>
        </form>

        <a href="index.php?action=login" class="auth-link">
            <?php echo $texts['ya_socio'] ?? '¿Ya eres socio?'; ?> <span><?php echo $texts['inicia_sesion'] ?? 'Inicia sesión'; ?></span>
        </a>
    </div>
    <script src="assets/js/validaciones.js"></script>
    <script src="assets/js/preferencias.js"></script>
</body>

</html>