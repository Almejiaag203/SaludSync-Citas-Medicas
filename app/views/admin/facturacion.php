<?php 
$titulo_pagina = 'Gestión de Facturación';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Gestión de Facturación</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Listado de todas las facturas del sistema</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <div class="card-header"><i class="fas fa-file-invoice-dollar me-1"></i>Facturas Emitidas</div>
        <div class="card-body">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th># Factura</th>
                        <th>Paciente</th>
                        <th>Fecha de Emisión</th>
                        <th>Monto Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($facturas)): ?>
                        <?php foreach ($facturas as $factura): ?>
                        <tr>
                            <td><?php echo $factura['id_factura']; ?></td>
                            <td><?php echo htmlspecialchars(($factura['apellidos'] ?? '') . ', ' . ($factura['nombres'] ?? '')); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($factura['fecha_emision'])); ?></td>
                            <td><?php echo htmlspecialchars($moneda_simbolo ?? '$'); ?><?php echo number_format($factura['monto_total'], 2); ?></td>
                            <td>
                                <?php 
                                    $badge_class = 'bg-secondary';
                                    if ($factura['estado'] == 'Pagada') $badge_class = 'bg-success';
                                    if ($factura['estado'] == 'Pendiente') $badge_class = 'bg-warning text-dark';
                                    if ($factura['estado'] == 'Anulada') $badge_class = 'bg-danger';
                                ?>
                                <span class="badge <?php echo $badge_class; ?>"><?php echo $factura['estado']; ?></span>
                            </td>
                            <td>
                                <?php if ($factura['estado'] == 'Pendiente'): ?>
                                    <a href="index.php?controller=Admin&action=cambiarEstadoFactura&id=<?php echo $factura['id_factura']; ?>&estado=Pagada" class="btn btn-success btn-sm" title="Marcar como Pagada">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="index.php?controller=Admin&action=cambiarEstadoFactura&id=<?php echo $factura['id_factura']; ?>&estado=Anulada" class="btn btn-danger btn-sm" title="Anular Factura">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay facturas registradas en el sistema.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>