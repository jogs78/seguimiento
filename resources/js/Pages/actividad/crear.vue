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

                  <!-- Semanas -->
                  <div class="form-group">
                    <label class="parrafo">¿En cuántas semanas realizarás esta actividad? *</label>
                    <input 
                      type="number" 
                      v-model="actividad.semanas"
                      class="input-number"
                      :class="{ 'error': errores.semanas && errores.semanas[index] }"
                      min="1"
                      step="1"
                    />
                    <span v-if="errores.semanas && errores.semanas[index]" class="error-mensaje">
                      {{ errores.semanas[index] }}
                    </span>
                  </div>

                  <!-- Orden con toggle recurrente -->
                  <div class="form-group">
                    <label class="parrafo">Orden de la actividad *</label>
                    
                    <!-- Toggle para actividad recurrente -->
                    <div class="form-group">
                      <label class="toggle-switch">
                        <input 
                          type="checkbox" 
                          v-model="actividad.esRecurrente"
                          @change="onToggleRecurrente(index)"
                        />
                        <span class="toggle-slider"></span>
                      </label>
                      <span class="toggle-label">
                        {{ actividad.esRecurrente ? '✅ Actividad recurrente (aparece en múltiples órdenes)' : '➡️ Actividad única' }}
                      </span>
                    </div>

                    <!-- Orden único -->
                    <div v-if="!actividad.esRecurrente" class="orden-simple">
                      <input 
                        type="number" 
                        v-model.number="actividad.ordenUnico"
                        class="input-number"
                        min="1"
                        placeholder="Ej: 3"
                        @change="actualizarOrdenTexto(index)"
                      />
                      <small class="help-text">La actividad aparecerá una sola vez en este orden</small>
                    </div>

                    <!-- Órdenes recurrentes -->
                    <div v-else class="orden-recurrente">
                      <div class="agregar-orden">
                        <input 
                          type="number" 
                          v-model.number="actividad.nuevoOrden"
                          class="input-number-small"
                          min="1"
                          placeholder="Nuevo orden"
                          @keyup.enter="agregarOrden(index)"
                        />
                        <button type="button" class="btn-agregar-orden" @click="agregarOrden(index)" :disabled="!actividad.nuevoOrden">
                          <i class="fas fa-plus"></i>
                          Agregar
                        </button>
                      </div>
                      
                      <div class="ordenes-lista" v-if="actividad.ordenesLista && actividad.ordenesLista.length > 0">
                        <label class="label-lista">Órdenes seleccionados:</label>
                        <div class="ordenes-tags">
                          <span 
                            v-for="ord in actividad.ordenesLista" 
                            :key="ord"
                            class="orden-tag"
                          >
                            {{ ord }}
                            <button type="button" class="remove-orden" @click="removerOrden(index, ord)">
                              <i class="fas fa-times"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                      
                      <small class="help-text">La actividad aparecerá en todos estos órdenes</small>
                    </div>

                    <!-- Vista previa del orden final -->
                    <div class="orden-preview" v-if="actividad.ordenTexto">
                      <i class="fas fa-eye"></i>
                      <strong>Orden final:</strong> {{ actividad.ordenTexto }}
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
  ordenesExistentes: Array
})

// Estructura de una actividad
const crearActividadVacia = (ordenInicial = 1) => ({
  nombre: '',
  descripcion: '',
  semanas: 1,
  ordenTexto: ordenInicial.toString(),
  esRecurrente: false,
  ordenUnico: ordenInicial,
  ordenesLista: [ordenInicial],
  nuevoOrden: ''
})

// Inicializar con una actividad
const actividades = ref([
  crearActividadVacia(1)
])

const guardando = ref(false)
const errores = reactive({
  nombre: {},
  descripcion: {},
  semanas: {},
  orden: {}
})

// Actualizar el texto del orden (se guarda como string)
const actualizarOrdenTexto = (index) => {
  const act = actividades.value[index]
  if (act.esRecurrente) {
    if (act.ordenesLista && act.ordenesLista.length > 0) {
      act.ordenTexto = act.ordenesLista.join(',')
    } else {
      act.ordenTexto = ''
    }
  } else {
    act.ordenTexto = act.ordenUnico ? act.ordenUnico.toString() : ''
  }
}

