<?php
// app/models/HistorialClinico.php
class HistorialClinico {
    private $conn;
    private $tabla = "historial_clinico";

    // Propiedades
    public $id_historial;
    public $id_cita;
    public $id_paciente;
    public $id_medico;
    public $analisis_diagnostico;
    public $plan_tratamiento;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- NUEVO MÉTODO ---
    // Crea una nueva entrada en el historial y devuelve su ID
    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " 
                  SET id_cita=:id_cita, id_paciente=:id_paciente, id_medico=:id_medico, 
                      analisis_diagnostico=:analisis, plan_tratamiento=:plan";
        
        $stmt = $this->conn->prepare($query);

        // Limpieza de datos
        $this->id_cita = htmlspecialchars(strip_tags($this->id_cita));
        $this->id_paciente = htmlspecialchars(strip_tags($this->id_paciente));
        $this->id_medico = htmlspecialchars(strip_tags($this->id_medico));
        $this->analisis_diagnostico = htmlspecialchars(strip_tags($this->analisis_diagnostico));
        $this->plan_tratamiento = htmlspecialchars(strip_tags($this->plan_tratamiento));

        // Vincular parámetros
        $stmt->bindParam(":id_cita", $this->id_cita);
        $stmt->bindParam(":id_paciente", $this->id_paciente);
        $stmt->bindParam(":id_medico", $this->id_medico);
        $stmt->bindParam(":analisis", $this->analisis_diagnostico);
        $stmt->bindParam(":plan", $this->plan_tratamiento);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Devolvemos el ID de la nota recién creada
        }
        return false;
    }
    
    // Método para leer el historial de un paciente
    public function leerPorPaciente($id_paciente) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_paciente = ? ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>