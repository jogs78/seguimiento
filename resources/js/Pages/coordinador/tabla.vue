<template>
  <!-- Barra de búsqueda principal con diseño mejorado -->
  <div class="search-section">

  <div class="search-grid">

    <!-- Estudiante -->
    <div class="search-card">
      <label>Buscar por estudiante</label>
      <div class="search-input-wrapper">
        <input 
          type="text" 
          v-model="filters.buscar"
          @input="buscarEstudiante"
          @keyup.enter="aplicarFiltros"
          placeholder="Ej: Juan Pérez"
        >
        <button @click="aplicarFiltros">Buscar</button>
      </div>

      <div v-if="sugerencias.estudiantes.length" class="suggestions-box">
        <div 
          v-for="sug in sugerencias.estudiantes" 
          :key="sug"
          @click="seleccionarSugerencia('buscar', sug)"
          class="suggestion-item"
        >
          {{ sug }}
        </div>
      </div>
    </div>

    <!-- Proyecto -->
    <div class="search-card">
      <label>Buscar por proyecto</label>
      <div class="search-input-wrapper">
        <input 
          type="text" 
          v-model="filters.buscar_proyecto"
          @input="buscarProyecto"
          @keyup.enter="aplicarFiltros"
          placeholder="Ej: Sistema web"
        >
        <button @click="aplicarFiltros">Buscar</button>
      </div>

      <div v-if="sugerencias.proyectos.length" class="suggestions-box">
        <div 
          v-for="sug in sugerencias.proyectos" 
          :key="sug"
          @click="seleccionarSugerencia('buscar_proyecto', sug)"
          class="suggestion-item"
        >
          {{ sug }}
        </div>
      </div>
    </div>

    <!-- Asesor -->
    <div class="search-card">
      <label>Buscar por asesor</label>
      <div class="search-input-wrapper">
        <input 
          type="text" 
          v-model="filters.buscar_asesor"
          @input="buscarAsesor"
          @keyup.enter="aplicarFiltros"
          placeholder="Ej: María López"
        >
        <button @click="aplicarFiltros">Buscar</button>
      </div>

      <div v-if="sugerencias.asesores.length" class="suggestions-box">
        <div 
          v-for="sug in sugerencias.asesores" 
          :key="sug"
          @click="seleccionarSugerencia('buscar_asesor', sug)"
          class="suggestion-item"
        >
          {{ sug }}
        </div>
      </div>
    </div>

    <!-- Empresa -->
    <div class="search-card">
      <label>Buscar por empresa</label>
      <div class="search-input-wrapper">
        <input 
          type="text" 
          v-model="filters.buscar_empresa"
          @input="buscarEmpresa"
          @keyup.enter="aplicarFiltros"
          placeholder="Ej: Google"
        >
        <button @click="aplicarFiltros">Buscar</button>
      </div>

      <div v-if="sugerencias.empresas.length" class="suggestions-box">
        <div 
          v-for="sug in sugerencias.empresas" 
          :key="sug"
          @click="seleccionarSugerencia('buscar_empresa', sug)"
          class="suggestion-item"
        >
          {{ sug }}
        </div>
      </div>
    </div>

  </div>

