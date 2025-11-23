<?php 
$titulo_pagina = 'Configuración General';
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Configuración General</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Ajustes del sistema</li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="index.php?controller=Admin&action=guardarConfiguracion" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="moneda_simbolo" class="form-label">Símbolo de Moneda</label>
                        <input type="text" class="form-control" id="moneda_simbolo" name="config[moneda_simbolo]" value="<?php echo htmlspecialchars($config['moneda_simbolo'] ?? '$'); ?>" required>
                        <div class="form-text">Ejemplos: $, €, S/, Bs.</div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Configuración</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>