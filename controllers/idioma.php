<?php
// idioma.php
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    // Guardamos la cookie por 30 días
    setcookie('idioma', $lang, time() + (60*60*24*30), "/");
}
// Volvemos a la página donde estábamos
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();