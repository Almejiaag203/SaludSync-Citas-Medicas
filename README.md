# 🏥 SaludSync - Sistema de Citas Médicas

**Gestión Integral de Clínicas**

Este es un sistema web robusto desarrollado para la administración eficiente de centros médicos. Permite la gestión automatizada de citas, historias clínicas y horarios del personal médico, ofreciendo una interfaz moderna y segura basada en roles.

---

## ✨ Características Principales

* **📅 Agenda Médica Visual:** Sistema intuitivo para agendar, reprogramar y cancelar citas médicas de manera visual y rápida.
* **📂 Historias Clínicas Digitales:** Registro centralizado de la información del paciente, diagnósticos y tratamientos previos.
* **👥 Gestión de Roles:** Administración completa de perfiles con permisos específicos (**Administrador**, **Médico**, **Paciente**).
* **📧 Acceso Seguro:** Login autenticado mediante correo electrónico y contraseña.
* **🎨 Interfaz Moderna:** Diseño renovado y *User Friendly* (amigable), adaptado a dispositivos móviles y de escritorio.

---

## 🚀 Acceso al Sistema (Entorno Local)

Una vez desplegado el proyecto en tu servidor local (XAMPP/WAMP), utiliza la siguiente URL exacta para acceder al Login, ya que el sistema utiliza un enrutador MVC:

### 🔗 Portal de Acceso (Login)
* **URL:** `http://localhost/sistema_citas_medicas/public/index.php?controller=Usuario&action=vistaLogin`
---

## 🔑 Credenciales de Acceso

Para ingresar al sistema con privilegios totales y probar todas las funcionalidades, utiliza los siguientes datos por defecto:

| Rol | Correo Electrónico | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `admin@clinica.com` | `Xvito2013$` |

> ⚠️ **Seguridad:** Estas credenciales son públicas en el repositorio. Se recomienda cambiarlas inmediatamente una vez implementado el sistema en un entorno de producción.

---

## 💻 Tecnologías y Librerías

El proyecto ha sido construido utilizando un stack tecnológico sólido y estándar en la industria:

### Backend & Base de Datos
* **PHP (Patrón MVC):** Lógica del servidor estructurada bajo el patrón Modelo-Vista-Controlador para garantizar escalabilidad y orden.
* **MySQL:** Base de datos relacional optimizada para el almacenamiento de pacientes y citas.

### Frontend & UI
* **HTML5 & CSS3:** Estructura semántica y estilos modernos.
* **Bootstrap 4:** Framework para asegurar un diseño totalmente *responsive*.
* **FontAwesome:** Iconografía profesional para la interfaz.
* **JavaScript:** Interactividad dinámica y validaciones en tiempo real en el cliente.

---

## 🛠️ Instalación y Configuración

Sigue estos pasos estrictamente para ejecutar el proyecto en tu computadora:

1.  **Clonar el Repositorio:**
    Descarga los archivos en tu carpeta de servidor (ej. `C:/xampp/htdocs/`).
    ```bash
    git clone <https://github.com/Almejiaag203/SaludSync-Citas-Medicas>
    ```

2.  **Configuración de Carpeta (¡Importante!):**
    Para que la URL funcione correctamente, asegúrate de que la carpeta raíz del proyecto se llame exactamente:
    > `sistema_citas_medicas`

3.  **Base de Datos:**
    * Abre tu gestor (ej. PHPMyAdmin).
    * Crea una nueva base de datos llamada: `citas_medicas_db`
    * Importa el archivo `.sql` incluido en la carpeta `database` del proyecto.

4.  **¡Listo!**
    Abre tu navegador, copia y pega la URL de acceso mencionada arriba e inicia sesión.

---

**TECHFUSION DATA © 2025**
*Desarrollado con fines educativos y profesionales.*
