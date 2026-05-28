<template>
  <div class="page-wrapper">
    <div class="page-content">
        <!-- Barra de búsqueda principal con diseño mejorado -->
        <div class="search-section">
        <div class="search-grid">
            <!-- Estudiante -->
            <div class="search-card">
            <div class="search-card-header">
                <i class="fas fa-user-graduate"></i>
                <label>Buscar por estudiante</label>
            </div>
            <div class="search-input-wrapper">
                <input 
                type="text" 
                v-model="filters.buscar"
                @input="buscarEstudiante"
                @keyup.enter="aplicarFiltros"
                placeholder="Ej: Julio Pérez"
                class="search-input"
                >
                <button @click="aplicarFiltros" class="search-btn">
                <i class="fas fa-search"></i> Buscar
                </button>
            </div>
            <div v-if="sugerencias.estudiantes.length" class="suggestions-box">
                <div 
                v-for="sug in sugerencias.estudiantes" 
                :key="sug"
                @click="seleccionarSugerencia('buscar', sug)"
                class="suggestion-item"
                >
                <i class="fas fa-user"></i> {{ sug }}
                </div>
            </div>
            </div>

            <!-- Proyecto -->
            <div class="search-card">
            <div class="search-card-header">
                <i class="fas fa-project-diagram"></i>
                <label>Buscar por proyecto</label>
            </div>
            <div class="search-input-wrapper">
                <input 
                type="text" 
                v-model="filters.buscar_proyecto"
                @input="buscarProyecto"
                @keyup.enter="aplicarFiltros"
                placeholder="Ej: Sistema web"
                class="search-input"
                >
                <button @click="aplicarFiltros" class="search-btn">
                <i class="fas fa-search"></i> Buscar
                </button>
            </div>
            <div v-if="sugerencias.proyectos.length" class="suggestions-box">
                <div 
                v-for="sug in sugerencias.proyectos" 
                :key="sug"
                @click="seleccionarSugerencia('buscar_proyecto', sug)"
                class="suggestion-item"
                >
                <i class="fas fa-folder"></i> {{ sug }}
                </div>
            </div>
            </div>

            <!-- Asesor -->
            <div class="search-card">
            <div class="search-card-header">
                <i class="fas fa-chalkboard-teacher"></i>
                <label>Buscar por asesor</label>
            </div>
            <div class="search-input-wrapper">
                <input 
                type="text" 
                v-model="filters.buscar_asesor"
                @input="buscarAsesor"
                @keyup.enter="aplicarFiltros"
                placeholder="Ej: Jorge Guzmán"
                class="search-input"
                >
                <button @click="aplicarFiltros" class="search-btn">
                <i class="fas fa-search"></i> Buscar
                </button>
            </div>
            <div v-if="sugerencias.asesores.length" class="suggestions-box">
                <div 
                v-for="sug in sugerencias.asesores" 
                :key="sug"
                @click="seleccionarSugerencia('buscar_asesor', sug)"
                class="suggestion-item"
                >
                <i class="fas fa-user-tie"></i> {{ sug }}
                </div>
            </div>
            </div>

            <!-- Empresa -->
            <div class="search-card">
            <div class="search-card-header">
                <i class="fas fa-building"></i>
                <label>Buscar por empresa</label>
            </div>
            <div class="search-input-wrapper">
                <input 
                type="text" 
                v-model="filters.buscar_empresa"
                @input="buscarEmpresa"
                @keyup.enter="aplicarFiltros"
                placeholder="Ej: CFE"
                class="search-input"
                >
                <button @click="aplicarFiltros" class="search-btn">
                <i class="fas fa-search"></i> Buscar
                </button>
            </div>
            <div v-if="sugerencias.empresas.length" class="suggestions-box">
                <div 
                v-for="sug in sugerencias.empresas" 
                :key="sug"
                @click="seleccionarSugerencia('buscar_empresa', sug)"
                class="suggestion-item"
                >
                <i class="fas fa-industry"></i> {{ sug }}
                </div>
            </div>
            </div>
        </div>
        </div>

        <!-- Título -->
        <div class="section-title">

        <div class="title-top">

            <!-- IZQUIERDA -->
            <div class="title-content">
                <i class="fas fa-table-list"></i>

                <h2>Tabla de Proyectos</h2>

                    <span v-if="periodoActual" class="periodo-badge">
                        <i class="fas fa-calendar-alt"></i>
                        {{ periodoActual.nombre }}
                    </span>
                </div>

                <!-- DERECHA -->
                
                <div class="outside-time-toggle">

                    <span class="toggle-label">
                        Calificación interna
                    </span>

                    <label class="switch">

                        <input
                            type="checkbox"
                            v-model="internoActivo"
                            @change="cambiarInterno"
                        >

                        <span class="slider"></span>

                    </label>

                    <span
                        class="toggle-status"
                        :class="{ active: internoActivo }"
                    >
                        {{ internoActivo ? 'SI' : 'NO' }}
                    </span>
                  </div>
            </div>

        <div class="title-underline"></div>

    </div>

        <!-- Tabla -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-file-alt"></i> Nombre proyecto</th>
                            <th><i class="fas fa-chalkboard-teacher"></i> Asesor interno</th>
                            <th><i class="fas fa-building"></i> Empresa / Asesor externo</th>
                            <th><i class="fas fa-users"></i> Estudiante(s)</th>

                            <!-- NUEVAS COLUMNAS -->
                            <th><i class="fas fa-file-signature"></i> Solicitud</th>
                            <th><i class="fas fa-file-pdf"></i> Anteproyecto</th>

                            <th><i class="fas fa-chart-line"></i> Seguimiento 1</th>
                            <th><i class="fas fa-chart-line"></i> Seguimiento 2</th>
                            <th><i class="fas fa-chart-line"></i> Seguimiento Final</th>
                            <th><i class="fas fa-clock"></i> Fuera de tiempo</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="proyecto in proyectosPaginados"
                            :key="proyecto.id"
                            class="table-row"
                        >

                            <!-- Nombre del proyecto -->
                            <td class="project-name">
                                <div class="project-title">
                                    <i class="fas fa-folder-open"></i>
                                    <span>{{ proyecto.nombre }}</span>
                                </div>
                            </td>

                            <!-- Asesor interno -->
                            <td class="advisor-cell">
                                <form
                                    @submit.prevent="asignarAsesor(proyecto)"
                                    class="advisor-form"
                                >
                                    <div class="select-wrapper">
                                        <select
                                            v-model="proyecto.asesor_seleccionado"
                                            class="form-select"
                                            :class="{ 'has-value': proyecto.asesor_seleccionado }"
                                        >
                                            <option value="" disabled>
                                                Elige un asesor...
                                            </option>

                                            <option
                                                v-for="asesor in asesores"
                                                :key="asesor.id"
                                                :value="asesor.id"
                                            >
                                                {{ asesor.nombre }}
                                                {{ asesor.apellido_paterno || '' }}
                                            </option>
                                        </select>

                                        <i class="fas fa-chevron-down select-arrow"></i>
                                    </div>

                                    <button
                                        type="submit"
                                        class="btn-assign"
                                        :class="{ 'btn-change': proyecto.asesor_id }"
                                    >
                                        <i
                                            class="fas"
                                            :class="proyecto.asesor_id
                                                ? 'fa-exchange-alt'
                                                : 'fa-user-plus'"
                                        ></i>

                                        {{
                                            proyecto.asesor_id
                                                ? 'CAMBIAR'
                                                : 'ASIGNAR'
                                        }}
                                    </button>
                                </form>

                                <div v-if="proyecto.asesor" class="email-section">
                                    <span class="advisor-name">
                                        <i class="fas fa-user-check"></i>
                                        {{ proyecto.asesor.nombre }}
                                    </span>

                                    <button
                                        @click="enviarCorreo('asesor', proyecto.asesor.id)"
                                        class="btn-email"
                                        title="Enviar correo al asesor"
                                    >
                                        <img
                                            src="/images/mail.png"
                                            alt="Email"
                                            class="email-icon"
                                        >
                                    </button>
                                </div>

                                <p v-else class="no-data">
                                    <i class="fas fa-user-slash"></i>
                                    Sin asesor interno
                                </p>
                            </td>

                            <!-- Empresa / Asesor externo -->
                            <td class="company-cell">
                                <div class="company-info">
                                    <i class="fas fa-building"></i>

                                    <span class="company-name">
                                        {{ proyecto.empresa?.nombre || 'Sin empresa' }}
                                    </span>
                                </div>

                                <div
                                    v-if="proyecto.externo"
                                    class="externo-info"
                                >
                                    <i class="fas fa-user-tie"></i>

                                    <span>
                                        {{ proyecto.externo.nombre }}
                                        {{ proyecto.externo.apellido_paterno || '' }}
                                    </span>

                                    <button
                                        @click="enviarCorreo('externo', proyecto.externo.id)"
                                        class="btn-email small"
                                        title="Enviar correo al asesor externo"
                                    >
                                        <img
                                            src="/images/mail.png"
                                            alt="Email"
                                            class="email-icon small"
                                        >
                                    </button>
                                </div>

                                <p v-else class="no-data-small">
                                    <i class="fas fa-user-slash"></i>
                                    Sin asesor externo
                                </p>
                            </td>

                            <!-- Estudiantes -->
                            <td class="students-cell">
                                <div
                                    v-for="estudiante in proyecto.estudiantes"
                                    :key="estudiante.id"
                                    class="student-item"
                                >
                                    <div class="student-info">
                                        <i class="fas fa-user-graduate"></i>

                                        <span>
                                            {{ estudiante.numero_control }}
                                            {{ estudiante.nombre }}
                                            {{ estudiante.apellido_paterno }}
                                            {{ estudiante.apellido_materno }}
                                        </span>
                                    </div>

                                    <button
                                        @click="enviarCorreo('estudiante', estudiante.id)"
                                        class="btn-email small"
                                        title="Enviar correo al estudiante"
                                    >
                                        <img
                                            src="/images/mail.png"
                                            alt="Email"
                                            class="email-icon small"
                                        >
                                    </button>
                                </div>
                            </td>

                           <!-- SOLICITUD -->
