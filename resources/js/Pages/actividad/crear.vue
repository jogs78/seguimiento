<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">Actividades del Proyecto: {{ proyecto.nombre }}</p>
      </div>
      
      <div class="horizontal" style="margin-bottom: 20px;">
        <p class="parrafo">Haz una descripción detallada de las actividades específicas que tienes planeadas</p>
      </div>

      <div class="centro">
        <form @submit.prevent="guardarActividades" class="form-principal">
          <div id="formularios-container">
            <div 
              v-for="(actividad, index) in actividades" 
              :key="index"
              class="formulario-actividad"
            >
              <div class="card">
                <div class="card-header">
                  <h3>Actividad {{ index + 1 }}</h3>
                  <button 
                    v-if="actividades.length > 1"
                    type="button" 
                    class="btn-eliminar"
                    @click="eliminarActividad(index)"
                  >
                    ✕ Eliminar
                  </button>
                </div>
                
                <div class="card-body">
                  <!-- Nombre -->
                  <div class="form-group">
                    <label class="parrafo">Nombre de la actividad *</label>
                    <input 
                      type="text" 
                      v-model="actividad.nombre"
                      class="input-text"
                      :class="{ 'error': errores.nombre && errores.nombre[index] }"
                    />
                    <span v-if="errores.nombre && errores.nombre[index]" class="error-mensaje">
                      {{ errores.nombre[index] }}
                    </span>
                  </div>

                  <!-- Descripción -->
                  <div class="form-group">
                    <label class="parrafo">Describe cómo realizarás esta actividad *</label>
                    <textarea 
                      v-model="actividad.descripcion"
                      class="textarea-auto"
                      :class="{ 'error': errores.descripcion && errores.descripcion[index] }"
                      rows="3"
                      @input="autoResize($event)"
                    ></textarea>
                    <span v-if="errores.descripcion && errores.descripcion[index]" class="error-mensaje">
                      {{ errores.descripcion[index] }}
                    </span>
                  </div>

                  <!-- Semanas - Ahora es semana_inicio y semana_fin -->
                  <div class="form-group-row">
                    <div class="form-group half">
                      <label class="parrafo">Semana de inicio *</label>
                      <input 
                        type="number" 
                        v-model.number="actividad.semana_inicio"
                        class="input-number"
                        :class="{ 'error': errores.semana_inicio && errores.semana_inicio[index] }"
                        min="1"
                      />
                      <span v-if="errores.semana_inicio && errores.semana_inicio[index]" class="error-mensaje">
                        {{ errores.semana_inicio[index] }}
                      </span>
                    </div>
                    
                    <div class="form-group half">
                      <label class="parrafo">Semana de fin *</label>
                      <input 
                        type="number" 
                        v-model.number="actividad.semana_fin"
                        class="input-number"
                        :class="{ 'error': errores.semana_fin && errores.semana_fin[index] }"
                        min="1"
                      />
                      <span v-if="errores.semana_fin && errores.semana_fin[index]" class="error-mensaje">
                        {{ errores.semana_fin[index] }}
                      </span>
                    </div>
                  </div>

                  <!-- Orden (único, ya no múltiple) -->
                  <div class="form-group">
                    <label class="parrafo">Orden de la actividad *</label>
                    <div class="orden-simple">
                      <input 
                        type="number" 
                        v-model.number="actividad.orden"
                        class="input-number"
                        :class="{ 'error': errores.orden && errores.orden[index] }"
                        min="1"
                        placeholder="Ej: 1, 2, 3..."
                      />
                      <small class="help-text">Orden en que aparecerá esta actividad en el cronograma</small>
                      
                      <!-- Mostrar órdenes ocupados -->
                      <div v-if="ordenesExistentes && ordenesExistentes.length > 0" class="ordenes-ocupadas">
                        <small class="warning-text">
                          <i class="fas fa-info-circle"></i>
                          Órdenes ya ocupados: {{ ordenesExistentes.join(', ') }}
                        </small>
                      </div>
                    </div>
                    <span v-if="errores.orden && errores.orden[index]" class="error-mensaje">
                      {{ errores.orden[index] }}
                    </span>
                  </div>
                </div>
              </div>
              <hr v-if="index < actividades.length - 1" />
            </div>
          </div>

          <div class="centro">
            <button type="button" class="btn-agregar" @click="agregarActividad">
              + Agregar otra actividad
            </button>
          </div>

          <div class="centro botones-accion">
            <button type="submit" class="btn-guardar" :disabled="guardando">
              {{ guardando ? 'Guardando...' : 'Guardar Actividades' }}
            </button>
            <Link :href="route('proyectos.show', proyecto.id)" class="btn-cancelar">
              Cancelar
            </Link>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  proyecto: Object,
  ordenesExistentes: Array  // Órdenes ya ocupados por otras actividades
})

