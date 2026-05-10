<?php
class UsuarioModel {
    private $conn;
    private $table = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar($nombre, $email, $password) {
        $pw_hash = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO " . $this->table . " (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $nombre, $email, $pw_hash);
        return $stmt->execute();
    }

    public function login($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function guardarIMC($id, $peso, $altura, $imc) {
        $query = "UPDATE " . $this->table . " SET peso = ?, altura = ?, imc = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("dddi", $peso, $altura, $imc, $id);
        return $stmt->execute();
    }

    public function guardarFotoPerfil($id, $ruta) {
        $query = "UPDATE " . $this->table . " SET foto_perfil = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $ruta, $id);
        return $stmt->execute();
    }
}
?>