<?php 
$titulo_pagina = 'Detalles de la Cita';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Detalles de la Cita</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?controller=Cita&action=index">Gestión de Citas</a></li>
        <li class="breadcrumb-item active">Cita #<?php echo $cita['id_cita']; ?></li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="my-0 fw-normal">Información de la Cita</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Paciente:</strong> <?php echo htmlspecialchars($cita['paciente_nombres'] . ' ' . $cita['paciente_apellidos']); ?></p>
                    <p><strong>Correo del Paciente:</strong> <?php echo htmlspecialchars($cita['paciente_correo']); ?></p>
                    <p><strong>Médico:</strong> <?php echo htmlspecialchars($cita['medico_nombres'] . ' ' . $cita['medico_apellidos']); ?></p>
                    <p><strong>Especialidad:</strong> <?php echo htmlspecialchars($cita['nombre_especialidad']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha y Hora:</strong> <?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora_inicio'])); ?></p>
                    <p><strong>Estado:</strong> <span class="badge bg-primary"><?php echo htmlspecialchars($cita['estado']); ?></span></p>
                    <p><strong>Motivo de la Consulta:</strong></p>
                    <p class="bg-light p-2 rounded"><?php echo nl2br(htmlspecialchars($cita['motivo_consulta'] ?? 'No especificado')); ?></p>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="index.php?controller=Cita&action=index" class="btn btn-secondary">Volver al Listado</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>