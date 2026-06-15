<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-chalkboard-teacher"></i>
          EVALUACIÓN FINAL POR EL ASESOR INTERNO
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

      <!-- Calificaciones del Asesor Externo (solo lectura) -->
      <div class="section-title">
        <i class="fas fa-building"></i>
        Calificaciones del Asesor Externo
      </div>

      <div class="table-responsive">
        <table class="evaluation-table readonly">
          <thead>
            <tr>
              <th class="thfondo">Criterios a Evaluar</th>
              <th class="thfondo">Calificación del Asesor Externo</th>
            </tr>
          </thead>
          <tbody>
            <tr><td class="criterio-text">Portada</td><td class="valor-readonly">{{ ultimo.portada_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Agradecimientos</td><td class="valor-readonly">{{ ultimo.agradecimientos_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Resumen</td><td class="valor-readonly">{{ ultimo.resumen_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Índice</td><td class="valor-readonly">{{ ultimo.indice_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Introducción</td><td class="valor-readonly">{{ ultimo.introduccion_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Problemas a resolver (priorizándolos)</td><td class="valor-readonly">{{ ultimo.problemas_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Objetivos</td><td class="valor-readonly">{{ ultimo.objetivos_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Justificación</td><td class="valor-readonly">{{ ultimo.justificacion_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Marco Teórico (fundamentos teóricos)</td><td class="valor-readonly">{{ ultimo.marco_teorico_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Procedimiento, descripción de las actividades realizadas</td><td class="valor-readonly">{{ ultimo.procedimiento_externo ?? 'N/A' }}</td></tr>
            <tr>
              <td class="criterio-text">Resultados: planos, gráficas, prototipos, manuales, programas, análisis estadísticos, modelos matemáticos, simulaciones, normativas, regulaciones y restricciones, entre otros. Solo para proyectos que lo requieran, estudios de mercado, estudio técnico y estudio económico.</td>
              <td class="valor-readonly">{{ ultimo.resultados_externo ?? 'N/A' }}</td>
            </tr>
            <tr><td class="criterio-text">Conclusiones, recomendaciones, y experiencia profesional adquirida.</td><td class="valor-readonly">{{ ultimo.conclusiones_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Competencias desarrolladas y/o aplicadas</td><td class="valor-readonly">{{ ultimo.competencias_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text">Fuentes de información</td><td class="valor-readonly">{{ ultimo.fuentes_externo ?? 'N/A' }}</td></tr>
            <tr><td class="criterio-text"><strong>Observaciones</strong></td><td class="valor-readonly">{{ ultimo.comentarios_externo ?? 'Sin observaciones' }}</td></tr>
          </tbody>
        </table>
      </div>

      <pre>{{ ultimo }}</pre>

      <!-- Calificaciones del Asesor Interno -->
      <div class="section-title" style="margin-top: 30px;">
        <i class="fas fa-star"></i>
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
              <tr v-for="criterio in criteriosInternos" :key="criterio.name">
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
            {{ cargando ? 'Guardando...' : 'Guardar Calificación Final' }}
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
  ultimo: {
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

// Criterios de evaluación para asesor interno (seguimiento final)
const criteriosInternos = [
  { name: 'portada_interno', label: 'Portada', max: 2 },
  { name: 'agradecimientos_interno', label: 'Agradecimientos', max: 2 },
  { name: 'resumen_interno', label: 'Resumen', max: 2 },
  { name: 'indice_interno', label: 'Índice', max: 2 },
  { name: 'introduccion_interno', label: 'Introducción', max: 2 },
  { name: 'problemas_interno', label: 'Problemas a resolver (priorizándolos)', max: 5 },
  { name: 'objetivos_interno', label: 'Objetivos', max: 2 },
  { name: 'justificacion_interno', label: 'Justificación', max: 3 },
  { name: 'marco_teorico_interno', label: 'Marco Teórico (fundamentos teóricos)', max: 10 },
  { name: 'procedimiento_interno', label: 'Procedimiento, descripción de las actividades realizadas', max: 5 },
  { name: 'resultados_interno', label: 'Resultados: planos, gráficas, prototipos, manuales, programas, análisis estadísticos, modelos matemáticos, simulaciones, normativas, regulaciones y restricciones, entre otros. Solo para proyectos que lo requieran, estudios de mercado, estudio técnico y estudio económico.', max: 45 },
  { name: 'conclusiones_interno', label: 'Conclusiones, recomendaciones, y experiencia profesional adquirida.', max: 15 },
  { name: 'competencias_interno', label: 'Competencias desarrolladas y/o aplicadas', max: 3 },
  { name: 'fuentes_interno', label: 'Fuentes de información', max: 2 }
]

// Formulario reactivo con valores iniciales
const form = ref({
  portada_interno: props.ultimo.portada_interno ?? 2,
  agradecimientos_interno: props.ultimo.agradecimientos_interno ?? 2,
  resumen_interno: props.ultimo.resumen_interno ?? 2,
  indice_interno: props.ultimo.indice_interno ?? 2,
  introduccion_interno: props.ultimo.introduccion_interno ?? 2,
  problemas_interno: props.ultimo.problemas_interno ?? 5,
  objetivos_interno: props.ultimo.objetivos_interno ?? 2,
  justificacion_interno: props.ultimo.justificacion_interno ?? 3,
  marco_teorico_interno: props.ultimo.marco_teorico_interno ?? 10,
  procedimiento_interno: props.ultimo.procedimiento_interno ?? 5,
  resultados_interno: props.ultimo.resultados_interno ?? 45,
  conclusiones_interno: props.ultimo.conclusiones_interno ?? 15,
  competencias_interno: props.ultimo.competencias_interno ?? 3,
  fuentes_interno: props.ultimo.fuentes_interno ?? 2,
  comentarios_interno: props.ultimo.comentarios_interno ?? ''
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
        title: '¡Calificación Final guardada!',
        text: 'La evaluación final ha sido registrada correctamente.',
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
  vertical-align: top;
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
  
  .criterio-text {
    font-size: 12px;
  }
}
</style>