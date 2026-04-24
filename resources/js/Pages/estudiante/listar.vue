<template>
  <AppLayout>
    <div class="bodydiv">
      <!-- Barra de búsqueda -->
      <div class="search-container">
        <form @submit.prevent="buscarEstudiantes" class="search-form">
          <input
            type="text"
            v-model="terminoBusqueda"
            placeholder="Buscar por nombre, apellidos o número de control"
            class="input-buscar"
            autocomplete="off"
            @input="onSearchInput"
          />
          <button type="submit" class="btn-buscar">Buscar</button>
        </form>
        
        <!-- Sugerencias -->
        <div v-if="sugerencias.length > 0" class="sugerencias">
          <div
            v-for="sugerencia in sugerencias"
            :key="sugerencia.id"
            class="sugerencia-item"
            @click="seleccionarSugerencia(sugerencia.value)"
          >
            {{ sugerencia.value }}
          </div>
        </div>
      </div>

      <!-- Título con período actual -->
      <div class="horizontal" style="margin-top: 20px">
        <p class="subtitulo">
          Lista de Estudiantes del período 
          <span class="periodo-nombre">{{ periodoActual?.nombre || 'Actual' }}</span>
        </p>
      </div>

      <!-- Tabla -->
      <div style="margin-bottom: 40px" class="centro">
        <table border="1" class="estudiantes-table">
          <thead>
            <tr>
              <th class="thfondo">ID</th>
              <th class="thfondo">NOMBRE</th>
              <th class="thfondo">APELLIDOS</th>
              <th class="thfondo">ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="estudiante in todos" :key="estudiante.id">
              <td style="padding: 5px">{{ estudiante.id }}</td>
              <td style="padding: 5px">{{ estudiante.nombre }}</td>
              <td style="padding: 5px">
                {{ estudiante.apellido_paterno }} {{ estudiante.apellido_materno }}
              </td>
              <td style="padding: 8px">
                <Link :href="route('estudiantes.edit', estudiante.id)" class="btn-editar">
                  Editar
                </Link>
                <form @submit.prevent="confirmarEliminacion(estudiante.id)">
                  <button 
                    type="submit" 
                    class="btn-borrar" 
                    :disabled="eliminando === estudiante.id"
                    style="margin-top: 5px;"
                  >
                    {{ eliminando === estudiante.id ? '...' : 'Borrar' }}
                  </button>
                </form>
              </td>
            </tr>
            
            <tr v-if="todos.length === 0">
              <td colspan="5" class="sin-datos">
                No hay estudiantes registrados en el período actual
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Botones de acción -->
      <div class="acciones">
        <Link :href="route('estudiantes.create')" class="btn-agregar">
          Agregar un Estudiante
        </Link>
        <a :href="route('generar-estudiantes.excel')" class="btn-descargar">
          Descargar lista
        </a>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'
import axios from 'axios'


const props = defineProps({
  todos: Array,
  periodoActual: Object,
  filtroBuscar: String,
  flash: Object
})

// Estado reactivo
const terminoBusqueda = ref(props.filtroBuscar || '')
const sugerencias = ref([])
const eliminando = ref(null)
let timeoutSugerencias = null

// Método para buscar estudiantes
const buscarEstudiantes = () => {
  router.get(route('estudiantes.index'), 
    { buscar: terminoBusqueda.value },
    { preserveState: true, preserveScroll: true }
  )
}

// Búsqueda en tiempo real para sugerencias
const onSearchInput = () => {
  clearTimeout(timeoutSugerencias)
  
  if (terminoBusqueda.value.length < 2) {
    sugerencias.value = []
    return
  }
  
  timeoutSugerencias = setTimeout(() => {
    axios.get('/estudiantes/buscar-estudiante', {
      params: { term: terminoBusqueda.value }
    }).then(response => {
      sugerencias.value = response.data
    }).catch(error => {
      console.error('Error al buscar sugerencias:', error)
    })
  }, 300)
}

// Seleccionar sugerencia
const seleccionarSugerencia = (valor) => {
  terminoBusqueda.value = valor
  sugerencias.value = []
  buscarEstudiantes()
}