<td class="status-cell">
    <div
        v-for="estudiante in proyecto.estudiantes"
        :key="'solicitud-' + estudiante.id"
        class="status-item"
    >
        <template v-if="getDocumentosEstudiante(estudiante.id)?.solicitud?.subido">
            <button
                @click="verDocumento(getDocumentosEstudiante(estudiante.id).solicitud)"
                class="btn-download"
                title="Ver solicitud"
            >
                <i class="fas fa-eye status-icon" style="color: #28a745;"></i>
            </button>

            <button
                @click="descargarDocumento(getDocumentosEstudiante(estudiante.id).solicitud)"
                class="btn-download"
                title="Descargar solicitud"
            >
                <i class="fas fa-download status-icon" style="color: #17a2b8;"></i>
            </button>

            <span class="status-label">Subido</span>
        </template>

        <template v-else>
            <div class="status-asesor">
                <i class="fas fa-times-circle status-icon" style="color: #d9534f;"></i>
                <span class="status-label">Pendiente</span>
            </div>
        </template>
    </div>
</td>

<!-- ANTEPROYECTO -->
<td class="status-cell">
    <div
        v-for="estudiante in proyecto.estudiantes"
        :key="'anteproyecto-' + estudiante.id"
        class="status-item"
    >
        <template v-if="getDocumentosEstudiante(estudiante.id)?.anteproyecto?.subido">
            <button
                @click="verDocumento(getDocumentosEstudiante(estudiante.id).anteproyecto)"
                class="btn-download"
                title="Ver anteproyecto"
            >
                <i class="fas fa-eye status-icon" style="color: #28a745;"></i>
            </button>

            <button
                @click="descargarDocumento(getDocumentosEstudiante(estudiante.id).anteproyecto)"
                class="btn-download"
                title="Descargar anteproyecto"
            >
                <i class="fas fa-download status-icon" style="color: #17a2b8;"></i>
            </button>

            <span class="status-label">Subido</span>
        </template>

        <template v-else>
            <div class="status-asesor">
                <i class="fas fa-times-circle status-icon" style="color: #d9534f;"></i>
                <span class="status-label">Pendiente</span>
            </div>
        </template>
    </div>
