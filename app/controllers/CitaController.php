<?php
// app/controllers/CitaController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/Medico.php';
require_once __DIR__ . '/../models/Especialidad.php';

class CitaController {

    public function index() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
            die('Acceso denegado.');
        }

        $db = (new Database())->getConnection();
        $cita_model = new Cita($db);
        $citas = $cita_model->leerTodas();

        require_once __DIR__ . '/../views/citas/index.php';
    }

    public function calendario() {
        $db = (new Database())->getConnection();
        $paciente_model = new Paciente($db);
        $pacientes = $paciente_model->leerTodosActivos();
        $medico_model = new Medico($db);
        $medicos = $medico_model->leerTodosActivos();
        $especialidad_model = new Especialidad($db);
        $especialidades = $especialidad_model->leerTodosActivas();
        require_once __DIR__ . '/../views/citas/calendario.php';
    }

    public function guardar() {
        if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'Administrador' && $_SESSION['rol'] !== 'Medico')) {
            die('Acceso denegado.');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $cita = new Cita($db);
            $fecha_hora_inicio = new DateTime($_POST['fecha_hora']);
            $fecha_hora_fin = clone $fecha_hora_inicio;
            $fecha_hora_fin->modify('+30 minutes');
            $cita->id_paciente = $_POST['id_paciente'];
            $cita->id_medico = $_POST['id_medico'];
            $cita->fecha_hora_inicio = $fecha_hora_inicio->format('Y-m-d H:i:s');
            $cita->fecha_hora_fin = $fecha_hora_fin->format('Y-m-d H:i:s');
            $cita->motivo_consulta = $_POST['motivo_consulta'];
            $cita->estado = 'Confirmada';
            if ($cita->crear()) {
                header("Location: index.php?controller=Cita&action=index&exito=1");
            } else {
                header("Location: index.php?controller=Cita&action=calendario&error=1");
            }
        }
    }

    public function ver() {
        if (!isset($_GET['id'])) {
            die("Error: ID de cita no especificado.");
        }
        $id_cita = $_GET['id'];
        $db = (new Database())->getConnection();
        $cita_model = new Cita($db);
        $cita = $cita_model->buscarPorId($id_cita);
        if ($cita) {
            require_once __DIR__ . '/../views/citas/ver.php';
        } else {
            die("Error: Cita no encontrada.");
        }
    }
}