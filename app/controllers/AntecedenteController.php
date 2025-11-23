<?php
// app/controllers/AntecedenteController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Antecedente.php';

class AntecedenteController {

    public function guardar() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Medico') {
            die('Acceso denegado.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $antecedente_model = new Antecedente($db);

            $id_paciente = $_POST['id_paciente'];
            $alergias = $_POST['alergias'];
            $medicamentos_actuales = $_POST['medicamentos_actuales'];
            $enfermedades_cronicas = $_POST['enfermedades_cronicas'];

            $antecedente_model->guardar($id_paciente, $alergias, $medicamentos_actuales, $enfermedades_cronicas);

            // Redirige a la página anterior para volver al expediente
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }
}