</td>

                            <!-- Seguimiento 1 -->
                            <td class="status-cell">
                                <div
                                    v-for="estudiante in proyecto.estudiantes"
                                    :key="estudiante.id"
                                    class="status-item"
                                >
                                    <div class="status-icons">

                                        <template
                                            v-if="
                                                estudiante.primer?.puntualidad_interno != null
                                                &&
                                                estudiante.primer?.puntualidad_externo != null
                                            "
                                        >
                                            <button
                                                @click="descargarSeguimiento(estudiante.id, 'primer')"
                                                class="btn-download"
                                                title="Descargar seguimiento"
                                            >
                                                <img
                                                    src="/images/UnoSegui.png"
                                                    alt="Descargar"
                                                    class="status-icon"
                                                >
                                            </button>
                                        </template>

                                        <template v-else>

                                            <div class="status-asesor">
                                                <img
                                                    v-if="estudiante.primer?.puntualidad_interno != null"
                                                    src="/images/IntSi.png"
                                                    alt="Interno OK"
                                                    class="status-icon"
                                                    title="Asesor Interno ya calificó"
                                                >

                                                <img
                                                    v-else
                                                    src="/images/IntNo.png"
                                                    alt="Interno pendiente"
                                                    class="status-icon"
                                                    title="Asesor Interno no ha calificado"
                                                >

                                                <span class="status-label">
                                                    Interno
                                                </span>
                                            </div>

                                            <div class="status-asesor">
                                                <img
                                                    v-if="estudiante.primer?.puntualidad_externo != null"
                                                    src="/images/ExtSi.png"
                                                    alt="Externo OK"
                                                    class="status-icon"
                                                    title="Asesor Externo ya calificó"
                                                >

                                                <img
                                                    v-else
                                                    src="/images/ExtNo.png"
                                                    alt="Externo pendiente"
                                                    class="status-icon"
                                                    title="Asesor Externo no ha calificado"
                                                >

                                                <span class="status-label">
                                                    Externo
                                                </span>
                                            </div>

                                        </template>

                                    </div>
                                </div>
                            </td>

                            <!-- Seguimiento 2 -->
                            <td class="status-cell">
                                <div
                                    v-for="estudiante in proyecto.estudiantes"
                                    :key="estudiante.id"
                                    class="status-item"
                                >
                                    <div class="status-icons">

                                        <template
                                            v-if="
                                                estudiante.segundo?.puntualidad_interno != null
                                                &&
                                                estudiante.segundo?.puntualidad_externo != null
                                            "
                                        >
                                            <button
                                                @click="descargarSeguimiento(estudiante.id, 'segundo')"
                                                class="btn-download"
                                                title="Descargar seguimiento"
                                            >
                                                <img
                                                    src="/images/DosSegui.png"
                                                    alt="Descargar"
                                                    class="status-icon"
                                                >
                                            </button>
                                        </template>

                                        <template v-else>

                                            <div class="status-asesor">
                                                <img
                                                    v-if="estudiante.segundo?.puntualidad_interno != null"
                                                    src="/images/IntSi.png"
                                                    alt="Interno OK"
                                                    class="status-icon"
                                                >

                                                <img
                                                    v-else
                                                    src="/images/IntNo.png"
                                                    alt="Interno pendiente"
                                                    class="status-icon"
                                                >

                                                <span class="status-label">
                                                    Interno
                                                </span>
                                            </div>

                                            <div class="status-asesor">
                                                <img
                                                    v-if="estudiante.segundo?.puntualidad_externo != null"
                                                    src="/images/ExtSi.png"
                                                    alt="Externo OK"
                                                    class="status-icon"
                                                >

                                                <img
                                                    v-else
                                                    src="/images/ExtNo.png"
                                                    alt="Externo pendiente"
                                                    class="status-icon"
                                                >

                                                <span class="status-label">
                                                    Externo
                                                </span>
                                            </div>

                                        </template>

                                    </div>
                                </div>
                            </td>

                            <!-- Seguimiento Final -->
                            <td class="status-cell">
                                <div
                                    v-for="estudiante in proyecto.estudiantes"
                                    :key="estudiante.id"
                                    class="status-item"
                                >
                                    <div class="status-icons">

                                        <template
                                            v-if="
                                                estudiante.ultimo?.promedio_interno != null
                                                &&
                                                estudiante.ultimo?.promedio_externo != null
                                            "
                                        >
                                            <button
                                                @click="descargarSeguimiento(estudiante.id, 'ultimo')"
                                                class="btn-download"
                                                title="Descargar seguimiento"
                                            >
                                                <img
                                                    src="/images/TresSegui.png"
                                                    alt="Descargar"
                                                    class="status-icon"
                                                >
                                            </button>
                                        </template>

                                        <template v-else>

                                            <div class="status-asesor">
                                                <img
                                                    v-if="estudiante.ultimo?.promedio_interno != null"
                                                    src="/images/IntSi.png"
                                                    alt="Interno OK"
                                                    class="status-icon"
                                                >

                                                <img
                                                    v-else
                                                    src="/images/IntNo.png"
                                                    alt="Interno pendiente"
                                                    class="status-icon"
                                                >

                                                <span class="status-label">
                                                    Interno
                                                </span>
                                            </div>

                                            <div class="status-asesor">
                                                <img
                                                    v-if="estudiante.ultimo?.promedio_externo != null"
                                                    src="/images/ExtSi.png"
                                                    alt="Externo OK"
                                                    class="status-icon"
                                                >

                                                <img
                                                    v-else
                                                    src="/images/ExtNo.png"
                                                    alt="Externo pendiente"
                                                    class="status-icon"
                                                >

                                                <span class="status-label">
                                                    Externo
                                                </span>
                                            </div>

                                        </template>

                                    </div>
                                </div>
                            </td>
                            <!-- FUERA DE TIEMPO (Toggle por proyecto) -->
                            <td class="status-cell-f">
                                <div class="status-item-f">
                                    <div class="toggle-container-f">
                                        <label class="toggle-switch-f">
                                            <input 
                                                type="checkbox" 
                                                :checked="proyecto.fuera_de_tiempo === 1 || proyecto.fuera_de_tiempo === true"
                                                @change="toggleFueraTiempo(proyecto, $event)"
                                                :disabled="cambiandoFueraTiempo === proyecto.id"
                                            />
                                            <span class="toggle-slider-f"></span>
                                        </label>
                                        <span class="status-label-f">
                                            {{ (proyecto.fuera_de_tiempo === 1 || proyecto.fuera_de_tiempo === true) ? 'Permitir fuera de tiempo' : 'Permitir solo en tiempo' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Paginación -->
      <!-- Paginación - Siempre visible, pero botones deshabilitados cuando no hay suficientes datos -->
      <div class="pagination-container">
        <div class="pagination">
          <button 
            @click="paginaActual = 1" 
            :disabled="paginaActual === 1 || totalPaginas <= 1"
            class="page-btn"
          >
            <i class="fas fa-angle-double-left"></i>
          </button>
          <button 
            @click="paginaActual--" 
            :disabled="paginaActual === 1 || totalPaginas <= 1"
            class="page-btn"
          >
            <i class="fas fa-angle-left"></i>
          </button>
          
          <span class="page-info">
            <template v-if="totalPaginas > 0">
              Página {{ paginaActual }} de {{ totalPaginas }}
            </template>
            <template v-else>
              No hay registros
            </template>
          </span>
          
          <button 
            @click="paginaActual++" 
            :disabled="paginaActual === totalPaginas || totalPaginas <= 1"
            class="page-btn"
          >
            <i class="fas fa-angle-right"></i>
          </button>
          <button 
            @click="paginaActual = totalPaginas" 
            :disabled="paginaActual === totalPaginas || totalPaginas <= 1"
            class="page-btn"
          >
            <i class="fas fa-angle-double-right"></i>
          </button>
        </div>
        
        <div class="page-size-selector">
          <label>Mostrar:</label>
          <select v-model="registrosPorPagina" @change="paginaActual = 1" :disabled="proyectos.length === 0">
            <option :value="4">4</option>
            <option :value="8">8</option>
            <option :value="12">12</option>
            <option :value="16">16</option>
          </select>
          <span>registros por página</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch} from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const props = defineProps({
    proyectos: Array,
    asesores: Array,
    periodoActual: Object,
    internoConfig: Object,
    documentosProcesados: Object  
})

const getDocumentosEstudiante = (estudianteId) => {
    return props.documentosProcesados[estudianteId] || {
        solicitud: null,
        anteproyecto: null,
        kardex: null,
        seguro: null,
        servicio: null
    }}
//const fueraTiempo = ref(props.fueraTiempoActivo)
// Estado para bloquear mientras guarda
const proyectosConEstado = ref([])

// Inicializar proyectos con el booleano
onMounted(() => {
    if (props.proyectos) {
        proyectosConEstado.value = props.proyectos.map(proyecto => ({
            ...proyecto,
            fuera_de_tiempo: proyecto.fuera_de_tiempo === 1 || 
                             proyecto.fuera_de_tiempo === true || 
                             proyecto.fuera_de_tiempo === 'si'
        }))
    }
})


watch(() => props.proyectos, (nuevosProyectos) => {
    if (nuevosProyectos) {
        proyectosConEstado.value = nuevosProyectos.map(proyecto => ({
            ...proyecto,
            fuera_de_tiempo: proyecto.fuera_de_tiempo === 1 || 
                             proyecto.fuera_de_tiempo === true || 
                             proyecto.fuera_de_tiempo === 'si'
        }))
    }
}, { immediate: true })

const internoActivo = ref(
  props.internoConfig?.valor === 'si'
)


/*
const cambiarFueraTiempo = () => {

    router.post('/configuracion/fuera-tiempo', {
        activo: fueraTiempo.value
    }, {
        preserveScroll: true
    })
}*/
// Estado para el toggle de fuera de tiempo
const cambiandoFueraTiempo = ref(null)

const toggleFueraTiempo = (proyecto, event) => {
    // Obtener el valor del checkbox
    const nuevoValor = event.target.checked
    
    // Bloquear el toggle mientras se procesa
    cambiandoFueraTiempo.value = proyecto.id
    
    // Actualizar visualmente inmediatamente
    proyecto.fuera_de_tiempo = nuevoValor
    
    router.post(route('configuracion.fuera-tiempo'), {
        activo: nuevoValor,
        proyecto_id: proyecto.id
    }, {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Actualizado',
                text: nuevoValor ? 'Proyecto marcado para permitir fuera de tiempo' : 'Proyecto marcado para permitir solo en tiempo',
                confirmButtonText: 'OK'
            })
        },
        onError: (errors) => {
            // Revertir el cambio visual
            proyecto.fuera_de_tiempo = !nuevoValor
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: Object.values(errors)[0] || 'No se pudo actualizar',
                confirmButtonText: 'OK'
            })
        },
        onFinish: () => {
            cambiandoFueraTiempo.value = null
        }
    })
}

