<?php 
$es_nuevo = !isset($especialidad);
$titulo_pagina = $es_nuevo ? 'Crear Especialidad' : 'Editar Especialidad';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $titulo_pagina; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?controller=Especialidad&action=index">Gestión de Especialidades</a></li>
        <li class="breadcrumb-item active"><?php echo $es_nuevo ? 'Nueva' : 'Editar'; ?></li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="index.php?controller=Especialidad&action=<?php echo $es_nuevo ? 'guardar' : 'actualizar'; ?>" method="POST">
                
                <?php if (!$es_nuevo): ?>
                    <input type="hidden" name="id_especialidad" value="<?php echo $especialidad['id_especialidad']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="nombre_especialidad" class="form-label">Nombre de la Especialidad</label>
                    <input type="text" class="form-control" id="nombre_especialidad" name="nombre_especialidad" value="<?php echo $especialidad['nombre_especialidad'] ?? ''; ?>" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="index.php?controller=Especialidad&action=index" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>