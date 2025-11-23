<?php
// MODO DEPURACIÓN (SOLO PARA DESARROLLO)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['id_usuario'])) {
    $controladorPermitido = isset($_GET['controller']) && in_array(ucfirst($_GET['controller']), ['Usuario']);
    $accionPermitida = isset($_GET['action']) && in_array($_GET['action'], ['vistaLogin', 'autenticar', 'activar', 'establecerContrasena']);
    
    if (!$controladorPermitido || !$accionPermitida) {
        header('Location: index.php?controller=Usuario&action=vistaLogin');
        exit();
    }
}

$nombreControlador = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'UsuarioController';
$nombreAccion = isset($_GET['action']) ? $_GET['action'] : 'vistaLogin';

if (isset($_SESSION['id_usuario']) && !isset($_GET['controller'])) {
    switch ($_SESSION['rol']) {
        case 'Administrador': $nombreControlador = 'AdminController'; $nombreAccion = 'dashboard'; break;
        case 'Medico': $nombreControlador = 'MedicoController'; $nombreAccion = 'agenda'; break;
        case 'Paciente': $nombreControlador = 'PacienteController'; $nombreAccion = 'perfil'; break;
    }
}

$rutaControlador = __DIR__ . '/../app/controllers/' . $nombreControlador . '.php';

if (file_exists($rutaControlador)) {
    require_once $rutaControlador;
    if (class_exists($nombreControlador)) {
        $controlador = new $nombreControlador();
        if (method_exists($controlador, $nombreAccion)) {
            $controlador->$nombreAccion();
        } else {
            die("Error 404: La acción '{$nombreAccion}' no fue encontrada en el controlador '{$nombreControlador}'.");
        }
    } else {
        die("Error: La clase del controlador '{$nombreControlador}' no fue encontrada.");
    }
} else {
    die("Error 404: El controlador '{$nombreControlador}' no fue encontrado.");
}