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

            <!-- Campo: Semanas -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-calendar-week"></i>
                ¿En cuántas semanas realizarás esta actividad?
              </label>
              <input 
                type="number" 
                v-model.number="form.semanas"
                class="input-number"
                :class="{ 'error': errores.semanas }"
                min="1"
                step="1"
                placeholder="Número de semanas"
              />
              <span v-if="errores.semanas" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i> {{ errores.semanas }}
              </span>
            </div>

            <!-- Campo: Orden con toggle para múltiples -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-sort-numeric-down"></i>
                Orden de la actividad
              </label>
              
              <!-- Toggle para actividad recurrente -->
              <div class="form-group">
                <label class="toggle-switch">
                  <input 
                    type="checkbox" 
                    v-model="esRecurrente"
                    @change="onToggleRecurrente"
                  />
                  <span class="toggle-slider"></span>
                </label>
                <span class="toggle-label">
                  {{ esRecurrente ? '✅ Actividad recurrente (aparece en múltiples órdenes)' : '➡️ Actividad única' }}
                </span>
              </div>

              <!-- Orden único -->
              <div v-if="!esRecurrente" class="orden-simple">
                <input 
                  type="number" 
                  v-model.number="ordenUnico"
                  class="input-number"
                  min="1"
                  placeholder="Ej: 3"
                />
                <small class="help-text">La actividad aparecerá una sola vez en este orden</small>
              </div>

              <!-- Órdenes recurrentes -->
              <div v-else class="orden-recurrente">
                <div class="agregar-orden">
                  <input 
                    type="number" 
                    v-model.number="nuevoOrden"
                    class="input-number-small"
                    min="1"
                    placeholder="Nuevo orden"
                    @keyup.enter="agregarOrden"
                  />
                  <button type="button" class="btn-agregar-orden" @click="agregarOrden" :disabled="!nuevoOrden">
                    <i class="fas fa-plus"></i>
                    Agregar
                  </button>
                </div>
                
                <div class="ordenes-lista" v-if="ordenesLista.length > 0">
                  <label class="label-lista">Órdenes seleccionados:</label>
                  <div class="ordenes-tags">
                    <span 
                      v-for="ord in ordenesLista" 
                      :key="ord"
                      class="orden-tag"
                    >
                      {{ ord }}
                      <button type="button" class="remove-orden" @click="removerOrden(ord)">
                        <i class="fas fa-times"></i>
                      </button>
                    </span>
                  </div>
                </div>
                
                <small class="help-text">La actividad aparecerá en todos estos órdenes</small>
              </div>

              <!-- Vista previa del orden final -->
              <div class="orden-preview" v-if="ordenFinalTexto">
                <i class="fas fa-eye"></i>
                <strong>Orden final:</strong> {{ ordenFinalTexto }}
              </div>

              <span v-if="errores.orden" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i> {{ errores.orden }}
              </span>
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
import { ref, computed, onMounted, nextTick, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  proyecto: { type: Object, required: true },
  actividad: { type: Object, required: true },
  flash: Object
})

const proyectoId = props.proyecto.id
const actividadId = props.actividad.id

// Formulario básico
const form = ref({
  nombre: props.actividad.nombre || '',
  descripcion: props.actividad.descripcion || '',
  semanas: props.actividad.semanas || 1,
  orden: props.actividad.orden || '1'
})

const cargando = ref(false)
const errores = ref({})
const textareaRef = ref(null)

// Estado para el toggle y órdenes
const ordenOriginal = props.actividad.orden || '1'
const ordenesLista = ref([])
const esRecurrente = ref(false)
const ordenUnico = ref(1)
const nuevoOrden = ref('')

// Inicializar según el orden actual
const inicializarOrdenes = () => {
  const ordenStr = form.value.orden.toString()
  if (ordenStr.includes(',')) {
    esRecurrente.value = true
    ordenesLista.value = ordenStr.split(',').map(o => parseInt(o.trim())).filter(o => !isNaN(o))
    ordenesLista.value.sort((a, b) => a - b)
  } else {
    esRecurrente.value = false
    ordenUnico.value = parseInt(ordenStr) || 1
  }
}

// Orden final como string
const ordenFinalTexto = computed(() => {
  if (esRecurrente.value && ordenesLista.value.length > 0) {
    return ordenesLista.value.join(', ')
  } else if (!esRecurrente.value && ordenUnico.value) {
    return ordenUnico.value.toString()
  }
  return ''
})

