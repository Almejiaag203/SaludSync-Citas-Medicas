<?php 
$titulo_pagina = 'Gestión de Citas';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Citas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Administración de todas las citas registradas en el sistema</li>
    </ol>

    <div class="mb-3">
        <a href="index.php?controller=Cita&action=calendario" class="btn btn-success">
            <i class="fas fa-plus"></i> Agendar Nueva Cita
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Listado Completo de Citas
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($citas)): ?>
                        <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora_inicio'])); ?></td>
                            <td><?php echo htmlspecialchars(($cita['paciente_nombres'] ?? '') . ' ' . ($cita['paciente_apellidos'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars(($cita['medico_nombres'] ?? '') . ' ' . ($cita['medico_apellidos'] ?? '')); ?></td>
                            <td><?php echo htmlspecialchars($cita['nombre_especialidad'] ?? ''); ?></td>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($cita['estado'] ?? ''); ?></span>
                            </td>
                            <td>
                                <a href="index.php?controller=Cita&action=ver&id=<?php echo $cita['id_cita']; ?>" class="btn btn-info btn-sm" title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="Cancelar Cita">
                                    <i class="fas fa-times"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay citas registradas en el sistema.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>