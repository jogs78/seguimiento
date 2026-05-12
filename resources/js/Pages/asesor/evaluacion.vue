<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-calendar-check"></i>
          Evaluación de Período
        </p>
      </div>

      <div class="centro">
        <div class="form-card">
          <form @submit.prevent="guardarEvaluacion" class="formulario">
            
            <!-- Número del Período -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-hashtag"></i>
                Número del Período
              </label>
              <div class="input-group">
                <i class="fas fa-calendar-alt input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.numero_periodo"
                  class="input-text"
                  :class="{ 'error': errores.numero_periodo }"
                  placeholder="Ej: 1, 2, 3..."
                  required
                />
              </div>
              <span v-if="errores.numero_periodo" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.numero_periodo }}
              </span>
              <small class="help-text">Número consecutivo del período de evaluación</small>
            </div>

            <!-- Nombre del Residente -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-user-graduate"></i>
                Nombre del Residente
              </label>
              <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.nombre_residente"
                  class="input-text"
                  :class="{ 'error': errores.nombre_residente }"
                  placeholder="Nombre completo del estudiante"
                  required
                />
              </div>
              <span v-if="errores.nombre_residente" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.nombre_residente }}
              </span>
            </div>

            <!-- Número de Control -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-id-card"></i>
                Número de Control
              </label>
              <div class="input-group">
                <i class="fas fa-qrcode input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.numero_control"
                  class="input-text"
                  :class="{ 'error': errores.numero_control }"
                  placeholder="Ej: A12345678"
                  required
                />
              </div>
              <span v-if="errores.numero_control" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.numero_control }}
              </span>
              <small class="help-text">Número de control del estudiante</small>
            </div>

            <!-- Nombre del Proyecto -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-project-diagram"></i>
                Nombre del Proyecto
              </label>
              <div class="input-group">
                <i class="fas fa-folder-open input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.nombre_proyecto"
                  class="input-text"
                  :class="{ 'error': errores.nombre_proyecto }"
                  placeholder="Título del proyecto de residencia"
                  required
                />
              </div>
              <span v-if="errores.nombre_proyecto" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.nombre_proyecto }}
              </span>
            </div>

            <!-- Período de realización -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-calendar-week"></i>
                Período de realización de la residencia profesional
              </label>
              <div class="input-group">
                <i class="fas fa-calendar-range input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.periodo_realizacion"
                  class="input-text"
                  :class="{ 'error': errores.periodo_realizacion }"
                  placeholder="Ej: Enero-Junio 2024"
                  required
                />
              </div>
              <span v-if="errores.periodo_realizacion" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.periodo_realizacion }}
              </span>
              <small class="help-text">Período en el que se realizó la residencia</small>
            </div>

            <!-- Calificación Parcial -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-chart-line"></i>
                Calificación Parcial (Promedio de ambas evaluaciones)
              </label>
              <div class="input-group">
                <i class="fas fa-star input-icon"></i>
                <input 
                  type="number" 
                  v-model="form.calificacion_parcial"
                  class="input-text"
                  :class="{ 'error': errores.calificacion_parcial }"
                  min="0"
                  max="100"
                  step="0.1"
                  placeholder="Ingresa la calificación (0-100)"
                  required
                />
                <span class="input-suffix">/ 100</span>
              </div>
              <span v-if="errores.calificacion_parcial" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.calificacion_parcial }}
              </span>
              <div class="calificacion-hint" v-if="form.calificacion_parcial">
                <div class="hint-text" :class="claseCalificacion">
                  <i class="fas" :class="iconoCalificacion"></i>
                  {{ textoCalificacion }}
                </div>
              </div>
            </div>

            <!-- Botones de acción -->
            <div class="botones-container">
              <button 
                type="submit" 
                class="btn-guardar"
                :disabled="cargando"
              >
                <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-save'"></i>
                {{ cargando ? 'Guardando...' : 'Guardar Evaluación' }}
              </button>
              
              <button 
                type="button" 
                class="btn-limpiar"
                @click="limpiarFormulario"
              >
                <i class="fas fa-eraser"></i>
                Limpiar
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
  datosIniciales: {
    type: Object,
    default: () => ({})
  }
})

// Formulario reactivo
const form = ref({
  numero_periodo: props.datosIniciales?.numero_periodo || '',
  nombre_residente: props.datosIniciales?.nombre_residente || '',
  numero_control: props.datosIniciales?.numero_control || '',
  nombre_proyecto: props.datosIniciales?.nombre_proyecto || '',
  periodo_realizacion: props.datosIniciales?.periodo_realizacion || '',
  calificacion_parcial: props.datosIniciales?.calificacion_parcial || ''
})

const cargando = ref(false)
const errores = ref({})

// Calcular texto e icono según calificación
const claseCalificacion = computed(() => {
  const calif = parseFloat(form.value.calificacion_parcial)
  if (isNaN(calif)) return ''
  if (calif >= 90) return 'excelente'
  if (calif >= 80) return 'bueno'
  if (calif >= 70) return 'regular'
  if (calif >= 60) return 'suficiente'
  return 'insuficiente'
})

