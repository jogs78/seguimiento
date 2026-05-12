<template>
  <AppLayout>
    <div class="bodydiv">
      <!-- Título -->
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-chalkboard-teacher"></i>
          EVALUACIÓN POR EL ASESOR INTERNO
        </p>
      </div>

      <!-- Información del estudiante -->
      <div class="info-card">
        <div class="info-row">
          <span class="info-label"><i class="fas fa-user-graduate"></i> Nombre del residente:</span>
          <span class="info-value">{{ nombreCompleto }}</span>
        </div>
        <div class="info-row">
          <span class="info-label"><i class="fas fa-project-diagram"></i> Nombre del Proyecto:</span>
          <span class="info-value">{{ estudiante.proyecto?.nombre || 'N/A' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label"><i class="fas fa-graduation-cap"></i> Programa educativo:</span>
          <span class="info-value">{{ estudiante.carrera?.nombre || 'N/A' }}</span>
        </div>
        <div class="info-row">
          <span class="info-label"><i class="fas fa-calendar-alt"></i> Periodo de realización:</span>
          <span class="info-value">{{ estudiante.proyecto?.periodo?.nombre || 'N/A' }}</span>
        </div>
      </div>

      <!-- Calificaciones del Asesor Externo -->
      <div class="section-title">
        <i class="fas fa-building"></i>
        Calificaciones del Asesor Externo
      </div>

      <div class="table-responsive">
        <table class="evaluation-table">
          <thead>
            <tr>
              <th class="thfondo">Criterios a Evaluar</th>
              <th class="thfondo">Calificaciones dadas por el Asesor Externo</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Asiste puntualmente en el horario establecido.</td><td>{{ segui.puntualidad_externo ?? 'N/A' }}</td></tr>
            <tr><td>Trabaja en equipo y se comunica de forma efectiva (oral y escrita).</td><td>{{ segui.equipo_externo ?? 'N/A' }}</td></tr>
            <tr><td>Tiene iniciativa para colaborar.</td><td>{{ segui.iniciativa_externo ?? 'N/A' }}</td></tr>
            <tr><td>Propone mejoras al proyecto.</td><td>{{ segui.mejoras_externo ?? 'N/A' }}</td></tr>
            <tr><td>Cumple con los objetivos correspondientes al proyecto.</td><td>{{ segui.objetivos_externo ?? 'N/A' }}</td></tr>
            <tr><td>Es ordenado y cumple satisfactoriamente con las actividades encomendadas en los tiempos establecidos en el cronograma.</td><td>{{ segui.orden_externo ?? 'N/A' }}</td></tr>
            <tr><td>Demuestra liderazgo en su actuar.</td><td>{{ segui.liderazgo_externo ?? 'N/A' }}</td></tr>
            <tr><td>Demuestra conocimiento en el área de su especialidad.</td><td>{{ segui.conocimiento_externo ?? 'N/A' }}</td></tr>
            <tr><td>Demuestra un comportamiento ético (es disciplinado, acata órdenes, respeta a sus compañeros de trabajo, entre otros).</td><td>{{ segui.etico_externo ?? 'N/A' }}</td></tr>
            <tr><td>Observaciones</td><td>{{ segui.comentarios_externo ?? 'Sin observaciones' }}</td></tr>
          </tbody>
        </table>
      </div>

      <!-- Calificaciones del Asesor Interno -->
      <div class="section-title">
        <i class="fas fa-user-tie"></i>
        Calificaciones del Asesor Interno
      </div>

      <form @submit.prevent="guardarCalificacion" class="evaluation-form">
        <div class="table-responsive">
          <table class="evaluation-table">
            <thead>
              <tr>
                <th class="thfondo">Criterios a Evaluar</th>
                <th class="thfondo">Valor</th>
                <th class="thfondo">Evaluación</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="criterio in criterios" :key="criterio.name">
                <td>{{ criterio.label }}</td>
                <td class="valor-cell">{{ criterio.max }}</td>
                <td class="slider-cell">
                  <div class="slider-container">
                    <input
                      type="range"
                      v-model.number="form[criterio.name]"
                      :min="0"
                      :max="criterio.max"
                      class="slider"
                      @input="actualizarValor(criterio.name, $event)"
                    />
                    <span class="slider-value">{{ form[criterio.name] }}</span>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Observaciones</td>
                <td colspan="2">
                  <textarea
                    v-model="form.comentarios_interno"
                    class="textarea-observaciones"
                    rows="5"
                    placeholder="Escribe aquí tus observaciones..."
                  ></textarea>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="button-container">
          <button type="submit" class="btn-submit" :disabled="cargando">
            <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-save'"></i>
            {{ cargando ? 'Guardando...' : 'Guardar Calificación' }}
          </button>
          <Link :href="route('home')" class="btn-cancel">
            <i class="fas fa-times"></i>
            Cancelar
          </Link>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  estudiante: {
    type: Object,
    required: true
  },
  consecutivo: {
    type: String,
    required: true
  },
  segui: {
    type: Object,
    required: true
  },
  flash: Object
})

// Nombre completo del estudiante
const nombreCompleto = computed(() => {
  const estudiante = props.estudiante
  return `${estudiante.nombre || ''} ${estudiante.apellido_paterno || ''} ${estudiante.apellido_materno || ''}`.trim()
})

