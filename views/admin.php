<!DOCTYPE html>
<!-- Vista del panel de administración para gestionar salas y usuarios -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo $texts['titulo_admin_page'] ?? 'Panel de Administración - Fortafyt'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'dark-mode' : ''; ?>">
    <div class="contenedor-principal">
        <header>
            <h1><?php echo $texts['titulo_admin'] ?? 'Panel de Control (ADMIN)'; ?></h1>
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

        <!-- GESTIÓN DE SALAS -->
        <section class="nueva-reserva" style="margin-top: 30px;">
            <h2><?php echo $texts['gestionar_salas'] ?? 'Gestionar Salas / Clases'; ?></h2>
            <form action="index.php?action=crear_sala" method="POST" class="form-reserva" style="display: flex; gap: 10px;">
                <input type="text" name="nombre_sala" placeholder="<?php echo $texts['nombre_sala_placeholder'] ?? 'Nombre de la nueva sala (ej. Yoga)'; ?>" required style="flex: 1;">
                <button type="submit" class="btn-reservar"><?php echo $texts['btn_anadir_sala'] ?? 'Añadir Sala'; ?></button>
            </form>

            <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid #444;">
                        <th><?php echo $texts['tabla_nombre_sala'] ?? 'Nombre de la Sala'; ?></th>
                        <th><?php echo $texts['tabla_accion'] ?? 'Acción'; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($todasLasSalas)): ?>
                        <?php foreach ($todasLasSalas as $s): ?>
                            <tr style="border-bottom: 1px solid #333;">
                                <td style="padding: 10px;"><?php echo htmlspecialchars($s['nombre']); ?></td>
                                <td>
                                    <a href="index.php?action=borrar_sala&id=<?php echo $s['id']; ?>"
                                        onclick="return confirm('<?php echo $texts['confirmar_borrar_sala'] ?? '¿Seguro que quieres borrar esta sala?'; ?>')"
                                        style="color: var(--accent-red); text-decoration: none;"><?php echo $texts['btn_eliminar'] ?? 'Eliminar'; ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2"><?php echo $texts['no_salas'] ?? 'No hay salas creadas.'; ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- GESTIÓN DE USUARIOS -->
        <section class="mis-reservas" style="margin-top: 40px;">
            <h2><?php echo $texts['usuarios_sistema'] ?? 'Usuarios del Sistema'; ?></h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid #444;">
                        <th><?php echo $texts['tabla_nombre'] ?? 'Nombre'; ?></th>
                        <th><?php echo $texts['tabla_email'] ?? 'Email'; ?></th>
                        <th><?php echo $texts['tabla_rol'] ?? 'Rol'; ?></th>
                        <th><?php echo $texts['tabla_accion'] ?? 'Acción'; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($todosLosUsuarios)): ?>
                        <?php foreach ($todosLosUsuarios as $u): ?>
                            <tr style="border-bottom: 1px solid #333;">
                                <td style="padding: 10px;"><?php echo htmlspecialchars($u['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><span class="tag-rol"><?php echo $u['rol']; ?></span></td>
                                <td>
                                    <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                                        <a href="index.php?action=borrar_usuario&id=<?php echo $u['id']; ?>"
                                            onclick="return confirm('<?php echo $texts['confirmar_expulsar'] ?? '¿Expulsar a este usuario?'; ?>')"
                                            style="color: var(--accent-red); text-decoration: none;"><?php echo $texts['btn_eliminar'] ?? 'Eliminar'; ?></a>
                                    <?php else: ?>
                                        <span style="color: #666;"><?php echo $texts['tu_usuario'] ?? '(Tú)'; ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
    <script src="assets/js/preferencias.js"></script>
</body>

</html>