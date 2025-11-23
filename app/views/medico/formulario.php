<?php 
$es_nuevo = !isset($medico['id_medico']);
$titulo_pagina = $es_nuevo ? 'Registrar Médico' : 'Editar Médico';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $titulo_pagina; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?controller=Medico&action=index">Gestión de Médicos</a></li>
        <li class="breadcrumb-item active"><?php echo $es_nuevo ? 'Nuevo' : 'Editar'; ?></li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="index.php?controller=Medico&action=<?php echo $es_nuevo ? 'guardar' : 'actualizar'; ?>" method="POST">
                
                <?php if (!$es_nuevo): ?>
                    <input type="hidden" name="id_medico" value="<?php echo $medico['id_medico']; ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo $medico['nombres'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $medico['apellidos'] ?? ''; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $medico['correo_electronico'] ?? ''; ?>" <?php echo $es_nuevo ? 'required' : 'readonly'; ?>>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="id_especialidad" class="form-label">Especialidad</label>
                        <select class="form-select" id="id_especialidad" name="id_especialidad" required>
                            <option value="">Seleccione una especialidad...</option>
                            <?php foreach ($especialidades as $esp): ?>
                                <option value="<?php echo $esp['id_especialidad']; ?>" <?php echo (!$es_nuevo && $esp['id_especialidad'] == $medico['id_especialidad']) ? 'selected' : ''; ?>>
                                    <?php echo $esp['nombre_especialidad']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php if ($es_nuevo): ?>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña Inicial</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="index.php?controller=Medico&action=index" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>