</div>


    <!-- Título -->
    <div class="section-title">
        <h2>Tabla de Proyectos</h2>
        <div class="title-underline"></div>
    </div>

        <!-- Panel de debug - ahora visible cuando mostrarDebug es true 
         
        <div v-if="mostrarDebug" class="debug-panel">
      <h4>🔍 Debug: Datos recibidos</h4>
      <button @click="mostrarDebug = false" class="debug-close">✕</button>
      <pre>{{ JSON.stringify(proyectos, null, 2) }}</pre>
    </div>
        -->
    

    <!-- Tabla -->
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Nombre proyecto</th>
                    <th>Asesor interno</th>
                    <th>Empresa / Asesor externo</th>
                    <th>Estudiante(s)</th>
                    <th>Seguimiento 1</th>
                    <th>Seguimiento 2</th>
                    <th>Seguimiento Final</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="proyecto in proyectos" :key="proyecto.id" class="table-row">
                    <!-- Nombre del proyecto -->
                    <td class="project-name">{{ proyecto.nombre }}</td>
                    
                    <!-- Asesor interno -->
                    <td class="advisor-cell">
                        <form @submit.prevent="asignarAsesor(proyecto)" class="advisor-form">
                            <select 
                            v-model="proyecto.asesor_seleccionado" 
                            class="form-select"
                            :class="{ 'has-value': proyecto.asesor_seleccionado }"
                            >
                            <option value="" disabled>Elige un asesor...</option>
                            <option 
                                v-for="asesor in asesores" 
                                :key="asesor.id"
                                :value="asesor.id"
                            >
                                {{ asesor.nombre }}
                            </option>
                            </select>
                            
                            <button 
                            type="submit" 
                            class="btn-assign"
                            :class="{ 'btn-change': proyecto.asesor_id }"
                            >
                            {{ proyecto.asesor_id ? 'CAMBIAR' : 'ASIGNAR' }}
                            </button>
                        </form>

                        <!-- Mostrar el asesor actual si existe -->
                        <div v-if="proyecto.asesor" class="email-section">
                            <span class="advisor-name">{{ proyecto.asesor.nombre }}</span>
                            <button 
                            @click="enviarCorreo('asesor', proyecto.asesor.id)"
                            class="btn-email"
                            title="Enviar correo al asesor"
                            >
                            <img src="/images/mail.png" alt="Email" class="email-icon">
                            </button>
                        </div>
                        <p v-else class="no-data">Sin asesor interno</p>
                    </td>

                    <!-- Empresa / Asesor externo -->
                    <td class="company-cell">
                        <div class="company-name">{{ proyecto.empresa?.nombre }}</div>
                        
                        <div v-if="proyecto.externo" class="email-section">
                            <button 
                                @click="enviarCorreo('externo', proyecto.externo.id)"
                                class="btn-email"
                                title="Enviar correo al asesor externo"
                            >
                                <!-- CORREGIDO: Ruta absoluta -->
                                <img src="/images/mail.png" alt="Email" class="email-icon">
                            </button>
                        </div>
                        <p v-else class="no-data">Sin asesor externo</p>
                    </td>

                    <!-- Estudiantes -->
                    <td class="students-cell">
                        <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="student-item">
                            <div class="student-info">
                                {{ estudiante.numero_control }} {{ estudiante.nombre }} 
                                {{ estudiante.apellido_paterno }} {{ estudiante.apellido_materno }}
                            </div>
                            <button 
                                @click="enviarCorreo('estudiante', estudiante.id)"
                                class="btn-email small"
                                title="Enviar correo al estudiante"
                            >
                                <!-- CORREGIDO: Ruta absoluta -->
                                <img src="/images/mail.png" alt="Email" class="email-icon small">
                            </button>
                        </div>
                    </td>

                    <!-- Seguimiento 1 -->
                    <td class="status-cell">
                        <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="status-item">
                            <div class="status-icons">
                                <template v-if="estudiante.primer?.puntualidad_interno && estudiante.primer?.puntualidad_externo">
                                    <button @click="descargarSeguimiento(estudiante.id, 'primer')" class="btn-download">
                                        <!-- CORREGIDO: Ruta absoluta -->
                                        <img src="/images/UnoSegui.png" alt="Descargar" class="status-icon">
                                    </button>
                                </template>
                                <template v-else>
                                    <!--  -->
                                    <img v-if="estudiante.primer?.puntualidad_interno" 
                                        src="/images/IntSi.png" 
                                        alt="Interno OK" 
                                        class="status-icon"
                                        title="Asesor Interno ya calificó">
                                    <img v-else 
                                        src="/images/IntNo.png" 
                                        alt="Interno pendiente" 
                                        class="status-icon"
                                        title="Asesor Interno no ha calificado">
                                    
                                    <img v-if="estudiante.primer?.puntualidad_externo" 
                                        src="/images/ExtSi.png" 
                                        alt="Externo OK" 
                                        class="status-icon"
                                        title="Asesor Externo ya calificó">
                                    <img v-else 
                                        src="/images/ExtNo.png" 
                                        alt="Externo pendiente" 
                                        class="status-icon"
                                        title="Asesor Externo no ha calificado">
                                </template>
                            </div>
                        </div>
                    </td>

                    <!-- Seguimiento 2 -->
                    <td class="status-cell">
                        <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="status-item">
                            <div class="status-icons">
                                <template v-if="estudiante.segundo?.puntualidad_interno && estudiante.segundo?.puntualidad_externo">
                                    <button @click="descargarSeguimiento(estudiante.id, 'segundo')" class="btn-download">
                                        <!-- CORREGIDO -->
                                        <img src="/images/DosSegui.png" alt="Descargar" class="status-icon">
                                    </button>
                                </template>
                                <template v-else>
                                    <!-- CORREGIDO -->
                                    <img v-if="estudiante.segundo?.puntualidad_interno" 
                                        src="/images/IntSi.png" 
                                        alt="Interno OK" 
                                        class="status-icon">
                                    <img v-else 
                                        src="/images/IntNo.png" 
                                        alt="Interno pendiente" 
                                        class="status-icon">
                                    
                                    <img v-if="estudiante.segundo?.puntualidad_externo" 
                                        src="/images/ExtSi.png" 
                                        alt="Externo OK" 
                                        class="status-icon">
                                    <img v-else 
                                        src="/images/ExtNo.png" 
                                        alt="Externo pendiente" 
                                        class="status-icon">
                                </template>
                            </div>
                        </div>
                    </td>

                    <!-- Seguimiento Final -->
                    <td class="status-cell">
                        <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="status-item">
                            <div class="status-icons">
                                <template v-if="estudiante.ultimo?.promedio_interno && estudiante.ultimo?.promedio_externo">
                                    <button @click="descargarSeguimiento(estudiante.id, 'ultimo')" class="btn-download">
                                        <!-- CORREGIDO -->
                                        <img src="/images/TresSegui.png" alt="Descargar" class="status-icon">
                                    </button>
                                </template>
                                <template v-else>
                                    <!-- CORREGIDO -->
                                    <img v-if="estudiante.ultimo?.promedio_interno" 
                                        src="/images/IntSi.png" 
                                        alt="Interno OK" 
                                        class="status-icon">
                                    <img v-else 
                                        src="/images/IntNo.png" 
                                        alt="Interno pendiente" 
                                        class="status-icon">
                                    
                                    <img v-if="estudiante.ultimo?.promedio_externo" 
                                        src="/images/ExtSi.png" 
                                        alt="Externo OK" 
                                        class="status-icon">
                                    <img v-else 
                                        src="/images/ExtNo.png" 
                                        alt="Externo pendiente" 
                                        class="status-icon">
                                </template>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>



