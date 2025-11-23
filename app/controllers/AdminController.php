<?php
// app/controllers/AdminController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Factura.php';
require_once __DIR__ . '/../models/Pago.php';
require_once __DIR__ . '/../models/Configuracion.php';

class AdminController {

    public function dashboard() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }

        $db = (new Database())->getConnection();

        $total_pacientes = $db->query("SELECT COUNT(*) FROM pacientes")->fetchColumn();
        $total_medicos = $db->query("SELECT COUNT(*) FROM medicos")->fetchColumn();
        $citas_hoy = $db->query("SELECT COUNT(*) FROM citas WHERE DATE(fecha_hora_inicio) = CURDATE()")->fetchColumn();
        $ingresos_mes = $db->query("SELECT SUM(monto_pagado) FROM pagos WHERE MONTH(fecha_pago) = MONTH(CURDATE()) AND YEAR(fecha_pago) = YEAR(CURDATE())")->fetchColumn() ?: 0;
        $proximas_citas = $db->query("SELECT c.fecha_hora_inicio, p.nombres AS paciente_nombres, m.apellidos AS medico_apellidos FROM citas c JOIN pacientes p ON c.id_paciente = p.id_paciente JOIN medicos m ON c.id_medico = m.id_medico WHERE c.fecha_hora_inicio > NOW() ORDER BY c.fecha_hora_inicio ASC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function usuarios() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        
        $db = (new Database())->getConnection();
        $usuario_model = new Usuario($db);
        $usuarios = $usuario_model->leerTodos();
        
        require_once __DIR__ . '/../views/admin/usuarios.php';
    }

    public function facturacion() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        
        $db = (new Database())->getConnection();
        
        $factura_model = new Factura($db);
        $facturas = $factura_model->leerTodas();
        
        // --- LÓGICA AÑADIDA ---
        // Cargar la configuración para obtener el símbolo de la moneda
        $config_model = new Configuracion($db);
        $config = $config_model->leerToda();
        $moneda_simbolo = $config['moneda_simbolo'] ?? '$'; // Si no existe, usa '$' por defecto
        // --- FIN DE LA LÓGICA AÑADIDA ---
        
        // Ahora la vista 'facturacion.php' tendrá acceso a la variable $moneda_simbolo
        require_once __DIR__ . '/../views/admin/facturacion.php';
    }
    
    public function servicios() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        
        $db = (new Database())->getConnection();
        $tipos_consulta = $db->query("SELECT * FROM tipos_consulta")->fetchAll(PDO::FETCH_ASSOC);

        $config_model = new Configuracion($db);
        $config = $config_model->leerToda();
        $moneda_simbolo = $config['moneda_simbolo'] ?? '$';

        require_once __DIR__ . '/../views/admin/servicios.php';
    }

    public function configuracion() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        
        $db = (new Database())->getConnection();
        $config_model = new Configuracion($db);
        $config = $config_model->leerToda();

        require_once __DIR__ . '/../views/admin/configuracion.php';
    }

    public function guardarConfiguracion() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $config_model = new Configuracion($db);
            
            foreach ($_POST['config'] as $clave => $valor) {
                $config_model->actualizar($clave, $valor);
            }
            
            header('Location: index.php?controller=Admin&action=configuracion&exito=1');
        }
    }

    public function actualizarContrasena() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $usuario = new Usuario($db);

            $id_usuario = $_POST['id_usuario'];
            $nueva_contrasena = $_POST['nueva_contrasena'];
            $hash = password_hash($nueva_contrasena, PASSWORD_BCRYPT);

            if ($usuario->actualizarContrasena($id_usuario, $hash)) {
                header('Location: index.php?controller=Admin&action=usuarios&exito=password');
            } else {
                header('Location: index.php?controller=Admin&action=usuarios&error=password');
            }
        }
    }

    public function cambiarEstadoUsuario() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        
        $id_usuario = $_GET['id_usuario'];
        $estado = $_GET['estado'];
        $db = (new Database())->getConnection();
        $usuario = new Usuario($db);
        $usuario->cambiarEstado($id_usuario, $estado);
        header('Location: index.php?controller=Admin&action=usuarios');
    }

    public function cambiarEstadoFactura() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }

        $id_factura = $_GET['id'];
        $nuevo_estado = $_GET['estado'];
        
        $db = (new Database())->getConnection();
        $factura_model = new Factura($db);

        if ($nuevo_estado === 'Pagada') {
            $factura_info = $factura_model->buscarPorId($id_factura);
            if ($factura_info) {
                $pago_model = new Pago($db);
                $pago_model->registrarPago($id_factura, $factura_info['monto_total'], 'Manual');
            }
        }

        $factura_model->cambiarEstado($id_factura, $nuevo_estado);
        
        header('Location: index.php?controller=Admin&action=facturacion');
        exit();
    }
}