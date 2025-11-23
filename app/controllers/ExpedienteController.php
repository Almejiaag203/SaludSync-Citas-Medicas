<?php
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../models/HistorialClinico.php';
require_once __DIR__ . '/../models/DocumentoMedico.php';
// ... otros modelos necesarios

class ExpedienteController {

    public function ver($id_paciente) {
        // **Validación de permisos:** ¿El médico que ha iniciado sesión tiene permiso para ver este expediente?
        // (Lógica para verificar que el médico tenga o haya tenido una cita con este paciente)
        
        $db = (new Database())->getConnection();
        
        // Cargar todos los datos del paciente
        $paciente_model = new Paciente($db);
        $datos_paciente = $paciente_model->buscarPorId($id_paciente);
        
        $historial_model = new HistorialClinico($db);
        $historial_clinico = $historial_model->leerPorPaciente($id_paciente);
        
        $documentos_model = new DocumentoMedico($db);
        $documentos = $documentos_model->leerPorPaciente($id_paciente);

        // Pasar todos los datos a la vista
        require_once __DIR__ . '/../views/expediente/ver.php';
    }

    public function guardarNota() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // **Validación de permisos**
            $db = (new Database())->getConnection();
            $historial_model = new HistorialClinico($db);
            
            // Asignar datos desde $_POST
            $historial_model->id_cita = $_POST['id_cita'];
            $historial_model->id_paciente = $_POST['id_paciente'];
            // ... resto de los campos (diagnóstico, tratamiento, etc.)

            if ($historial_model->crear()) {
                // Redirigir de vuelta al expediente con un mensaje de éxito
            } else {
                // Manejar error
            }
        }
    }
}
?>