// Estructura de una actividad (nueva versión)
const crearActividadVacia = (ordenInicial = null) => ({
  nombre: '',
  descripcion: '',
  semana_inicio: 1,
  semana_fin: 4,
  orden: ordenInicial || obtenerSiguienteOrdenDisponible()
})

// Obtener el siguiente orden disponible
const obtenerSiguienteOrdenDisponible = () => {
  if (!props.ordenesExistentes || props.ordenesExistentes.length === 0) {
    return 1
  }
  // Buscar el primer número faltante
  for (let i = 1; i <= props.ordenesExistentes.length + 1; i++) {
    if (!props.ordenesExistentes.includes(i)) {
      return i
    }
  }
  return props.ordenesExistentes.length + 1
}

// Inicializar con una actividad
const actividades = ref([
  crearActividadVacia()
])

const guardando = ref(false)
const errores = reactive({
  nombre: {},
  descripcion: {},
  semana_inicio: {},
  semana_fin: {},
  orden: {}
})

// Agregar nueva actividad
const agregarActividad = () => {
  actividades.value.push(crearActividadVacia())
}

// Eliminar actividad
const eliminarActividad = (index) => {
  actividades.value.splice(index, 1)
}

// Auto-resize para textarea
const autoResize = (event) => {
  const textarea = event.target
  textarea.style.height = 'auto'
  textarea.style.height = textarea.scrollHeight + 'px'
}

// Validar antes de guardar
const validarFormulario = () => {
  let valido = true
  
  // Limpiar errores anteriores
  errores.nombre = {}
  errores.descripcion = {}
  errores.semana_inicio = {}
  errores.semana_fin = {}
  errores.orden = {}
  
  const ordenesUtilizados = []
  
  for (let index = 0; index < actividades.value.length; index++) {
    const act = actividades.value[index]
    
    // Validar nombre
    if (!act.nombre || !act.nombre.trim()) {
      errores.nombre[index] = 'El nombre de la actividad es requerido'
      valido = false
    }
    
    // Validar descripción
    if (!act.descripcion || !act.descripcion.trim()) {
      errores.descripcion[index] = 'La descripción es requerida'
      valido = false
    }
    
    // Validar semana_inicio
    if (!act.semana_inicio || act.semana_inicio < 1) {
      errores.semana_inicio[index] = 'La semana de inicio debe ser al menos 1'
      valido = false
    }
    
    // Validar semana_fin
    if (!act.semana_fin || act.semana_fin < 1) {
      errores.semana_fin[index] = 'La semana de fin debe ser al menos 1'
      valido = false
    }
    
    // Validar que semana_fin >= semana_inicio
    if (act.semana_fin && act.semana_inicio && act.semana_fin < act.semana_inicio) {
      errores.semana_fin[index] = 'La semana de fin debe ser mayor o igual a la semana de inicio'
      valido = false
    }
    
    // Validar orden
    if (!act.orden || act.orden < 1) {
      errores.orden[index] = 'El orden es requerido y debe ser mayor a 0'
      valido = false
    } else if (ordenesUtilizados.includes(act.orden)) {
      errores.orden[index] = `El orden ${act.orden} ya está siendo utilizado por otra actividad`
      valido = false
    } else {
      // Verificar contra órdenes existentes en la base de datos
      if (props.ordenesExistentes && props.ordenesExistentes.includes(act.orden)) {
        errores.orden[index] = `El orden ${act.orden} ya está ocupado por otra actividad guardada`
        valido = false
      } else {
        ordenesUtilizados.push(act.orden)
      }
    }
  }
  
  return valido
}

// Guardar actividades
const guardarActividades = () => {
  if (!validarFormulario()) {
    // Construir mensaje de error
    const mensajesError = []
    Object.values(errores.nombre).forEach(e => mensajesError.push(`• ${e}`))
    Object.values(errores.descripcion).forEach(e => mensajesError.push(`• ${e}`))
    Object.values(errores.semana_inicio).forEach(e => mensajesError.push(`• ${e}`))
    Object.values(errores.semana_fin).forEach(e => mensajesError.push(`• ${e}`))
    Object.values(errores.orden).forEach(e => mensajesError.push(`• ${e}`))
    
    Swal.fire({
      icon: 'error',
      title: 'Errores en el formulario',
      html: '<ul style="text-align: left;">' + mensajesError.map(m => `<li>${m}</li>`).join('') + '</ul>',
      confirmButtonText: 'Corregir'
    })
    return
  }
  
  guardando.value = true
  
  // Preparar datos para enviar
  const datosEnvio = {
    actividades: actividades.value.map(act => ({
      nombre: act.nombre,
      descripcion: act.descripcion,
      semana_inicio: act.semana_inicio,
      semana_fin: act.semana_fin,
      orden: act.orden
    }))
  }
  
  router.post(route('proyectos.actividades.store', props.proyecto.id), datosEnvio, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'Actividades guardadas correctamente',
        confirmButtonText: 'OK'
      }).then(() => {
        router.visit(route('proyectos.show', props.proyecto.id))
      })
    },
    onError: (errors) => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errors.message || 'Hubo un error al guardar las actividades',
        confirmButtonText: 'OK'
      })
    },
    onFinish: () => {
      guardando.value = false
    }
  })
}
</script>

