<?php
// app/controllers/UsuarioController.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Paciente.php';

class UsuarioController {

    /**
     * Muestra la vista del formulario de inicio de sesión.
     */
    public function vistaLogin() {
        require_once __DIR__ . '/../views/usuario/login.php';
    }
    
    /**
     * Muestra la vista del formulario de registro.
     */
    public function vistaRegistro() {
        require_once __DIR__ . '/../views/usuario/registro.php';
    }

    /**
     * Procesa el formulario de registro de un nuevo paciente.
     */
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            
            $usuario = new Usuario($db);
            $usuario->correo_electronico = $_POST['correo'];
            $usuario->contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
            $usuario->rol = 'Paciente';
            
            $nuevo_id_usuario = $usuario->crear();

            if(!$nuevo_id_usuario) {
                header("Location: index.php?controller=Usuario&action=vistaRegistro&error=email_existente");
                exit;
            }

            $paciente = new Paciente($db);
            $paciente->id_usuario = $nuevo_id_usuario;
            $paciente->nombres = $_POST['nombres'];
            $paciente->apellidos = $_POST['apellidos'];
            $paciente->fecha_nacimiento = $_POST['fecha_nacimiento'];
            $paciente->telefono = $_POST['telefono'];

            if ($paciente->crear()) {
                header("Location: index.php?controller=Usuario&action=vistaLogin&exito=registro_completo");
            } else {
                header("Location: index.php?controller=Usuario&action=vistaRegistro&error=creacion_perfil");
            }
        }
    }

    /**
     * Procesa el formulario de inicio de sesión y autentica al usuario.
     */
    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $usuario_model = new Usuario($db);
            
            $usuario = $usuario_model->buscarPorCorreo($_POST['correo']);

            if ($usuario && password_verify($_POST['contrasena'], $usuario['contrasena'])) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['rol'] = $usuario['rol'];
                
                switch ($usuario['rol']) {
                    case 'Administrador':
                        header("Location: index.php?controller=Admin&action=dashboard");
                        break;
                    case 'Medico':
                        header("Location: index.php?controller=Medico&action=agenda");
                        break;
                    default: // Paciente
                        header("Location: index.php?controller=Paciente&action=perfil");
                        break;
                }
                exit();

            } else {
                header("Location: index.php?controller=Usuario&action=vistaLogin&error=credenciales_invalidas");
                exit();
            }
        }
    }

    /**
     * Cierra la sesión del usuario actual.
     */
    public function cerrarSesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: index.php?controller=Usuario&action=vistaLogin");
        exit();
    }

    /**
     * Muestra el formulario para establecer una nueva contraseña.
     */
    public function activar() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            die("Token no proporcionado.");
        }

        $db = (new Database())->getConnection();
        $usuario_model = new Usuario($db);
        $usuario = $usuario_model->buscarPorToken($token);

        if ($usuario && strtotime($usuario['token_expiracion']) > time()) {
            require_once __DIR__ . '/../views/usuario/establecer_contrasena.php';
        } else {
            die("El enlace de activación es inválido o ha expirado.");
        }
    }

    /**
     * Guarda la nueva contraseña establecida por el usuario.
     */
    public function establecerContrasena() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $contrasena = $_POST['contrasena'];

            $db = (new Database())->getConnection();
            $usuario_model = new Usuario($db);
            
            $hash = password_hash($contrasena, PASSWORD_BCRYPT);

            if ($usuario_model->activarCuenta($token, $hash)) {
                header("Location: index.php?controller=Usuario&action=vistaLogin&exito=cuenta_activada");
            } else {
                die("Hubo un error al activar su cuenta.");
            }
        }
    }
}