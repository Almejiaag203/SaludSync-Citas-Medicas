<?php
// app/controllers/PacienteController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../models/DocumentoMedico.php';
require_once __DIR__ . '/../models/Factura.php';
require_once __DIR__ . '/../models/HistorialClinico.php';
require_once __DIR__ . '/../models/Antecedente.php';
require_once __DIR__ . '/../models/Medicamento.php';

class PacienteController {

    public function index() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
            die('Acceso denegado.');
        }
        $db = (new Database())->getConnection();
        $paciente_model = new Paciente($db);
        $pacientes = $paciente_model->leerTodos();
        require_once __DIR__ . '/../views/paciente/index.php';
    }

    public function crear() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        require_once __DIR__ . '/../views/paciente/formulario.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            
            $usuario = new Usuario($db);
            $usuario->correo_electronico = $_POST['correo'];
            $usuario->contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
            $usuario->rol = 'Paciente';
            $nuevo_id_usuario = $usuario->crear(); // Usamos el método de creación directa

            if ($nuevo_id_usuario) {
                $paciente = new Paciente($db);
                $paciente->id_usuario = $nuevo_id_usuario;
                $paciente->nombres = $_POST['nombres'];
                $paciente->apellidos = $_POST['apellidos'];
                $paciente->fecha_nacimiento = $_POST['fecha_nacimiento'];
                $paciente->telefono = $_POST['telefono'];
                $paciente->crear();
            }
            header('Location: index.php?controller=Paciente&action=index&exito=creado');
            exit();
        }
    }

    public function editar() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        $id = $_GET['id'];
        $db = (new Database())->getConnection();
        $paciente_model = new Paciente($db);
        $paciente = $paciente_model->buscarPorId($id); 
        require_once __DIR__ . '/../views/paciente/formulario.php';
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $paciente = new Paciente($db);
            $paciente->id_paciente = $_POST['id_paciente'];
            $paciente->nombres = $_POST['nombres'];
            $paciente->apellidos = $_POST['apellidos'];
            $paciente->fecha_nacimiento = $_POST['fecha_nacimiento'];
            $paciente->telefono = $_POST['telefono'];
            $paciente->actualizar();
            header('Location: index.php?controller=Paciente&action=index');
            exit();
        }
    }

    public function cambiarEstado() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        $id_usuario = $_GET['id_usuario'];
        $estado = $_GET['estado'];
        $db = (new Database())->getConnection();
        $usuario = new Usuario($db);
        $usuario->cambiarEstado($id_usuario, $estado);
        header('Location: index.php?controller=Paciente&action=index');
        exit();
    }
    
    public function verExpediente() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        $id_paciente = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        if (!$id_paciente) { die("Error: ID de paciente no proporcionado."); }
        $db = (new Database())->getConnection();
        $paciente_model = new Paciente($db);
        $datos_paciente = $paciente_model->buscarPorId($id_paciente);
        $historial_model = new HistorialClinico($db);
        $historial_clinico = $historial_model->leerPorPaciente($id_paciente);
        $documentos_model = new DocumentoMedico($db);
        $documentos = $documentos_model->leerPorPaciente($id_paciente);
        $antecedente_model = new Antecedente($db);
        $antecedentes = $antecedente_model->buscarPorPaciente($id_paciente);
        $medicamento_model = new Medicamento($db);
        $medicamentos_lista = $medicamento_model->leerTodos();
        $id_cita_actual = 0;
        require_once __DIR__ . '/../views/medico/expediente.php';
    }

    public function perfil() {
        // ... (código existente)
    }
}