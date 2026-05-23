<!DOCTYPE html>
<!-- Vista independiente de la Calculadora de IMC -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Fortafyt - <?php echo $texts['calc_imc_titulo'] ?? 'Calculadora IMC'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'dark-mode' : ''; ?>">
    <div class="contenedor-principal" style="max-width: 1000px; margin: auto;">
        <?php 
        $active_action = $_GET['action'] ?? 'imc';
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

        <div style="max-width: 600px; margin: 20px auto;">
            <!-- CALCULADORA IMC -->
            <section class="calculadora-imc" style="background: var(--bg-section); border: 1px solid var(--border-light); padding: 40px 30px; border-radius: 24px; text-align: center; box-shadow: var(--shadow-main); backdrop-filter: var(--glass-blur);">
                <h2 style="margin-bottom: 20px; font-size: 1.8rem;"><?php echo $texts['calc_imc_titulo'] ?? 'Calculadora IMC'; ?></h2>
                <form action="index.php?action=guardar_imc" method="POST" style="margin-bottom: 15px;">
                    <div style="display:flex; gap: 20px; justify-content: center; margin-bottom: 30px;">
                        <div style="text-align: left; flex: 1;">
                            <label style="font-size: 0.85rem; color: var(--text-muted);"><?php echo $texts['peso_kg'] ?? 'Peso (kg)'; ?></label>
                            <input type="number" step="0.1" name="peso" min="1" max="100" value="<?php echo htmlspecialchars($usuario_actual['peso'] ?? ''); ?>" required style="width: 100%; padding: 14px 16px; border-radius: 12px;">
                        </div>
                        <div style="text-align: left; flex: 1;">
                            <label style="font-size: 0.85rem; color: var(--text-muted);"><?php echo $texts['altura_m'] ?? 'Altura (m)'; ?></label>
                            <input type="number" step="0.01" name="altura" min="0.5" max="2.5" value="<?php echo htmlspecialchars($usuario_actual['altura'] ?? ''); ?>" required style="width: 100%; padding: 14px 16px; border-radius: 12px;">
                        </div>
                    </div>
                    <button type="submit" class="btn-reservar" style="padding: 14px 40px; font-size: 0.95rem; font-weight: 700; border-radius: 12px;"><?php echo $texts['btn_calcular_imc'] ?? 'Calcular y Guardar'; ?></button>
                </form>

                <?php if (isset($usuario_actual['imc']) && floatval($usuario_actual['imc']) > 0): ?>
                    <div style="margin-top: 35px; padding-top: 25px; border-top: 2px dashed var(--border-light);">
                        <p style="font-size: 1.05rem; color: var(--text-muted); margin-bottom: 12px; font-weight: 500;"><?php echo $texts['tu_imc'] ?? 'Tu IMC actual:'; ?></p>
                        <?php 
                            $imc = floatval($usuario_actual['imc']);
                            $color = '#2ecc71'; // Saludable
                            $etiqueta = $texts['imc_saludable'] ?? 'Saludable';
                            
                            if ($imc < 18.5) {
                                $color = '#f1c40f'; // Bajo peso
                                $etiqueta = $texts['imc_bajo'] ?? 'Bajo Peso';
                            } elseif ($imc >= 25 && $imc < 30) {
                                $color = '#e67e22'; // Sobrepeso
                                $etiqueta = $texts['imc_sobrepeso'] ?? 'Sobrepeso';
                            } elseif ($imc >= 30) {
                                $color = '#e74c3c'; // Obesidad
                                $etiqueta = $texts['imc_obesidad'] ?? 'Obesidad';
                            }
                        ?>
                        <div style="font-size: 3.5rem; font-weight: 800; color: <?php echo $color; ?>; line-height: 1; text-shadow: 0 4px 12px <?php echo $color; ?>22;">
                            <?php echo $imc; ?>
                        </div>
                        <div style="background: <?php echo $color; ?>22; color: <?php echo $color; ?>; padding: 10px 24px; border-radius: 50px; display: inline-block; font-size: 1.05rem; font-weight: 700; margin-top: 15px; box-shadow: 0 4px 15px <?php echo $color; ?>11;">
                            <?php echo $etiqueta; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/preferencias.js"></script>
</body>

</html>
