<?php
// app/models/Factura.php

class Factura {
    private $conn;
    private $tabla = "facturas";
    
    public $id_factura;
    public $id_cita;
    public $id_paciente;
    public $monto_total;
    public $fecha_emision;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " 
                  SET id_cita = :id_cita, id_paciente = :id_paciente, monto_total = :monto, 
                      fecha_emision = :fecha, estado = :estado";
        
        $stmt = $this->conn->prepare($query);

        $this->id_cita = htmlspecialchars(strip_tags($this->id_cita));
        $this->id_paciente = htmlspecialchars(strip_tags($this->id_paciente));
        $this->monto_total = htmlspecialchars(strip_tags($this->monto_total));
        $this->fecha_emision = htmlspecialchars(strip_tags($this->fecha_emision));
        $this->estado = htmlspecialchars(strip_tags($this->estado));

        $stmt->bindParam(":id_cita", $this->id_cita);
        $stmt->bindParam(":id_paciente", $this->id_paciente);
        $stmt->bindParam(":monto", $this->monto_total);
        $stmt->bindParam(":fecha", $this->fecha_emision);
        $stmt->bindParam(":estado", $this->estado);

        return $stmt->execute();
    }

    public function leerTodas() {
        $query = "SELECT f.id_factura, f.fecha_emision, f.monto_total, f.estado, p.nombres, p.apellidos
                  FROM " . $this->tabla . " f
                  LEFT JOIN pacientes p ON f.id_paciente = p.id_paciente
                  ORDER BY f.fecha_emision DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerPorPaciente($id_paciente) {
        $query = "SELECT id_factura, fecha_emision, monto_total, estado 
                  FROM " . $this->tabla . " 
                  WHERE id_paciente = ? 
                  ORDER BY fecha_emision DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id_factura) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_factura = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_factura);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function cambiarEstado($id_factura, $nuevo_estado) {
        $query = "UPDATE " . $this->tabla . " SET estado = :estado WHERE id_factura = :id_factura";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $nuevo_estado);
        $stmt->bindParam(':id_factura', $id_factura);
        return $stmt->execute();
    }
}