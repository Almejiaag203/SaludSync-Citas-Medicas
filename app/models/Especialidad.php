<?php
// app/models/Especialidad.php

class Especialidad {
    private $conn;
    private $tabla = "especialidades";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function leerTodas() {
        $query = "SELECT * FROM " . $this->tabla . " ORDER BY nombre_especialidad ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerTodosActivas() {
        $query = "SELECT * FROM " . $this->tabla . " WHERE activo = 1 ORDER BY nombre_especialidad ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_especialidad = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre) {
        $query = "INSERT INTO " . $this->tabla . " SET nombre_especialidad = :nombre";
        $stmt = $this->conn->prepare($query);
        $nombre_limpio = htmlspecialchars(strip_tags($nombre));
        $stmt->bindParam(':nombre', $nombre_limpio);
        return $stmt->execute();
    }
    
    public function actualizar($id, $nombre) {
        $query = "UPDATE " . $this->tabla . " SET nombre_especialidad = :nombre WHERE id_especialidad = :id";
        $stmt = $this->conn->prepare($query);
        $nombre_limpio = htmlspecialchars(strip_tags($nombre));
        $stmt->bindParam(':nombre', $nombre_limpio);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function cambiarEstado($id, $estado) {
        $query = "UPDATE " . $this->tabla . " SET activo = :estado WHERE id_especialidad = :id";
        $stmt = $this->conn->prepare($query);
        
        // --- CORRECCIÓN AQUÍ ---
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}