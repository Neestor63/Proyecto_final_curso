<?php
// Controlador para gestionar el cambio de idioma de la plataforma.
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    setcookie('idioma', $lang, time() + (60*60*24*30), "/");
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();