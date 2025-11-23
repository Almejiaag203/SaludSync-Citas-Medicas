-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-09-2025 a las 18:41:32
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `citas_medicas_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `antecedentes_paciente`
--

CREATE TABLE `antecedentes_paciente` (
  `id_antecedente` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `alergias` text DEFAULT NULL,
  `medicamentos_actuales` text DEFAULT NULL,
  `enfermedades_cronicas` text DEFAULT NULL,
  `ultima_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `antecedentes_paciente`
--

INSERT INTO `antecedentes_paciente` (`id_antecedente`, `id_paciente`, `alergias`, `medicamentos_actuales`, `enfermedades_cronicas`, `ultima_actualizacion`) VALUES
(1, 1, 'información de prueba 1', 'información de prueba 1', 'información de prueba 1', '2025-09-27 07:33:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloqueos_agenda`
--

CREATE TABLE `bloqueos_agenda` (
  `id_bloqueo` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `fecha_hora_inicio` datetime NOT NULL,
  `fecha_hora_fin` datetime NOT NULL,
  `motivo` varchar(255) DEFAULT NULL COMMENT 'Vacaciones, Conferencia, etc.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `id_tipo_consulta` int(11) DEFAULT NULL,
  `fecha_hora_inicio` datetime NOT NULL,
  `fecha_hora_fin` datetime NOT NULL,
  `estado` enum('Programada','Confirmada','Cancelada','Completada','No-Show') NOT NULL DEFAULT 'Programada',
  `motivo_consulta` text DEFAULT NULL,
  `es_recurrente` tinyint(1) DEFAULT 0,
  `id_cita_padre_recurrencia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_paciente`, `id_medico`, `id_tipo_consulta`, `fecha_hora_inicio`, `fecha_hora_fin`, `estado`, `motivo_consulta`, `es_recurrente`, `id_cita_padre_recurrencia`) VALUES
(1, 1, 1, 0, '2025-09-27 09:00:00', '2025-09-27 09:30:00', 'Completada', 'consulta médica 01', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `clave` varchar(50) NOT NULL,
  `valor` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`clave`, `valor`) VALUES
('moneda_simbolo', '$');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_medicos`
--

CREATE TABLE `documentos_medicos` (
  `id_documento` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_cita` int(11) DEFAULT NULL,
  `titulo_documento` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL COMMENT 'Ruta en el servidor',
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre_especialidad` varchar(100) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre_especialidad`, `activo`) VALUES
(1, 'Medicina General', 1),
(2, 'Cardiología', 1),
(3, 'Dermatología', 1),
(4, 'Pediatría', 1),
(5, 'Ginecología', 1),
(6, 'Traumatología', 1),
(7, 'Medicina Interna', 1),
(8, 'Medicina Familiar', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id_factura` int(11) NOT NULL,
  `id_cita` int(11) DEFAULT NULL,
  `id_paciente` int(11) NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `fecha_emision` date NOT NULL,
  `estado` enum('Pendiente','Pagada','Anulada') NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id_factura`, `id_cita`, `id_paciente`, `monto_total`, `fecha_emision`, `estado`) VALUES
