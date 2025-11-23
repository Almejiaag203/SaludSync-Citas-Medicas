<?php
// app/models/Medicamento.php

class Medicamento {
    private $conn;
    private $tabla = "medicamentos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function leerTodos() {
        $query = "SELECT id_medicamento, nombre_comercial, es_controlado FROM " . $this->tabla . " ORDER BY nombre_comercial ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}