<?php
// app/models/Configuracion.php

class Configuracion {
    private $conn;
    private $tabla = "configuracion";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lee toda la configuración y la devuelve como un array asociativo
    public function leerToda() {
        $config = [];
        $query = "SELECT clave, valor FROM " . $this->tabla;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $config[$fila['clave']] = $fila['valor'];
        }
        return $config;
    }

    // Actualiza un valor de configuración
    public function actualizar($clave, $valor) {
        $query = "UPDATE " . $this->tabla . " SET valor = :valor WHERE clave = :clave";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':clave', $clave);
        return $stmt->execute();
    }
}