(1, 1, 1, 50.00, '2025-09-27', 'Pagada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_clinico`
--

CREATE TABLE `historial_clinico` (
  `id_historial` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `analisis_diagnostico` text DEFAULT NULL,
  `plan_tratamiento` text DEFAULT NULL,
  `notas_evolucion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_clinico`
--

INSERT INTO `historial_clinico` (`id_historial`, `id_cita`, `id_paciente`, `id_medico`, `analisis_diagnostico`, `plan_tratamiento`, `notas_evolucion`, `fecha_creacion`) VALUES
(1, 1, 1, 1, 'Paciente presenta síntomas de resfriado común.', 'Se recomienda reposo y mantenerse hidratado.', NULL, '2025-09-27 07:57:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_espera`
--

CREATE TABLE `lista_espera` (
  `id_lista_espera` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_medico` int(11) NOT NULL,
  `id_especialidad` int(11) DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamentos`
--

CREATE TABLE `medicamentos` (
  `id_medicamento` int(11) NOT NULL,
  `nombre_comercial` varchar(255) NOT NULL,
  `compuesto_generico` varchar(255) DEFAULT NULL,
  `es_controlado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medicamentos`
--

INSERT INTO `medicamentos` (`id_medicamento`, `nombre_comercial`, `compuesto_generico`, `es_controlado`) VALUES
(1, 'Amoxicilina 500mg', 'Amoxicilina', 0),
(2, 'Paracetamol 1g', 'Paracetamol', 0),
(3, 'Clonazepam 2mg', 'Clonazepam', 1),
(4, 'Ibuprofeno 600mg', 'Ibuprofeno', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `id_medico` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `numero_colegiatura` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`id_medico`, `id_usuario`, `nombres`, `apellidos`, `id_especialidad`, `numero_colegiatura`) VALUES
(1, 2, 'MEDICO1', '001', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `id_remitente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `cuerpo_mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `leido` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `documento_identidad` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `id_usuario`, `nombres`, `apellidos`, `documento_identidad`, `fecha_nacimiento`, `telefono`) VALUES
(1, 3, 'Paciente01', '001', NULL, '2000-02-01', '21212121');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_factura` int(11) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `id_transaccion_externa` varchar(255) DEFAULT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `id_factura`, `monto_pagado`, `metodo_pago`, `id_transaccion_externa`, `fecha_pago`) VALUES
(1, 1, 50.00, 'Manual', NULL, '2025-09-27 09:25:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prescripciones`
--

CREATE TABLE `prescripciones` (
  `id_prescripcion` int(11) NOT NULL,
  `id_historial` int(11) NOT NULL,
  `fecha_emision` date NOT NULL,
  `valido_hasta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prescripcion_medicamentos`
--

CREATE TABLE `prescripcion_medicamentos` (
  `id_detalle` int(11) NOT NULL,
  `id_prescripcion` int(11) NOT NULL,
  `nombre_medicamento` varchar(255) NOT NULL,
  `dosis` varchar(100) NOT NULL,
  `frecuencia` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguros_paciente`
--

CREATE TABLE `seguros_paciente` (
  `id` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `nombre_aseguradora` varchar(150) NOT NULL,
  `numero_poliza` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_consulta`
--

CREATE TABLE `tipos_consulta` (
  `id_tipo_consulta` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Presencial, Videollamada, Seguimiento',
  `costo` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_consulta`
--

INSERT INTO `tipos_consulta` (`id_tipo_consulta`, `nombre`, `costo`) VALUES
(1, 'Presencial', 50.00),
(2, 'Videollamada', 40.00),
(3, 'Seguimiento', 30.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol` enum('Paciente','Medico','Administrador') NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `token_activacion` varchar(64) DEFAULT NULL,
  `token_expiracion` datetime DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `correo_electronico`, `contrasena`, `rol`, `activo`, `token_activacion`, `token_expiracion`, `fecha_creacion`) VALUES
(1, 'admin@clinica.com', '$2y$10$hFSqKARbUEbnt7bLQw90/ew5qw0FCI1t1Lq1d4HEpWsCR0UBo00fG', 'Administrador', 1, NULL, NULL, '2025-09-27 07:12:17'),
(2, 'medico1@clinica.com', '$2y$10$7T9xQsIBEeCvh8dsrhIlsuotmOkaHk/n5yJsep48jxXkDysYxpbPO', 'Medico', 1, NULL, NULL, '2025-09-27 07:23:47'),
(3, 'paciente01@correo.com', '$2y$10$wg0gJcDR6SNyLi/I3Almq.CVOVYIeIz3RZkQO4OUODd/Hw79S0poO', 'Paciente', 1, NULL, NULL, '2025-09-27 07:25:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `antecedentes_paciente`
--
ALTER TABLE `antecedentes_paciente`
  ADD PRIMARY KEY (`id_antecedente`),
  ADD UNIQUE KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `bloqueos_agenda`
--
ALTER TABLE `bloqueos_agenda`
  ADD PRIMARY KEY (`id_bloqueo`),
  ADD KEY `id_medico` (`id_medico`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_medico` (`id_medico`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`clave`);

--
-- Indices de la tabla `documentos_medicos`
--
ALTER TABLE `documentos_medicos`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_cita` (`id_cita`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`),
  ADD UNIQUE KEY `nombre_especialidad` (`nombre_especialidad`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id_factura`),
  ADD UNIQUE KEY `id_cita` (`id_cita`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `historial_clinico`
--
ALTER TABLE `historial_clinico`
  ADD PRIMARY KEY (`id_historial`),
  ADD UNIQUE KEY `id_cita` (`id_cita`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  ADD PRIMARY KEY (`id_lista_espera`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_medico` (`id_medico`);

--
-- Indices de la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD PRIMARY KEY (`id_medicamento`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id_medico`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_remitente` (`id_remitente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_factura` (`id_factura`);

--
-- Indices de la tabla `prescripciones`
--
ALTER TABLE `prescripciones`
  ADD PRIMARY KEY (`id_prescripcion`),
  ADD KEY `id_historial` (`id_historial`);

--
-- Indices de la tabla `prescripcion_medicamentos`
--
ALTER TABLE `prescripcion_medicamentos`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_prescripcion` (`id_prescripcion`);

--
-- Indices de la tabla `seguros_paciente`
--
ALTER TABLE `seguros_paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `tipos_consulta`
--
ALTER TABLE `tipos_consulta`
  ADD PRIMARY KEY (`id_tipo_consulta`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`),
  ADD UNIQUE KEY `token_activacion` (`token_activacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `antecedentes_paciente`
--
ALTER TABLE `antecedentes_paciente`
  MODIFY `id_antecedente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `bloqueos_agenda`
--
ALTER TABLE `bloqueos_agenda`
  MODIFY `id_bloqueo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `documentos_medicos`
--
ALTER TABLE `documentos_medicos`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_clinico`
--
ALTER TABLE `historial_clinico`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  MODIFY `id_lista_espera` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  MODIFY `id_medicamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id_medico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `prescripciones`
--
ALTER TABLE `prescripciones`
  MODIFY `id_prescripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prescripcion_medicamentos`
--
ALTER TABLE `prescripcion_medicamentos`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguros_paciente`
--
ALTER TABLE `seguros_paciente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_consulta`
--
ALTER TABLE `tipos_consulta`
  MODIFY `id_tipo_consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `antecedentes_paciente`
--
ALTER TABLE `antecedentes_paciente`
  ADD CONSTRAINT `antecedentes_paciente_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);

--
-- Filtros para la tabla `bloqueos_agenda`
--
ALTER TABLE `bloqueos_agenda`
  ADD CONSTRAINT `bloqueos_agenda_ibfk_1` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`);

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`);

--
-- Filtros para la tabla `documentos_medicos`
--
ALTER TABLE `documentos_medicos`
  ADD CONSTRAINT `documentos_medicos_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `documentos_medicos_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  ADD CONSTRAINT `facturas_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);

--
-- Filtros para la tabla `historial_clinico`
--
ALTER TABLE `historial_clinico`
  ADD CONSTRAINT `historial_clinico_ibfk_1` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`),
  ADD CONSTRAINT `historial_clinico_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);

--
-- Filtros para la tabla `lista_espera`
--
ALTER TABLE `lista_espera`
  ADD CONSTRAINT `lista_espera_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`),
  ADD CONSTRAINT `lista_espera_ibfk_2` FOREIGN KEY (`id_medico`) REFERENCES `medicos` (`id_medico`);

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`id_remitente`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`);

--
-- Filtros para la tabla `prescripciones`
--
ALTER TABLE `prescripciones`
  ADD CONSTRAINT `prescripciones_ibfk_1` FOREIGN KEY (`id_historial`) REFERENCES `historial_clinico` (`id_historial`);

--
-- Filtros para la tabla `prescripcion_medicamentos`
--
ALTER TABLE `prescripcion_medicamentos`
  ADD CONSTRAINT `prescripcion_medicamentos_ibfk_1` FOREIGN KEY (`id_prescripcion`) REFERENCES `prescripciones` (`id_prescripcion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `seguros_paciente`
--
ALTER TABLE `seguros_paciente`
  ADD CONSTRAINT `seguros_paciente_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `pacientes` (`id_paciente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
