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
        <div class="table-responsive">
          <table border="1" class="estudiantes-table">
            <thead>
              <tr>
                <th class="thfondo">ID</th>
                <th class="thfondo">Número de Control</th>
                <th class="thfondo">NOMBRE</th>
                <th class="thfondo">APELLIDOS</th>
                <th class="thfondo">KARDEX (SII)</th>
                <th class="thfondo">Seguro Social</th>
                <th class="thfondo">Servicio Social</th>
                <th class="thfondo">ACCIONES</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="estudiante in todos" :key="estudiante.id">
                <td style="padding: 5px">{{ estudiante.id }}</td>
                <td style="padding: 5px">{{ estudiante.numero_de_control || '-' }}</td>
                <td style="padding: 5px">{{ estudiante.nombre }}</td>
                <td style="padding: 5px">
                  {{ estudiante.apellido_paterno }} {{ estudiante.apellido_materno }}
                </td>
                
                <!-- KARDEX -->
                <td class="documento-cell">
                  <div v-if="getDocumentoPorNombre(estudiante, 'KARDEX (SII)')" class="documento-status">
                    <span :class="getDocumentoClase(estudiante, 'KARDEX (SII)')">
                      <i :class="getDocumentoIcono(estudiante, 'KARDEX (SII)')"></i>
                      {{ getDocumentoTexto(estudiante, 'KARDEX (SII)') }}
                    </span>
                    <button 
                      v-if="getDocumentoUrl(estudiante, 'KARDEX (SII)')"
                      @click="verDocumento(getDocumentoUrl(estudiante, 'KARDEX (SII)'))"
                      class="btn-ver-documento"
                      title="Ver documento"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                  <span v-else class="documento-pendiente">
                    <i class="fas fa-clock"></i> No subido
                  </span>
                </td>
                
                <!-- Seguro Social -->
                <td class="documento-cell">
                  <div v-if="getDocumentoPorNombre(estudiante, 'Afiliación del seguro social (carnet IMSS)')" class="documento-status">
                    <span :class="getDocumentoClase(estudiante, 'Afiliación del seguro social (carnet IMSS)')">
                      <i :class="getDocumentoIcono(estudiante, 'Afiliación del seguro social (carnet IMSS)')"></i>
                      {{ getDocumentoTexto(estudiante, 'Afiliación del seguro social (carnet IMSS)') }}
                    </span>
                    <button 
                      v-if="getDocumentoUrl(estudiante, 'Afiliación del seguro social (carnet IMSS)')"
                      @click="verDocumento(getDocumentoUrl(estudiante, 'Afiliación del seguro social (carnet IMSS)'))"
                      class="btn-ver-documento"
                      title="Ver documento"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                  <span v-else class="documento-pendiente">
                    <i class="fas fa-clock"></i> No subido
                  </span>
                </td>
                
                <!-- Servicio Social -->
                <td class="documento-cell">
                  <div v-if="getDocumentoPorNombre(estudiante, 'Constancia de servicio social')" class="documento-status">
                    <span :class="getDocumentoClase(estudiante, 'Constancia de servicio social')">
                      <i :class="getDocumentoIcono(estudiante, 'Constancia de servicio social')"></i>
                      {{ getDocumentoTexto(estudiante, 'Constancia de servicio social') }}
                    </span>
                    <button 
                      v-if="getDocumentoUrl(estudiante, 'Constancia de servicio social')"
                      @click="verDocumento(getDocumentoUrl(estudiante, 'Constancia de servicio social'))"
                      class="btn-ver-documento"
                      title="Ver documento"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                  <span v-else class="documento-pendiente">
                    <i class="fas fa-clock"></i> No subido
                  </span>
                </td>
                
                <td style="padding: 8px; white-space: nowrap;">
                  <Link :href="route('estudiantes.edit', estudiante.id)" class="btn-editar">
                    <i class="fas fa-edit"></i> Editar
                  </Link>
                  <form @submit.prevent="confirmarEliminacion(estudiante.id)">
                    <button 
                      type="submit" 
                      class="btn-borrar" 
                      :disabled="eliminando === estudiante.id"
                      style="margin-top: 5px;"
                    >
                      <i class="fas fa-trash"></i> {{ eliminando === estudiante.id ? '...' : 'Borrar' }}
                    </button>
                  </form>
                 </td>
                
              </tr>
              
              <tr v-if="todos.length === 0">
                <td colspan="8" class="sin-datos">
                  No hay estudiantes registrados en el período actual
                </td>
                
              </tr>
            </tbody>
           </table>
        </div>
      </div>

      <!-- Botones de acción -->
      <div class="acciones">
        <Link :href="route('estudiantes.create')" class="btn-agregar">
          <i class="fas fa-plus"></i> Agregar un Estudiante
        </Link>
        <a :href="route('generar-estudiantes.excel')" class="btn-descargar">
          <i class="fas fa-download"></i> Descargar lista
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
  flash: Object,
  tiposDocumentosRequeridos: Array
})

