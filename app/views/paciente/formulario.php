<?php 
$es_nuevo = !isset($paciente['id_paciente']);
$titulo_pagina = $es_nuevo ? 'Registrar Paciente' : 'Editar Paciente';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $titulo_pagina; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?controller=Paciente&action=index">Gestión de Pacientes</a></li>
        <li class="breadcrumb-item active"><?php echo $es_nuevo ? 'Nuevo' : 'Editar'; ?></li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="index.php?controller=Paciente&action=<?php echo $es_nuevo ? 'guardar' : 'actualizar'; ?>" method="POST">
                
                <?php if (!$es_nuevo): ?>
                    <input type="hidden" name="id_paciente" value="<?php echo htmlspecialchars($paciente['id_paciente'] ?? ''); ?>">
                    <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($paciente['id_usuario'] ?? ''); ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" value="<?php echo htmlspecialchars($paciente['nombres'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($paciente['apellidos'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($paciente['correo_electronico'] ?? ''); ?>" <?php echo $es_nuevo ? 'required' : 'readonly'; ?>>
                </div>

                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($paciente['telefono'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($paciente['fecha_nacimiento'] ?? ''); ?>">
                    </div>
                </div>

                <?php if ($es_nuevo): ?>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña Inicial</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="index.php?controller=Paciente&action=index" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>