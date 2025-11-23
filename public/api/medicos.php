<?php
// public/api/medicos.php
header('Content-Type: application/json');

// Incluir los archivos necesarios de configuración y el modelo
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/models/Medico.php';

$id_especialidad = filter_input(INPUT_GET, 'id_especialidad', FILTER_SANITIZE_NUMBER_INT);

if (!$id_especialidad) {
    echo json_encode([]); // Devolver un array vacío si no hay ID
    exit;
}

$db = (new Database())->getConnection();
$medico_model = new Medico($db);
$medicos = $medico_model->buscarPorEspecialidad($id_especialidad);

echo json_encode($medicos);