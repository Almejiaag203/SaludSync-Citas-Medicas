<?php
// app/models/Antecedente.php

class Antecedente {
    private $conn;
    private $tabla = "antecedentes_paciente";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function buscarPorPaciente($id_paciente) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_paciente = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Guarda (Crea o Actualiza) los antecedentes de un paciente.
     */
    public function guardar($id_paciente, $alergias, $medicamentos, $cronicas) {
        // Primero, verifica si ya existe un registro
        $stmt_check = $this->conn->prepare("SELECT id_antecedente FROM " . $this->tabla . " WHERE id_paciente = ?");
        $stmt_check->bindParam(1, $id_paciente);
        $stmt_check->execute();
        $existe = $stmt_check->fetch();

        if ($existe) {
            // Si existe, se actualiza (UPDATE)
            $query = "UPDATE " . $this->tabla . " SET alergias = ?, medicamentos_actuales = ?, enfermedades_cronicas = ? WHERE id_paciente = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $alergias);
            $stmt->bindParam(2, $medicamentos);
            $stmt->bindParam(3, $cronicas);
            $stmt->bindParam(4, $id_paciente);
        } else {
            // Si no existe, se crea (INSERT)
            $query = "INSERT INTO " . $this->tabla . " (id_paciente, alergias, medicamentos_actuales, enfermedades_cronicas) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $id_paciente);
            $stmt->bindParam(2, $alergias);
            $stmt->bindParam(3, $medicamentos);
            $stmt->bindParam(4, $cronicas);
        }

        return $stmt->execute();
    }
}