<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-clipboard-list"></i>
          Seguimiento de Documentos - Estudiantes
        </p>
      </div>

      <!-- Filtros -->
      <div class="filtros-section">
        <div class="filtros-card">
          <div class="filtros-header">
            <i class="fas fa-filter"></i>
            <h3>Filtros</h3>
          </div>
          <div class="filtros-body">
            <div class="filtro-group">
              <label>Buscar estudiante:</label>
              <input 
                type="text" 
                v-model="filtros.buscar" 
                placeholder="Nombre o número de control..."
                class="input-filtro"
              />
            </div>
            <div class="filtro-group">
              <label>Filtrar por estado:</label>
              <select v-model="filtros.estado" class="select-filtro">
                <option value="todos">Todos</option>
                <option value="completado">Documentos completos</option>
                <option value="incompleto">Documentos pendientes</option>
              </select>
            </div>
            <div class="filtro-group">
              <label>Progreso mínimo:</label>
              <input 
                type="range" 
                v-model.number="filtros.progresoMinimo" 
                min="0" 
                max="100"
                class="range-filtro"
              />
              <span>{{ filtros.progresoMinimo }}%</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de estudiantes -->
      <div class="tabla-container">
        <table class="estudiantes-table">
          <thead>
            <tr>
              <th class="thfondo">#</th>
              <th class="thfondo">Estudiante</th>
              <th class="thfondo">Carrera</th>
              <th class="thfondo">Progreso</th>
              <th class="thfondo">Solicitud</th>
              <th class="thfondo">Anteproyecto</th>
              <th class="thfondo">Cancelación</th>
              <th class="thfondo">KARDEX</th>
              <th class="thfondo">Seguro Social</th>
              <th class="thfondo">Servicio Social</th>
              <th class="thfondo">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(estudiante, index) in estudiantesFiltrados" :key="estudiante.id">
              <td>{{ index + 1 }}</td>
              <td>{{ estudiante.nombre }} {{ estudiante.apellido_paterno }} {{ estudiante.apellido_materno }}</td>
              <td>{{ estudiante.carrera }}</td>
              <td class="progreso-cell">
                <div class="progress-small">
                  <div class="progress-bar-small" :style="{ width: estudiante.progreso + '%' }"></div>
                </div>
                <span class="progreso-texto">{{ estudiante.progreso }}%</span>
              </td>
              
              <!-- Solicitud -->
              <td class="documento-cell">
                <div v-if="estudiante.documentos.solicitud.subido" class="doc-subido">
                  <i class="fas fa-check-circle"></i>
                  <span class="doc-nombre">{{ estudiante.documentos.solicitud.nombre }}</span>
                  <a href="#" class="doc-link" @click.prevent="verDocumento(estudiante, 'solicitud')">
                    <i class="fas fa-eye"></i>
                  </a>
                </div>
                <div v-else class="doc-pendiente">
                  <i class="fas fa-clock"></i>
                  <span>Pendiente</span>
                </div>
              </td>
              
              <!-- Anteproyecto -->
              <td class="documento-cell">
                <div v-if="estudiante.documentos.anteproyecto.subido" class="doc-subido">
                  <i class="fas fa-check-circle"></i>
                  <span class="doc-nombre">{{ estudiante.documentos.anteproyecto.nombre }}</span>
                  <a href="#" class="doc-link" @click.prevent="verDocumento(estudiante, 'anteproyecto')">
                    <i class="fas fa-eye"></i>
                  </a>
                </div>
                <div v-else class="doc-pendiente">
                  <i class="fas fa-clock"></i>
                  <span>Pendiente</span>
                </div>
              </td>
              
              <!-- Cancelación (opcional) -->
              <td class="documento-cell">
                <div v-if="estudiante.documentos.cancelacion.subido" class="doc-subido">
                  <i class="fas fa-check-circle"></i>
                  <span class="doc-nombre">{{ estudiante.documentos.cancelacion.nombre }}</span>
                  <a href="#" class="doc-link" @click.prevent="verDocumento(estudiante, 'cancelacion')">
                    <i class="fas fa-eye"></i>
                  </a>
                </div>
                <div v-else class="doc-opcional">
                  <i class="fas fa-minus-circle"></i>
                  <span>No requerido</span>
                </div>
              </td>
              
              <!-- KARDEX -->
              <td class="documento-cell">
                <div v-if="estudiante.documentos.kardex.subido" class="doc-subido">
                  <i class="fas fa-check-circle"></i>
                  <span class="doc-nombre">{{ estudiante.documentos.kardex.nombre }}</span>
                  <a href="#" class="doc-link" @click.prevent="verDocumento(estudiante, 'kardex')">
                    <i class="fas fa-eye"></i>
                  </a>
                </div>
                <div v-else class="doc-pendiente">
                  <i class="fas fa-clock"></i>
                  <span>Pendiente</span>
                </div>
              </td>
              
              <!-- Seguro Social -->
              <td class="documento-cell">
                <div v-if="estudiante.documentos.seguro.subido" class="doc-subido">
                  <i class="fas fa-check-circle"></i>
                  <span class="doc-nombre">{{ estudiante.documentos.seguro.nombre }}</span>
                  <a href="#" class="doc-link" @click.prevent="verDocumento(estudiante, 'seguro')">
                    <i class="fas fa-eye"></i>
                  </a>
                </div>
                <div v-else class="doc-pendiente">
                  <i class="fas fa-clock"></i>
                  <span>Pendiente</span>
                </div>
              </td>
              
              <!-- Servicio Social -->
              <td class="documento-cell">
                <div v-if="estudiante.documentos.servicio.subido" class="doc-subido">
                  <i class="fas fa-check-circle"></i>
                  <span class="doc-nombre">{{ estudiante.documentos.servicio.nombre }}</span>
                  <a href="#" class="doc-link" @click.prevent="verDocumento(estudiante, 'servicio')">
                    <i class="fas fa-eye"></i>
                  </a>
                </div>
                <div v-else class="doc-pendiente">
                  <i class="fas fa-clock"></i>
                  <span>Pendiente</span>
                </div>
              </td>
              
              <!-- Acciones -->
              <td class="acciones-cell">
                <button @click="enviarRecordatorio(estudiante)" class="btn-recordatorio" title="Enviar recordatorio">
                  <i class="fas fa-bell"></i>
                </button>
                <button @click="verDetalle(estudiante)" class="btn-ver" title="Ver detalles">
                  <i class="fas fa-eye"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Resumen -->
      <div class="resumen-section">
        <div class="resumen-card">
          <div class="resumen-item">
            <i class="fas fa-users"></i>
            <div class="resumen-info">
              <span class="resumen-numero">{{ estudiantes.length }}</span>
              <span class="resumen-label">Total estudiantes</span>
            </div>
          </div>
          <div class="resumen-item">
            <i class="fas fa-check-circle"></i>
            <div class="resumen-info">
              <span class="resumen-numero">{{ estudiantesCompletos }}</span>
              <span class="resumen-label">Documentos completos</span>
            </div>
          </div>
          <div class="resumen-item">
            <i class="fas fa-chart-line"></i>
            <div class="resumen-info">
              <span class="resumen-numero">{{ promedioGeneral }}%</span>
              <span class="resumen-label">Promedio general</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para ver documento -->
    <div v-if="modalVisible" class="modal-overlay" @click.self="cerrarModal">
      <div class="modal-container">
        <div class="modal-header">
          <h3>
            <i class="fas fa-file-alt"></i>
            Documento de {{ estudianteSeleccionado?.nombre }}
          </h3>
          <button class="modal-close" @click="cerrarModal">✕</button>
        </div>
        <div class="modal-body">
          <div class="documento-info">
            <p><strong>Tipo:</strong> {{ tipoDocumentoLabel }}</p>
            <p><strong>Nombre del archivo:</strong> {{ documentoSeleccionado?.nombre }}</p>
            <p><strong>Tamaño:</strong> {{ documentoSeleccionado?.tamaño }} KB</p>
            <p><strong>Fecha de subida:</strong> {{ documentoSeleccionado?.fecha }}</p>
          </div>
          <div class="modal-actions">
            <a :href="route('documentos.download', documentoSeleccionado?.id)" class="btn-descargar-modal" v-if="documentoSeleccionado?.id">
              <i class="fas fa-download"></i>
              Descargar
            </a>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn-cerrar" @click="cerrarModal">Cerrar</button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppLayout from '@/Layouts/appLayout.vue'
