<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-folder-open"></i>
          Mis Documentos
        </p>
      </div>

      <!-- Información de requisitos -->
      <div class="info-section">
        <div class="info-card">
          <i class="fas fa-info-circle"></i>
          <div class="info-text">
            <strong>Documentos requeridos para tu residencia profesional:</strong>
            <ul>
              <li>Todos los documentos deben estar en formato PDF</li>
              <li>Tamaño máximo por archivo: 10MB</li>
              <li>Los documentos pueden ser actualizados en cualquier momento</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Debug: Verificar que tiposDocumento tiene datos -->
      <div v-if="tiposDocumento.length === 0" class="debug-warning">
        <i class="fas fa-exclamation-triangle"></i>
        No se encontraron tipos de documento. Ejecuta el seeder.
      </div>

      <!-- Grid de documentos -->
      <div class="documentos-grid">
        <div 
          v-for="tipo in tiposDocumento" 
          :key="tipo.id"
          class="documento-card" 
          :class="{ 'completado': getDocumentoPorTipo(tipo.id) }"
        >
          <div class="card-header">
            <i class="fas" :class="getIconoPorTipo(tipo.id)"></i>
            <h3>
              {{ tipo.nombre }}
              <span v-if="tipo.obligatorio" class="required">*</span>
              <span v-else class="opcional">(Opcional)</span>
            </h3>
            <span class="estado" :class="{ 'subido': getDocumentoPorTipo(tipo.id) }">
              <i v-if="getDocumentoPorTipo(tipo.id)" class="fas fa-check-circle"></i>
              <i v-else class="fas fa-clock"></i>
              {{ getDocumentoPorTipo(tipo.id) ? 'Subido' : 'Pendiente' }}
            </span>
          </div>
          
          <div class="card-body">
            <!-- Formulario de subida - SIEMPRE visible -->
            <form @submit.prevent="subirDocumento(tipo.id)" class="upload-form">
              <div class="file-input-container">
                <input 
                  type="file" 
                  :ref="el => setInputRef(tipo.id, el)"
                  @change="handleFileChange(tipo.id, $event)"
                  accept=".pdf"
                  class="file-input"
                />
                <button type="button" class="btn-seleccionar" @click="seleccionarArchivo(tipo.id)">
                  <i class="fas fa-folder-open"></i>
                  Seleccionar archivo
                </button>
                <span class="nombre-archivo" v-if="archivosSeleccionados[tipo.id]">
                  {{ archivosSeleccionados[tipo.id]?.name }}
                </span>
              </div>
              <button type="submit" class="btn-subir" :disabled="!archivosSeleccionados[tipo.id] || cargando[tipo.id]">
                <i class="fas" :class="cargando[tipo.id] ? 'fa-spinner fa-pulse' : 'fa-upload'"></i>
                {{ cargando[tipo.id] ? 'Subiendo...' : 'Subir documento' }}
              </button>
            </form>
            
            <!-- Documento ya subido (si existe) -->
            <div v-if="getDocumentoPorTipo(tipo.id)" class="documento-subido">
              <div class="info-documento">
                <i class="fas fa-file-pdf"></i>
                <div class="detalles">
                  <span class="nombre">{{ getDocumentoPorTipo(tipo.id).nombre_original }}</span>
                  <span class="tamaño">{{ formatTamaño(getDocumentoPorTipo(tipo.id).peso_bytes) }}</span>
                  <span class="fecha">Subido: {{ formatFecha(getDocumentoPorTipo(tipo.id).created_at) }}</span>
                </div>
              </div>
              <div class="acciones-documento">
                <a :href="route('documentos.download', getDocumentoPorTipo(tipo.id).id)" class="btn-descargar">
                  <i class="fas fa-download"></i>
                  Descargar
                </a>
                <button @click="eliminarDocumento(getDocumentoPorTipo(tipo.id).id, tipo.id)" class="btn-eliminar">
                  <i class="fas fa-trash"></i>
                  Eliminar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Progreso general -->
      <div class="progreso-section">
        <div class="progreso-card">
          <h4><i class="fas fa-chart-line"></i> Progreso de documentos</h4>
          <div class="progress-bar-container">
            <div class="progress-bar" :style="{ width: porcentajeCompletado + '%' }"></div>
          </div>
          <p class="progreso-texto">{{ documentosCompletados }} de {{ totalDocumentos }} documentos subidos</p>
          <p class="progreso-porcentaje">{{ porcentajeCompletado }}% completado</p>
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
  documentos: {
    type: Array,
    default: () => []
  },
  tiposDocumento: {
    type: Array,
    default: () => []
  },
  flash: Object
})

