<?php
// app/models/Paciente.php

class Paciente {
    private $conn;
    private $tabla = "pacientes";

    // Propiedades del objeto
    public $id_paciente;
    public $id_usuario;
    public $nombres;
    public $apellidos;
    public $documento_identidad;
    public $fecha_nacimiento;
    public $telefono;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " 
                  SET id_usuario=:id_usuario, nombres=:nombres, apellidos=:apellidos, 
                      fecha_nacimiento=:fecha_nacimiento, telefono=:telefono";
        
        $stmt = $this->conn->prepare($query);

        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));

        $stmt->bindParam(":id_usuario", $this->id_usuario);
        $stmt->bindParam(":nombres", $this->nombres);
        $stmt->bindParam(":apellidos", $this->apellidos);
        $stmt->bindParam(":fecha_nacimiento", $this->fecha_nacimiento);
        $stmt->bindParam(":telefono", $this->telefono);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function buscarPorIdUsuario($id_usuario) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE id_usuario = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id_paciente) {
        $query = "SELECT p.*, u.correo_electronico 
                  FROM " . $this->tabla . " p
                  LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
                  WHERE p.id_paciente = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paciente);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function leerTodos() {
        $query = "SELECT p.id_paciente, p.nombres, p.apellidos, p.telefono, u.correo_electronico, u.activo, u.id_usuario
                  FROM " . $this->tabla . " p
                  LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
                  ORDER BY p.apellidos ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function leerTodosActivos() {
        $query = "SELECT p.id_paciente, p.nombres, p.apellidos
                  FROM " . $this->tabla . " p
                  JOIN usuarios u ON p.id_usuario = u.id_usuario
                  WHERE u.activo = 1
                  ORDER BY p.apellidos ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar() {
        $query = "UPDATE " . $this->tabla . " 
                  SET nombres = :nombres, apellidos = :apellidos, telefono = :telefono, fecha_nacimiento = :fecha_nacimiento
                  WHERE id_paciente = :id_paciente";

        $stmt = $this->conn->prepare($query);

        $this->nombres = htmlspecialchars(strip_tags($this->nombres));
        $this->apellidos = htmlspecialchars(strip_tags($this->apellidos));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->id_paciente = htmlspecialchars(strip_tags($this->id_paciente));
        
        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':apellidos', $this->apellidos);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);
        $stmt->bindParam(':id_paciente', $this->id_paciente);
        
        return $stmt->execute();
    }
}