<script setup>
import { ref, reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'

// Inicializar asesor_seleccionado para cada proyecto con el asesor actual
const proyectosConSeleccion = ref([])

onMounted(() => {
  proyectosConSeleccion.value = props.proyectos.map(proyecto => ({
    ...proyecto,
    asesor_seleccionado: proyecto.asesor_id || '' // ← Inicializar con el ID actual
  }))
})



// Declara debug como ref, quitar después
const mostrarDebug = ref(true) // o true si quieres ver los datos


const props = defineProps({
    proyectos: Array,
    asesores: Array
})

// Filtros de búsqueda
const filters = reactive({
    buscar: '',
    buscar_proyecto: '',
    buscar_asesor: '',
    buscar_empresa: ''
})

// Sugerencias de autocompletado
const sugerencias = reactive({
    estudiantes: [],
    proyectos: [],
    asesores: [],
    empresas: []
})

// Timeouts para debounce
let timeoutEstudiante, timeoutProyecto, timeoutAsesor, timeoutEmpresa

// Métodos de búsqueda
const buscarEstudiante = () => {
    clearTimeout(timeoutEstudiante)
    timeoutEstudiante = setTimeout(() => {
        if (filters.buscar.length >= 2) {
            axios.get(route('coordinadores.sugerencias'), {
                params: { query: filters.buscar }
            }).then(response => {
                sugerencias.estudiantes = response.data
            })
        } else {
            sugerencias.estudiantes = []
        }
    }, 300)
}

const buscarProyecto = () => {
    clearTimeout(timeoutProyecto)
    timeoutProyecto = setTimeout(() => {
        if (filters.buscar_proyecto.length >= 2) {
            axios.get(route('coordinadores.sugerenciasProyecto'), {
                params: { query: filters.buscar_proyecto }
            }).then(response => {
                sugerencias.proyectos = response.data
            })
        } else {
            sugerencias.proyectos = []
        }
    }, 300)
}

const buscarAsesor = () => {
    clearTimeout(timeoutAsesor)
    timeoutAsesor = setTimeout(() => {
        if (filters.buscar_asesor.length >= 2) {
            axios.get(route('coordinadores.sugerenciasAsesor'), {
                params: { query: filters.buscar_asesor }
            }).then(response => {
                sugerencias.asesores = response.data
            })
        } else {
            sugerencias.asesores = []
        }
    }, 300)
}

const buscarEmpresa = () => {
    clearTimeout(timeoutEmpresa)
    timeoutEmpresa = setTimeout(() => {
        if (filters.buscar_empresa.length >= 2) {
            axios.get(route('coordinadores.sugerenciasEmpresa'), {
                params: { query: filters.buscar_empresa }
            }).then(response => {
                sugerencias.empresas = response.data
            })
        } else {
            sugerencias.empresas = []
        }
    }, 300)
}

/*
const seleccionarSugerencia = (campo, valor) => {
    filters[campo] = valor
    sugerencias[campo === 'buscar' ? 'estudiantes' : 
                 campo === 'buscar_proyecto' ? 'proyectos' :
                 campo === 'buscar_asesor' ? 'asesores' : 'empresas'] = []
    aplicarFiltros()
} 
    

const aplicarFiltros = () => {
    router.get(route('proyectos.index'), filters, {
        preserveState: true,
        preserveScroll: true
    })
}
*/
// Método para seleccionar una sugerencia
const seleccionarSugerencia = (campo, valor) => {
  filters[campo] = valor
  // Limpiar sugerencias del campo correspondiente
  if (campo === 'buscar') {
    sugerencias.estudiantes = []
  } else if (campo === 'buscar_proyecto') {
    sugerencias.proyectos = []
  } else if (campo === 'buscar_asesor') {
    sugerencias.asesores = []
  } else if (campo === 'buscar_empresa') {
    sugerencias.empresas = []
  }
  aplicarFiltros()
}

// Método para aplicar todos los filtros
const aplicarFiltros = () => {
  // Construir objeto con solo los filtros que tienen valor
  const params = {}
  
  if (filters.buscar) params.buscar = filters.buscar
  if (filters.buscar_proyecto) params.buscar_proyecto = filters.buscar_proyecto
  if (filters.buscar_asesor) params.buscar_asesor = filters.buscar_asesor
  if (filters.buscar_empresa) params.buscar_empresa = filters.buscar_empresa
  
  // Usar Inertia para navegar con los filtros
  router.get(route('proyectos.index'), params, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  })
}