// Estado
const archivosSeleccionados = ref({})
const cargando = ref({})
const inputsRef = ref({})

// Crear un mapa de documentos por tipo_id para búsqueda rápida
const documentosMap = computed(() => {
  const map = {}
  props.documentos.forEach(doc => {
    map[doc.tipo_documento_id] = doc
  })
  return map
})

// Obtener documento por tipo ID
const getDocumentoPorTipo = (tipoId) => {
  return documentosMap.value[tipoId] || null
}

// Iconos por tipo de documento
const getIconoPorTipo = (tipoId) => {
  const iconos = {
    1: 'fa-file-alt',
    2: 'fa-file-pdf',
    3: 'fa-file-excel',
    4: 'fa-chart-line',
    5: 'fa-shield-alt',
    6: 'fa-handshake'
  }
  return iconos[tipoId] || 'fa-file'
}

// Configurar referencia del input
const setInputRef = (tipoId, el) => {
  if (el) {
    inputsRef.value[tipoId] = el
  }
}

// Seleccionar archivo
const seleccionarArchivo = (tipoId) => {
  if (inputsRef.value[tipoId]) {
    inputsRef.value[tipoId].click()
  }
}

// Manejar cambio de archivo
const handleFileChange = (tipoId, event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 10 * 1024 * 1024) {
      Swal.fire({
        icon: 'error',
        title: 'Archivo muy grande',
        text: 'El archivo no debe superar los 10MB',
        confirmButtonText: 'OK'
      })
      event.target.value = ''
      return
    }
    archivosSeleccionados.value[tipoId] = file
  }
}

// Subir documento
const subirDocumento = (tipoId) => {
  if (!archivosSeleccionados.value[tipoId]) return
  
  cargando.value[tipoId] = true
  
  const formData = new FormData()
  formData.append('tipo_documento_id', tipoId)
  formData.append('archivo', archivosSeleccionados.value[tipoId])
  
  router.post(route('documentos.store'), formData, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Documento subido!',
        text: 'El documento se ha subido correctamente',
        confirmButtonText: 'OK'
      })
      // Limpiar selección
      archivosSeleccionados.value[tipoId] = null
      if (inputsRef.value[tipoId]) {
        inputsRef.value[tipoId].value = ''
      }
      // Recargar para mostrar el documento
      router.reload({ preserveScroll: true })
    },
    onError: (errors) => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errors.message || 'No se pudo subir el documento',
        confirmButtonText: 'OK'
      })
    },
    onFinish: () => {
      cargando.value[tipoId] = false
    }
  })
}

// Eliminar documento
const eliminarDocumento = (documentoId, tipoId) => {
  Swal.fire({
    title: '¿Eliminar documento?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(route('documentos.destroy', documentoId), {
        preserveScroll: true,
        onSuccess: () => {
          Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            text: 'Documento eliminado correctamente',
            confirmButtonText: 'OK'
          })
          router.reload({ preserveScroll: true })
        },
        onError: (errors) => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errors.message || 'No se pudo eliminar el documento',
            confirmButtonText: 'OK'
          })
        }
      })
    }
  })
}

