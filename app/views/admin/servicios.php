<?php 
$titulo_pagina = 'Servicios y Tarifas';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Servicios y Tarifas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Gestión de los tipos de consulta y sus costos</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <i class="fas fa-dollar-sign me-1"></i>
            Tarifario de Consultas
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Tipo de Consulta</th>
                        <th>Costo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tipos_consulta)): ?>
                        <?php foreach ($tipos_consulta as $tipo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($moneda_simbolo ?? '$'); ?><?php echo number_format($tipo['costo'], 2); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm">Editar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No hay servicios registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>