// Al cambiar el toggle
const onToggleRecurrente = (index) => {
  const act = actividades.value[index]
  if (act.esRecurrente) {
    // Cambiando a recurrente: usar los órdenes existentes
    if (act.ordenUnico && (!act.ordenesLista || !act.ordenesLista.includes(act.ordenUnico))) {
      act.ordenesLista = [act.ordenUnico]
    } else if (!act.ordenesLista || act.ordenesLista.length === 0) {
      act.ordenesLista = [1]
    }
  } else {
    // Cambiando a único: tomar el primer orden de la lista
    act.ordenUnico = (act.ordenesLista && act.ordenesLista.length > 0) ? act.ordenesLista[0] : 1
  }
  actualizarOrdenTexto(index)
}

// Agregar orden a la lista
const agregarOrden = (index) => {
  const act = actividades.value[index]
  if (!act.nuevoOrden || act.nuevoOrden < 1) return
  
  if (!act.ordenesLista.includes(act.nuevoOrden)) {
    act.ordenesLista.push(act.nuevoOrden)
    act.ordenesLista.sort((a, b) => a - b)
    act.nuevoOrden = ''
    actualizarOrdenTexto(index)
  } else {
    Swal.fire({
      icon: 'warning',
      title: 'Orden duplicado',
      text: `El orden ${act.nuevoOrden} ya está en la lista`,
      confirmButtonText: 'OK'
    })
  }
}

// Remover orden de la lista
const removerOrden = (index, orden) => {
  const act = actividades.value[index]
  const pos = act.ordenesLista.indexOf(orden)
  if (pos > -1) {
    act.ordenesLista.splice(pos, 1)
    actualizarOrdenTexto(index)
  }
}

// Agregar nueva actividad
const agregarActividad = () => {
  const nuevoOrden = actividades.value.length + 1
  actividades.value.push(crearActividadVacia(nuevoOrden))
}

// Eliminar actividad y reordenar
const eliminarActividad = (index) => {
  actividades.value.splice(index, 1)
  // Reasignar órdenes automáticamente
  actividades.value.forEach((act, i) => {
    const nuevoOrd = i + 1
    if (act.esRecurrente) {
      act.ordenesLista = [nuevoOrd]
    } else {
      act.ordenUnico = nuevoOrd
    }
    actualizarOrdenTexto(i)
  })
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
  
  errores.nombre = {}
  errores.descripcion = {}
  errores.semanas = {}
  errores.orden = {}
  
  for (let index = 0; index < actividades.value.length; index++) {
    const act = actividades.value[index]
    
    if (!act.nombre || !act.nombre.trim()) {
      errores.nombre[index] = 'El nombre de la actividad es requerido'
      valido = false
    }
    
    if (!act.descripcion || !act.descripcion.trim()) {
      errores.descripcion[index] = 'La descripción es requerida'
      valido = false
    }
    
    if (!act.semanas || act.semanas < 1) {
      errores.semanas[index] = 'Las semanas deben ser al menos 1'
      valido = false
    }
    
    if (!act.ordenTexto || act.ordenTexto.trim() === '') {
      errores.orden[index] = 'El orden es requerido'
      valido = false
    }
  }
  
  return valido
}

// Guardar actividades
const guardarActividades = () => {
  // Actualizar textos de orden antes de enviar
  actividades.value.forEach((_, index) => {
    actualizarOrdenTexto(index)
  })
  
  if (!validarFormulario()) {
    Swal.fire({
      icon: 'error',
      title: 'Errores en el formulario',
      html: '<ul style="text-align: left;">' + 
        Object.values(errores.nombre).map(e => `<li>${e}</li>`).join('') +
        Object.values(errores.descripcion).map(e => `<li>${e}</li>`).join('') +
        Object.values(errores.semanas).map(e => `<li>${e}</li>`).join('') +
        Object.values(errores.orden).map(e => `<li>${e}</li>`).join(''),
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
      semanas: act.semanas,
      orden: act.ordenTexto
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