// Formatear tamaño
const formatTamaño = (bytes) => {
  if (!bytes) return '0 KB'
  if (bytes < 1024) return `${bytes} B`
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(2)} KB`
  return `${(bytes / (1024 * 1024)).toFixed(2)} MB`
}

// Formatear fecha
const formatFecha = (fecha) => {
  if (!fecha) return ''
  const date = new Date(fecha)
  return date.toLocaleDateString('es-MX', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Calcular progreso
const totalDocumentos = computed(() => {
  return props.tiposDocumento.filter(t => t.es_requerido).length
})

const documentosCompletados = computed(() => {
  let completados = 0
  for (const tipo of props.tiposDocumento) {
    if (tipo.es_requerido && documentosMap.value[tipo.id]) {
      completados++
    }
  }
  return completados
})

const porcentajeCompletado = computed(() => {
  if (totalDocumentos.value === 0) return 0
  return Math.round((documentosCompletados.value / totalDocumentos.value) * 100)
})

// Mostrar mensajes flash
if (props.flash?.success) {
  Swal.fire({
    icon: 'success',
    title: '¡Éxito!',
    text: props.flash.success,
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
  font-size: 32px;
  font-weight: bold;
  margin: 20px 0;
  color: #050E3C;
}

.subtitulo i {
  margin-right: 12px;
}

.info-section {
  margin-bottom: 30px;
}

.info-card {
  background: linear-gradient(135deg, #e8f4fd 0%, #d1ecf1 100%);
  border-radius: 12px;
  padding: 20px;
  display: flex;
  gap: 15px;
  border-left: 4px solid #050E3C;
}

.info-card i {
  font-size: 32px;
  color: #050E3C;
}

.info-text {
  flex: 1;
}

.info-text strong {
  display: block;
  margin-bottom: 10px;
  color: #050E3C;
}

.info-text ul {
  margin: 0;
  padding-left: 20px;
}

.info-text li {
  margin: 5px 0;
  color: #555;
}

.debug-warning {
  background-color: #fff3cd;
  border: 1px solid #ffc107;
  color: #856404;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 20px;
  text-align: center;
}

.documentos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.documento-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.documento-card.completado {
  border-left: 4px solid #28a745;
}

.card-header {
  background-color: #f8f9fa;
  padding: 15px 20px;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.card-header i {
  font-size: 24px;
  color: #050E3C;
}

.card-header h3 {
  flex: 1;
  margin: 0;
  font-size: 16px;
  color: #333;
}

.required {
  color: #dc3545;
  font-size: 12px;
}

.opcional {
  color: #6c757d;
  font-size: 12px;
  font-weight: normal;
}

.estado {
  font-size: 12px;
  padding: 4px 10px;
  border-radius: 20px;
  background-color: #fff3cd;
  color: #856404;
  display: flex;
  align-items: center;
  gap: 5px;
}

.estado.subido {
  background-color: #d4edda;
  color: #155724;
}

.card-body {
  padding: 20px;
}

.upload-form {
  margin-bottom: 15px;
}

.file-input-container {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
  flex-wrap: wrap;
}

.file-input {
  display: none;
}

.btn-seleccionar {
  background-color: #6c757d;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
}

.btn-seleccionar:hover {
  background-color: #5a6268;
}

.nombre-archivo {
  font-size: 12px;
  color: #28a745;
  max-width: 200px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.btn-subir {
  width: 100%;
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  border: none;
  padding: 10px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-size: 14px;
  transition: all 0.3s ease;
}

.btn-subir:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-subir:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.documento-subido {
  background-color: #f8f9fa;
  border-radius: 8px;
  padding: 12px;
  margin-top: 15px;
  border-left: 3px solid #28a745;
}

.info-documento {
  display: flex;
  gap: 12px;
  margin-bottom: 10px;
}

.info-documento i {
  font-size: 32px;
  color: #dc3545;
}

.detalles {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.nombre {
  font-size: 13px;
  font-weight: 500;
  color: #333;
  word-break: break-all;
}

.tamaño {
  font-size: 11px;
  color: #6c757d;
}

.fecha {
  font-size: 11px;
  color: #6c757d;
}

.acciones-documento {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

.btn-descargar, .btn-eliminar {
  padding: 5px 12px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  text-decoration: none;
  border: none;
}

.btn-descargar {
  background-color: #050E3C;
  color: white;
}

.btn-descargar:hover {
  background-color: #0a1a6e;
  text-decoration: none;
  color: white;
}

.btn-eliminar {
  background-color: #dc3545;
  color: white;
}

.btn-eliminar:hover {
  background-color: #c82333;
}

.progreso-section {
  margin-top: 30px;
}

.progreso-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.progreso-card h4 {
  margin: 0 0 15px 0;
  color: #050E3C;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.progress-bar-container {
  background-color: #e9ecef;
  border-radius: 10px;
  height: 20px;
  overflow: hidden;
  margin-bottom: 10px;
}

.progress-bar {
  background: linear-gradient(90deg, #28a745, #20c997);
  height: 100%;
  border-radius: 10px;
  transition: width 0.5s ease;
}

.progreso-texto {
  margin: 5px 0;
  font-size: 14px;
  color: #555;
}

.progreso-porcentaje {
  font-size: 18px;
  font-weight: bold;
  color: #28a745;
  margin: 0;
}

@media (max-width: 768px) {
  .documentos-grid {
    grid-template-columns: 1fr;
  }
  
  .subtitulo {
    font-size: 24px;
  }
  
  .info-card {
    flex-direction: column;
  }
  
  .card-header {
    flex-direction: column;
    text-align: center;
  }
  
  .file-input-container {
    flex-direction: column;
    align-items: stretch;
  }
  
  .btn-seleccionar {
    justify-content: center;
  }
  
  .nombre-archivo {
    text-align: center;
    max-width: none;
  }
}
</style>