const cambiarInterno = () => {
  router.post(route('configuracion.interno'), {
    valor: internoActivo.value ? 'si' : 'no'
  })
}
//Paginacion
const paginaActual = ref(1)
const registrosPorPagina = ref(4)

// Calcular proyectos paginados
const proyectosPaginados = computed(() => {
  const inicio = (paginaActual.value - 1) * registrosPorPagina.value
  const fin = inicio + registrosPorPagina.value
  return props.proyectos.slice(inicio, fin)
})

// Calcular total de páginas
const totalPaginas = computed(() => {
  return Math.ceil(props.proyectos.length / registrosPorPagina.value)
})


// Resetear a página 1 cuando cambian los proyectos (por búsqueda)
watch(() => props.proyectos, () => {
  paginaActual.value = 1
})

// Inicializar asesor_seleccionado
const proyectosConSeleccion = ref([])

onMounted(() => {
  proyectosConSeleccion.value = props.proyectos.map(proyecto => ({
    ...proyecto,
    asesor_seleccionado: proyecto.asesor_id || ''
  }))
})

// Filtros de búsqueda
const filters = reactive({
    buscar: '',
    buscar_proyecto: '',
    buscar_asesor: '',
    buscar_empresa: ''
})

// Sugerencias
const sugerencias = reactive({
    estudiantes: [],
    proyectos: [],
    asesores: [],
    empresas: []
})