import { router } from '@inertiajs/vue3'


const props = defineProps({
  estudiantes: {
    type: Array,
    default: () => []
  },
  tiposDocumento: {
    type: Array,
    default: () => []
  },
  flash: Object
})

// Filtros
const filtros = ref({
  buscar: '',
  estado: 'todos',
  progresoMinimo: 0
})

// Modal
const modalVisible = ref(false)
const estudianteSeleccionado = ref(null)
const tipoDocumentoSeleccionado = ref('')
const documentoSeleccionado = ref(null)

// Tipos de documentos para etiquetas
const tiposDocumentoMap = {
  solicitud: 'Solicitud de residencia profesional',
  anteproyecto: 'Anteproyecto',
  cancelacion: 'Solicitud de cancelación',
  kardex: 'KARDEX (SII)',
  seguro: 'Afiliación del seguro social',
  servicio: 'Constancia de servicio social'
}

const tipoDocumentoLabel = computed(() => {
  return tiposDocumentoMap[tipoDocumentoSeleccionado.value] || ''
})

// Filtrar estudiantes
const estudiantesFiltrados = computed(() => {
  let result = props.estudiantes
  
  if (filtros.value.buscar) {
    const busqueda = filtros.value.buscar.toLowerCase()
    result = result.filter(e => 
      e.nombre.toLowerCase().includes(busqueda) ||
      e.apellido_paterno.toLowerCase().includes(busqueda) ||
      e.numero_control.toLowerCase().includes(busqueda)
    )
  }
  
  if (filtros.value.estado === 'completado') {
    result = result.filter(e => e.progreso === 100)
  } else if (filtros.value.estado === 'incompleto') {
    result = result.filter(e => e.progreso < 100)
  }
  
  result = result.filter(e => e.progreso >= filtros.value.progresoMinimo)
  
  return result
})

