<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-edit"></i>
          Actualizar Actividad
        </p>
      </div>

      <div class="centro">
        <div class="form-card">
          <form @submit.prevent="actualizarActividad" class="formulario">
            
            <!-- ✅ CAMPO OCULTO PARA cronograma_id -->
            <input type="hidden" v-model="form.cronograma_id" />

            <!-- Campo: Nombre -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-tag"></i>
                Nombre de la actividad
              </label>
              <input 
                type="text" 
                v-model="form.nombre"
                class="input-text"
                :class="{ 'error': errores.nombre }"
                placeholder="Ingrese el nombre de la actividad"
              />
              <span v-if="errores.nombre" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i> {{ errores.nombre }}
              </span>
            </div>

            <!-- Campo: Descripción -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-align-left"></i>
                Describe cómo realizarás esta actividad
              </label>
              <textarea 
                ref="textareaRef"
                v-model="form.descripcion"
                class="textarea-auto"
                :class="{ 'error': errores.descripcion }"
                rows="1"
                placeholder="Describe detalladamente la actividad..."
                @input="autoResize"
              ></textarea>
              <span v-if="errores.descripcion" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i> {{ errores.descripcion }}
              </span>
            </div>

            <!-- Campos: Semanas (inicio y fin) -->
            <div class="form-group-row">
              <div class="form-group half">
                <label class="parrafo">
                  <i class="fas fa-play-circle"></i>
                  Semana de inicio
                </label>
                <input 
                  type="number" 
                  v-model.number="form.semana_inicio"
                  class="input-number"
                  :class="{ 'error': errores.semana_inicio }"
                  min="1"
                  step="1"
                  placeholder="Semana inicial"
                />
                <span v-if="errores.semana_inicio" class="error-mensaje">
                  <i class="fas fa-exclamation-circle"></i> {{ errores.semana_inicio }}
                </span>
              </div>

              <div class="form-group half">
                <label class="parrafo">
                  <i class="fas fa-stop-circle"></i>
                  Semana de fin
                </label>
                <input 
                  type="number" 
                  v-model.number="form.semana_fin"
                  class="input-number"
                  :class="{ 'error': errores.semana_fin }"
                  min="1"
                  step="1"
                  placeholder="Semana final"
                />
                <span v-if="errores.semana_fin" class="error-mensaje">
                  <i class="fas fa-exclamation-circle"></i> {{ errores.semana_fin }}
                </span>
              </div>
            </div>

            <!-- Campo: Orden (único) -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-sort-numeric-down"></i>
                Orden de la actividad
              </label>
              
              <div class="orden-simple">
                <input 
                  type="number" 
                  v-model.number="form.orden"
                  class="input-number"
                  :class="{ 'error': errores.orden }"
                  min="1"
                  placeholder="Ej: 1, 2, 3..."
                />
                <small class="help-text">Orden en que aparecerá esta actividad en el cronograma</small>
                
                <!-- Mostrar órdenes ocupados por otras actividades -->
                <div v-if="ordenesOcupados && ordenesOcupados.length > 0" class="ordenes-ocupadas">
                  <i class="fas fa-info-circle"></i>
                  <small class="warning-text">
                    Órdenes ocupados por otras actividades: {{ ordenesOcupados.join(', ') }}
                  </small>
                </div>
              </div>

              <span v-if="errores.orden" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i> {{ errores.orden }}
              </span>
            </div>

            <!-- Mostrar información adicional -->
            <div v-if="cronogramaInfo" class="info-card">
              <i class="fas fa-info-circle"></i>
              <div class="info-content">
                <p><strong>Información actual:</strong></p>
                <p>📅 Semanas: {{ cronogramaInfo.semana_inicio }} a {{ cronogramaInfo.semana_fin }}</p>
                <p>🔢 Orden: {{ cronogramaInfo.orden }}</p>
              </div>
            </div>

            <!-- Botones -->
            <div class="botones-container">
              <button type="submit" class="btn-actualizar" :disabled="cargando">
                <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-save'"></i>
                {{ cargando ? 'Actualizando...' : 'Actualizar Actividad' }}
              </button>
              <Link :href="route('proyectos.show', proyectoId)" class="btn-cancelar">
                <i class="fas fa-times"></i> Cancelar
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  proyecto: { type: Object, required: true },
  actividad: { type: Object, required: true },
  cronograma: { type: Object, required: false },
  ordenesOcupados: { type: Array, default: () => [] },
  flash: Object
})

const proyectoId = props.proyecto.id
const actividadId = props.actividad.id

// ✅ Inicializar formulario con cronograma_id
const form = ref({
  nombre: props.actividad.nombre || '',
  descripcion: props.actividad.descripcion || '',
  semana_inicio: props.cronograma?.semana_inicio || 1,
  semana_fin: props.cronograma?.semana_fin || 4,
  orden: props.cronograma?.orden || 1,
  cronograma_id: props.cronograma?.id || null  // ← CAMPO IMPORTANTE
})

// Información del cronograma actual
const cronogramaInfo = computed(() => {
  if (props.cronograma) {
    return {
      semana_inicio: props.cronograma.semana_inicio,
      semana_fin: props.cronograma.semana_fin,
      orden: props.cronograma.orden
    }
  }
  return null
})

const cargando = ref(false)
const errores = ref({})
const textareaRef = ref(null)

// Auto-resize
const autoResize = () => {
  nextTick(() => {
    if (textareaRef.value) {
      textareaRef.value.style.height = 'auto'
      textareaRef.value.style.height = textareaRef.value.scrollHeight + 'px'
    }
  })
}

