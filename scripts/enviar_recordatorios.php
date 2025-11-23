<?php
// Este script se configura como un Cron Job para ejecutarse una vez al día.
// Ejemplo de comando Cron: 0 8 * * * /usr/bin/php /ruta/a/tu/proyecto/scripts/enviar_recordatorios.php

require_once '/ruta/a/tu/proyecto/app/config/Database.php';
require_once '/ruta/a/tu/proyecto/vendor/phpmailer/phpmailer/src/PHPMailer.php';
// ... incluir otras clases de PHPMailer

$db = (new Database())->getConnection();

// Buscar citas para mañana
$fecha_manana = date('Y-m-d', strtotime('+1 day'));
$stmt = $db->prepare("
    SELECT p.nombres, u.correo_electronico, c.fecha_hora_inicio, m.nombres as medico_nombre
    FROM citas c
    JOIN pacientes p ON c.id_paciente = p.id_paciente
    JOIN usuarios u ON p.id_usuario = u.id_usuario
    JOIN medicos m ON c.id_medico = m.id_medico
    WHERE DATE(c.fecha_hora_inicio) = ? AND c.estado = 'Confirmada'
");
$stmt->execute([$fecha_manana]);

$citas_para_recordar = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($citas_para_recordar as $cita) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Configuración del servidor de correo (SMTP)
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Tu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'tu_usuario@example.com';
        $mail->Password = 'tu_contraseña';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Contenido del correo
        $mail->setFrom('no-reply@clinicamvc.com', 'Clínica MVC');
        $mail->addAddress($cita['correo_electronico'], $cita['nombres']);
        $mail->isHTML(true);
        $mail->Subject = 'Recordatorio de su cita medica';
        $mail->Body = "Hola {$cita['nombres']},<br>Le recordamos su cita para mañana a las " . date('H:i', strtotime($cita['fecha_hora_inicio'])) . " con el Dr. {$cita['medico_nombre']}.<br><br>Gracias.";
        
        $mail->send();
        echo 'Mensaje enviado a ' . $cita['correo_electronico'] . "\n";
    } catch (Exception $e) {
        echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}\n";
    }
}
?>