<?php
// /api/eventos.php
header('Content-Type: application/json');
require_once '../app/config/Database.php';
// Aquí incluirías los modelos Cita y BloqueoAgenda

$id_medico = filter_input(INPUT_GET, 'id_medico', FILTER_SANITIZE_NUMBER_INT);
if (!$id_medico) {
    echo json_encode([]);
    exit;
}

$db = (new Database())->getConnection();
$eventos = [];

// 1. Obtener las citas existentes
$stmt_citas = $db->prepare("SELECT fecha_hora_inicio, fecha_hora_fin FROM citas WHERE id_medico = ? AND estado NOT IN ('Cancelada')");
$stmt_citas->execute([$id_medico]);
while ($fila = $stmt_citas->fetch(PDO::FETCH_ASSOC)) {
    $eventos[] = [
        'title' => 'Horario Ocupado',
        'start' => $fila['fecha_hora_inicio'],
        'end' => $fila['fecha_hora_fin'],
        'backgroundColor' => '#d9534f', // Color rojo para citas ocupadas
        'borderColor' => '#d9534f'
    ];
}

// 2. Obtener los bloqueos de agenda (vacaciones, etc.)
$stmt_bloqueos = $db->prepare("SELECT fecha_hora_inicio, fecha_hora_fin, motivo FROM bloqueos_agenda WHERE id_medico = ?");
$stmt_bloqueos->execute([$id_medico]);
while ($fila = $stmt_bloqueos->fetch(PDO::FETCH_ASSOC)) {
    $eventos[] = [
        'title' => $fila['motivo'],
        'start' => $fila['fecha_hora_inicio'],
        'end' => $fila['fecha_hora_fin'],
        'display' => 'background', // Muestra como un fondo de color
        'color' => '#f0ad4e' // Color ámbar para bloqueos
    ];
}

echo json_encode($eventos);
?>