// Validar formulario
const validarFormulario = () => {
  const nuevosErrores = {}
  
  if (!form.value.nombre.trim()) {
    nuevosErrores.nombre = 'El nombre de la actividad es requerido'
  }
  
  if (!form.value.descripcion.trim()) {
    nuevosErrores.descripcion = 'La descripción es requerida'
  }
  
  if (!form.value.semana_inicio || form.value.semana_inicio < 1) {
    nuevosErrores.semana_inicio = 'La semana de inicio debe ser al menos 1'
  }
  
  if (!form.value.semana_fin || form.value.semana_fin < 1) {
    nuevosErrores.semana_fin = 'La semana de fin debe ser al menos 1'
  }
  
  if (form.value.semana_inicio && form.value.semana_fin && form.value.semana_fin < form.value.semana_inicio) {
    nuevosErrores.semana_fin = 'La semana de fin debe ser mayor o igual a la semana de inicio'
  }
  
  if (!form.value.orden || form.value.orden < 1) {
    nuevosErrores.orden = 'El orden debe ser mayor a 0'
  } else if (props.ordenesOcupados.includes(form.value.orden)) {
    nuevosErrores.orden = `El orden ${form.value.orden} ya está ocupado por otra actividad`
  }
  
  // ✅ Validar que cronograma_id esté presente
  if (!form.value.cronograma_id) {
    nuevosErrores.general = 'Error interno: No se encontró el cronograma asociado'
  }
  
  errores.value = nuevosErrores
  return Object.keys(nuevosErrores).length === 0
}

// Actualizar actividad
const actualizarActividad = () => {
  if (!validarFormulario()) {
    const mensajesError = []
    Object.values(errores.value).forEach(e => mensajesError.push(`• ${e}`))
    
    Swal.fire({
      icon: 'error',
      title: 'Errores en el formulario',
      html: '<ul style="text-align: left;">' + mensajesError.map(m => `<li>${m}</li>`).join('') + '</ul>',
      confirmButtonText: 'Corregir'
    })
    return
  }
  
  cargando.value = true
  
  // ✅ Datos a enviar (incluyendo cronograma_id)
  const datosEnvio = {
    nombre: form.value.nombre,
    descripcion: form.value.descripcion,
    semana_inicio: form.value.semana_inicio,
    semana_fin: form.value.semana_fin,
    orden: form.value.orden,
    cronograma_id: form.value.cronograma_id
  }
  
  router.put(route('proyectos.actividades.update', [proyectoId, actividadId]), datosEnvio, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Actualizada!',
        text: 'La actividad ha sido actualizada correctamente',
        confirmButtonText: 'OK'
      }).then(() => {
        router.visit(route('proyectos.show', proyectoId))
      })
    },
    onError: (errors) => {
      const nuevosErrores = {}
      if (errors.nombre) nuevosErrores.nombre = errors.nombre
      if (errors.descripcion) nuevosErrores.descripcion = errors.descripcion
      if (errors.semana_inicio) nuevosErrores.semana_inicio = errors.semana_inicio
      if (errors.semana_fin) nuevosErrores.semana_fin = errors.semana_fin
      if (errors.orden) nuevosErrores.orden = errors.orden
      if (errors.cronograma_id) nuevosErrores.general = errors.cronograma_id
      
      errores.value = nuevosErrores
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: Object.values(errors)[0] || 'No se pudo actualizar la actividad',
        confirmButtonText: 'OK'
      })
    },
    onFinish: () => {
      cargando.value = false
    }
  })
}

// Inicializar auto-resize
onMounted(() => {
  autoResize()
})
</script>

<style scoped>
/* Estilos actualizados */
.form-group-row {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group.half {
  flex: 1;
}

.orden-simple {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.ordenes-ocupadas {
  margin-top: 0.5rem;
  padding: 0.5rem 0.75rem;
  background-color: #fff3cd;
  border-radius: 6px;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.warning-text {
  color: #856404;
  font-size: 0.8rem;
}

.help-text {
  color: #6c757d;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.info-card {
  background-color: #e8f4fd;
  border-left: 4px solid #002455;
  padding: 0.75rem 1rem;
  border-radius: 8px;
  margin-bottom: 1.5rem;
  display: flex;
  gap: 0.75rem;
  align-items: flex-start;
}

.info-card i {
  color: #002455;
  font-size: 1.2rem;
  margin-top: 0.2rem;
}

.info-content p {
  margin: 0.25rem 0;
  font-size: 0.85rem;
}

.input-text,
.input-number,
.textarea-auto {
  width: 100%;
  padding: 0.6rem 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.input-text:focus,
.input-number:focus,
.textarea-auto:focus {
  outline: none;
  border-color: #002455;
  box-shadow: 0 0 0 3px rgba(0, 36, 85, 0.1);
}

.error {
  border-color: #dc3545 !important;
}

.error-mensaje {
  color: #dc3545;
  font-size: 0.75rem;
  margin-top: 0.25rem;
  display: block;
}

.botones-container {
  display: flex;
  gap: 1rem;
  justify-content: center;
  margin-top: 2rem;
}

.btn-actualizar {
  background: linear-gradient(135deg, #002455, #050E3C);
  color: white;
  border: none;
  padding: 0.6rem 1.5rem;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-actualizar:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 2px 8px rgba(0, 36, 85, 0.3);
}

.btn-actualizar:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancelar {
  background: #6c757d;
  color: white;
  border: none;
  padding: 0.6rem 1.5rem;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: background 0.2s;
}

.btn-cancelar:hover {
  background: #5a6268;
}

/* Responsive */
@media (max-width: 768px) {
  .form-group-row {
    flex-direction: column;
    gap: 1rem;
  }
  
  .botones-container {
    flex-direction: column;
  }
  
  .btn-actualizar,
  .btn-cancelar {
    width: 100%;
    justify-content: center;
  }
}
</style>
