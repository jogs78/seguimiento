<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-check-circle"></i>
          Evaluación del Estudiante
        </p>
      </div>

      <div class="centro">
        <div class="form-card">
          <!-- Mensaje de éxito -->
          <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <span>El estudiante ya subió su calificación y documentación</span>
          </div>

          <!-- Información del estudiante -->
          <div class="info-section">
            <h3><i class="fas fa-user-graduate"></i> Información del Estudiante</h3>
            <div class="info-grid">
              <div class="info-item">
                <span class="info-label">Nombre:</span>
                <span class="info-value">{{ estudiante.nombre }} {{ estudiante.apellido_paterno }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Número de Control:</span>
                <span class="info-value">{{ estudiante.numero_control }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Proyecto:</span>
                <span class="info-value">{{ estudiante.proyecto?.nombre || 'N/A' }}</span>
              </div>
            </div>
          </div>

          <!-- Descargar PDF -->
          <div class="download-section">
            <h3><i class="fas fa-file-pdf"></i> Documento del Estudiante</h3>
            <a 
              :href="archivoUrl" 
              download 
              class="btn-download"
              target="_blank"
            >
              <i class="fas fa-download"></i>
              Descargar PDF subido por el Estudiante
            </a>
            <p class="help-text">El estudiante ha subido su reporte de residencias en formato PDF</p>
          </div>

          <!-- Calificación -->
          <form @submit.prevent="guardarCalificacion" class="formulario">
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-star"></i>
                Promedio Final
              </label>
              <div class="input-group">
                <i class="fas fa-chart-line input-icon"></i>
                <input 
                  type="number" 
                  v-model="promedio"
                  class="input-text"
                  :class="{ 'error': errores.promedio }"
                  min="0"
                  max="100"
                  step="0.1"
                  placeholder="Ingresa el promedio (0-100)"
                  required
                />
                <span class="input-suffix">/ 100</span>
              </div>
              <span v-if="errores.promedio" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.promedio }}
              </span>
              <small class="help-text">Ingresa un valor entre 0 y 100, puedes usar decimales</small>
            </div>

            <!-- Observaciones (opcional) -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-comment"></i>
                Observaciones (opcional)
              </label>
              <textarea 
                v-model="observaciones"
                class="textarea"
                rows="4"
                placeholder="Escribe aquí tus comentarios sobre el desempeño del estudiante..."
              ></textarea>
            </div>

            <div class="botones-container">
              <button type="submit" class="btn-guardar" :disabled="cargando">
                <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-save'"></i>
                {{ cargando ? 'Guardando...' : 'Guardar Calificación' }}
              </button>
              <Link :href="route('home')" class="btn-cancelar">
                <i class="fas fa-times"></i>
                Cancelar
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  estudiante: {
    type: Object,
    required: true
  },
  archivoUrl: {
    type: String,
    default: '/archivo.pdf'
  },
  calificacionExistente: {
    type: Object,
    default: null
  }
})

const promedio = ref(props.calificacionExistente?.promedio || '')
const observaciones = ref(props.calificacionExistente?.observaciones || '')
const cargando = ref(false)
const errores = ref({})

const guardarCalificacion = () => {
  // Validación
  errores.value = {}
  
  if (!promedio.value) {
    errores.value.promedio = 'El promedio es requerido'
    return
  }
  
  const numPromedio = parseFloat(promedio.value)
  if (isNaN(numPromedio) || numPromedio < 0 || numPromedio > 100) {
    errores.value.promedio = 'El promedio debe estar entre 0 y 100'
    return
  }
  
  cargando.value = true
  
  router.post(route('asesor.guardar-calificacion'), {
    estudiante_id: props.estudiante.id,
    promedio: numPromedio,
    observaciones: observaciones.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Calificación guardada!',
        text: 'La calificación ha sido registrada correctamente',
        confirmButtonText: 'OK'
      })
    },
    onError: (errors) => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errors.message || 'No se pudo guardar la calificación',
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

.centro {
  display: flex;
  justify-content: center;
}

.subtitulo {
  text-align: center;
  font-size: 28px;
  font-weight: bold;
  margin: 20px 0;
  color: #28a745;
}

.subtitulo i {
  margin-right: 12px;
}

.form-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  padding: 30px;
  width: 100%;
  max-width: 650px;
  margin: 20px 0;
}

.success-message {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  border-radius: 8px;
  padding: 12px 15px;
  margin-bottom: 25px;
  display: flex;
  align-items: center;
  gap: 10px;
  color: #155724;
}

.success-message i {
  font-size: 20px;
}

.info-section, .download-section {
  margin-bottom: 25px;
  padding-bottom: 20px;
  border-bottom: 1px solid #eee;
}

.info-section h3, .download-section h3 {
  font-size: 18px;
  margin-bottom: 15px;
  color: #050E3C;
}

.info-section h3 i, .download-section h3 i {
  margin-right: 8px;
}

.info-grid {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 8px;
}

.info-item {
  display: flex;
  padding: 8px 0;
}

.info-label {
  width: 150px;
  font-weight: 600;
  color: #555;
}

.info-value {
  flex: 1;
  color: #333;
}

.btn-download {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-download:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
  text-decoration: none;
  color: white;
}

.help-text {
  display: block;
  margin-top: 10px;
  font-size: 12px;
  color: #6c757d;
}

.form-group {
  margin-bottom: 20px;
}

.parrafo {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 8px;
  display: block;
  color: #333;
}

.parrafo i {
  margin-right: 8px;
  color: #050E3C;
}

.input-group {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 12px;
  color: #999;
  font-size: 16px;
}

.input-text {
  width: 100%;
  padding: 12px 12px 12px 40px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.input-text:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.input-text.error {
  border-color: #dc3545;
}

.input-suffix {
  position: absolute;
  right: 12px;
  color: #999;
  font-size: 14px;
}

.textarea {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  resize: vertical;
  font-family: inherit;
}

.textarea:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.error-mensaje {
  display: block;
  margin-top: 5px;
  font-size: 12px;
  color: #dc3545;
}

.error-mensaje i {
  margin-right: 4px;
}

.botones-container {
  display: flex;
  gap: 15px;
  margin-top: 25px;
}

.btn-guardar {
  flex: 1;
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.btn-guardar:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-guardar:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancelar {
  flex: 1;
  background-color: #6c757d;
  color: white;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  text-align: center;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.btn-cancelar:hover {
  background-color: #5a6268;
  text-decoration: none;
  color: white;
}

@media (max-width: 768px) {
  .form-card {
    padding: 20px;
  }
  
  .info-item {
    flex-direction: column;
  }
  
  .info-label {
    width: 100%;
    margin-bottom: 5px;
  }
  
  .botones-container {
    flex-direction: column;
  }
}
</style>