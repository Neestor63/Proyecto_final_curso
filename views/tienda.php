<!DOCTYPE html>
<!-- Vista de la tienda online con catálogo de productos y carrito de compras -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo $texts['tienda_titulo'] ?? 'Tienda Fortafyt'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'dark-mode' : ''; ?>">
    <div class="contenedor-principal">
        <header>
            <h1><?php echo $texts['tienda_titulo'] ?? 'Tienda Fortafyt'; ?></h1>
            <div class="header-actions">
                <div class="preferencias">
                    <a href="index.php?action=cambiar_idioma&lang=es" class="btn-lang" title="Español">🇪🇸</a>
                    <a href="index.php?action=cambiar_idioma&lang=en" class="btn-lang" title="English">🇬🇧</a>
                    <button id="toggle-dark" class="btn-oscuro"><?php echo $texts['modo_oscuro'] ?? 'Modo Oscuro'; ?></button>
                </div>
                
                <div class="acciones-usuario">
                    <a href="index.php?action=dashboard" class="btn-admin"><?php echo $texts['volver_inicio'] ?? 'Volver al Inicio'; ?></a>
                    <a href="index.php?action=logout" class="btn-salir"><?php echo $texts['salir'] ?? 'Cerrar Sesión'; ?></a>
                </div>
            </div>
        </header>

        <?php if (isset($_SESSION['mensaje_compra'])): ?>
            <div class="alerta-exito">
                <?php echo $texts['compra_exito'] ?? '¡Compra realizada con éxito!'; ?>
            </div>
            <?php unset($_SESSION['mensaje_compra']); ?>
        <?php endif; ?>

        <div class="tienda-layout">
            <!-- CATÁLOGO -->
            <section class="catalogo-productos">
                <h2><?php echo $texts['productos'] ?? 'Productos Disponibles'; ?></h2>
                <div class="grid-productos">
                    <?php foreach ($catalogo as $prod): ?>
                        <div class="tarjeta-producto">
                            <div class="producto-imagen-wrapper">
                                <img src="assets/img/productos/<?php echo $prod['id']; ?>.png" alt="<?php echo $prod['id']; ?>" class="producto-img">
                            </div>
                            <h3><?php echo $texts[$prod['id']] ?? ucfirst($prod['id']); ?></h3>
                            <p class="desc"><?php echo $texts[$prod['id'].'_desc'] ?? ''; ?></p>
                            <p class="precio"><?php echo $texts['precio'] ?? 'Precio:'; ?> <span><?php echo number_format($prod['precio'], 2); ?>€</span></p>
                            <a href="index.php?action=add_cart&id=<?php echo $prod['id']; ?>" class="btn-reservar" style="width:100%; margin-top:10px; text-decoration:none;">
                                <?php echo $texts['btn_anadir_carrito'] ?? 'Añadir al Carrito'; ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- CARRITO -->
            <section class="carrito-seccion">
                <h2><?php echo $texts['carrito'] ?? 'Tu Carrito'; ?> 🛒</h2>
                <aside class="carrito-lateral">
                    <?php if (empty($_SESSION['carrito'])): ?>
                    <p style="color:var(--text-muted); text-align:center; padding: 20px 0;">
                        <?php echo $texts['carrito_vacio'] ?? 'El carrito está vacío.'; ?>
                    </p>
                <?php else: ?>
                    <div class="lista-carrito">
                        <?php 
                        $total = 0;
                        foreach ($_SESSION['carrito'] as $id => $cantidad): 
                            $item_precio = $catalogo[$id]['precio'] * $cantidad;
                            $total += $item_precio;
                        ?>
                            <div class="item-carrito">
                                <div>
                                    <strong><?php echo $texts[$id] ?? ucfirst($id); ?></strong>
                                    <div class="controles-cantidad">
                                        <a href="index.php?action=remove_cart&id=<?php echo $id; ?>" class="btn-qty">-</a>
                                        <span><?php echo $cantidad; ?></span>
                                        <a href="index.php?action=add_cart&id=<?php echo $id; ?>" class="btn-qty">+</a>
                                    </div>
                                </div>
                                <div><?php echo number_format($item_precio, 2); ?>€</div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="carrito-total">
                        <strong><?php echo $texts['total'] ?? 'Total:'; ?></strong>
                        <strong style="color:var(--primary-color); font-size:1.2rem;"><?php echo number_format($total, 2); ?>€</strong>
                    </div>
                    <div class="carrito-acciones">
                        <a href="index.php?action=empty_cart" class="btn-cancelar" style="text-align:center; display:block; margin-bottom:10px;">
                            <?php echo $texts['btn_vaciar_carrito'] ?? 'Vaciar Carrito'; ?>
                        </a>
                        <a href="index.php?action=checkout" class="btn-full" style="text-align:center; text-decoration:none;">
                            <?php echo $texts['btn_comprar'] ?? 'Comprar'; ?>
                        </a>
                    </div>
                <?php endif; ?>
                </aside>
            </section>
        </div>
    </div>
    <script src="assets/js/preferencias.js"></script>
</body>
</html>
