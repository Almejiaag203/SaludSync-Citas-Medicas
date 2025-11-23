<?php 
$titulo_pagina = 'Agenda del Médico';
// RUTA CORREGIDA: Solo sube un nivel
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="content-fluid">
    <h1 class="mt-4">Agenda del Dr(a). <?php echo htmlspecialchars($datos_medico['apellidos']); ?></h1>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="my-0 fw-normal">Citas de Hoy (<?php echo date('d/m/Y'); ?>)</h4>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (empty($citas_hoy)): ?>
                        <div class="list-group-item">No hay citas programadas para hoy.</div>
                    <?php else: ?>
                        <?php foreach ($citas_hoy as $cita): ?>
                            <a href="index.php?controller=Medico&action=verExpediente&id_paciente=<?php echo $cita['id_paciente']; ?>&id_cita=<?php echo $cita['id_cita']; ?>" class="list-group-item list-group-item-action">
                                <strong><?php echo date('H:i', strtotime($cita['fecha_hora_inicio'])); ?></strong> - 
                                <?php echo htmlspecialchars($cita['nombres'] . ' ' . $cita['apellidos']); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 fw-normal">Próximas Citas</h4>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (empty($citas_proximas)): ?>
                        <div class="list-group-item">No hay más citas programadas.</div>
                    <?php else: ?>
                        <?php foreach ($citas_proximas as $cita): ?>
                             <a href="index.php?controller=Medico&action=verExpediente&id_paciente=<?php echo $cita['id_paciente']; ?>&id_cita=<?php echo $cita['id_cita']; ?>" class="list-group-item list-group-item-action">
                                <strong><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora_inicio'])); ?></strong> - 
                                <?php echo htmlspecialchars($cita['nombres'] . ' ' . $cita['apellidos']); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// RUTA CORREGIDA: Solo sube un nivel
require_once __DIR__ . '/../layout/footer.php'; 
?>