<?php
require_once 'models/reservaModel.php';

class ReservaController
{
    private $model;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->model = new ReservaModel($db);
    }

    public function listarSalas()
    {
        return $this->model->obtenerSalas();
    }

    public function hacerReserva($id_usuario, $id_sala, $fecha, $hora)
    {
        if (!empty($id_sala) && !empty($fecha) && !empty($hora)) {
            return $this->model->guardarReserva($id_usuario, $id_sala, $fecha, $hora);
        }
        return false;
    }

    public function listarMisReservas($id_usuario)
    {
        return $this->model->obtenerReservasUsuario($id_usuario);
    }

    public function cancelarReserva($id_reserva, $id_usuario)
    {
        return $this->model->eliminarReserva($id_reserva, $id_usuario);
    }

    public function crearNuevaSala($nombre) {
        if (empty($nombre)) {
            return false;
        }

        $sql = "INSERT INTO salas (nombre) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bind_param("s", $nombre);
        
        return $stmt->execute();
    }

    public function borrarSala($id) {
        if (empty($id)) {
            return false;
        }

        $sql = "DELETE FROM salas WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        // "i" porque el ID es un entero
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
}