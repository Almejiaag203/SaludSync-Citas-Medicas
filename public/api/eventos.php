<?php
// public/api/eventos.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/models/Medico.php'; // Necesitamos el modelo Medico

$id_medico_filtro = filter_input(INPUT_GET, 'id_medico', FILTER_SANITIZE_NUMBER_INT);

if (!$id_medico_filtro) {
    echo json_encode([]);
    exit;
}

$db = (new Database())->getConnection();
$eventos = [];

// Actualizamos la consulta para que también nos devuelva el id_especialidad del médico
$sql_citas = "
    SELECT 
        c.id_cita, c.fecha_hora_inicio, c.fecha_hora_fin, c.estado, c.motivo_consulta,
        p.id_paciente, p.nombres AS paciente_nombres, p.apellidos AS paciente_apellidos,
        m.id_medico, m.nombres AS medico_nombres, m.apellidos AS medico_apellidos,
        m.id_especialidad
    FROM citas c
    JOIN pacientes p ON c.id_paciente = p.id_paciente
    JOIN medicos m ON c.id_medico = m.id_medico
    WHERE c.id_medico = ? AND c.estado NOT IN ('Cancelada')
";

$stmt_citas = $db->prepare($sql_citas);
$stmt_citas->execute([$id_medico_filtro]);

while ($fila = $stmt_citas->fetch(PDO::FETCH_ASSOC)) {
    $eventos[] = [
        'id'    => $fila['id_cita'],
        'title' => htmlspecialchars($fila['paciente_apellidos'] . ', ' . $fila['paciente_nombres']),
        'start' => $fila['fecha_hora_inicio'],
        'end'   => $fila['fecha_hora_fin'],
        'color' => '#0d6efd',
        'textColor' => 'white',
        'extendedProps' => [
            'id_cita'           => $fila['id_cita'],
            'id_paciente'       => $fila['id_paciente'],
            'id_medico'         => $fila['id_medico'],
            'id_especialidad'   => $fila['id_especialidad'],
            'motivo'            => htmlspecialchars($fila['motivo_consulta'] ?? '')
        ]
    ];
}

echo json_encode($eventos);