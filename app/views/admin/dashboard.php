<?php 
$titulo_pagina = 'Dashboard Administrativo';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard Principal</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Resumen del Sistema</li>
    </ol>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h4><?php echo $citas_hoy; ?> Citas Hoy</h4>
                    <span class="small">Consultas programadas para <?php echo date('d/m/Y'); ?></span>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="index.php?controller=Cita&action=index">Ver Detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <h4>$<?php echo number_format($ingresos_mes, 2); ?></h4>
                    <span class="small">Ingresos del Mes</span>
                </div>
                 <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="index.php?controller=Admin&action=facturacion">Ver Reporte</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <h4><?php echo $total_pacientes; ?> Pacientes</h4>
                     <span class="small">Total de pacientes registrados</span>
                </div>
                 <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="index.php?controller=Paciente&action=index">Gestionar Pacientes</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <h4><?php echo $total_medicos; ?> Médicos</h4>
                     <span class="small">Total de médicos activos</span>
                </div>
                 <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="index.php?controller=Medico&action=index">Gestionar Médicos</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Próximas 5 Citas en el Sistema
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proximas_citas as $cita): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($cita['fecha_hora_inicio'])); ?></td>
                        <td><?php echo htmlspecialchars($cita['paciente_nombres']); ?></td>
                        <td>Dr(a). <?php echo htmlspecialchars($cita['medico_apellidos']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>