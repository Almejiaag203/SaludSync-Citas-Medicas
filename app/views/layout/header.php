<?php
// Iniciar la sesión si no está activa para poder leer el rol del usuario
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lógica para definir los items del menú según el rol
$menuItems = [];
$rolUsuario = $_SESSION['rol'] ?? null;

switch ($rolUsuario) {
    case 'Administrador':
        $menuItems = [
            ['label' => 'Dashboard', 'controller' => 'Admin', 'action' => 'dashboard', 'icon' => 'fa-tachometer-alt'],
            ['label' => 'Citas', 'controller' => 'Cita', 'action' => 'index', 'icon' => 'fa-calendar-alt'],
            ['label' => 'Pacientes', 'controller' => 'Paciente', 'action' => 'index', 'icon' => 'fa-user-injured'],
            ['label' => 'Médicos', 'controller' => 'Medico', 'action' => 'index', 'icon' => 'fa-user-md'],
            ['label' => 'Especialidades', 'controller' => 'Especialidad', 'action' => 'index', 'icon' => 'fa-stream'],
            ['label' => 'Usuarios', 'controller' => 'Admin', 'action' => 'usuarios', 'icon' => 'fa-users-cog'],
            ['label' => 'Facturación', 'controller' => 'Admin', 'action' => 'facturacion', 'icon' => 'fa-file-invoice-dollar'],
            ['label' => 'Servicios', 'controller' => 'Admin', 'action' => 'servicios', 'icon' => 'fa-concierge-bell'],
            ['label' => 'Configuración', 'controller' => 'Admin', 'action' => 'configuracion', 'icon' => 'fa-cogs'],
        ];
        break;
    case 'Medico':
        $menuItems = [
            ['label' => 'Mi Agenda', 'controller' => 'Medico', 'action' => 'agenda', 'icon' => 'fa-calendar-alt'],
        ];
        break;
    case 'Paciente':
        $menuItems = [
            ['label' => 'Mi Perfil', 'controller' => 'Paciente', 'action' => 'perfil', 'icon' => 'fa-user-circle'],
            ['label' => 'Agendar Cita', 'controller' => 'Cita', 'action' => 'calendario', 'icon' => 'fa-calendar-plus'],
        ];
        break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina ?? 'Clínica MVC'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="sidebar" id="sidebar">
    <a class="navbar-brand" href="#">Clínica MVC</a>
    
    <ul class="nav flex-column">
        <?php foreach ($menuItems as $item): ?>
            <?php 
                $controladorActual = isset($_GET['controller']) ? $_GET['controller'] : 'Admin';
                $accionActual = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
                $isActive = (ucfirst($controladorActual) === $item['controller'] && $accionActual === $item['action']) ? 'active' : ''; 
            ?>
            <li class="nav-item">
                <a class="nav-link <?php echo $isActive; ?>" href="index.php?controller=<?php echo $item['controller']; ?>&action=<?php echo $item['action']; ?>">
                    <i class="fas <?php echo $item['icon']; ?>"></i>
                    <?php echo $item['label']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="main-content" id="main-content">
    
    <div class="top-bar">
        <button class="btn btn-primary d-lg-none" id="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>

        <?php if (isset($_SESSION['id_usuario'])): ?>
            <a class="btn-logout ms-auto" href="index.php?controller=Usuario&action=cerrarSesion">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        <?php endif; ?>
    </div>

    <div class="content-fluid">