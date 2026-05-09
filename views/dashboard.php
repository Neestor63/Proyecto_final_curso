<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo $texts['titulo_dashboard'] ?? 'Gimnasio - Reservas'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="<?php echo (isset($_COOKIE['modo']) && $_COOKIE['modo'] === 'oscuro') ? 'dark-mode' : ''; ?>">
    <div class="contenedor-principal" style="max-width: 800px; margin: auto;">
        <header>
            <div class="header-title-group">
                <form id="form-foto" action="index.php?action=subir_foto" method="POST" enctype="multipart/form-data" style="display:none;">
                    <input type="file" id="input-foto" name="foto_perfil" accept="image/*" onchange="document.getElementById('form-foto').submit();">
                </form>
                <div class="avatar-container" onclick="document.getElementById('input-foto').click();" title="<?php echo $texts['cambiar_foto'] ?? 'Cambiar Foto'; ?>">
                    <?php if(!empty($usuario_actual['foto_perfil']) && file_exists($usuario_actual['foto_perfil'])): ?>
                        <img src="<?php echo $usuario_actual['foto_perfil']; ?>" alt="Avatar">
                    <?php else: ?>
                        <div style="width:100%; height:100%; background:var(--bg-section); display:flex; align-items:center; justify-content:center; font-size:1.8rem;">👤</div>
                    <?php endif; ?>
                    <div class="avatar-overlay">📷</div>
                </div>
                <h1 style="margin: 0;"><?php echo $texts['bienvenido_user'] ?? 'Bienvenido/a,'; ?> <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
            </div>
            <div class="header-actions">
                <div class="preferencias">
                    <a href="index.php?action=cambiar_idioma&lang=es" class="btn-lang" title="Español">🇪🇸</a>
                    <a href="index.php?action=cambiar_idioma&lang=en" class="btn-lang" title="English">🇬🇧</a>
                    <button id="toggle-dark" class="btn-oscuro"><?php echo $texts['modo_oscuro'] ?? 'Modo Oscuro'; ?></button>
                </div>
                
                <div class="acciones-usuario">
                    <a href="index.php?action=tienda" class="btn-reservar" style="padding: 8px 16px; border-radius: 12px; font-size: 0.95rem;">
                        <?php echo $texts['btn_tienda'] ?? 'TIENDA'; ?> 🛒
                    </a>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <a href="index.php?action=admin" class="btn-admin">
                            <?php echo $texts['panel_admin'] ?? 'PANEL ADMIN'; ?>
                        </a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="btn-salir"><?php echo $texts['salir'] ?? 'Cerrar Sesión'; ?></a>
                </div>
            </div>

        </header>

        <div class="dashboard-grid" style="display: flex; gap: 30px; flex-wrap: wrap; margin-bottom: 30px;">
            <section class="nueva-reserva" style="flex: 2; min-width: 300px; margin-bottom: 0;">
                <h2><?php echo $texts['reservar'] ?? 'Reservar una Clase'; ?></h2>
                <form action="index.php?action=reservar" method="POST" class="form-reserva">
                    <div class="campo">
                        <label><?php echo $texts['selecciona_clase'] ?? 'Selecciona la Clase:'; ?></label>
                        <select name="id_sala" required>
                            <option value=""><?php echo $texts['elige_actividad'] ?? '-- Elige una actividad --'; ?></option>
                            <?php if (!isset($salas) || !is_array($salas)) {
                                $salas = [];
                            } ?>
                            <?php foreach ($salas as $sala): ?>
                                <option value="<?php echo $sala['id']; ?>"><?php echo $sala['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo">
                        <label><?php echo $texts['fecha_ir'] ?? 'Fecha para ir:'; ?></label>
                        <input type="date" name="fecha" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <input type="hidden" name="hora" value="00:00">

                    <button type="submit" class="btn-reservar"><?php echo $texts['confirmar_plaza'] ?? 'Confirmar Mi Plaza'; ?></button>
                </form>
            </section>

            <!-- ACCESO QR -->
            <section class="acceso-qr" style="flex: 1; min-width: 250px; background: var(--bg-section); border: 1px solid var(--border-light); padding: 30px; border-radius: 16px; text-align: center;">
                <h2><?php echo $texts['mi_qr'] ?? 'Pase QR'; ?></h2>
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 15px; font-weight: bold;">
                    <?php echo $texts['qr_desc'] ?? 'Escanea este código en la entrada.'; ?>
                </p>
                <div style="background: white; padding: 10px; border-radius: 10px; display: inline-block; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=fortafyt_user_<?php echo $_SESSION['usuario_id']; ?>" alt="QR de Acceso" style="display: block;">
                </div>
        </div>

        <!-- CALCULADORA IMC -->
        <section class="calculadora-imc" style="background: var(--bg-section); border: 1px solid var(--border-light); padding: 30px; border-radius: 16px; text-align: center; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto;">
            <h2><?php echo $texts['calc_imc_titulo'] ?? 'Calculadora IMC'; ?></h2>
            <form action="index.php?action=guardar_imc" method="POST" style="margin-bottom: 15px;">
                <div style="display:flex; gap: 15px; justify-content: center; margin-bottom: 20px;">
                    <div style="text-align: left;">
                        <label style="font-size: 0.85rem; color: var(--text-muted);"><?php echo $texts['peso_kg'] ?? 'Peso (kg)'; ?></label>
                        <input type="number" step="0.1" name="peso" value="<?php echo htmlspecialchars($usuario_actual['peso'] ?? ''); ?>" required style="width: 100%; padding: 12px; border-radius: 10px;">
                    </div>
                    <div style="text-align: left;">
                        <label style="font-size: 0.85rem; color: var(--text-muted);"><?php echo $texts['altura_m'] ?? 'Altura (m)'; ?></label>
                        <input type="number" step="0.01" name="altura" value="<?php echo htmlspecialchars($usuario_actual['altura'] ?? ''); ?>" required style="width: 100%; padding: 12px; border-radius: 10px;">
                    </div>
                </div>
                <button type="submit" class="btn-reservar" style="padding: 12px 30px;"><?php echo $texts['btn_calcular_imc'] ?? 'Calcular y Guardar'; ?></button>
            </form>

            <?php if (isset($usuario_actual['imc']) && floatval($usuario_actual['imc']) > 0): ?>
                <div style="margin-top: 30px; padding-top: 20px; border-top: 2px dashed var(--border-light);">
                    <p style="font-size: 1rem; color: var(--text-muted); margin-bottom: 10px;"><?php echo $texts['tu_imc'] ?? 'Tu IMC actual:'; ?></p>
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
                    <div style="font-size: 3rem; font-weight: 800; color: <?php echo $color; ?>; line-height: 1;">
                        <?php echo $imc; ?>
                    </div>
                    <div style="background: <?php echo $color; ?>22; color: <?php echo $color; ?>; padding: 8px 20px; border-radius: 20px; display: inline-block; font-size: 1rem; font-weight: bold; margin-top: 10px;">
                        <?php echo $etiqueta; ?>
                    </div>
                </div>
            <?php endif; ?>
        </section>

        <hr>

        <section class="mis-reservas">
            <h2><?php echo $texts['mis_proximas_clases'] ?? 'Mis Próximas Clases'; ?></h2>
            <?php if (empty($misReservas)): ?>
                <p><?php echo $texts['no_reservas'] ?? 'Aún no tienes ninguna reserva. ¡Anímate a entrenar!'; ?></p>
            <?php else: ?>
                <table border="1" style="width: 100%; text-align: left;">
                    <thead>
                        <tr>
                            <th><?php echo $texts['tabla_actividad'] ?? 'Actividad'; ?></th>
                            <th><?php echo $texts['tabla_fecha'] ?? 'Fecha'; ?></th>
                            <th><?php echo $texts['tabla_accion'] ?? 'Acción'; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($misReservas as $res): ?>
                            <tr>
                                <td><?php echo $res['sala_nombre']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($res['fecha'])); ?></td>
                                <td>
                                    <a href="index.php?action=cancelar&id=<?php echo $res['id']; ?>"
                                        onclick="return confirm('<?php echo $texts['confirmar_cancelar'] ?? '¿Quieres cancelar esta clase?'; ?>')"
                                        style="color: red;"><?php echo $texts['btn_cancelar'] ?? 'Cancelar'; ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/preferencias.js"></script>
</body>

</html>