<?php
// Script para el cambio de idioma rápido mediante cookie.
if (isset($_GET['lang'])) {
    setcookie('idioma', $_GET['lang'], time() + (60 * 60 * 24 * 30), "/");
}

$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: " . $url);
exit();