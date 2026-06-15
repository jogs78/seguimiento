<template>
  <AppLayout>
    <div class="bodydiv">
      <!-- Título -->
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-building"></i>
          EVALUACIÓN POR EL ASESOR EXTERNO
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
        <i class="fas fa-star"></i>
        Calificaciones del Asesor Externo
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
              <tr v-for="criterio in criteriosExternos" :key="criterio.name">
                <td class="criterio-text">{{ criterio.label }}</td>
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

              <!-- Observaciones -->
              <tr>
                <td class="criterio-text"><strong>Observaciones</strong></td>
                <td colspan="2">
                  <textarea
                    v-model="form.comentarios_externo"
                    class="textarea-observaciones"
                    rows="5"
                    placeholder="Escribe aquí tus observaciones..."
                  ></textarea>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Calificaciones del Asesor Interno (solo lectura) -->
        <div class="section-title" style="margin-top: 30px;">
          <i class="fas fa-chalkboard-teacher"></i>
          Calificaciones del Asesor Interno
        </div>

        <div class="table-responsive">
          <table class="evaluation-table readonly">
            <thead>
              <tr>
                <th class="thfondo">Criterios a Evaluar</th>
                <th class="thfondo">Calificación del Asesor Interno</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="criterio-text">Asistió puntualmente a las reuniones de asesoría.</td>
                <td class="valor-readonly">{{ segui.puntualidad_interno ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td class="criterio-text">Demuestra conocimiento en el área de su especialidad.</td>
                <td class="valor-readonly">{{ segui.conocimiento_interno ?? 'N/A' }}</td>
               </tr>
              <tr>
                <td class="criterio-text">Trabaja en equipo y se comunica de forma efectiva (oral y escrita).</td>
                <td class="valor-readonly">{{ segui.equipo_interno ?? 'N/A' }}</td>
               </tr>
              <tr>
                <td class="criterio-text">Es dedicado y proactivo en las actividades encomendadas.</td>
                <td class="valor-readonly">{{ segui.dedicado_interno ?? 'N/A' }}</td>
               </tr>
              <tr>
                <td class="criterio-text">Es ordenado y cumple satisfactoriamente con las actividades encomendadas en los tiempos establecidos en el cronograma.</td>
                <td class="valor-readonly">{{ segui.orden_interno ?? 'N/A' }}</td>
               </tr>
              <tr>
                <td class="criterio-text">Propone mejoras al proyecto.</td>
                <td class="valor-readonly">{{ segui.mejoras_interno ?? 'N/A' }}</td>
               </tr>
              <tr>
                <td class="criterio-text"><strong>Observaciones</strong></td>
                <td class="valor-readonly">{{ segui.comentarios_interno ?? 'Sin observaciones' }}</td>
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

// Criterios de evaluación para asesor externo
const criteriosExternos = [
  { name: 'puntualidad_externo', label: 'Asiste puntualmente en el horario establecido.', max: 5 },
  { name: 'equipo_externo', label: 'Trabaja en equipo y se comunica de forma efectiva (oral y escrita).', max: 10 },
  { name: 'iniciativa_externo', label: 'Tiene iniciativa para colaborar.', max: 5 },
  { name: 'mejoras_externo', label: 'Propone mejoras al proyecto.', max: 10 },
  { name: 'objetivos_externo', label: 'Cumple con los objetivos correspondientes al proyecto.', max: 2 },
  { name: 'orden_externo', label: 'Es ordenado y cumple satisfactoriamente con las actividades encomendadas en los tiempos establecidos del cronograma.', max: 15 },
  { name: 'liderazgo_externo', label: 'Demuestra liderazgo en su actuar.', max: 10 },
  { name: 'conocimiento_externo', label: 'Demuestra conocimiento en el área de su especialidad.', max: 20 },
  { name: 'etico_externo', label: 'Demuestra un comportamiento ético (es disciplinado, acata órdenes, respeta a sus compañeros de trabajo, entre otros).', max: 10 }
]

// Formulario reactivo con valores iniciales
const form = ref({
  puntualidad_externo: props.segui.puntualidad_externo ?? 5,
  equipo_externo: props.segui.equipo_externo ?? 10,
  iniciativa_externo: props.segui.iniciativa_externo ?? 5,
  mejoras_externo: props.segui.mejoras_externo ?? 10,
  objetivos_externo: props.segui.objetivos_externo ?? 2,
  orden_externo: props.segui.orden_externo ?? 15,
  liderazgo_externo: props.segui.liderazgo_externo ?? 10,
  conocimiento_externo: props.segui.conocimiento_externo ?? 20,
  etico_externo: props.segui.etico_externo ?? 10,
  comentarios_externo: props.segui.comentarios_externo ?? ''
})

const cargando = ref(false)
const errores = ref({})

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

.evaluation-table.readonly td {
  background-color: #f8f9fa;
}

.criterio-text {
  background-color: #f8f9fa;
  font-weight: 500;
}

.valor-cell {
  text-align: center;
  width: 70px;
  font-weight: bold;
  color: #050E3C;
}

.valor-readonly {
  text-align: center;
  font-weight: bold;
  color: #28a745;
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