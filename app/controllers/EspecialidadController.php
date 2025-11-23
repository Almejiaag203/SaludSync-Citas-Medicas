<?php
// app/controllers/EspecialidadController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Especialidad.php';

class EspecialidadController {

    public function index() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') { die('Acceso denegado.'); }
        
        $db = (new Database())->getConnection();
        $especialidad_model = new Especialidad($db);
        $especialidades = $especialidad_model->leerTodas();
        
        require_once __DIR__ . '/../views/especialidad/index.php';
    }

    public function crear() {
        require_once __DIR__ . '/../views/especialidad/formulario.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $especialidad = new Especialidad($db);
            $especialidad->crear($_POST['nombre_especialidad']);
            header('Location: index.php?controller=Especialidad&action=index');
        }
    }

    public function editar() {
        $db = (new Database())->getConnection();
        $especialidad_model = new Especialidad($db);
        $especialidad = $especialidad_model->buscarPorId($_GET['id']);
        require_once __DIR__ . '/../views/especialidad/formulario.php';
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $especialidad = new Especialidad($db);
            $especialidad->actualizar($_POST['id_especialidad'], $_POST['nombre_especialidad']);
            header('Location: index.php?controller=Especialidad&action=index');
        }
    }

    public function cambiarEstado() {
        $db = (new Database())->getConnection();
        $especialidad = new Especialidad($db);
        $especialidad->cambiarEstado($_GET['id'], $_GET['estado']);
        header('Location: index.php?controller=Especialidad&action=index');
    }
}