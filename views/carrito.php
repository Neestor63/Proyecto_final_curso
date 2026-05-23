<!DOCTYPE html>
<!-- Vista independiente del Carrito de Compras -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Fortafyt - <?php echo $texts['nav_carrito'] ?? 'Carrito'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'dark-mode' : ''; ?>">
    <div class="contenedor-principal" style="max-width: 1000px; margin: auto;">
        <?php 
        $active_action = $_GET['action'] ?? 'carrito';
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

        <?php if (isset($_SESSION['mensaje_compra'])): ?>
            <div class="alerta-exito">
                <?php echo $texts['compra_exito'] ?? '¡Compra realizada con éxito! Gracias por confiar en Fortafyt.'; ?>
            </div>
            <?php unset($_SESSION['mensaje_compra']); ?>
        <?php endif; ?>

        <div style="max-width: 700px; margin: auto;">
            <section class="carrito-seccion" style="background: var(--bg-section); border: 1px solid var(--border-light); padding: 30px; border-radius: 20px; box-shadow: var(--shadow-main); backdrop-filter: var(--glass-blur);">
                <h2 style="font-size: 1.8rem; margin-bottom: 20px; text-align: center;"><?php echo $texts['carrito'] ?? 'Tu Carrito'; ?></h2>
                
                <?php if (empty($_SESSION['carrito'])): ?>
                    <div style="text-align: center; padding: 40px 0;">
                        <p style="color:var(--text-muted); font-size: 1.1rem; font-weight: 500; margin-bottom: 20px;">
                            <?php echo $texts['carrito_vacio'] ?? 'El carrito está vacío.'; ?>
                        </p>
                        <a href="index.php?action=tienda" class="btn-reservar" style="display: inline-block; text-decoration: none; padding: 10px 24px; border-radius: 10px;">
                            <?php echo $texts['nav_tienda'] ?? 'Tienda'; ?>
                        </a>
                    </div>
                <?php else: ?>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-light);">
                                <th style="padding: 12px; font-weight: 800; color: var(--text-muted); text-align: left;"><?php echo $texts['tabla_actividad'] ?? 'Producto'; ?></th>
                                <th style="padding: 12px; text-align: center; font-weight: 800; color: var(--text-muted);"><?php echo $texts['cantidad'] ?? 'Cant'; ?></th>
                                <th style="padding: 12px; text-align: right; font-weight: 800; color: var(--text-muted);"><?php echo $texts['precio'] ?? 'Precio'; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($_SESSION['carrito'] as $id => $cantidad): 
                                $item_precio = $catalogo[$id]['precio'] * $cantidad;
                                $total += $item_precio;
                            ?>
                                <tr style="border-bottom: 1px solid var(--border-light);">
                                    <td style="padding: 16px 12px; text-align: left;">
                                        <strong style="color: var(--text-main);"><?php echo $texts[$id] ?? ucfirst($id); ?></strong>
                                    </td>
                                    <td style="padding: 16px 12px; text-align: center;">
                                        <div class="controles-cantidad" style="justify-content: center;">
                                            <a href="index.php?action=remove_cart&id=<?php echo $id; ?>" class="btn-qty">-</a>
                                            <span style="font-weight: 600; min-width: 25px; display: inline-block; text-align: center; color: var(--text-main);"><?php echo $cantidad; ?></span>
                                            <?php if ($cantidad >= 20): ?>
                                                <span class="btn-qty disabled" style="opacity: 0.3; cursor: not-allowed; pointer-events: none;" title="Límite máximo de 20 unidades alcanzado">+</span>
                                            <?php else: ?>
                                                <a href="index.php?action=add_cart&id=<?php echo $id; ?>" class="btn-qty">+</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td style="padding: 16px 12px; text-align: right; font-weight: 600; color: var(--text-main);">
                                        <?php echo number_format($item_precio, 2); ?>€
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div class="carrito-total" style="font-size: 1.2rem; padding: 20px 0; border-top: 2px dashed var(--border-light);">
                        <strong><?php echo $texts['total'] ?? 'Total:'; ?></strong>
                        <strong style="color:var(--primary-color); font-size:1.5rem;"><?php echo number_format($total, 2); ?>€</strong>
                    </div>
                    
                    <div class="carrito-acciones" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; gap: 20px;">
                        <a href="index.php?action=empty_cart" class="btn-cancelar" style="font-weight: 700; text-decoration: none; padding: 12px 24px; border-radius: 12px; transition: 0.3s;">
                            <?php echo $texts['btn_vaciar_carrito'] ?? 'Vaciar Carrito'; ?>
                        </a>
                        <a href="index.php?action=checkout" class="btn-full" style="text-align:center; text-decoration:none; padding: 14px 40px; border-radius: 12px; flex: 1;">
                            <?php echo $texts['btn_comprar'] ?? 'Comprar'; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
    <script src="assets/js/preferencias.js"></script>
</body>

</html>
