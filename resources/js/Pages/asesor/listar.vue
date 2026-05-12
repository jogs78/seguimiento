<template>
  <AppLayout>
    <div class="bodydiv">
      <!-- Barra de búsqueda -->
      <div class="search-container"><!-- ... --></div>

      <!-- Título -->
      <div class="horizontal"><p class="subtitulo">Asesores Internos Registrados</p></div>

      <!-- Tabla -->
      <div style="margin-bottom: 40px" class="centro">
        <table border="1" class="asesores-table">
          <thead>
            <tr>
              <th class="thfondo">ID</th>
              <th class="thfondo">NOMBRE</th>
              <th class="thfondo">APELLIDOS</th>
              <th class="thfondo">PROYECTOS</th>
              <th class="thfondo">ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="asesor in todos" :key="asesor.id">
              <td style="padding: 5px">{{ asesor.id }}</td>
              <td style="padding: 5px">{{ asesor.nombre }}</td>
              <td style="padding: 5px">{{ asesor.apellido_paterno }} {{ asesor.apellido_materno }}</td>
              
              <!-- Columna de Proyectos -->
              <td style="padding: 5px; text-align: center;">
                <div v-if="asesor.proyectos_del_periodo && asesor.proyectos_del_periodo.length > 0">
                  <button 
                    @click="verProyectos(asesor)" 
                    class="btn-ver-proyectos"
                  >
                    📋 Ver {{ asesor.proyectos_del_periodo.length }} proyecto(s)
                  </button>
                </div>
                <span v-else class="sin-proyectos">Sin proyectos</span>
              </td>
              
              <!-- Columna de Acciones -->
              <td style="padding: 8px">
                <Link :href="route('asesores.edit', asesor.id)" class="btn-editar">
                  Editar
                </Link>
                
                <div v-if="!asesor.proyectos_del_periodo || asesor.proyectos_del_periodo.length === 0">
                  <form @submit.prevent="confirmarEliminacion(asesor.id)">
                    <button type="submit" class="btn-borrar" :disabled="eliminando === asesor.id">
                      {{ eliminando === asesor.id ? '...' : 'Borrar' }}
                    </button>
                  </form>
                </div>
                <div v-else class="proyecto-asignado">
                  {{ asesor.proyectos_del_periodo.length }} proyecto(s) asignado(s)
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Botones de acción -->
      <div class="acciones">
        
        <a :href="route('generar-asesores.excel')" class="btn-descargar">
          Descargar lista
        </a>
      </div>
    </div>

    <!-- MODAL para ver proyectos -->
    <div v-if="modalVisible" class="modal-overlay" @click.self="cerrarModal">
      <div class="modal-container">
        <div class="modal-header">
          <h3>Proyectos de {{ asesorSeleccionado?.nombre }} {{ asesorSeleccionado?.apellido_paterno }}</h3>
          <button class="modal-close" @click="cerrarModal">✕</button>
        </div>
        
        <div class="modal-body">
          <table class="modal-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombre del Proyecto</th>
                <th>Empresa</th>
                <th>Estudiantes</th>
               
              </tr>
            </thead>
            <tbody>
              <tr v-for="(proyecto, index) in asesorSeleccionado?.proyectos_del_periodo" :key="proyecto.id">
                <td>{{ index + 1 }}</td>
                <td><strong>{{ proyecto.nombre }}</strong></td>
                <td>{{ proyecto.empresa?.nombre || 'Sin empresa' }}</td>
                <td>
                  <div v-for="est in proyecto.estudiantes" :key="est.id" class="estudiante-item">
                    {{ est.numero_control }} - {{ est.nombre }} {{ est.apellido_paterno }}
                  </div>
                  <span v-if="!proyecto.estudiantes?.length" class="sin-datos">Sin estudiantes</span>
                </td>
                
              </tr>
            </tbody>
          </table>
        </div>
        
        <div class="modal-footer">
          <button class="btn-cerrar" @click="cerrarModal">Cerrar</button>
        </div>
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
  filtroBuscar: String,
  flash: Object
})