let timeoutEstudiante, timeoutProyecto, timeoutAsesor, timeoutEmpresa

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
        onSuccess: () => {}
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

// Ver documento
const verDocumento = (documento) => {
    if (!documento || !documento.subido) {
        Swal.fire({
            icon: 'warning',
            title: 'Documento no disponible',
            text: 'El estudiante aún no ha subido este documento',
            confirmButtonText: 'OK'
        })
        return
    }
    
    // Si tiene URL externa
    if (documento.url_documento) {
        window.open(documento.url_documento, '_blank')
        return
    }
    
    // Si tiene archivo subido
    if (documento.ruta_archivo) {
        window.open(route('documentos.ver', documento.id), '_blank')
    }
}

// Descargar documento
const descargarDocumento = (documento) => {
    if (!documento || !documento.subido) {
        Swal.fire({
            icon: 'warning',
            title: 'Documento no disponible',
            text: 'El estudiante aún no ha subido este documento',
            confirmButtonText: 'OK'
        })
        return
    }
    
    if (documento.ruta_archivo) {
        window.open(route('documentos.download', documento.id), '_blank')
    } else if (documento.url_documento) {
        window.open(documento.url_documento, '_blank')
    }
}
</script>

<script>
export default {
  layout: AppLayout
}
</script>

