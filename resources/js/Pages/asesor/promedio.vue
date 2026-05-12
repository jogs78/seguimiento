<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-edit"></i>
          Evaluación de Seguimiento
        </p>
      </div>

      <div class="centro">
        <div class="form-card">
          <!-- Información del seguimiento -->
          <div class="info-section">
            <div class="info-item">
              <span class="info-label"><i class="fas fa-hashtag"></i> Seguimiento número:</span>
              <span class="info-value">{{ consecutivo | formatConsecutivo }}</span>
            </div>
            <div class="info-item">
              <span class="info-label"><i class="fas fa-user-graduate"></i> Estudiante:</span>
              <span class="info-value">{{ estudiante.nombre }} {{ estudiante.apellido_paterno }}</span>
            </div>
            <div class="info-item">
              <span class="info-label"><i class="fas fa-project-diagram"></i> Proyecto:</span>
              <span class="info-value">{{ estudiante.proyecto?.nombre || 'N/A' }}</span>
            </div>
          </div>

          <!-- Formulario de calificación -->
          <form @submit.prevent="guardarCalificacion" class="formulario">
            
            <!-- Comentarios (si es evaluador) -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-comment-dots"></i>
                Deje sus comentarios aquí
              </label>
              <textarea 
                v-model="comentarios"
                class="textarea"
                rows="5"
                placeholder="Escribe tus comentarios sobre el desempeño del estudiante en este seguimiento..."
              ></textarea>
              <small class="help-text">Tus comentarios serán visibles para el estudiante y el coordinador</small>
            </div>

            <!-- Promedio -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-chart-line"></i>
                Promedio
              </label>
              <div class="input-group">
                <i class="fas fa-star input-icon"></i>
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
              <small class="help-text">El promedio actual del estudiante se actualizará automáticamente</small>
            </div>

            <!-- Resumen de notas (opcional) -->
            <div class="promedio-display" v-if="promedio">
              <div class="promedio-card">
                <span class="promedio-label">Promedio ingresado:</span>
                <span class="promedio-valor" :class="clasePromedio">
                  {{ promedio }} / 100
                </span>
              </div>
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
    default: 'primer' // primer, segundo, ultimo
  },
  calificacionExistente: {
    type: Object,
    default: null
  }
})

const comentarios = ref(props.calificacionExistente?.comentarios || '')
const promedio = ref(props.calificacionExistente?.promedio || '')
const cargando = ref(false)
const errores = ref({})

// Formatear consecutivo
const formatConsecutivo = (value) => {
  const map = {
    'primer': '1er Seguimiento',
    'segundo': '2do Seguimiento',
    'ultimo': 'Seguimiento Final'
  }
  return map[value] || value
}

// Clase del color según el promedio
const clasePromedio = computed(() => {
  const num = parseFloat(promedio.value)
  if (isNaN(num)) return ''
  if (num >= 90) return 'excelente'
  if (num >= 80) return 'bueno'
  if (num >= 70) return 'regular'
  if (num >= 60) return 'suficiente'
  return 'insuficiente'
})

const guardarCalificacion = () => {
  // Validación
  errores.value = {}
  
  if (!promedio.value && promedio.value !== 0) {
    errores.value.promedio = 'El promedio es requerido'
    return
  }
  
  const numPromedio = parseFloat(promedio.value)
  if (isNaN(numPromedio) || numPromedio < 0 || numPromedio > 100) {
    errores.value.promedio = 'El promedio debe estar entre 0 y 100'
    return
  }
  
  cargando.value = true
  
  router.post(route('asesor.guardar-seguimiento'), {
    estudiante_id: props.estudiante.id,
    consecutivo: props.consecutivo,
    promedio: numPromedio,
    comentarios: comentarios.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Calificación guardada!',
        text: `El ${formatConsecutivo(props.consecutivo)} ha sido registrado correctamente`,
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
  color: #050E3C;
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

.info-section {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 15px 20px;
  margin-bottom: 25px;
}

.info-item {
  display: flex;
  padding: 8px 0;
}

.info-label {
  width: 160px;
  font-weight: 600;
  color: #555;
}

.info-label i {
  margin-right: 8px;
  color: #050E3C;
}

.info-value {
  flex: 1;
  color: #333;
  font-weight: 500;
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

.help-text {
  display: block;
  margin-top: 5px;
  font-size: 12px;
  color: #6c757d;
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

.promedio-display {
  margin: 20px 0;
}

.promedio-card {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 15px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.promedio-label {
  font-weight: 600;
  color: #555;
}

.promedio-valor {
  font-size: 24px;
  font-weight: bold;
}

.promedio-valor.excelente { color: #28a745; }
.promedio-valor.bueno { color: #17a2b8; }
.promedio-valor.regular { color: #ffc107; }
.promedio-valor.suficiente { color: #fd7e14; }
.promedio-valor.insuficiente { color: #dc3545; }

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
  
  .promedio-card {
    flex-direction: column;
    text-align: center;
    gap: 10px;
  }
}
</style>