// Estadísticas
const estudiantesCompletos = computed(() => {
  return props.estudiantes.filter(e => e.progreso === 100).length
})

const promedioGeneral = computed(() => {
  if (props.estudiantes.length === 0) return 0
  const suma = props.estudiantes.reduce((acc, e) => acc + e.progreso, 0)
  return Math.round(suma / props.estudiantes.length)
})

// Ver documento
const verDocumento = (estudiante, tipo) => {
  estudianteSeleccionado.value = estudiante
  tipoDocumentoSeleccionado.value = tipo
  documentoSeleccionado.value = estudiante.documentos[tipo]
  
  if (!documentoSeleccionado.value?.subido) {
    Swal.fire({
      icon: 'warning',
      title: 'Documento no disponible',
      text: 'El estudiante aún no ha subido este documento',
      confirmButtonText: 'OK'
    })
    return
  }
  
  modalVisible.value = true
}

// Cerrar modal
const cerrarModal = () => {
  modalVisible.value = false
  estudianteSeleccionado.value = null
  tipoDocumentoSeleccionado.value = ''
  documentoSeleccionado.value = null
}

// Enviar recordatorio
const enviarRecordatorio = (estudiante) => {
  Swal.fire({
    icon: 'info',
    title: 'Recordatorio enviado',
    text: `Se ha enviado un recordatorio a ${estudiante.nombre} ${estudiante.apellido_paterno}`,
    confirmButtonText: 'OK'
  })
}

// Ver detalle del estudiante
const verDetalle = (estudiante) => {
  Swal.fire({
    icon: 'info',
    title: 'Detalles del estudiante',
    html: `
      <p><strong>Nombre:</strong> ${estudiante.nombre} ${estudiante.apellido_paterno} ${estudiante.apellido_materno}</p>
      <p><strong>N° Control:</strong> ${estudiante.numero_de_control}</p>
      <p><strong>Carrera:</strong> ${estudiante.carrera}</p>
      <p><strong>Proyecto:</strong> ${estudiante.proyecto}</p>
    `,
    confirmButtonText: 'OK'
  })
}
</script>