// Criterios de evaluación
const criterios = [
  { name: 'puntualidad_interno', label: 'Asistió puntualmente a las reuniones de asesoría.', max: 10 },
  { name: 'conocimiento_interno', label: 'Demuestra conocimiento en el área de su especialidad.', max: 20 },
  { name: 'equipo_interno', label: 'Trabaja en equipo y se comunica de forma efectiva (oral y escrita).', max: 15 },
  { name: 'dedicado_interno', label: 'Es dedicado y proactivo en las actividades encomendadas.', max: 20 },
  { name: 'orden_interno', label: 'Es ordenado y cumple satisfactoriamente con las actividades encomendadas en los tiempos establecidos en el cronograma.', max: 20 },
  { name: 'mejoras_interno', label: 'Propone mejoras al proyecto.', max: 15 }
]

// Formulario reactivo
const form = ref({
  puntualidad_interno: props.segui.puntualidad_interno ?? 10,
  conocimiento_interno: props.segui.conocimiento_interno ?? 20,
  equipo_interno: props.segui.equipo_interno ?? 15,
  dedicado_interno: props.segui.dedicado_interno ?? 20,
  orden_interno: props.segui.orden_interno ?? 20,
  mejoras_interno: props.segui.mejoras_interno ?? 15,
  comentarios_interno: props.segui.comentarios_interno ?? ''
})

const cargando = ref(false)

// Actualizar valor del slider
const actualizarValor = (name, event) => {
  form.value[name] = parseInt(event.target.value)
}

// Guardar calificación
const guardarCalificacion = () => {
  cargando.value = true

  router.post(route('guardar-seguimientos', [props.estudiante.id, props.consecutivo]), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Calificación guardada!',
        text: 'La evaluación ha sido registrada correctamente.',
        confirmButtonText: 'OK'
      }).then(() => {
        router.visit(route('home'))
      })
    },
    onError: (errors) => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errors.message || 'No se pudo guardar la calificación. Intenta nuevamente.',
        confirmButtonText: 'OK'
      })
    },
    onFinish: () => {
      cargando.value = false
    }
  })
}
</script>

<style scoped>
.bodydiv {
  margin-left: 20px;
  margin-right: 20px;
  padding: 20px;
}

.horizontal {
  display: flex;
  justify-content: center;
  width: 100%;
}

.subtitulo {
  text-align: center;
  font-size: 28px;
  font-weight: bold;
  margin: 20px 0;
  color: #050E3C;
}

.subtitulo i {
  margin-right: 12px;
}

/* Tarjeta de información */
.info-card {
  background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
  border-radius: 12px;
  padding: 20px;
  margin: 20px 0;
  border-left: 4px solid #050E3C;
}

.info-row {
  display: flex;
  flex-wrap: wrap;
  padding: 8px 0;
  border-bottom: 1px solid #dee2e6;
}

.info-row:last-child {
  border-bottom: none;
}

.info-label {
  width: 200px;
  font-weight: 600;
  color: #050E3C;
}

.info-label i {
  margin-right: 8px;
}

.info-value {
  flex: 1;
  color: #333;
}

/* Secciones */
.section-title {
  font-size: 22px;
  font-weight: bold;
  margin: 30px 0 15px;
  padding-bottom: 10px;
  border-bottom: 2px solid #050E3C;
  color: #050E3C;
}

.section-title i {
  margin-right: 10px;
}

/* Tablas */
.table-responsive {
  overflow-x: auto;
}

.evaluation-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.evaluation-table th,
.evaluation-table td {
  border: 1px solid #dee2e6;
  padding: 12px;
  vertical-align: middle;
}

.thfondo {
  background-color: #050E3C;
  color: white;
  font-weight: 600;
}

.evaluation-table td:first-child {
  background-color: #f8f9fa;
  font-weight: 500;
}

.valor-cell {
  text-align: center;
  width: 70px;
  font-weight: bold;
  color: #050E3C;
}

.slider-cell {
  width: 250px;
}

.slider-container {
  display: flex;
  align-items: center;
  gap: 15px;
}

.slider {
  flex: 1;
  height: 6px;
  background: #dee2e6;
  border-radius: 3px;
  outline: none;
}

.slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #050E3C;
  cursor: pointer;
  border: none;
}

.slider::-webkit-slider-thumb:hover {
  background: #0a1a6e;
  transform: scale(1.2);
}

.slider-value {
  min-width: 35px;
  text-align: center;
  font-weight: bold;
  background: #050E3C;
  color: white;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 14px;
}

/* Textarea */
.textarea-observaciones {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  resize: vertical;
  font-family: inherit;
}

.textarea-observaciones:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

/* Botones */
.button-container {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin: 30px 0;
}

.btn-submit {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  border: none;
  padding: 12px 30px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancel {
  background-color: #6c757d;
  color: white;
  text-decoration: none;
  padding: 12px 30px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn-cancel:hover {
  background-color: #5a6268;
  text-decoration: none;
  color: white;
}

/* Responsive */
@media (max-width: 768px) {
  .subtitulo {
    font-size: 22px;
  }
  
  .info-label {
    width: 100%;
    margin-bottom: 5px;
  }
  
  .slider-container {
    flex-direction: column;
  }
  
  .button-container {
    flex-direction: column;
    align-items: center;
  }
  
  .btn-submit, .btn-cancel {
    width: 100%;
    justify-content: center;
  }
}
</style>