<style scoped>
/* Estilos existentes... */

.orden-container {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.orden-sugerencia {
  font-size: 12px;
  color: #28a745;
  background-color: #d4edda;
  padding: 2px 8px;
  border-radius: 12px;
}

.input-number {
  width: 100%;
}

.input-number-small {
  width: 120px;
  padding: 8px 12px;
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 6px;
}

/* Toggle switch */
.toggle-container {
  display: flex;
  align-items: center;
  gap: 15px;
  margin: 15px 0;
  padding: 10px;
  background: #f8f9fa;
  border-radius: 10px;
}

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 34px;
}

.toggle-slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

input:checked + .toggle-slider {
  background-color: #28a745;
}

input:checked + .toggle-slider:before {
  transform: translateX(26px);
}

.toggle-label {
  font-size: 14px;
  font-weight: 500;
  color: #333;
}

/* Órdenes recurrentes */
.orden-simple {
  margin-top: 10px;
}

.orden-recurrente {
  margin-top: 10px;
}

.agregar-orden {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}

.btn-agregar-orden {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
}

.btn-agregar-orden:hover:not(:disabled) {
  background-color: #218838;
}

.btn-agregar-orden:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.ordenes-lista {
  margin-bottom: 10px;
}

.label-lista {
  font-size: 13px;
  font-weight: 500;
  color: #555;
  margin-bottom: 8px;
  display: block;
}

.ordenes-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.orden-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background-color: #050E3C;
  color: white;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 500;
}

.remove-orden {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  font-size: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.remove-orden:hover {
  color: #ffc107;
}

.orden-preview {
  margin-top: 15px;
  padding: 10px;
  background: #e8f4fd;
  border-radius: 8px;
  font-size: 14px;
  color: #050E3C;
}

.orden-preview i {
  margin-right: 8px;
}

/* Resto de estilos existentes se mantienen */
.bodydiv {
  margin-left: 20px;
  margin-right: 20px;
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
  font-size: 32px;
  font-weight: bold;
  margin: 20px 0;
}

.parrafo {
  font-size: 18px;
  font-weight: 500;
  margin-bottom: 8px;
  color: #333;
}

.form-principal {
  width: 80%;
  max-width: 900px;
}

.formulario-actividad {
  margin-bottom: 30px;
}

.card {
  border: 1px solid #ddd;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  background-color: #f5f5f5;
  border-bottom: 1px solid #ddd;
}

.card-header h3 {
  margin: 0;
  color: #050E3C;
}

.card-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 20px;
  display: flex;
  flex-direction: column;
}

.input-text,
.input-number,
.textarea-auto {
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
  transition: border-color 0.3s;
}

.input-text:focus,
.input-number:focus,
.textarea-auto:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.input-text.error,
.input-number.error,
.textarea-auto.error {
  border-color: #dc3545;
}

.textarea-auto {
  resize: none;
  font-family: inherit;
}

.help-text {
  font-size: 12px;
  color: #6c757d;
  margin-top: 4px;
}

.error-mensaje {
  font-size: 12px;
  color: #dc3545;
  margin-top: 4px;
}

.btn-agregar {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  margin: 20px 0;
}

.btn-agregar:hover {
  background-color: #218838;
}

.btn-eliminar {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
}

.btn-eliminar:hover {
  background-color: #c82333;
}

.botones-accion {
  gap: 15px;
  margin-top: 20px;
  margin-bottom: 40px;
}

.btn-guardar {
  background-color: #050E3C;
  color: white;
  border: none;
  padding: 12px 25px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.btn-guardar:hover:not(:disabled) {
  background-color: #0a1a6e;
}

.btn-guardar:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancelar {
  background-color: #6c757d;
  color: white;
  text-decoration: none;
  padding: 12px 25px;
  border-radius: 5px;
  font-size: 16px;
}

.btn-cancelar:hover {
  background-color: #5a6268;
}

hr {
  margin: 20px 0;
  border: none;
  border-top: 2px dashed #ddd;
}

@media (max-width: 768px) {
  .form-principal {
    width: 95%;
  }
  
  .agregar-orden {
    flex-direction: column;
  }
  
  .input-number-small {
    width: 100%;
  }
}
</style>