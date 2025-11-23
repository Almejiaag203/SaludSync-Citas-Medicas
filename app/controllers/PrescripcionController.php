<?php
// app/controllers/PrescripcionController.php

require_once __DIR__ . '/../lib/fpdf/fpdf.php'; // Incluimos la librería FPDF

class PrescripcionController {

    public function generar() {
        session_start();
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Medico') {
            header("Location: index.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['medicamentos'])) {
            $medicamentos = $_POST['medicamentos'];

            // Aquí iría la lógica para guardar la prescripción en la base de datos
            // $prescripcion_model->crear(...);

            // --- Generación del PDF ---
            $pdf = new FPDF();
            $pdf->AddPage();
            
            // Encabezado
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Receta Medica', 0, 1, 'C');
            $pdf->Ln(10);

            // Datos del Paciente y Médico (estos datos deberían venir de la BD)
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 7, 'Paciente: Juan Perez', 0, 1);
            $pdf->Cell(0, 7, 'Medico: Dr. Apellido', 0, 1);
            $pdf->Cell(0, 7, 'Fecha: ' . date('d/m/Y'), 0, 1);
            $pdf->Ln(10);

            // Título de la prescripción
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Rp.', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            // Listado de medicamentos
            $numero = 1;
            foreach ($medicamentos as $med) {
                $linea = $numero . ". " . $med['nombre'] . " - " . $med['dosis'] . " - " . $med['frecuencia'];
                $pdf->MultiCell(0, 7, utf8_decode($linea)); // utf8_decode para manejar tildes
                $numero++;
            }
            $pdf->Ln(20);

            // Firma
            $pdf->Cell(0, 10, '_________________________', 0, 1, 'C');
            $pdf->Cell(0, 7, 'Firma del Medico', 0, 1, 'C');

            // Salida del PDF (fuerza la descarga)
            $pdf->Output('D', 'receta_medica.pdf');
        }
    }
}
?>