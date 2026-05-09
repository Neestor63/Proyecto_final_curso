<?php
require_once 'models/usuarioModel.php';

class UsuarioController
{
    private $model;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->model = new UsuarioModel($db);
    }

    public function crearUsuario($nombre, $email, $password)
    {
        if (!empty($nombre) && !empty($email) && !empty($password)) {
            return $this->model->registrar($nombre, $email, $password);
        }
        return false;
    }

    public function iniciarSesion($email, $password)
    {
        $usuario = $this->model->login($email);
        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];
            return true;
        }
        return false;
    }

    public function listarTodos()
    {
        $sql = "SELECT id, nombre, email, rol FROM usuarios";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminarUsuario($id)
    {
        if ($id == $_SESSION['usuario_id']) return false;

        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function obtenerUsuario($id) {
        return $this->model->obtenerPorId($id);
    }

    public function guardarIMC($id, $peso, $altura) {
        if ($peso > 0 && $altura > 0) {
            if ($altura > 3) {
                $altura = $altura / 100;
            }
            $imc = $peso / ($altura * $altura);
            $imc = round($imc, 2);
            return $this->model->guardarIMC($id, $peso, $altura, $imc);
        }
        return false;
    }

    public function subirFoto($id, $fileArray) {
        if (isset($fileArray) && $fileArray['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $fileArray['tmp_name'];
            $fileName = $fileArray['name'];
            $fileSize = $fileArray['size'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'webp');
            if (in_array($fileExtension, $allowedfileExtensions) && $fileSize < 5000000) {
                $uploadFileDir = 'assets/uploads/perfiles/';
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $this->model->guardarFotoPerfil($id, $dest_path);
                    return true;
                }
            }
        }
        return false;
    }
}
