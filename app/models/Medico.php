<?php
// app/models/Medico.php

class Medico {
    private $conn;
    private $tabla = "medicos";

    // Propiedades
    public $id_medico;
    public $id_usuario;
    public $nombres;
    public $apellidos;
    public $id_especialidad;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function leerTodos() {
        $query = "SELECT m.id_medico, m.nombres, m.apellidos, e.nombre_especialidad, u.correo_electronico, u.activo, u.id_usuario
                  FROM " . $this->tabla . " m
                  LEFT JOIN usuarios u ON m.id_usuario = u.id_usuario
                  LEFT JOIN especialidades e ON m.id_especialidad = e.id_especialidad
                  ORDER BY m.apellidos ASC";
        
        $stmt = $this->conn->prepare($query); // <-- CORRECCIÓN AQUÍ
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerTodosActivos() {
        $query = "SELECT m.id_medico, m.nombres, m.apellidos
                  FROM " . $this->tabla . " m
                  JOIN usuarios u ON m.id_usuario = u.id_usuario
                  WHERE u.activo = 1
                  ORDER BY m.apellidos ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id_medico) {
        $query = "SELECT m.*, u.correo_electronico
                  FROM " . $this->tabla . " m
                  LEFT JOIN usuarios u ON m.id_usuario = u.id_usuario
                  WHERE m.id_medico = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_medico);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorIdUsuario($id_usuario) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_usuario = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function buscarPorEspecialidad($id_especialidad) {
        $query = "SELECT m.id_medico, m.nombres, m.apellidos
                  FROM " . $this->tabla . " m
                  JOIN usuarios u ON m.id_usuario = u.id_usuario
                  WHERE u.activo = 1 AND m.id_especialidad = ?
                  ORDER BY m.apellidos ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_especialidad, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($id_usuario, $nombres, $apellidos, $id_especialidad) {
        $query = "INSERT INTO " . $this->tabla . " SET id_usuario=?, nombres=?, apellidos=?, id_especialidad=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_usuario);
        $stmt->bindParam(2, $nombres);
        $stmt->bindParam(3, $apellidos);
        $stmt->bindParam(4, $id_especialidad);
        return $stmt->execute();
    }

    public function actualizar($id_medico, $nombres, $apellidos, $id_especialidad) {
        $query = "UPDATE " . $this->tabla . " SET nombres=?, apellidos=?, id_especialidad=? WHERE id_medico=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $nombres);
        $stmt->bindParam(2, $apellidos);
        $stmt->bindParam(3, $id_especialidad);
        $stmt->bindParam(4, $id_medico);
        return $stmt->execute();
    }
}