// Estado para el modal
const modalVisible = ref(false)
const asesorSeleccionado = ref(null)

// Abrir modal con los proyectos del asesor
const verProyectos = (asesor) => {
  asesorSeleccionado.value = asesor
  modalVisible.value = true
}

// Cerrar modal
const cerrarModal = () => {
  modalVisible.value = false
  asesorSeleccionado.value = null
}

// Estado reactivo
const terminoBusqueda = ref(props.filtroBuscar || '')
const sugerencias = ref([])
const eliminando = ref(null)
let timeoutSugerencias = null

// Método para buscar asesores
const buscarAsesores = () => {
  router.get(route('asesores.index'), 
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
    axios.get('/asesores/buscar-asesor', {
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
  buscarAsesores()
}

// Confirmar eliminación
const confirmarEliminacion = (id) => {
  Swal.fire({
    title: '¿Estás seguro?',
    text: '⚠️ Al eliminar este Asesor Interno ya no se podrá restaurar.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      eliminarAsesor(id)
    }
  })
}

// Eliminar asesor
const eliminarAsesor = (id) => {
  eliminando.value = id
  
  router.delete(route('asesores.destroy', id), {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire('Eliminado', 'El asesor ha sido eliminado', 'success')
    },
    onError: (errors) => {
      Swal.fire('Error', errors.message || 'No se pudo eliminar el asesor', 'error')
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

.btn-ver-proyectos {
  background-color: #050E3C;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
}

.btn-ver-proyectos:hover {
  background-color: #0a1a6e;
}

.sin-proyectos {
  color: #999;
  font-style: italic;
  font-size: 12px;
}

/* MODAL */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-container {
  background-color: white;
  border-radius: 8px;
  width: 80%;
  max-width: 900px;
  max-height: 80vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  border-bottom: 1px solid #ddd;
  background-color: #050E3C;
  color: white;
  border-radius: 8px 8px 0 0;
}

.modal-header h3 {
  margin: 0;
  font-size: 18px;
}

.modal-close {
  background: none;
  border: none;
  color: white;
  font-size: 24px;
  cursor: pointer;
}

.modal-close:hover {
  color: #ffd700;
}

.modal-body {
  padding: 20px;
  overflow-y: auto;
  flex: 1;
}

.modal-table {
  width: 100%;
  border-collapse: collapse;
}

.modal-table th,
.modal-table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
}

.modal-table th {
  background-color: #f5f5f5;
  font-weight: bold;
}

.estudiante-item {
  font-size: 12px;
  margin-bottom: 3px;
}

.btn-ver-proyecto {
  background-color: rgb(25, 118, 210);
  color: white;
  text-decoration: none;
  padding: 4px 8px;
  border-radius: 3px;
  font-size: 12px;
}

.btn-ver-proyecto:hover {
  background-color: rgb(74, 139, 204);
}

.modal-footer {
  padding: 15px 20px;
  border-top: 1px solid #ddd;
  display: flex;
  justify-content: flex-end;
}

.btn-cerrar {
  background-color: #6c757d;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
}

.btn-cerrar:hover {
  background-color: #5a6268;
}

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
  width: 300px;
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
  width: 330px;
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

.centro {
  display: flex;
  justify-content: center;
}

/* Tabla */
.asesores-table {
  border: 2px solid rgb(19, 46, 68);
  border-collapse: collapse;
  margin-top: 20px;
  width: 80%;
}

.asesores-table th,
.asesores-table td {
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

.proyecto-asignado {
  margin-top: 8px;
  font-size: 12px;
  color: #666;
  background: #f5f5f5;
  padding: 5px;
  border-radius: 4px;
}

.proyecto-asignado strong {
  color: #050E3C;
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