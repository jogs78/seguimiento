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

                  <!-- Orden -->
                  <div class="form-group">
                    <label class="parrafo">¿Cuál es el orden de esta actividad? *</label>
                    <input 
                      type="number" 
                      v-model="actividad.orden"
                      class="input-number"
                      :class="{ 'error': errores.orden && errores.orden[index] }"
                      min="1"
                      step="1"
                    />
                    <small class="help-text">Ej: 1 para primera actividad, 2 para segunda, etc.</small>
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
  ordenesExistentes: Array  // Este array viene vacío ([])
})

// Inicializar con una actividad y orden = 1
const actividades = ref([
  {
    nombre: '',
    descripcion: '',
    semanas: 1,
    orden: 1
  }
])

const guardando = ref(false)
const errores = reactive({
  nombre: {},
  descripcion: {},
  semanas: {},
  orden: {}
})

// Agregar nueva actividad (el orden se asigna automáticamente)
const agregarActividad = () => {
  const nuevoOrden = actividades.value.length + 1
  actividades.value.push({
    nombre: '',
    descripcion: '',
    semanas: 1,
    orden: nuevoOrden  // ← Orden automático basado en posición
  })
}

// Eliminar actividad y reordenar
const eliminarActividad = (index) => {
  actividades.value.splice(index, 1)
  // Reasignar órdenes automáticamente según posición
  actividades.value.forEach((act, i) => {
    act.orden = i + 1
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
  
  // Limpiar errores previos
  errores.nombre = {}
  errores.descripcion = {}
  errores.semanas = {}
  errores.orden = {}
  
  actividades.value.forEach((actividad, index) => {
    if (!actividad.nombre || !actividad.nombre.trim()) {
      errores.nombre[index] = 'El nombre de la actividad es requerido'
      valido = false
    }
    
    if (!actividad.descripcion || !actividad.descripcion.trim()) {
      errores.descripcion[index] = 'La descripción es requerida'
      valido = false
    }
    
    if (!actividad.semanas || actividad.semanas < 1) {
      errores.semanas[index] = 'Las semanas deben ser al menos 1'
      valido = false
    }
    
    if (!actividad.orden || actividad.orden < 1) {
      errores.orden[index] = 'El orden debe ser un número positivo'
      valido = false
    }
  })
  
  // Verificar órdenes duplicados en el mismo formulario
  const ordenes = actividades.value.map(a => a.orden)
  const ordenesDuplicadas = ordenes.filter((orden, i) => ordenes.indexOf(orden) !== i)
  
  if (ordenesDuplicadas.length > 0) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: `Los órdenes ${[...new Set(ordenesDuplicadas)].join(', ')} están duplicados. Cada actividad debe tener un orden único.`
    })
    valido = false
  }
  
  return valido
}

// Guardar actividades
const guardarActividades = () => {
  if (!validarFormulario()) {
    return
  }
  
  guardando.value = true
  
  router.post(route('proyectos.actividades.store', props.proyecto.id), {
    actividades: actividades.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'Actividades guardadas correctamente',
        confirmButtonText: 'OK'
      })
    },
    onError: (errors) => {
      console.error('Error:', errors)
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
  width: 100px;
}

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
</style>