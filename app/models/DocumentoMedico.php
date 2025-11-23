<?php
// app/models/DocumentoMedico.php
class DocumentoMedico {
    private $conn;
    private $tabla = "documentos_medicos";

    // Propiedades
    public $id_paciente;
    public $titulo_documento;
    public $ruta_archivo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- NUEVO MÉTODO ---
    public function leerPorPaciente($id_paciente) {
        $query = "SELECT titulo_documento, ruta_archivo FROM " . $this->tabla . " WHERE id_paciente = ? ORDER BY fecha_subida DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- NUEVO MÉTODO ---
    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " SET id_paciente=?, titulo_documento=?, ruta_archivo=?";
        $stmt = $this->conn->prepare($query);

        // Limpieza de datos
        $this->id_paciente = htmlspecialchars(strip_tags($this->id_paciente));
        $this->titulo_documento = htmlspecialchars(strip_tags($this->titulo_documento));
        $this->ruta_archivo = htmlspecialchars(strip_tags($this->ruta_archivo));

        // Vincular
        $stmt->bindParam(1, $this->id_paciente);
        $stmt->bindParam(2, $this->titulo_documento);
        $stmt->bindParam(3, $this->ruta_archivo);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>