// Al cambiar el toggle, actualizar el orden final
const onToggleRecurrente = () => {
  if (esRecurrente.value) {
    // Cambiando a recurrente: usar los órdenes existentes o inicializar con el único
    if (ordenUnico.value && !ordenesLista.value.includes(ordenUnico.value)) {
      ordenesLista.value = [ordenUnico.value]
    } else if (ordenesLista.value.length === 0) {
      ordenesLista.value = [1]
    }
  } else {
    // Cambiando a único: tomar el primer orden de la lista o usar 1
    ordenUnico.value = ordenesLista.value.length > 0 ? ordenesLista.value[0] : 1
  }
  actualizarOrdenForm()
}

// Agregar orden a la lista
const agregarOrden = () => {
  if (!nuevoOrden.value || nuevoOrden.value < 1) return
  
  if (!ordenesLista.value.includes(nuevoOrden.value)) {
    ordenesLista.value.push(nuevoOrden.value)
    ordenesLista.value.sort((a, b) => a - b)
    nuevoOrden.value = ''
    actualizarOrdenForm()
  } else {
    Swal.fire({
      icon: 'warning',
      title: 'Orden duplicado',
      text: `El orden ${nuevoOrden.value} ya está en la lista`,
      confirmButtonText: 'OK'
    })
  }
}

// Remover orden de la lista
const removerOrden = (orden) => {
  const index = ordenesLista.value.indexOf(orden)
  if (index > -1) {
    ordenesLista.value.splice(index, 1)
    actualizarOrdenForm()
  }
}

// Actualizar el campo orden del formulario
const actualizarOrdenForm = () => {
  if (esRecurrente.value && ordenesLista.value.length > 0) {
    form.value.orden = ordenesLista.value.join(',')
  } else if (!esRecurrente.value && ordenUnico.value) {
    form.value.orden = ordenUnico.value.toString()
  } else {
    form.value.orden = '1'
  }
}

// Auto-resize
const autoResize = () => {
  nextTick(() => {
    if (textareaRef.value) {
      textareaRef.value.style.height = 'auto'
      textareaRef.value.style.height = textareaRef.value.scrollHeight + 'px'
    }
  })
}

// Validar formulario (solo campos básicos, el orden lo valida el backend)
const validarFormulario = () => {
  const nuevosErrores = {}
  
  if (!form.value.nombre.trim()) {
    nuevosErrores.nombre = 'El nombre de la actividad es requerido'
  }
  
  if (!form.value.descripcion.trim()) {
    nuevosErrores.descripcion = 'La descripción es requerida'
  }
  
  if (!form.value.semanas || form.value.semanas < 1) {
    nuevosErrores.semanas = 'Las semanas deben ser al menos 1'
  }
  
  if (!form.value.orden || form.value.orden.trim() === '') {
    nuevosErrores.orden = 'El orden es requerido'
  }
  
  errores.value = nuevosErrores
  return Object.keys(nuevosErrores).length === 0
}

// Actualizar actividad
const actualizarActividad = () => {
  // Actualizar el orden antes de enviar
  actualizarOrdenForm()
  
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
  
  router.put(route('proyectos.actividades.update', [proyectoId, actividadId]), form.value, {
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
      if (errors.semanas) nuevosErrores.semanas = errors.semanas
      if (errors.orden) nuevosErrores.orden = errors.orden
      
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

// Inicializar
onMounted(() => {
  inicializarOrdenes()
  autoResize()
})
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
  font-size: 32px;
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
  padding: 40px;
  width: 100%;
  max-width: 700px;
  margin: 20px 0;
}

.formulario {
  width: 100%;
}

.form-group {
  margin-bottom: 24px;
  display: flex;
  flex-direction: column;
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

.input-text, .input-number, .input-number-small {
  padding: 12px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.input-number-small {
  width: 120px;
}

.input-text:focus, .input-number:focus, .input-number-small:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.textarea-auto {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  resize: none;
  font-family: inherit;
  transition: all 0.3s ease;
  overflow: hidden;
}

.textarea-auto:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
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

/* Órdenes */
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

.botones-container {
  display: flex;
  gap: 15px;
  margin-top: 30px;
}

.btn-actualizar {
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

.btn-actualizar:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-actualizar:disabled {
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
    padding: 25px;
  }
  
  .subtitulo {
    font-size: 24px;
  }
  
  .botones-container {
    flex-direction: column;
  }
  
  .agregar-orden {
    flex-direction: column;
  }
  
  .input-number-small {
    width: 100%;
  }
}
</style>