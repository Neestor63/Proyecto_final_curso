<?php
// Archivo principal de enrutamiento y punto de entrada de la aplicación.
session_start();

$idioma = $_COOKIE['idioma'] ?? 'es';
$ruta_json = "languages/$idioma.json";

if (file_exists($ruta_json)) {
    $json_data = file_get_contents($ruta_json);
    $texts = json_decode($json_data, true);
} else {
    $texts = ['bienvenido' => 'Fortafyt', 'reservar' => 'Reservar'];
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config/database.php';
require_once 'controllers/usuarioController.php';
require_once 'controllers/reservaController.php';

$database = new Database();
$db = $database->getConnection();

$usuarioCtrl = new UsuarioController($db);
$reservaCtrl = new ReservaController($db);

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'cambiar_idioma':
        if (isset($_GET['lang'])) {
            setcookie('idioma', $_GET['lang'], time() + (86400 * 30), "/");
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?action=dashboard';
        header("Location: " . $referer);
        exit();
        break;

    case 'registrar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $res = $usuarioCtrl->crearUsuario($_POST['nombre'], $_POST['email'], $_POST['password']);
            if ($res === 'exists') {
                $error_registro = $texts['error_usuario_existe'] ?? 'Ya hay un usuario en la base de datos.';
            } elseif ($res === 'short_password') {
                $error_registro = $texts['error_contrasena_corta'] ?? 'La contraseña debe tener al menos 6 caracteres.';
            } elseif ($res) {
                header("Location: index.php?action=login");
                exit();
            } else {
                $error_registro = 'Error al crear la cuenta.';
            }
        }
        include 'views/registro.php';
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($usuarioCtrl->iniciarSesion($_POST['email'], $_POST['password'])) {
                header("Location: index.php?action=dashboard");
                exit();
            } else {
                $error = "Correo o contraseña incorrectos";
            }
        }
        include 'views/login.php';
        break;

    case 'dashboard':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $usuario_actual = $usuarioCtrl->obtenerUsuario($_SESSION['usuario_id']);
        $salas = $reservaCtrl->listarSalas();
        $misReservas = $reservaCtrl->listarMisReservas($_SESSION['usuario_id']);
        include 'views/dashboard.php';
        break;

    case 'qr':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $usuario_actual = $usuarioCtrl->obtenerUsuario($_SESSION['usuario_id']);
        include 'views/qr.php';
        break;

    case 'imc':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $usuario_actual = $usuarioCtrl->obtenerUsuario($_SESSION['usuario_id']);
        include 'views/imc.php';
        break;

    case 'guardar_imc':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
            $peso = floatval($_POST['peso'] ?? 0);
            $altura = floatval($_POST['altura'] ?? 0);
            $usuarioCtrl->guardarIMC($_SESSION['usuario_id'], $peso, $altura);
        }
        header("Location: index.php?action=imc");
        exit();
        break;

    case 'subir_foto':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
            if (isset($_FILES['foto_perfil'])) {
                $usuarioCtrl->subirFoto($_SESSION['usuario_id'], $_FILES['foto_perfil']);
            }
        }
        header("Location: index.php?action=dashboard");
        exit();
        break;

    case 'reservar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
            $reservaCtrl->hacerReserva($_SESSION['usuario_id'], $_POST['id_sala'], $_POST['fecha'], $_POST['hora']);
        }
        header("Location: index.php?action=dashboard");
        exit();
        break;

    case 'cancelar':
        if (isset($_GET['id']) && isset($_SESSION['usuario_id'])) {
            $reservaCtrl->cancelarReserva($_GET['id'], $_SESSION['usuario_id']);
        }
        header("Location: index.php?action=dashboard");
        exit();
        break;

    case 'tienda':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $catalogo = [
            'creatina' => ['id' => 'creatina', 'precio' => 19.99],
            'proteina' => ['id' => 'proteina', 'precio' => 29.99],
            'preentreno' => ['id' => 'preentreno', 'precio' => 24.99],
            'bcaas' => ['id' => 'bcaas', 'precio' => 15.99],
            'vitaminas' => ['id' => 'vitaminas', 'precio' => 12.99],
            'barritas' => ['id' => 'barritas', 'precio' => 9.99]
        ];
        
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        
        include 'views/tienda.php';
        break;

    case 'carrito':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $catalogo = [
            'creatina' => ['id' => 'creatina', 'precio' => 19.99],
            'proteina' => ['id' => 'proteina', 'precio' => 29.99],
            'preentreno' => ['id' => 'preentreno', 'precio' => 24.99],
            'bcaas' => ['id' => 'bcaas', 'precio' => 15.99],
            'vitaminas' => ['id' => 'vitaminas', 'precio' => 12.99],
            'barritas' => ['id' => 'barritas', 'precio' => 9.99]
        ];
        
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        
        include 'views/carrito.php';
        break;

    case 'add_cart':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $id_prod = $_GET['id'] ?? '';
        if ($id_prod) {
            if (!isset($_SESSION['carrito'][$id_prod])) {
                $_SESSION['carrito'][$id_prod] = 0;
            }
            if ($_SESSION['carrito'][$id_prod] < 20) {
                $_SESSION['carrito'][$id_prod]++;
            }
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?action=tienda';
        header("Location: " . $referer);
        exit();
        break;

    case 'remove_cart':
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $id_prod = $_GET['id'] ?? '';
        if ($id_prod && isset($_SESSION['carrito'][$id_prod])) {
            $_SESSION['carrito'][$id_prod]--;
            if ($_SESSION['carrito'][$id_prod] <= 0) {
                unset($_SESSION['carrito'][$id_prod]);
            }
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?action=carrito';
        header("Location: " . $referer);
        exit();
        break;

    case 'empty_cart':
        $_SESSION['carrito'] = [];
        header("Location: index.php?action=carrito");
        exit();
        break;

    case 'checkout':
        $_SESSION['carrito'] = [];
        $_SESSION['mensaje_compra'] = true;
        header("Location: index.php?action=carrito");
        exit();
        break;

    case 'admin':
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header("Location: index.php?action=dashboard");
            exit();
        }
        $todasLasSalas = $reservaCtrl->listarSalas();
        $todosLosUsuarios = $usuarioCtrl->listarTodos();
        include 'views/admin.php';
        break;

    case 'crear_sala':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
            $reservaCtrl->crearNuevaSala($_POST['nombre_sala']);
        }
        header("Location: index.php?action=admin");
        break;

    case 'borrar_sala':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
            $reservaCtrl->borrarSala($_GET['id']);
        }
        header("Location: index.php?action=admin");
        break;

    case 'borrar_usuario':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
            $usuarioCtrl->eliminarUsuario($_GET['id']);
        }
        header("Location: index.php?action=admin");
        break;

    case 'logout':
        session_destroy();
        header("Location: index.php?action=login");
        exit();
        break;

    default:
        include 'views/login.php';
        break;
}