<style scoped>
/* ===== VARIABLES ===== */
:root {
  --primary-dark: #050E3C;
  --primary-medium: #002455;
  --primary-light: #1a3a6e;
  --danger: #DC0000;
  --danger-light: #FF3838;
  --success: #28a745;
  --warning: #ffc107;
  --gray-100: #f8f9fa;
  --gray-200: #e9ecef;
  --gray-300: #dee2e6;
  --gray-400: #ced4da;
  --gray-500: #adb5bd;
  --gray-600: #6c757d;
  --gray-700: #495057;
  --gray-800: #343a40;
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Estilos para el toggle switch */
/* Asegurar que cada celda tenga posición relativa */
.status-cell-f {
    position: relative;
    vertical-align: middle;
    text-align: center;
}

.status-item-f {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60px;
}

.toggle-container-f {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* Toggle switch */
.toggle-switch-f {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch-f input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider-f {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #28a745;
    transition: 0.3s;
    border-radius: 24px;
}

.toggle-slider-f:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-slider-f {
    background-color: #d9534f;
}

input:checked + .toggle-slider-f:before {
    transform: translateX(26px);
}

input:disabled + .toggle-slider-f {
    opacity: 0.6;
    cursor: not-allowed;
}

.status-label-f {
    font-size: 0.7rem;
    color: #6c757d;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #28a745;
    transition: 0.3s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #d9534f;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

input:disabled + .toggle-slider {
    opacity: 0.6;
    cursor: not-allowed;
}

.status-label {
    font-size: 0.7rem;
    color: #6c757d;
}

.title-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
}

.outside-time-toggle{
    display:flex;
    align-items:center;
    gap:12px;
    background:#f8fafc;
    padding:10px 16px;
    border-radius:14px;
    border:1px solid #e2e8f0;
}

.toggle-label{
    font-weight:600;
    color:#334155;
}

.toggle-status{
    font-weight:bold;
    color:#ef4444;
    min-width:25px;
}

.toggle-status.active{
    color:#22c55e;
}

.switch{
    position:relative;
    display:inline-block;
    width:54px;
    height:28px;
}

.switch input{
    opacity:0;
    width:0;
    height:0;
}

.slider{
    position:absolute;
    cursor:pointer;
    inset:0;
    background:#cbd5e1;
    transition:.3s;
    border-radius:30px;
}

.slider:before{
    position:absolute;
    content:"";
    height:22px;
    width:22px;
    left:3px;
    bottom:3px;
    background:white;
    transition:.3s;
    border-radius:50%;
}

.switch input:checked + .slider{
    background:#22c55e;
}

.switch input:checked + .slider:before{
    transform:translateX(26px);
}

/* ===== PAGINACIÓN ===== */
.pagination-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1.5rem;
  padding: 1rem;
  background: white;
  border-radius: 12px;
  flex-wrap: wrap;
  gap: 1rem;
}

.pagination {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.page-btn {
  background: var(--gray-200);
  border: none;
  padding: 0.5rem 0.75rem;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
  color: var(--gray-700);
}

.page-btn:hover:not(:disabled) {
  background: var(--primary-medium);
  color: white;
}

.page-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.page-info {
  font-weight: 500;
  color: var(--gray-700);
  margin: 0 0.5rem;
}

.page-size-selector {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.page-size-selector label {
  font-size: 0.85rem;
  color: var(--gray-600);
}

.page-size-selector select {
  padding: 0.4rem 0.75rem;
  border: 1px solid var(--gray-300);
  border-radius: 8px;
  background: white;
  cursor: pointer;
}

.page-size-selector span {
  font-size: 0.85rem;
  color: var(--gray-600);
}


.page-wrapper {
  background: linear-gradient(135deg, #f5f7fa 0%, #e9edf5 100%);
  width: 100%;
  height: 100%;  
  padding: 0;
}

.page-content {
  
  width: 100%;
  box-sizing: border-box;
}
/* ===== SEARCH SECTION ===== */
.search-section {
  margin-bottom: 2rem;
}

.search-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}

.search-card {
  background: white;
  border-radius: 16px;
  padding: 1.25rem; 
  box-shadow: var(--shadow-md);
  position: relative;
  /*transition: transform 0.2s ease, box-shadow 0.2s ease;*/
}

.search-card:hover {
  /*transform: translateY(-2px);*/
  box-shadow: var(--gray-800);
}

.search-card-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
}

.search-card-header i {
  font-size: 1.2rem;
  color: var(--primary-medium);
}

.search-card-header label {
  font-weight: 600;
  color: var(--primary-dark);
  font-size: 0.9rem;
}

.search-input-wrapper {
  display: flex;
  gap: 0.5rem;
}

.search-input {
  flex: 1;
  padding: 0.6rem 1rem;
  border: 2px solid var(--gray-300);
  border-radius: 10px;
  font-size: 0.9rem;
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: var(--primary-medium);
  box-shadow: 0 0 0 3px rgba(0, 36, 85, 0.1);
}

.search-btn {
  background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
  color: white;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 10px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.search-btn:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.suggestions-box {
  position: absolute;
  z-index: 100;
  background: white;
  border: 1px solid var(--gray-300);
  border-radius: 10px;
  max-height: 200px;
  overflow-y: auto;
  width: calc(100% - 80px);
  margin-top: 0.25rem;
  box-shadow: var(--shadow-md);
  width: 100%;
 
}

.suggestion-item {
  padding: 0.6rem 1rem;
  cursor: pointer;
  transition: background 0.2s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.suggestion-item i {
  color: var(--primary-medium);
  font-size: 0.8rem;
}

.suggestion-item:hover {
  background: var(--gray-100);
}

/* ===== TITLE SECTION ===== */
.section-title {
  margin-bottom: 1.5rem;
}

.title-content {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.title-content i {
  font-size: 1.5rem;
  color: var(--primary-medium);
}

.title-content h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: var(--primary-dark);
  margin: 0;
}

.periodo-badge {
  background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.8rem;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}

.title-underline {
  height: 3px;
  background: linear-gradient(90deg, var(--primary-medium), var(--danger-light));
  width: 80px;
  margin-top: 0.5rem;
  border-radius: 2px;
}

/* ===== TABLE ===== */
.table-container {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-lg);
  width: 100%;
}

.table-responsive {
  max-width: 100%;
  overflow-x: auto;
  overflow-y: visible;
  
}

.modern-table {
 min-width: 1000px;
  border-collapse: collapse;
  /* ← Ancho mínimo para que la tabla sea scrollable */
  
}

.table-responsive {
  border: 1px solid red; /* ← Temporal para ver si el contenedor existe */
}
.modern-table thead th {
  background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
  color: white;
  padding: 1rem;
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.modern-table thead th i {
  margin-right: 0.5rem;
  font-size: 0.9rem;
}

.modern-table tbody td {
  padding: 1rem;
  border-bottom: 1px solid var(--gray-200);
  vertical-align: top;
}

.table-row:hover {
  background: var(--gray-100);
}

/* Project name */
.project-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  color: var(--primary-dark);
}

.project-title i {
  color: var(--primary-medium);
}

/* Asesor cell */
.advisor-form {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
  flex-wrap: wrap;
}

.select-wrapper {
  position: relative;
  flex: 1;
}

.form-select {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 2px solid var(--gray-300);
  border-radius: 8px;
  background: white;
  font-size: 0.85rem;
  appearance: none;
  cursor: pointer;
}

.form-select:focus {
  outline: none;
  border-color: var(--primary-medium);
}

.select-arrow {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: var(--gray-500);
  pointer-events: none;
  font-size: 0.8rem;
}

.btn-assign {
  background: var(--primary-medium);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.btn-assign:hover {
  background: var(--primary-dark);
  transform: translateY(-2px);
}

.btn-assign.btn-change {
  background: var(--warning);
  color: var(--gray-800);
}

.email-section {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.5rem;
  padding-top: 0.5rem;
  border-top: 1px solid var(--gray-200);
}

.advisor-name {
  font-size: 0.85rem;
  color: var(--success);
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

/* Company cell */
.company-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
}

.company-info i {
  color: var(--primary-medium);
}

.company-name {
  font-weight: 500;
  color: var(--gray-700);
}

.externo-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.8rem;
  flex-wrap: wrap;
}

.externo-info i {
  color: var(--success);
}

/* Students cell */


.student-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  background: var(--gray-100);
  border-radius: 8px;
  gap: 0.5rem;
}

.student-item:last-child {
  margin-bottom: 0;
}

.student-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.8rem;
  flex-wrap: wrap;
}

.student-info i {
  color: var(--primary-medium);
}

/* Status cell */


.status-item {
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  background: var(--gray-100);
  border-radius: 8px;
  text-align: center;
}

.status-item:last-child {
  margin-bottom: 0;
}

.status-icons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.status-asesor {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
}

.status-icon {
  width: 35px;
  height: auto;
}

.status-label {
  font-size: 0.65rem;
  color: var(--gray-600);
}

.btn-download {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
}

.btn-download:hover {
  transform: scale(1.05);
}

.btn-email {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  transition: transform 0.2s ease;
}

.btn-email:hover {
  transform: scale(1.1);
}

.email-icon {
  width: 32px;
  height: auto;
}

.email-icon.small {
  width: 24px;
}

.no-data, .no-data-small {
  color: var(--danger);
  font-size: 0.75rem;
  margin-top: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.no-data-small {
  font-size: 0.7rem;
  color: var(--gray-500);
}

/* Responsive */
@media (max-width: 768px) {
  .page-wrapper {
    padding: 1rem;
  }
  
  .pagination-container {
    flex-direction: column;
    justify-content: center;
  }
  
  .search-grid {
    grid-template-columns: 1fr;
  }
  
  .title-content h2 {
    font-size: 1.2rem;
  }
  
  .advisor-form {
    flex-direction: column;
  }
  
  .btn-assign {
    width: 100%;
    justify-content: center;
  }
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>



