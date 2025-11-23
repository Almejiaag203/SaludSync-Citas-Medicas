<?php
// app/models/Cita.php

class Cita {
    private $conn;
    private $tabla = "citas";
    
    // Propiedades del objeto
    public $id_cita;
    public $id_paciente;
    public $id_medico;
    public $id_tipo_consulta;
    public $fecha_hora_inicio;
    public $fecha_hora_fin;
    public $motivo_consulta;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " 
                  SET id_paciente=:id_paciente, id_medico=:id_medico, id_tipo_consulta=:id_tipo_consulta, 
                      fecha_hora_inicio=:inicio, fecha_hora_fin=:fin, motivo_consulta=:motivo, estado=:estado";
        
        $stmt = $this->conn->prepare($query);

        $this->id_paciente = htmlspecialchars(strip_tags($this->id_paciente));
        $this->id_medico = htmlspecialchars(strip_tags($this->id_medico));
        $this->id_tipo_consulta = htmlspecialchars(strip_tags($this->id_tipo_consulta));
        $this->fecha_hora_inicio = htmlspecialchars(strip_tags($this->fecha_hora_inicio));
        $this->fecha_hora_fin = htmlspecialchars(strip_tags($this->fecha_hora_fin));
        $this->motivo_consulta = htmlspecialchars(strip_tags($this->motivo_consulta));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        $stmt->bindParam(":id_paciente", $this->id_paciente);
        $stmt->bindParam(":id_medico", $this->id_medico);
        $stmt->bindParam(":id_tipo_consulta", $this->id_tipo_consulta);
        $stmt->bindParam(":inicio", $this->fecha_hora_inicio);
        $stmt->bindParam(":fin", $this->fecha_hora_fin);
        $stmt->bindParam(":motivo", $this->motivo_consulta);
        $stmt->bindParam(":estado", $this->estado);

        if ($stmt->execute()) { return true; }
        return false;
    }

    public function leerTodas() {
        $query = "SELECT 
                    c.id_cita, c.fecha_hora_inicio, c.estado, 
                    p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos, 
                    m.nombres AS medico_nombres, m.apellidos AS medico_apellidos,
                    e.nombre_especialidad
                  FROM citas c
                  LEFT JOIN pacientes p ON c.id_paciente = p.id_paciente
                  LEFT JOIN medicos m ON c.id_medico = m.id_medico
                  LEFT JOIN especialidades e ON m.id_especialidad = e.id_especialidad
                  ORDER BY c.fecha_hora_inicio DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id_cita) {
        $query = "SELECT c.*, 
                    p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos, up.correo_electronico AS paciente_correo,
                    m.nombres AS medico_nombres, m.apellidos AS medico_apellidos,
                    e.nombre_especialidad
                  FROM citas c
                  LEFT JOIN pacientes p ON c.id_paciente = p.id_paciente
                  LEFT JOIN usuarios up ON p.id_usuario = up.id_usuario
                  LEFT JOIN medicos m ON c.id_medico = m.id_medico
                  LEFT JOIN especialidades e ON m.id_especialidad = e.id_especialidad
                  WHERE c.id_cita = ?
                  LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_cita, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function cambiarEstado($id_cita, $nuevo_estado) {
        $query = "UPDATE " . $this->tabla . " SET estado = :estado WHERE id_cita = :id_cita";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $nuevo_estado);
        $stmt->bindParam(':id_cita', $id_cita);
        return $stmt->execute();
    }
    
    public function marcarComoCompletada($id_cita) {
        return $this->cambiarEstado($id_cita, 'Completada');
    }

    public function leerPorMedicoHoy($id_medico) {
        $query = "SELECT c.id_cita, c.fecha_hora_inicio, p.nombres, p.apellidos, c.id_paciente
                  FROM " . $this->tabla . " c
                  JOIN pacientes p ON c.id_paciente = p.id_paciente
                  WHERE c.id_medico = ? AND DATE(c.fecha_hora_inicio) = CURDATE()
                  ORDER BY c.fecha_hora_inicio ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_medico);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function leerProximasPorMedico($id_medico) {
        $query = "SELECT c.id_cita, c.fecha_hora_inicio, p.nombres, p.apellidos, c.id_paciente
                  FROM " . $this->tabla . " c
                  JOIN pacientes p ON c.id_paciente = p.id_paciente
                  WHERE c.id_medico = ? AND DATE(c.fecha_hora_inicio) > CURDATE()
                  ORDER BY c.fecha_hora_inicio ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_medico);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function actualizar() {
        $query = "UPDATE " . $this->tabla . " 
                  SET id_paciente = :id_paciente, 
                      id_medico = :id_medico, 
                      fecha_hora_inicio = :inicio, 
                      fecha_hora_fin = :fin, 
                      motivo_consulta = :motivo
                  WHERE id_cita = :id_cita";
        
        $stmt = $this->conn->prepare($query);
        // ... (limpieza y vinculación de parámetros)
        return $stmt->execute();
    }
}

