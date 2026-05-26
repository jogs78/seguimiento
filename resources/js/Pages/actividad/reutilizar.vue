<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-copy"></i>
          Reutilizar Actividad
        </p>
      </div>

      <div class="centro">
        <div class="form-card">
          <!-- Información de la actividad original (solo lectura) -->
          <div class="info-card">
            <div class="info-header">
              <i class="fas fa-info-circle"></i>
              <h3>Actividad a reutilizar</h3>
            </div>
            <div class="info-content">
              <p><strong>Nombre:</strong> {{ actividad.nombre }}</p>
              <p><strong>Descripción:</strong> {{ actividad.descripcion }}</p>
              <div v-if="actividad.cronogramas && actividad.cronogramas.length > 0" class="info-original">
                <p><strong>Configuración original:</strong></p>
                <ul>
                  <li v-for="cron in actividad.cronogramas" :key="cron.id">
                    Orden: {{ cron.orden }} | Semanas: {{ cron.semana_inicio }} - {{ cron.semana_fin }}
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Formulario para nueva configuración -->
          <form @submit.prevent="reutilizarActividad" class="formulario">
            <div class="form-group-row">
              <div class="form-group half">
                <label class="parrafo">
                  <i class="fas fa-play-circle"></i>
                  Semana de inicio *
                </label>
                <input 
                  type="number" 
                  v-model.number="form.semana_inicio"
                  class="input-number"
                  :class="{ 'error': errores.semana_inicio }"
                  min="1"
                  placeholder="Ej: 1"
                />
                <span v-if="errores.semana_inicio" class="error-mensaje">
                  <i class="fas fa-exclamation-circle"></i> {{ errores.semana_inicio }}
                </span>
              </div>

              <div class="form-group half">
                <label class="parrafo">
                  <i class="fas fa-stop-circle"></i>
                  Semana de fin *
                </label>
                <input 
                  type="number" 
                  v-model.number="form.semana_fin"
                  class="input-number"
                  :class="{ 'error': errores.semana_fin }"
                  :min="form.semana_inicio || 1"
                  placeholder="Ej: 4"
                />
                <span v-if="errores.semana_fin" class="error-mensaje">
                  <i class="fas fa-exclamation-circle"></i> {{ errores.semana_fin }}
                </span>
              </div>
            </div>

            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-sort-numeric-down"></i>
                Orden de la actividad *
              </label>
              <div class="orden-simple">
                <input 
                  type="number" 
                  v-model.number="form.orden"
                  class="input-number"
                  :class="{ 'error': errores.orden }"
                  min="1"
                  placeholder="Ej: 5"
                />
                <small class="help-text">Orden en que aparecerá esta actividad en el cronograma</small>
                
                <div v-if="ordenesExistentes && ordenesExistentes.length > 0" class="ordenes-ocupadas">
                  <i class="fas fa-info-circle"></i>
                  <small class="warning-text">
                    Órdenes ocupados en este proyecto: {{ ordenesExistentes.join(', ') }}
                  </small>
                </div>
              </div>
              <span v-if="errores.orden" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i> {{ errores.orden }}
              </span>
            </div>

            <!-- Botones -->
            <div class="botones-container">
              <button type="submit" class="btn-reutilizar" :disabled="cargando">
                <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-copy'"></i>
                {{ cargando ? 'Reutilizando...' : 'Reutilizar Actividad' }}
              </button>
              <Link :href="route('proyectos.show', proyecto.id)" class="btn-cancelar">
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
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  proyecto: { type: Object, required: true },
  actividad: { type: Object, required: true },
  ordenesExistentes: { type: Array, default: () => [] }
})

const form = ref({
  semana_inicio: 1,
  semana_fin: 4,
  orden: null
})

const cargando = ref(false)
const errores = ref({})

// Validar formulario
const validarFormulario = () => {
  const nuevosErrores = {}
  
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
  } else if (props.ordenesExistentes.includes(form.value.orden)) {
    nuevosErrores.orden = `El orden ${form.value.orden} ya está ocupado`
  }
  
  errores.value = nuevosErrores
  return Object.keys(nuevosErrores).length === 0
}

// Reutilizar actividad
const reutilizarActividad = () => {
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
  
  router.post(route('proyectos.actividades.storeReutilizar', [props.proyecto.id, props.actividad.id]), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Reutilizada!',
        text: 'La actividad ha sido reutilizada correctamente',
        confirmButtonText: 'OK'
      }).then(() => {
        router.visit(route('proyectos.show', props.proyecto.id))
      })
    },
    onError: (errors) => {
      const nuevosErrores = {}
      if (errors.semana_inicio) nuevosErrores.semana_inicio = errors.semana_inicio
      if (errors.semana_fin) nuevosErrores.semana_fin = errors.semana_fin
      if (errors.orden) nuevosErrores.orden = errors.orden
      
      errores.value = nuevosErrores
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: Object.values(errors)[0] || 'No se pudo reutilizar la actividad',
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
.form-card {
  background: white;
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  width: 100%;
}

.info-card {
  background-color: #e8f4fd;
  border-left: 4px solid #002455;
  border-radius: 10px;
  padding: 1rem;
  margin-bottom: 2rem;
}

.info-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.info-header i {
  color: #002455;
  font-size: 1.2rem;
}

.info-header h3 {
  margin: 0;
  font-size: 1.1rem;
  color: #002455;
}

.info-content p {
  margin: 0.5rem 0;
}

.info-original {
  margin-top: 0.75rem;
  padding-top: 0.75rem;
  border-top: 1px solid #cce5ff;
}

.info-original ul {
  margin: 0.5rem 0 0 1.5rem;
}

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
}

.input-number {
  width: 100%;
  padding: 0.6rem 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 1rem;
}

.input-number:focus {
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

.btn-reutilizar {
  background: linear-gradient(135deg, #17a2b8, #138496);
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

.btn-reutilizar:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
}

.btn-reutilizar:disabled {
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
}

.btn-cancelar:hover {
  background: #5a6268;
}

@media (max-width: 768px) {
  .form-group-row {
    flex-direction: column;
    gap: 1rem;
  }
  
  .botones-container {
    flex-direction: column;
  }
  
  .btn-reutilizar,
  .btn-cancelar {
    width: 100%;
    justify-content: center;
  }
}
</style>