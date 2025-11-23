<?php 
$titulo_pagina = 'Gestión de Médicos';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Médicos</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Administración de médicos registrados</li>
    </ol>

    <div class="mb-3">
        <a href="index.php?controller=Medico&action=crear" class="btn btn-success">
            <i class="fas fa-plus"></i> Registrar Nuevo Médico
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <i class="fas fa-user-md me-1"></i>
            Médicos Activos
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Apellidos y Nombres</th>
                        <th>Especialidad</th>
                        <th>Correo Electrónico</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($medicos as $medico): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(($medico['apellidos'] ?? '') . ', ' . ($medico['nombres'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($medico['nombre_especialidad'] ?? 'Sin especialidad'); ?></td>
                        <td><?php echo htmlspecialchars($medico['correo_electronico'] ?? ''); ?></td>
                        <td>
                            <?php if ($medico['activo']): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=Medico&action=editar&id=<?php echo $medico['id_medico']; ?>" class="btn btn-primary btn-sm" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <?php if ($medico['activo']): ?>
                                <a href="index.php?controller=Medico&action=cambiarEstado&id_usuario=<?php echo $medico['id_usuario']; ?>&estado=0" class="btn btn-warning btn-sm" title="Desactivar">
                                    <i class="fas fa-toggle-off"></i>
                                </a>
                            <?php else: ?>
                                <a href="index.php?controller=Medico&action=cambiarEstado&id_usuario=<?php echo $medico['id_usuario']; ?>&estado=1" class="btn btn-success btn-sm" title="Activar">
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