// Confirmar eliminación
const confirmarEliminacion = (id) => {
  Swal.fire({
    title: '¿Estás seguro?',
    text: '⚠️ Al eliminar este Estudiante ya no se podrá restaurar.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      eliminarEstudiante(id)
    }
  })
}

// Eliminar estudiante
const eliminarEstudiante = (id) => {
  eliminando.value = id
  
  router.delete(route('estudiantes.destroy', id), {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire('Eliminado', 'El estudiante ha sido eliminado', 'success')
    },
    onError: (errors) => {
      Swal.fire('Error', errors.message || 'No se pudo eliminar el estudiante', 'error')
    },
    onFinish: () => {
      eliminando.value = null
    }
  })
}

// Mostrar mensajes flash
if (props.flash?.success) {
  Swal.fire('Éxito', props.flash.success, 'success')
}

if (props.flash?.error) {
  Swal.fire('Error', props.flash.error, 'error')
}
</script>

<style scoped>
.bodydiv {
  margin-left: 20px;
  margin-right: 20px;
}

/* Barra de búsqueda */
.search-container {
  display: flex;
  justify-content: flex-end;
  margin-right: 35px;
  position: relative;
}

.search-form {
  position: relative;
  display: flex;
}

.input-buscar {
  font-size: 18px;
  padding: 5px 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  width: 350px;
}

.btn-buscar {
  background-color: rgb(25, 118, 210);
  color: white;
  border: none;
  cursor: pointer;
  padding: 5px 15px;
  border-radius: 4px;
  margin-left: 5px;
}

.btn-buscar:hover {
  background-color: rgb(74, 139, 204);
}

/* Sugerencias */
.sugerencias {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  border: 1px solid #ccc;
  width: 380px;
  max-height: 200px;
  overflow-y: auto;
  z-index: 1000;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.sugerencia-item {
  padding: 8px;
  cursor: pointer;
  border-bottom: 1px solid #eee;
}

.sugerencia-item:hover {
  background-color: #f0f0f0;
}

/* Título */
.horizontal {
  display: flex;
  justify-content: center;
  width: 100%;
}

.subtitulo {
  text-align: center;
  font-size: 32px;
  font-weight: bold;
  margin: 20px 0;
}

.periodo-nombre {
  background: linear-gradient(135deg, #050E3C 0%, #0a1a6e 100%);
  color: white;
  padding: 4px 15px;
  border-radius: 25px;
  font-size: 24px;
  display: inline-block;
  margin-left: 10px;
}

.centro {
  display: flex;
  justify-content: center;
}

/* Tabla */
.estudiantes-table {
  border: 2px solid rgb(19, 46, 68);
  border-collapse: collapse;
  margin-top: 20px;
  width: 80%;
}

.estudiantes-table th,
.estudiantes-table td {
  border: 1px solid rgb(40, 95, 139);
  padding: 8px;
}

.thfondo {
  background-color: rgb(204, 216, 228);
}

.sin-datos {
  text-align: center;
  padding: 40px;
  color: #999;
}

/* Botones */
.btn-editar {
  background-color: rgb(25, 118, 210);
  color: white;
  cursor: pointer;
  text-decoration: none;
  padding: 5px 12px;
  border-radius: 4px;
  border: none;
  display: inline-block;
}

.btn-editar:hover {
  background-color: rgb(74, 139, 204);
}

.btn-borrar {
  background-color: rgb(210, 25, 25);
  color: white;
  cursor: pointer;
  text-decoration: none;
  padding: 5px 12px;
  border-radius: 4px;
  border: none;
  width: 100%;
}

.btn-borrar:hover {
  background-color: rgb(204, 74, 74);
}

.btn-borrar:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Acciones */
.acciones {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 20px;
  margin-bottom: 40px;
}

.btn-agregar,
.btn-descargar {
  background-color: rgb(25, 118, 210);
  color: white;
  text-decoration: none;
  padding: 8px 20px;
  border-radius: 5px;
  cursor: pointer;
  display: inline-block;
}

.btn-agregar:hover,
.btn-descargar:hover {
  background-color: rgb(74, 139, 204);
}
</style>