// Al cargar el componente, sincronizar filters con los query params
const page = usePage()
const urlParams = new URLSearchParams(window.location.search)

if (urlParams.has('buscar')) filters.buscar = urlParams.get('buscar')
if (urlParams.has('buscar_proyecto')) filters.buscar_proyecto = urlParams.get('buscar_proyecto')
if (urlParams.has('buscar_asesor')) filters.buscar_asesor = urlParams.get('buscar_asesor')
if (urlParams.has('buscar_empresa')) filters.buscar_empresa = urlParams.get('buscar_empresa')



const asignarAsesor = (proyecto) => {
    router.put(route('coordinadores.asignarAsesor3', proyecto.id), {
        asesor_id: proyecto.asesor_seleccionado,
        proyecto_id: proyecto.id
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Mostrar notificación de éxito
        }
    })
}

const enviarCorreo = (tipo, id) => {
    router.get(route('correo.create', { type: tipo, id: id }))
}

const descargarSeguimiento = (estudianteId, tipo) => {
    const rutas = {
        primer: 'estudiante.impresiones.seguimientos.primer',
        segundo: 'estudiante.impresiones.seguimientos.segundo',
        ultimo: 'estudiante.impresiones.seguimientos.ultimo'
    }
    
    window.open(route(rutas[tipo], estudianteId), '_blank')
}
</script>

<script>
// Esta es la forma de asignar layout en Vue 3 con Options API
export default {
  layout: AppLayout
}
</script>

<style scoped>
/* Variables de color */
:root {
    --primary-blue: #1976D2;
    --secondary-blue: #2196F3;
    --light-blue: #E3F2FD;
    --dark-blue: #0D47A1;
    --accent-red: #F44336;
    --light-red: #FFEBEE;
    --success-green: #4CAF50;
    --warning-orange: #FF9800;
    --text-dark: #2C3E50;
    --text-light: #ECF0F1;
    --border-color: #E0E0E0;
    --shadow: 0 2px 4px rgba(0,0,0,0.1);
}
/* CONTENEDOR */
.table-responsive {
    overflow-x: auto;
    background: #f5f9ff; /* fondo general suave */
    border-radius: 12px;
    padding: 10px;
}

