<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Establecer Contraseña</h2>
                    <form action="index.php?controller=Usuario&action=establecerContrasena" method="POST">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Guardar Contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>