<?php
// app/models/Usuario.php

class Usuario {
    private $conn;
    private $tabla = "usuarios";
    
    public $id_usuario;
    public $correo_electronico;
    public $contrasena;
    public $rol;
    public $token_activacion;
    public $token_expiracion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function leerTodos() {
        $query = "SELECT u.id_usuario, u.correo_electronico, u.rol, u.activo,
                         COALESCE(p.nombres, m.nombres, 'Admin') AS nombres,
                         COALESCE(p.apellidos, m.apellidos, 'Sistema') AS apellidos
                  FROM usuarios u
                  LEFT JOIN pacientes p ON u.id_usuario = p.id_usuario
                  LEFT JOIN medicos m ON u.id_usuario = m.id_usuario
                  ORDER BY u.rol, apellidos ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorCorreo($correo) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE correo_electronico = ? AND activo = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $correo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear() {
        $query = "INSERT INTO " . $this->tabla . " (correo_electronico, contrasena, rol, activo) VALUES (:correo, :pass, :rol, 1)";
        $stmt = $this->conn->prepare($query);
        $this->correo_electronico = htmlspecialchars(strip_tags($this->correo_electronico));
        $this->contrasena = htmlspecialchars(strip_tags($this->contrasena));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $stmt->bindParam(":correo", $this->correo_electronico);
        $stmt->bindParam(":pass", $this->contrasena);
        $stmt->bindParam(":rol", $this->rol);
        if ($stmt->execute()) { return $this->conn->lastInsertId(); }
        return false;
    }

    public function crearConToken() {
        $query = "INSERT INTO " . $this->tabla . " 
                  SET correo_electronico=:correo, rol=:rol, token_activacion=:token, token_expiracion=:expiracion, activo=0";
        $stmt = $this->conn->prepare($query);
        $this->correo_electronico = htmlspecialchars(strip_tags($this->correo_electronico));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->token_activacion = htmlspecialchars(strip_tags($this->token_activacion));
        $this->token_expiracion = htmlspecialchars(strip_tags($this->token_expiracion));
        $stmt->bindParam(":correo", $this->correo_electronico);
        $stmt->bindParam(":rol", $this->rol);
        $stmt->bindParam(":token", $this->token_activacion);
        $stmt->bindParam(":expiracion", $this->token_expiracion);
        if ($stmt->execute()) { return $this->conn->lastInsertId(); }
        return false;
    }

    public function buscarPorToken($token) {
        $query = "SELECT * FROM " . $this->tabla . " WHERE token_activacion = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function activarCuenta($token, $contrasena_hash) {
        $query = "UPDATE " . $this->tabla . " 
                  SET contrasena = :pass, activo = 1, token_activacion = NULL, token_expiracion = NULL 
                  WHERE token_activacion = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pass', $contrasena_hash);
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
    }

    public function cambiarEstado($id_usuario, $estado) {
        $query = "UPDATE " . $this->tabla . " SET activo = :estado WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function actualizarContrasena($id_usuario, $contrasena_hash) {
        $query = "UPDATE " . $this->tabla . " SET contrasena = :pass WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pass', $contrasena_hash);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    }
}