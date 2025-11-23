<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1 class="mb-4">Bienvenido, <?php echo htmlspecialchars($datos_paciente['nombres']); ?></h1>

<div class="row">
    <div class="col-lg-4">
        
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 fw-normal">Mi Perfil</h4>
            </div>
            <div class="card-body">
                <p><strong>Nombre Completo:</strong> <?php echo htmlspecialchars($datos_paciente['nombres'] . ' ' . $datos_paciente['apellidos']); ?></p>
                <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($datos_paciente['fecha_nacimiento']); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($datos_paciente['telefono']); ?></p>
                <a href="#" class="btn btn-outline-primary">Editar Perfil</a>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 fw-normal">Mis Próximas Citas</h4>
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($citas_proximas as $cita): ?>
                    <li class="list-group-item">
                        <strong><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora_inicio'])); ?></strong><br>
                        Con Dr(a). <?php echo htmlspecialchars($cita['medico_apellidos']); ?><br>
                        <small class="text-muted"><?php echo htmlspecialchars($cita['nombre_especialidad']); ?></small>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($citas_proximas)): ?>
                    <li class="list-group-item">No tiene citas programadas.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-8">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial" type="button">Historial de Citas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documentos-tab" data-bs-toggle="tab" data-bs-target="#documentos" type="button">Mis Documentos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="facturas-tab" data-bs-toggle="tab" data-bs-target="#facturas" type="button">Facturación</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            
            <div class="tab-pane fade show active" id="historial" role="tabpanel">
                <div class="table-responsive mt-3">
                    <table class="table table-striped">
                        <thead><tr><th>Fecha</th><th>Médico</th><th>Estado</th><th>Acciones</th></tr></thead>
                        <tbody>
                        <?php foreach ($citas_pasadas as $cita): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora_inicio'])); ?></td>
                                <td>Dr(a). <?php echo htmlspecialchars($cita['medico_apellidos']); ?></td>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($cita['estado']); ?></span></td>
                                <td>
                                    <?php if ($cita['estado'] == 'Completada'): ?>
                                        <a href="#" class="btn btn-info btn-sm">Ver Resumen</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="documentos" role="tabpanel">
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Subir un nuevo documento</h5>
                        <form action="index.php?controller=Documento&action=subir" method="POST" enctype="multipart/form-data">
                            <div class="input-group">
                                <input type="file" class="form-control" name="archivo" required>
                                <button class="btn btn-success" type="submit">Subir</button>
                            </div>
                        </form>
                        <hr>
                        <ul class="list-group">
                        <?php foreach ($documentos as $doc): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($doc['titulo_documento']); ?>
                                <a href="<?php echo htmlspecialchars($doc['ruta_archivo']); ?>" class="btn btn-primary btn-sm" download>Descargar</a>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="facturas" role="tabpanel">
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead><tr><th>ID</th><th>Fecha Emisión</th><th>Monto</th><th>Estado</th><th>Acciones</th></tr></thead>
                        <tbody>
                        <?php foreach ($facturas as $factura): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($factura['id_factura']); ?></td>
                                <td><?php echo htmlspecialchars($factura['fecha_emision']); ?></td>
                                <td>$<?php echo htmlspecialchars($factura['monto_total']); ?></td>
                                <td>
                                    <span class="badge <?php echo $factura['estado'] == 'Pagada' ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo htmlspecialchars($factura['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($factura['estado'] == 'Pendiente'): ?>
                                        <a href="index.php?controller=Pago&action=procesar&factura_id=<?php echo $factura['id_factura']; ?>" class="btn btn-success btn-sm">Pagar Ahora</a>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-info btn-sm">Ver Recibo</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>