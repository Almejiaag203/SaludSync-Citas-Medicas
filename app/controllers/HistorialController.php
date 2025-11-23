<?php
// app/controllers/HistorialController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/HistorialClinico.php';
require_once __DIR__ . '/../models/Cita.php';
require_once __DIR__ . '/../models/Medico.php';
require_once __DIR__ . '/../models/Factura.php';

class HistorialController {

    public function guardar() {
        if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['Medico', 'Administrador'])) {
            die('Acceso denegado.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            
            $historial_model = new HistorialClinico($db);
            $cita_model = new Cita($db);
            $factura_model = new Factura($db);

            $id_cita = $_POST['id_cita'];
            $id_paciente = $_POST['id_paciente'];
            
            $cita_info = $cita_model->buscarPorId($id_cita);
            $id_medico = $cita_info['id_medico'];
            
            $historial_model->id_cita = $id_cita;
            $historial_model->id_paciente = $id_paciente;
            $historial_model->id_medico = $id_medico;
            $historial_model->analisis_diagnostico = $_POST['analisis_diagnostico'];
            $historial_model->plan_tratamiento = $_POST['plan_tratamiento'];
            
            if ($historial_model->crear()) {
                if ($id_cita > 0) {
                    $cita_model->marcarComoCompletada($id_cita);
                    
                    $id_tipo_consulta = $cita_info['id_tipo_consulta'];
                    $stmt_costo = $db->prepare("SELECT costo FROM tipos_consulta WHERE id_tipo_consulta = ?");
                    $stmt_costo->execute([$id_tipo_consulta]);
                    $costo_consulta = $stmt_costo->fetchColumn() ?: 50.00;

                    $factura_model->id_cita = $id_cita;
                    $factura_model->id_paciente = $id_paciente;
                    $factura_model->monto_total = $costo_consulta;
                    $factura_model->fecha_emision = date('Y-m-d');
                    $factura_model->estado = 'Pendiente';
                    $factura_model->crear();
                }

                if ($_SESSION['rol'] === 'Medico') {
                    header("Location: index.php?controller=Medico&action=agenda&exito=nota_guardada");
                } else {
                    header("Location: index.php?controller=Paciente&action=index&exito=nota_guardada");
                }
                exit();
            } else {
                header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=guardado_fallido");
                exit();
            }
        }
    }
}