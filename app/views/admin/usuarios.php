<?php 
$titulo_pagina = 'Gestión de Usuarios';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Usuarios</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Todos los usuarios del sistema</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <div class="card-header"><i class="fas fa-users me-1"></i>Usuarios Registrados</div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(($usuario['apellidos'] ?? '') . ', ' . ($usuario['nombres'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($usuario['correo_electronico'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($usuario['rol'] ?? ''); ?></td>
                        <td>
                            <?php if ($usuario['activo']): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm password-reset-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#passwordModal" 
                                    data-id-usuario="<?php echo $usuario['id_usuario']; ?>"
                                    data-nombre-usuario="<?php echo htmlspecialchars(($usuario['nombres'] ?? '') . ' ' . ($usuario['apellidos'] ?? '')); ?>"
                                    title="Cambiar Contraseña">
                                <i class="fas fa-key"></i>
                            </button>
                            
                            <?php if ($usuario['activo']): ?>
                                <a href="index.php?controller=Admin&action=cambiarEstadoUsuario&id_usuario=<?php echo $usuario['id_usuario']; ?>&estado=0" class="btn btn-warning btn-sm" title="Desactivar">
                                    <i class="fas fa-toggle-off"></i>
                                </a>
                            <?php else: ?>
                                <a href="index.php?controller=Admin&action=cambiarEstadoUsuario&id_usuario=<?php echo $usuario['id_usuario']; ?>&estado=1" class="btn btn-success btn-sm" title="Activar">
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

<div class="modal fade" id="passwordModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordModalLabel">Restablecer Contraseña</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="index.php?controller=Admin&action=actualizarContrasena" method="POST">
        <div class="modal-body">
            <input type="hidden" name="id_usuario" id="id_usuario_modal">
            <p>Estás cambiando la contraseña para el usuario: <strong id="nombre_usuario_modal"></strong></p>
            <div class="mb-3">
                <label for="nueva_contrasena" class="form-label">Nueva Contraseña</label>
                <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Script para pasar el ID del usuario al modal
const passwordModal = document.getElementById('passwordModal');
passwordModal.addEventListener('show.bs.modal', function (event) {
  // Botón que activó el modal
  const button = event.relatedTarget;
  // Extraer info de los atributos data-*
  const idUsuario = button.getAttribute('data-id-usuario');
  const nombreUsuario = button.getAttribute('data-nombre-usuario');
  
  // Actualizar el contenido del modal
  const modalTitle = passwordModal.querySelector('.modal-title');
  const modalBodyInput = passwordModal.querySelector('#id_usuario_modal');
  const modalBodyName = passwordModal.querySelector('#nombre_usuario_modal');

  modalBodyInput.value = idUsuario;
  modalBodyName.textContent = nombreUsuario;
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>