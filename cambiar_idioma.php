<?php
// cambiar_idioma.php
if (isset($_GET['lang'])) {
    // Guardamos la cookie del idioma por 30 días
    setcookie('idioma', $_GET['lang'], time() + (60 * 60 * 24 * 30), "/");
}

// Redirigir a la página anterior o al index si no hay anterior
$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: " . $url);
exit();