const iconoCalificacion = computed(() => {
  const clase = claseCalificacion.value
  if (clase === 'excelente') return 'fa-trophy'
  if (clase === 'bueno') return 'fa-smile'
  if (clase === 'regular') return 'fa-meh'
  if (clase === 'suficiente') return 'fa-frown'
  return 'fa-sad-tear'
})

const textoCalificacion = computed(() => {
  const calif = parseFloat(form.value.calificacion_parcial)
  if (isNaN(calif)) return ''
  if (calif >= 90) return '¡Excelente! Desempeño sobresaliente'
  if (calif >= 80) return 'Muy bien - Buen desempeño'
  if (calif >= 70) return 'Regular - Cumple con lo esperado'
  if (calif >= 60) return 'Suficiente - Aprobado'
  return 'Insuficiente - Requiere mejorar'
})

// Validar formulario
const validarFormulario = () => {
  const nuevosErrores = {}
  
  if (!form.value.numero_periodo.trim()) {
    nuevosErrores.numero_periodo = 'El número del período es requerido'
  }
  
  if (!form.value.nombre_residente.trim()) {
    nuevosErrores.nombre_residente = 'El nombre del residente es requerido'
  }
  
  if (!form.value.numero_control.trim()) {
    nuevosErrores.numero_control = 'El número de control es requerido'
  }
  
  if (!form.value.nombre_proyecto.trim()) {
    nuevosErrores.nombre_proyecto = 'El nombre del proyecto es requerido'
  }
  
  if (!form.value.periodo_realizacion.trim()) {
    nuevosErrores.periodo_realizacion = 'El período de realización es requerido'
  }
  
  if (!form.value.calificacion_parcial && form.value.calificacion_parcial !== 0) {
    nuevosErrores.calificacion_parcial = 'La calificación parcial es requerida'
  } else {
    const calif = parseFloat(form.value.calificacion_parcial)
    if (isNaN(calif) || calif < 0 || calif > 100) {
      nuevosErrores.calificacion_parcial = 'La calificación debe estar entre 0 y 100'
    }
  }
  
  errores.value = nuevosErrores
  return Object.keys(nuevosErrores).length === 0
}

// Limpiar formulario
const limpiarFormulario = () => {
  form.value = {
    numero_periodo: '',
    nombre_residente: '',
    numero_control: '',
    nombre_proyecto: '',
    periodo_realizacion: '',
    calificacion_parcial: ''
  }
  errores.value = {}
}

// Guardar evaluación
const guardarEvaluacion = () => {
  if (!validarFormulario()) {
    Swal.fire({
      icon: 'error',
      title: 'Errores en el formulario',
      html: '<ul style="text-align: left;">' + 
        Object.values(errores.value).map(e => `<li><i class="fas fa-times"></i> ${e}</li>`).join('') + 
        '</ul>',
      confirmButtonText: 'Corregir'
    })
    return
  }
  
  cargando.value = true
  
  router.post(route('asesor.guardar-evaluacion-periodo'), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Evaluación guardada!',
        text: 'La evaluación de período ha sido registrada correctamente',
        confirmButtonText: 'OK'
      }).then(() => {
        limpiarFormulario()
      })
    },
    onError: (errors) => {
      const nuevosErrores = {}
      if (errors.numero_periodo) nuevosErrores.numero_periodo = errors.numero_periodo
      if (errors.nombre_residente) nuevosErrores.nombre_residente = errors.nombre_residente
      if (errors.numero_control) nuevosErrores.numero_control = errors.numero_control
      if (errors.nombre_proyecto) nuevosErrores.nombre_proyecto = errors.nombre_proyecto
      if (errors.periodo_realizacion) nuevosErrores.periodo_realizacion = errors.periodo_realizacion
      if (errors.calificacion_parcial) nuevosErrores.calificacion_parcial = errors.calificacion_parcial
      
      errores.value = nuevosErrores
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: Object.values(errors)[0] || 'No se pudo guardar la evaluación',
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

.formulario {
  width: 100%;
}

.form-group {
  margin-bottom: 24px;
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
  background: white;
  padding-left: 5px;
}

.help-text {
  display: block;
  margin-top: 5px;
  font-size: 12px;
  color: #6c757d;
}

.help-text i {
  margin-right: 4px;
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

.calificacion-hint {
  margin-top: 10px;
}

.hint-text {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 500;
}

.hint-text.excelente {
  background: #d4edda;
  color: #155724;
}

.hint-text.bueno {
  background: #d1ecf1;
  color: #0c5460;
}

.hint-text.regular {
  background: #fff3cd;
  color: #856404;
}

.hint-text.suficiente {
  background: #f8d7da;
  color: #721c24;
}

.hint-text.insuficiente {
  background: #f8d7da;
  color: #721c24;
}

.botones-container {
  display: flex;
  gap: 15px;
  margin-top: 30px;
  flex-wrap: wrap;
}

.btn-guardar {
  flex: 2;
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

.btn-limpiar {
  flex: 1;
  background-color: #ffc107;
  color: #333;
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

.btn-limpiar:hover {
  background-color: #e0a800;
  transform: translateY(-2px);
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
  
  .subtitulo {
    font-size: 22px;
  }
  
  .botones-container {
    flex-direction: column;
  }
  
  .btn-guardar, .btn-limpiar, .btn-cancelar {
    width: 100%;
  }
}
</style>