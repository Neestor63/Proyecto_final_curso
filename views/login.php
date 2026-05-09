<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Fortafyt - <?php echo $texts['login_titulo'] ?? 'Login'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'dark-mode' : ''; ?>">
    <div class="auth-container">
        <h1 style="font-size: 3rem; margin-bottom: 5px;">FORTAFYT</h1>
        <h2 style="font-size: 1.2rem; font-weight: 400; color: var(--text-muted); margin-bottom: 30px;">
            <?php echo $texts['bienvenido'] ?? 'Bienvenido a Fortafyt'; ?>
        </h2>
        <?php if (isset($error)) echo "<p style='color:var(--accent-red); margin-bottom:15px;'>$error</p>"; ?>

        <form action="index.php?action=login" method="POST">
            <input type="email" name="email" placeholder="<?php echo $texts['email_placeholder'] ?? 'Correo electrónico'; ?>" required>
            <!-- Dentro del formulario, debajo del input de password -->
            <input type="password" name="password" id="pass-input" placeholder="<?php echo $texts['password_placeholder'] ?? 'Contraseña'; ?>" required>
            <div id="pass-mensaje" style="font-size: 0.8rem; margin-top: -15px; margin-bottom: 15px; text-align: left; height: 15px;"></div>
            <button type="submit" class="btn-reservar btn-full"><?php echo $texts['btn_entrar'] ?? 'ENTRAR'; ?></button>
        </form>

        <a href="index.php?action=registrar" class="auth-link">
            <?php echo $texts['no_cuenta'] ?? '¿No tienes cuenta?'; ?> <span><?php echo $texts['registrate_aqui'] ?? 'Regístrate aquí'; ?></span>
        </a>
    </div>
    <script src="assets/js/validaciones.js"></script>
    <script src="assets/js/preferencias.js"></script>
</body>

</html>