/* TABLA */
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #ffffff; 
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* HEADER */
.modern-table thead tr {
    background: linear-gradient(135deg, var(--dark-blue), var(--primary-blue));
    color: #b30e08;
}

.modern-table th {
    padding: 14px;
    font-weight: 600;
    text-align: left;
    font-size: 0.95rem;
    letter-spacing: 0.5px;
}

/* FILAS */
.modern-table td {
    padding: 14px;
    border-bottom: 1px solid #eef3f9;
    color: var(--text-dark);
}

/* EFECTO HOVER */
.table-row {
    transition: all 0.2s ease;
}

.table-row:hover {
    background: var(--light-blue);
    transform: scale(1.002);
}

/* FILAS ALTERNADAS */
.modern-table tbody tr:nth-child(even) {
    background: #f9fbff;
}

/* NOMBRE DEL PROYECTO */
.project-name {
    font-weight: 600;
    color: var(--dark-blue);
}

/* SELECT */
.form-select {
    flex: 1;
    padding: 6px;
    border: 1.5px solid var(--border-color);
    border-radius: 6px;
    background: white;
    transition: all 0.2s;
}

.form-select:focus {
    border-color: var(--primary-blue);
    box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.15);
}

/* BOTÓN ASIGNAR */
.btn-assign {
    padding: 6px 12px;
    background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
    color: rgb(36, 24, 211);
    border: none;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.btn-assign:hover {
    background: var(--dark-blue);
}


.btn-change {
    background: linear-gradient(135deg, var(--accent-red), #d32f2f);
}

.btn-change:hover {
    background: #1f65a7;
}

/* Sección de email */
.email-section {
    display: flex;
    justify-content: center;
    margin-top: 0.5rem;
}

.btn-email {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: transform 0.2s ease;
}

.btn-email:hover {
    transform: scale(1.1);
}

.email-icon {
    width: 40px;
    height: auto;
}

.email-icon.small {
    width: 30px;
}

/* Estudiantes */
.students-cell {
    max-width: 300px;
}

.student-item {
    padding: 0.5rem;
    margin: 0.25rem 0;
    background: var(--light-blue);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
}

.student-info {
    font-size: 0.9rem;
    color: var(--text-dark);
}

/* Estados de seguimiento */
.status-cell {
    min-width: 120px;
}

.status-item {
    padding: 0.5rem 0;
    border-bottom: 1px dashed var(--border-color);
}

.status-item:last-child {
    border-bottom: none;
}

.status-icons {
    display: flex;
    gap: 0.25rem;
    justify-content: center;
    align-items: center;
}

.status-icon {
    width: 30px;
    height: auto;
    cursor: help;
}

.btn-download {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}

.btn-download:hover {
    transform: scale(1.1);
}

/* Mensajes de datos no disponibles */
.no-data {
    color: #999;
    font-style: italic;
    font-size: 0.9rem;
    text-align: center;
    margin: 0.5rem 0;
}

.company-name {
    font-weight: 500;
    color: var(--dark-blue);
    margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 1200px) {
    .search-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

@media (max-width: 768px) {
    .advisor-form {
        flex-direction: column;
    }
    
    .student-item {
        flex-direction: column;
        text-align: center;
    }
}

.search-section {
  padding: 20px;
}

.search-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr); /* 2 por fila */
  gap: 20px;
}

.search-card {
  background: #fff;
  padding: 15px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  position: relative;
}

.search-card label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
}

.search-input-wrapper {
  display: flex;
  gap: 10px;
}

.search-input-wrapper input {
  flex: 1;
  padding: 8px;
  border-radius: 8px;
  border: 1px solid #ccc;
}

.search-input-wrapper button {
  padding: 8px 12px;
  border: none;
  background: #3147c2;
  color: white;
  border-radius: 8px;
  cursor: pointer;
}

.search-input-wrapper button:hover {
  background: #553dbe;
}

.suggestions-box {
  position: absolute;
  top: 85px;
  left: 15px;
  right: 15px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  max-height: 150px;
  overflow-y: auto;
  z-index: 10;
}

.suggestion-item {
  padding: 8px;
  cursor: pointer;
}

.suggestion-item:hover {
  background: #f0f0f0;
}

/* Responsive */
@media (max-width: 768px) {
  .search-grid {
    grid-template-columns: 1fr; /* 1 por fila en móvil */
  }
}

</style>