<style scoped>
/* Mantén todos tus estilos existentes aquí */
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

/* Filtros */
.filtros-section {
  margin-bottom: 25px;
}

.filtros-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.filtros-header {
  background-color: #050E3C;
  color: white;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.filtros-header h3 {
  margin: 0;
  font-size: 16px;
}

.filtros-body {
  padding: 15px 20px;
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  align-items: flex-end;
}

.filtro-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
  min-width: 200px;
}

.filtro-group label {
  font-size: 12px;
  font-weight: 600;
  color: #555;
}

.input-filtro, .select-filtro {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
}

.range-filtro {
  width: 150px;
}

/* Tabla */
.tabla-container {
  overflow-x: auto;
  margin-bottom: 25px;
}

.estudiantes-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
  min-width: 1200px;
}

.estudiantes-table th,
.estudiantes-table td {
  border: 1px solid #ddd;
  padding: 10px 8px;
  text-align: left;
  vertical-align: middle;
}

.thfondo {
  background-color: #f2f2f2;
  font-weight: 600;
}

.progreso-cell {
  width: 80px;
}

.progress-small {
  background-color: #e9ecef;
  border-radius: 10px;
  height: 6px;
  overflow: hidden;
  margin-bottom: 4px;
}

.progress-bar-small {
  background: linear-gradient(90deg, #28a745, #20c997);
  height: 100%;
  border-radius: 10px;
  transition: width 0.3s ease;
}

.progreso-texto {
  font-size: 11px;
  font-weight: bold;
}

.documento-cell {
  min-width: 100px;
}

.doc-subido {
  display: flex;
  align-items: center;
  gap: 6px;
  flex-wrap: wrap;
}

.doc-subido i {
  color: #28a745;
  font-size: 14px;
}

.doc-nombre {
  font-size: 11px;
  max-width: 100px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.doc-link {
  color: #050E3C;
  cursor: pointer;
}

.doc-link:hover {
  color: #0a1a6e;
}

.doc-pendiente {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #ffc107;
}

.doc-opcional {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #6c757d;
}

.acciones-cell {
  white-space: nowrap;
}

.btn-recordatorio, .btn-ver {
  background: none;
  border: none;
  cursor: pointer;
  padding: 5px 8px;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.btn-recordatorio {
  color: #ffc107;
}

.btn-recordatorio:hover {
  background-color: #fff3cd;
}

.btn-ver {
  color: #050E3C;
}

.btn-ver:hover {
  background-color: #e8f4fd;
}

/* Resumen */
.resumen-section {
  margin-top: 25px;
}

.resumen-card {
  background: linear-gradient(135deg, #050E3C 0%, #0a1a6e 100%);
  border-radius: 12px;
  padding: 20px;
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap: 20px;
}

.resumen-item {
  display: flex;
  align-items: center;
  gap: 15px;
  color: white;
}

.resumen-item i {
  font-size: 32px;
  opacity: 0.8;
}

.resumen-info {
  display: flex;
  flex-direction: column;
}

.resumen-numero {
  font-size: 28px;
  font-weight: bold;
}

.resumen-label {
  font-size: 12px;
  opacity: 0.8;
}

/* Modal */
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
  border-radius: 12px;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
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
  border-radius: 12px 12px 0 0;
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

.modal-body {
  padding: 20px;
  overflow-y: auto;
  flex: 1;
}

.documento-info p {
  margin: 10px 0;
}

.modal-actions {
  margin-top: 20px;
  text-align: center;
}

.btn-descargar-modal {
  background-color: #28a745;
  color: white;
  padding: 10px 20px;
  border-radius: 6px;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.btn-descargar-modal:hover {
  background-color: #218838;
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
  border-radius: 6px;
  cursor: pointer;
}

@media (max-width: 768px) {
  .subtitulo {
    font-size: 22px;
  }
  
  .filtros-body {
    flex-direction: column;
  }
  
  .filtro-group {
    width: 100%;
  }
  
  .range-filtro {
    width: 100%;
  }
  
  .resumen-card {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  
  .resumen-item {
    flex-direction: column;
    text-align: center;
  }
}
</style>