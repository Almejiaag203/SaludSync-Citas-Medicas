<?php 
$titulo_pagina = 'Gestión de Pacientes';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Pacientes</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Administración de pacientes registrados</li>
    </ol>

    <div class="mb-3">
        <a href="index.php?controller=Paciente&action=crear" class="btn btn-success">
            <i class="fas fa-plus"></i> Registrar Nuevo Paciente
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Pacientes Registrados
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Apellidos y Nombres</th>
                        <th>Correo Electrónico</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pacientes as $paciente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(($paciente['apellidos'] ?? '') . ', ' . ($paciente['nombres'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($paciente['correo_electronico'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($paciente['telefono'] ?? ''); ?></td>
                        <td>
                            <?php if ($paciente['activo']): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=Paciente&action=editar&id=<?php echo $paciente['id_paciente']; ?>" class="btn btn-primary btn-sm" title="Editar Datos Personales">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            
                            <a href="index.php?controller=Paciente&action=verExpediente&id=<?php echo $paciente['id_paciente']; ?>" class="btn btn-info btn-sm" title="Ver Expediente Clínico">
                                <i class="fas fa-file-medical"></i>
                            </a>

                            <?php if ($paciente['activo']): ?>
                                <a href="index.php?controller=Paciente&action=cambiarEstado&id_usuario=<?php echo $paciente['id_usuario']; ?>&estado=0" class="btn btn-warning btn-sm" title="Desactivar">
                                    <i class="fas fa-toggle-off"></i>
                                </a>
                            <?php else: ?>
                                <a href="index.php?controller=Paciente&action=cambiarEstado&id_usuario=<?php echo $paciente['id_usuario']; ?>&estado=1" class="btn btn-success btn-sm" title="Activar">
                                    <i class="fas fa-toggle-on"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>