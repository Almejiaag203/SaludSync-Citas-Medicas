<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Expediente de: <?php echo htmlspecialchars($datos_paciente['nombres'] . ' ' . $datos_paciente['apellidos']); ?></h1>
    
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] == 'Administrador'): ?>
        <a href="index.php?controller=Paciente&action=index" class="btn btn-secondary">Volver al Listado</a>
    <?php else: ?>
        <a href="index.php?controller=Medico&action=agenda" class="btn btn-secondary">Volver a la Agenda</a>
    <?php endif; ?>
    
</div>
<hr>

<div class="row">
    <div class="col-md-8">
        <ul class="nav nav-tabs" id="expedienteTab" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link active" id="nota-actual-tab" data-bs-toggle="tab" data-bs-target="#nota-actual" type="button">Registrar Consulta</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="prescripcion-tab" data-bs-toggle="tab" data-bs-target="#prescripcion" type="button">Crear Prescripción</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button">Historial Clínico</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="documentos-tab" data-bs-toggle="tab" data-bs-target="#documentos" type="button">Documentos</button></li>
        </ul>
        <div class="tab-content pt-3" id="expedienteTabContent">
            <div class="tab-pane fade show active" id="nota-actual" role="tabpanel">
                <h4>Nota de Evolución (Cita ID: <?php echo $id_cita_actual; ?>)</h4>
                <form action="index.php?controller=Historial&action=guardar" method="POST">
                    <input type="hidden" name="id_cita" value="<?php echo $id_cita_actual; ?>">
                    <input type="hidden" name="id_paciente" value="<?php echo $datos_paciente['id_paciente']; ?>">
                    <div class="mb-3">
                        <label for="analisis_diagnostico" class="form-label">Análisis y Diagnóstico</label>
                        <textarea class="form-control" id="analisis_diagnostico" name="analisis_diagnostico" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="plan_tratamiento" class="form-label">Plan de Tratamiento</label>
                        <textarea class="form-control" id="plan_tratamiento" name="plan_tratamiento" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Nota y Completar Cita</button>
                </form>
            </div>
            
            <div class="tab-pane fade" id="prescripcion" role="tabpanel">
                 </div>

            <div class="tab-pane fade" id="historial" role="tabpanel">
                </div>

            <div class="tab-pane fade" id="documentos" role="tabpanel">
                 </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">Información del Paciente</div>
            <div class="card-body">
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($datos_paciente['telefono'] ?? 'N/A'); ?></p>
                <p><strong>Nacimiento:</strong> <?php echo htmlspecialchars($datos_paciente['fecha_nacimiento'] ?? 'N/A'); ?></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                Antecedentes Médicos
                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#antecedentesModal">
                    Editar
                </button>
            </div>
            <div class="card-body">
                <strong>Alergias:</strong>
                <p class="card-text bg-light p-2 rounded"><?php echo nl2br(htmlspecialchars($antecedentes['alergias'] ?? 'No registradas')); ?></p>
                <hr>
                <strong>Medicamentos Actuales:</strong>
                <p class="card-text bg-light p-2 rounded"><?php echo nl2br(htmlspecialchars($antecedentes['medicamentos_actuales'] ?? 'No registrados')); ?></p>
                <hr>
                <strong>Enfermedades Crónicas:</strong>
                <p class="card-text bg-light p-2 rounded"><?php echo nl2br(htmlspecialchars($antecedentes['enfermedades_cronicas'] ?? 'No registradas')); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="antecedentesModal" tabindex="-1">
    </div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>