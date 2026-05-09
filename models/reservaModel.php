<?php
class ReservaModel
{
    private $conn;

    // Al crear el modelo, recibimos la conexión a la base de datos
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // 1. Obtener todas las salas (para el desplegable del formulario)
    public function obtenerSalas()
    {
        $query = "SELECT * FROM salas";
        $resultado = $this->conn->query($query);
        // Retornamos los datos como un array asociativo
        return $resultado->fetch_all(MYSQLI_ASSOC);
        echo "Paso por el Modelo";
    }

    // 2. Guardar una nueva reserva en la tabla 'reservas'
    public function guardarReserva($id_usuario, $id_sala, $fecha, $hora)
    {
        $query = "INSERT INTO reservas (id_usuario, id_sala, fecha, hora) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // "iiss" significa: entero, entero, string, string (los tipos de datos)
        $stmt->bind_param("iiss", $id_usuario, $id_sala, $fecha, $hora);

        return $stmt->execute();
    }

    public function obtenerReservasUsuario($id_usuario)
    {
        // Añadimos r.id para que PHP sepa qué reserva borrar luego
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

    // Aprovecha para añadir la función de eliminar si no la tenías:
    public function eliminarReserva($id_reserva, $id_usuario)
    {
        $query = "DELETE FROM reservas WHERE id = ? AND id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_reserva, $id_usuario);
        return $stmt->execute();
    }
}
