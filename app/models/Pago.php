<?php
// app/models/Pago.php

class Pago {
    private $conn;
    private $tabla = "pagos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrarPago($id_factura, $monto, $metodo = 'Manual') {
        $query = "INSERT INTO " . $this->tabla . " (id_factura, monto_pagado, metodo_pago) VALUES (:id_factura, :monto, :metodo)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id_factura', $id_factura);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':metodo', $metodo);
        
        return $stmt->execute();
    }
}