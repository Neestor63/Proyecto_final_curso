<!DOCTYPE html>
<!-- Vista independiente del Pase QR de acceso -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Fortafyt - <?php echo $texts['mi_qr'] ?? 'Pase QR'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'dark-mode' : ''; ?>">
    <div class="contenedor-principal" style="max-width: 1000px; margin: auto;">
        <?php 
        $active_action = $_GET['action'] ?? 'qr';
        $cart_count = 0;
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $qty) {
                $cart_count += $qty;
            }
        }
        ?>
        <header class="main-header">
            <div class="header-left">
                <a href="index.php?action=dashboard" class="logo">FORTAFYT</a>
            </div>

            <nav class="app-nav">
                <a href="index.php?action=dashboard" class="nav-item<?php echo $active_action === 'dashboard' ? ' active' : ''; ?>">
                    <?php echo $texts['nav_clases'] ?? 'Clases'; ?>
                </a>
                <a href="index.php?action=qr" class="nav-item<?php echo $active_action === 'qr' ? ' active' : ''; ?>">
                    <?php echo $texts['nav_qr'] ?? 'Pase QR'; ?>
                </a>
                <a href="index.php?action=imc" class="nav-item<?php echo $active_action === 'imc' ? ' active' : ''; ?>">
                    <?php echo $texts['nav_imc'] ?? 'Calculadora IMC'; ?>
                </a>
                <a href="index.php?action=tienda" class="nav-item<?php echo $active_action === 'tienda' ? ' active' : ''; ?>">
                    <?php echo $texts['nav_tienda'] ?? 'Tienda'; ?>
                </a>
                <a href="index.php?action=carrito" class="nav-item<?php echo $active_action === 'carrito' ? ' active' : ''; ?>">
                    <?php echo $texts['nav_carrito'] ?? 'Carrito'; ?>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge-text">(<?php echo $cart_count; ?>)</span>
                    <?php endif; ?>
                </a>
            </nav>

            <div class="header-right">
                <div class="user-profile">
                    <div class="avatar-container" onclick="document.getElementById('input-foto').click();" title="<?php echo $texts['cambiar_foto'] ?? 'Cambiar Foto'; ?>">
                        <?php if(!empty($usuario_actual['foto_perfil']) && file_exists($usuario_actual['foto_perfil'])): ?>
                            <img src="<?php echo $usuario_actual['foto_perfil']; ?>" alt="Avatar">
                        <?php else: ?>
                            <div class="avatar-fallback"><?php echo strtoupper(substr($_SESSION['nombre'], 0, 1)); ?></div>
                        <?php endif; ?>
                        <div class="avatar-overlay">EDIT</div>
                    </div>
                    <span class="welcome-text"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                </div>

                <div class="preferencias">
                    <a href="index.php?action=cambiar_idioma&lang=es" class="btn-lang lang-es<?php echo ($idioma ?? 'es') === 'es' ? ' active' : ''; ?>" title="Español">ES</a>
                    <a href="index.php?action=cambiar_idioma&lang=en" class="btn-lang lang-en<?php echo ($idioma ?? 'es') === 'en' ? ' active' : ''; ?>" title="English">EN</a>
                    <button id="toggle-dark" class="btn-oscuro"><?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'LIGHT' : 'DARK'; ?></button>
                </div>
                
                <div class="acciones-usuario">
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <a href="index.php?action=admin" class="btn-admin">ADMIN</a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="btn-salir">LOGOUT</a>
                </div>
            </div>
        </header>

        <form id="form-foto" action="index.php?action=subir_foto" method="POST" enctype="multipart/form-data" style="display:none;">
            <input type="file" id="input-foto" name="foto_perfil" accept="image/*" onchange="document.getElementById('form-foto').submit();">
        </form>

        <div style="max-width: 500px; margin: 40px auto; text-align: center;">
            <section class="acceso-qr" style="background: var(--bg-section); border: 1px solid var(--border-light); padding: 40px 30px; border-radius: 24px; text-align: center; box-shadow: var(--shadow-main); backdrop-filter: var(--glass-blur);">
                <h2 style="margin-bottom: 10px; font-size: 1.8rem;"><?php echo $texts['mi_qr'] ?? 'Pase QR'; ?></h2>
                <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 30px; font-weight: 500;">
                    <?php echo $texts['qr_desc'] ?? 'Escanea este código en la entrada de las instalaciones.'; ?>
                </p>
                <div style="background: white; padding: 20px; border-radius: 20px; display: inline-block; box-shadow: 0 10px 30px rgba(0,0,0,0.15); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=fortafyt_user_<?php echo $_SESSION['usuario_id']; ?>" alt="QR de Acceso" style="display: block; border-radius: 10px;">
                </div>
            </section>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/preferencias.js"></script>
</body>

</html>