// Estado reactivo
const terminoBusqueda = ref(props.filtroBuscar || '')
const sugerencias = ref([])
const eliminando = ref(null)
let timeoutSugerencias = null

// Función para obtener el documento por nombre
const getDocumentoPorNombre = (estudiante, nombreDocumento) => {
  if (!estudiante.documentos) return null
  
  return estudiante.documentos.find(doc => 
    doc.tipo_documento?.nombre === nombreDocumento || 
    doc.tipo_documento?.nombre === nombreDocumento
  )
}

// Obtener URL del documento
const getDocumentoUrl = (estudiante, nombreDocumento) => {
  const doc = getDocumentoPorNombre(estudiante, nombreDocumento)
  if (!doc) return null
  
  // Si tiene ruta_archivo, generar URL de descarga
  if (doc.ruta_archivo) {
    return route('documentos.download', doc.id)
  }
  
  // Si tiene URL externa
  if (doc.url_documento) {
    return doc.url_documento
  }
  
  return null
}

// Obtener clase CSS según estado
const getDocumentoClase = (estudiante, nombreDocumento) => {
  const doc = getDocumentoPorNombre(estudiante, nombreDocumento)
  if (!doc) return 'documento-pendiente'
  
  if (doc.ruta_archivo || doc.url_documento) {
    return 'documento-subido'
  }
  
  return 'documento-pendiente'
}

// Obtener icono según estado
const getDocumentoIcono = (estudiante, nombreDocumento) => {
  const doc = getDocumentoPorNombre(estudiante, nombreDocumento)
  if (!doc) return 'fas fa-clock'
  
  if (doc.ruta_archivo || doc.url_documento) {
    return 'fas fa-check-circle'
  }
  
  return 'fas fa-clock'
}

// Obtener texto según estado
const getDocumentoTexto = (estudiante, nombreDocumento) => {
  const doc = getDocumentoPorNombre(estudiante, nombreDocumento)
  if (!doc) return 'No subido'
  
  if (doc.ruta_archivo || doc.url_documento) {
    return 'Subido'
  }
  
  return 'Pendiente'
}

// Ver documento
const verDocumento = (url) => {
  window.open(url, '_blank')
}

// Buscar estudiantes
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
.table-responsive {
  overflow-x: auto;
  width: 100%;
}

.estudiantes-table {
  width: 100%;
  border-collapse: collapse;
  min-width: 1000px;
}

.documento-cell {
  text-align: center;
  vertical-align: middle;
  padding: 10px;
}

.documento-status {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
}

.documento-subido {
  color: #28a745;
  font-weight: 500;
}

.documento-pendiente {
  color: #856404;
  font-weight: 500;
}

.btn-ver-documento {
  background: none;
  border: none;
  cursor: pointer;
  color: #002455;
  transition: all 0.2s;
}

.btn-ver-documento:hover {
  color: #ffc107;
  transform: scale(1.1);
}

.btn-editar, .btn-borrar {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 10px;
  border-radius: 5px;
  text-decoration: none;
  font-size: 0.85rem;
}

.btn-editar {
  background-color: #002455;
  color: white;
}

.btn-editar:hover {
  background-color: #050E3C;
}

.btn-borrar {
  background-color: #dc3545;
  color: white;
  border: none;
  cursor: pointer;
}

.btn-borrar:hover {
  background-color: #c82333;
}

.acciones {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
  flex-wrap: wrap;
}

.btn-agregar, .btn-descargar {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
}

.btn-agregar {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
}

.btn-descargar {
  background: linear-gradient(135deg, #17a2b8, #138496);
  color: white;
}

.btn-agregar:hover, .btn-descargar:hover {
  transform: translateY(-2px);
}

.sin-datos {
  text-align: center;
  padding: 40px;
  color: #6c757d;
}
</style>