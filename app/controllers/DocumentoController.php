<?php
// app/controllers/DocumentoController.php

require_once __DIR__ . '/../models/DocumentoMedico.php';
require_once __DIR__ . '/../models/Paciente.php';
require_once __DIR__ . '/../config/Database.php';

class DocumentoController {

    public function subir() {
        session_start();
        // **Seguridad 1: Verificar sesión y rol**
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Paciente') {
            header("Location: index.php?controller=Usuario&action=vistaLogin");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
            $db = (new Database())->getConnection();
            $paciente_model = new Paciente($db);
            $datos_paciente = $paciente_model->buscarPorIdUsuario($_SESSION['id_usuario']);
            $id_paciente = $datos_paciente['id_paciente'];

            $archivo = $_FILES['archivo'];

            // **Seguridad 2: Verificar errores de subida**
            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                header("Location: index.php?controller=Paciente&action=perfil&error=subida");
                exit;
            }

            // **Seguridad 3: Validar tamaño y tipo de archivo**
            $tamano_maximo = 5 * 1024 * 1024; // 5 MB
            if ($archivo['size'] > $tamano_maximo) {
                header("Location: index.php?controller=Paciente&action=perfil&error=tamano");
                exit;
            }

            $tipos_permitidos = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!in_array($archivo['type'], $tipos_permitidos)) {
                header("Location: index.php?controller=Paciente&action=perfil&error=tipo");
                exit;
            }

            // **Seguridad 4: Crear un nombre de archivo único y seguro**
            $nombre_original = $archivo['name'];
            $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
            $nombre_seguro = uniqid('doc_' . $id_paciente . '_', true) . '.' . $extension;

            // Mover el archivo a una carpeta segura
            $directorio_subidas = __DIR__ . '/../../uploads/documentos/';
            if (!is_dir($directorio_subidas)) {
                mkdir($directorio_subidas, 0755, true);
            }
            $ruta_destino = $directorio_subidas . $nombre_seguro;

            if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                // Guardar la referencia en la base de datos
                $documento_model = new DocumentoMedico($db);
                $documento_model->id_paciente = $id_paciente;
                $documento_model->titulo_documento = $nombre_original;
                $documento_model->ruta_archivo = 'uploads/documentos/' . $nombre_seguro; // Ruta relativa para acceso web
                
                if ($documento_model->crear()) {
                    header("Location: index.php?controller=Paciente&action=perfil&exito=subida");
                } else {
                    // Error al guardar en BD
                }
            } else {
                // Error al mover el archivo
            }
        }
    }
}
?>