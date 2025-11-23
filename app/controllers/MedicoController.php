<?php
// app/controllers/MedicoController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Medico.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Especialidad.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/HistorialClinico.php';
require_once __DIR__ . '/../models/DocumentoMedico.php';
require_once __DIR__ . '/../models/Antecedente.php';
require_once __DIR__ . '/../models/Medicamento.php';
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class MedicoController {

    public function index() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        $db = (new Database())->getConnection();
        $medico_model = new Medico($db);
        $medicos = $medico_model->leerTodos();
        require_once __DIR__ . '/../views/medico/index.php';
    }

    public function agenda() {
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Medico') {
            header("Location: index.php?controller=Usuario&action=vistaLogin");
            exit;
        }
        
        $db = (new Database())->getConnection();
        $medico_model = new Medico($db);
        $datos_medico = $medico_model->buscarPorIdUsuario($_SESSION['id_usuario']);
        $id_medico = $datos_medico['id_medico'];
        
        $cita_model = new Cita($db); 
        $citas_hoy = $cita_model->leerPorMedicoHoy($id_medico);
        $citas_proximas = $cita_model->leerProximasPorMedico($id_medico);
        
        require_once __DIR__ . '/../views/medico/agenda.php';
    }

    public function verExpediente() {
        if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['Administrador', 'Medico'])) {
            die('Acceso denegado.');
        }

        $id_paciente = filter_input(INPUT_GET, 'id_paciente', FILTER_SANITIZE_NUMBER_INT);
        $id_cita_actual = filter_input(INPUT_GET, 'id_cita', FILTER_SANITIZE_NUMBER_INT);

        if (!$id_paciente) {
            die("Error: Faltan parámetros para cargar el expediente.");
        }

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

        require_once __DIR__ . '/../views/medico/expediente.php';
    }

    public function crear() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        $db = (new Database())->getConnection();
        $especialidad_model = new Especialidad($db);
        $especialidades = $especialidad_model->leerTodosActivas();
        require_once __DIR__ . '/../views/medico/formulario.php';
    }

    public function guardar() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            
            $usuario = new Usuario($db);
            $usuario->correo_electronico = $_POST['correo'];
            $usuario->contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
            $usuario->rol = 'Medico';
            $nuevo_id_usuario = $usuario->crear();

            if ($nuevo_id_usuario) {
                $medico = new Medico($db);
                $medico->crear($nuevo_id_usuario, $_POST['nombres'], $_POST['apellidos'], $_POST['id_especialidad']);
            }
            header('Location: index.php?controller=Medico&action=index');
        }
    }

    public function editar() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        $id = $_GET['id'];
        $db = (new Database())->getConnection();
        $medico_model = new Medico($db);
        $medico = $medico_model->buscarPorId($id);
        $especialidad_model = new Especialidad($db);
        $especialidades = $especialidad_model->leerTodosActivas();
        require_once __DIR__ . '/../views/medico/formulario.php';
    }

    public function actualizar() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $medico = new Medico($db);
            $medico->actualizar($_POST['id_medico'], $_POST['nombres'], $_POST['apellidos'], $_POST['id_especialidad']);
            header('Location: index.php?controller=Medico&action=index');
        }
    }

    public function cambiarEstado() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        $id_usuario = $_GET['id_usuario'];
        $estado = $_GET['estado'];
        $db = (new Database())->getConnection();
        $usuario_model = new Usuario($db);
        $usuario_model->cambiarEstado($id_usuario, $estado);
        header('Location: index.php?controller=Medico&action=index');
        exit();
    }
}