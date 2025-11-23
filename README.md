SaludSync - Sistema de Citas Médicas

Bienvenido a SaludSync, la plataforma integral diseñada para facilitar la administración de turnos, pacientes y personal médico de manera eficiente y moderna.

🚀 Acceso Rápido (Entorno Local)

Para acceder al sistema una vez desplegado en tu servidor local, utiliza los siguientes datos:

URL de Acceso: http://localhost/sistema_citas_medicas/public/index.php?controller=Usuario&action=vistaLogin

🔑 Credenciales de Acceso

Rol

Correo Electrónico

Contraseña

Administrador

admin@clinica.com

Xvito2013$

Nota de Seguridad: Estas son credenciales por defecto para el entorno de desarrollo. Se recomienda cambiarlas inmediatamente antes de pasar a producción.

🛠️ Tecnologías Utilizadas

SaludSync ha sido construido utilizando un stack robusto y ligero:

Lenguaje Backend: PHP (Arquitectura MVC)

Base de Datos: MySQL

Frontend: HTML5, CSS3, Bootstrap 4

Servidor Web: Apache (XAMPP / WAMP)

📋 Guía de Instalación

Servidor: Asegúrate de tener XAMPP, WAMP o Laragon corriendo.

Despliegue: Coloca la carpeta del proyecto en C:/xampp/htdocs/.

Renombrado: La carpeta raíz debe llamarse exactamente sistema_citas_medicas para que los enlaces funcionen.

Base de Datos:

Entra a http://localhost/phpmyadmin.

Crea una base de datos llamada citas_medicas_db (o el nombre configurado en config/database.php).

Importa el script SQL ubicado en la carpeta /database del proyecto.

📝 Características Destacadas

Gestión de Citas: Agenda visual e intuitiva.

Historias Clínicas: Registro digital de pacientes.

Interfaz Moderna: Diseño de Login renovado con soporte para logotipos personalizados.

SaludSync © 2024 - Soluciones Médicas Digitales
