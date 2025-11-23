<?php 
$titulo_pagina = 'Agenda de Citas';
require_once __DIR__ . '/../layout/header.php'; 
?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/es.js'></script>

<div class="container-fluid px-4">
    <h1 class="mt-4">Agenda de Citas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Visualización y gestión de la agenda</li>
    </ol>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body row align-items-center">
            <div class="col-md-4">
                <label for="filtro_especialidad" class="form-label fw-bold">1. Seleccione Especialidad:</label>
                <select id="filtro_especialidad" class="form-select">
                    <option value="">Todas las especialidades...</option>
                    <?php foreach ($especialidades as $esp): ?>
                        <option value="<?php echo $esp['id_especialidad']; ?>"><?php echo htmlspecialchars($esp['nombre_especialidad']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filtro_medico" class="form-label fw-bold">2. Ver Agenda de:</label>
                <select id="filtro_medico" class="form-select" disabled>
                    <option value="">Seleccione una especialidad...</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div id='calendar'></div>
        </div>
    </div>
</div>

<div class="modal fade" id="citaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Agendar Nueva Cita</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formCita" action="index.php?controller=Cita&action=guardar" method="POST">
                <input type="hidden" name="fecha_hora" id="fecha_hora_input">
                <input type="hidden" name="id_medico" id="id_medico_input">
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="fecha_input" class="form-label">Fecha</label><input type="date" class="form-control" id="fecha_input" required></div>
                    <div class="col-md-6 mb-3"><label for="hora_input" class="form-label">Hora</label><input type="time" class="form-control" id="hora_input" step="1800" required></div>
                </div>
                <div class="mb-3">
                    <label for="id_paciente" class="form-label">Paciente</label>
                    <select class="form-select" name="id_paciente" id="id_paciente" required>
                        <option value="">Seleccione un paciente...</option>
                        <?php foreach ($pacientes as $paciente): ?>
                            <option value="<?php echo $paciente['id_paciente']; ?>"><?php echo htmlspecialchars($paciente['apellidos'] . ', ' . $paciente['nombres']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="motivo_consulta" class="form-label">Motivo de la Consulta</label>
                    <textarea class="form-control" name="motivo_consulta" id="motivo_consulta" rows="3"></textarea>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formCita" class="btn btn-primary">Confirmar Cita</button>
          </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detalleCitaModal" tabindex="-1">
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const filtroEspecialidad = document.getElementById('filtro_especialidad');
    const filtroMedico = document.getElementById('filtro_medico');
    const citaModalEl = document.getElementById('citaModal');
    const citaModal = new bootstrap.Modal(citaModalEl);
    const fechaInput = document.getElementById('fecha_input');
    const horaInput = document.getElementById('hora_input');
    const fechaHoraHiddenInput = document.getElementById('fecha_hora_input');
    const medicoHiddenInput = document.getElementById('id_medico_input');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día'
        },
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        events: function(fetchInfo, successCallback, failureCallback) {
            const id_medico = filtroMedico.value;
            if (id_medico) {
                fetch(`/sistema_citas_medicas/public/api/eventos.php?id_medico=${id_medico}`)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => failureCallback(error));
            } else {
                successCallback([]);
            }
        },
        dateClick: function(info) {
            const medicoSeleccionadoId = filtroMedico.value;
            if (!medicoSeleccionadoId) {
                alert('Por favor, seleccione un médico en el filtro "Ver Agenda de:" antes de agendar una cita.');
                return;
            }
            medicoHiddenInput.value = medicoSeleccionadoId;
            const fecha = info.dateStr.substring(0, 10);
            const hora = info.dateStr.substring(11, 16);
            fechaInput.value = fecha;
            horaInput.value = hora;
            actualizarHiddenInput();
            citaModal.show();
        },
        eventClick: function(info) {
            // Lógica para el modal de detalles
        }
    });
    calendar.render();

    filtroEspecialidad.addEventListener('change', function() {
        const id_especialidad = this.value;
        filtroMedico.innerHTML = '<option value="">Cargando...</option>';
        filtroMedico.disabled = true;
        calendar.refetchEvents();

        if (id_especialidad) {
            fetch(`/sistema_citas_medicas/public/api/medicos.php?id_especialidad=${id_especialidad}`)
            .then(response => response.json())
            .then(data => {
                filtroMedico.innerHTML = '<option value="">Seleccione un médico...</option>';
                if (data.length > 0) {
                    data.forEach(medico => {
                        const option = new Option(`${medico.apellidos}, ${medico.nombres}`, medico.id_medico);
                        filtroMedico.add(option);
                    });
                    filtroMedico.disabled = false;
                } else {
                    filtroMedico.innerHTML = '<option value="">No hay médicos</option>';
                }
            });
        } else {
            filtroMedico.innerHTML = '<option value="">Seleccione una especialidad...</option>';
        }
    });

    filtroMedico.addEventListener('change', function() {
        calendar.refetchEvents();
    });

    function actualizarHiddenInput() {
        fechaHoraHiddenInput.value = fechaInput.value + 'T' + horaInput.value;
    }
    fechaInput.addEventListener('change', actualizarHiddenInput);
    horaInput.addEventListener('change', actualizarHiddenInput);
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
```eof