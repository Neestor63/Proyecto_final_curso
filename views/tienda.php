<!DOCTYPE html>
<!-- Vista de la tienda online con catálogo de productos y carrito de compras -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo $texts['tienda_titulo'] ?? 'Tienda Fortafyt'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'dark-mode' : ''; ?>">
    <div class="contenedor-principal" style="max-width: 1000px; margin: auto;">
        <?php 
        $active_action = $_GET['action'] ?? 'tienda';
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

        <div class="tienda-layout" style="display: block;">
            <!-- CATÁLOGO -->
            <section class="catalogo-productos" style="width: 100%;">
                <h2><?php echo $texts['productos'] ?? 'Productos Disponibles'; ?></h2>
                <div class="grid-productos" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px;">
                    <?php foreach ($catalogo as $prod): ?>
                        <div class="tarjeta-producto">
                            <div class="producto-imagen-wrapper" style="height: 180px;">
                                <img src="assets/img/productos/<?php echo $prod['id']; ?>.png" alt="<?php echo $prod['id']; ?>" class="producto-img">
                            </div>
                            <h3><?php echo $texts[$prod['id']] ?? ucfirst($prod['id']); ?></h3>
                            <p class="desc"><?php echo $texts[$prod['id'].'_desc'] ?? ''; ?></p>
                            <p class="precio"><?php echo $texts['precio'] ?? 'Precio:'; ?> <span><?php echo number_format($prod['precio'], 2); ?>€</span></p>
                            <?php 
                            $cant_actual = $_SESSION['carrito'][$prod['id']] ?? 0;
                            if ($cant_actual >= 20): 
                            ?>
                                <span class="btn-reservar disabled" style="width:100%; margin-top:10px; text-decoration:none; border-radius: 10px; padding: 12px 20px; text-align: center; display: inline-block; background: rgba(0,0,0,0.05) !important; color: var(--text-muted) !important; border: 1px solid var(--border-light) !important; cursor: not-allowed; pointer-events: none;">
                                    Límite Alcanzado (20)
                                </span>
                            <?php else: ?>
                                <a href="index.php?action=add_cart&id=<?php echo $prod['id']; ?>" class="btn-reservar" style="width:100%; margin-top:10px; text-decoration:none; border-radius: 10px; padding: 12px 20px; text-align: center; display: inline-block;">
                                    <?php echo $texts['btn_anadir_carrito'] ?? 'Añadir al Carrito'; ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </div>
    <script src="assets/js/preferencias.js"></script>
</body>
</html>
