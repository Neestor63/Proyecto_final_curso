<?php
// Modelo encargado de interactuar con la base de datos para la gestión de reservas y salas.
class ReservaModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Obtiene todas las salas registradas.
    public function obtenerSalas()
    {
        $query = "SELECT * FROM salas";
        $resultado = $this->conn->query($query);
        // Retornamos los datos como un array asociativo
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Registra una nueva reserva vinculada a un usuario y sala.
    public function guardarReserva($id_usuario, $id_sala, $fecha, $hora)
    {
        $query = "INSERT INTO reservas (id_usuario, id_sala, fecha, hora) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("iiss", $id_usuario, $id_sala, $fecha, $hora);

        return $stmt->execute();
    }

    // Consulta las reservas futuras asociadas a un ID de usuario.
    public function obtenerReservasUsuario($id_usuario)
    {
        $query = "SELECT r.id, r.fecha, s.nombre as sala_nombre 
              FROM reservas r 
              JOIN salas s ON r.id_sala = s.id 
              WHERE r.id_usuario = ? 
              ORDER BY r.fecha ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Elimina el registro de una reserva concreta.
    public function eliminarReserva($id_reserva, $id_usuario)
    {
        $query = "DELETE FROM reservas WHERE id = ? AND id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_reserva, $id_usuario);
        return $stmt->execute();
    }
}
