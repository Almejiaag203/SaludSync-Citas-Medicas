<?php 
$titulo_pagina = 'Gestión de Especialidades';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Especialidades</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Administración de especialidades médicas del sistema</li>
    </ol>

    <div class="mb-3">
        <a href="index.php?controller=Especialidad&action=crear" class="btn btn-success">
            <i class="fas fa-plus"></i> Crear Nueva Especialidad
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header"><i class="fas fa-stream me-1"></i>Especialidades Registradas</div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre de la Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($especialidades as $esp): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($esp['nombre_especialidad']); ?></td>
                        <td>
                            <?php if ($esp['activo']): ?>
                                <span class="badge bg-success">Activa</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactiva</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=Especialidad&action=editar&id=<?php echo $esp['id_especialidad']; ?>" class="btn btn-primary btn-sm" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <?php if ($esp['activo']): ?>
                                <a href="index.php?controller=Especialidad&action=cambiarEstado&id=<?php echo $esp['id_especialidad']; ?>&estado=0" class="btn btn-warning btn-sm" title="Desactivar">
                                    <i class="fas fa-toggle-off"></i>
                                </a>
                            <?php else: ?>
                                <a href="index.php?controller=Especialidad&action=cambiarEstado&id=<?php echo $esp['id_especialidad']; ?>&estado=1" class="btn btn-success btn-sm" title="Activar">
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