<template>
  <div class="page-wrapper">
    <!-- Selector de periodo -->
    <div class="filtro-container">
      <div class="filtro-card">
        <div class="filtro-header">
          <i class="fas fa-calendar-alt"></i>
          <h3>Histórico de Proyectos</h3>
          <span class="rol-badge">
            <i class="fas fa-user-tie"></i> Coordinador
          </span>
          <span v-if="carreraActual" class="carrera-badge">
            <i class="fas fa-graduation-cap"></i> {{ carreraActual.nombre }}
          </span>
        </div>
        
        <div class="filtro-control">
          <label for="periodo">Seleccionar Periodo:</label>
          <select id="periodo" v-model="periodoSeleccionado" @change="cambiarPeriodo" class="periodo-select">
            <option :value="null">Todos los periodos</option>
            <option v-for="periodo in periodos" :key="periodo.id" :value="periodo.id">
              {{ periodo.nombre }}
            </option>
          </select>
          <span class="info-badge">
            <i class="fas fa-info-circle"></i> Modo solo lectura
          </span>
        </div>
      </div>
    </div>

    <!-- Tabla de proyectos -->
    <div class="table-container">
      <div class="table-responsive">
        <table class="proyectos-table">
          <thead>
            <tr>
              <th class="thfondo">Periodo</th>
              <th class="thfondo">Nombre proyecto</th>
              <th class="thfondo">Asesor interno</th>
              <th class="thfondo">Empresa / Asesor externo</th>
              <th class="thfondo">Estudiante(s)</th>
              <th class="thfondo">Seguimiento 1</th>
              <th class="thfondo">Seguimiento 2</th>
              <th class="thfondo">Seguimiento Final</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="proyecto in proyectos" :key="proyecto.id">
              <!-- Periodo -->
              <td class="periodo-cell">
                <span class="periodo-badge" :class="{ 'periodo-actual': proyecto.periodo_id === periodoActual?.id }">
                  {{ proyecto.periodo?.nombre || 'Sin periodo' }}
                </span>
              </td>

              <!-- Nombre proyecto -->
              <td class="proyecto-cell">
                {{ proyecto.nombre }}
                <div class="proyecto-detalles">
                  <small><i class="fas fa-map-marker-alt"></i> {{ proyecto.lugar || 'Sin lugar' }}</small>
                </div>
              </td>

              <!-- Asesor interno -->
              <td class="asesor-cell">
                <div v-if="proyecto.asesor" class="asesor-info">
                  <i class="fas fa-chalkboard-teacher"></i>
                  {{ proyecto.asesor.nombre }} {{ proyecto.asesor.apellido_paterno || '' }}
                </div>
                <div v-else class="no-data">
                  <i class="fas fa-user-slash"></i> Sin asesor
                </div>
              </td>

              <!-- Empresa / Asesor externo -->
              <td class="empresa-cell">
                <div class="empresa-info">
                  <i class="fas fa-building"></i>
                  {{ proyecto.empresa?.nombre || 'Sin empresa' }}
                </div>
                <div v-if="proyecto.externo" class="externo-info">
                  <small><i class="fas fa-user-tie"></i> Asesor Ext: {{ proyecto.externo.nombre }}</small>
                </div>
                <div v-else class="no-data-small">
                  <small>Sin asesor externo</small>
                </div>
              </td>

              <!-- Estudiantes -->
              <td class="estudiantes-cell">
                <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="estudiante-card">
                  <div class="estudiante-info">
                    <i class="fas fa-user-graduate"></i>
                    {{ estudiante.nombre }} {{ estudiante.apellido_paterno }} {{ estudiante.apellido_materno }}
                  </div>
                  <div class="estudiante-detalles">
                    <small><i class="fas fa-id-card"></i> Control: {{ estudiante.numero_de_control || 'N/A' }}</small>
                  </div>
                </div>
                <div v-if="!proyecto.estudiantes?.length" class="no-data-small">
                  Sin estudiantes asignados
                </div>
              </td>

              <!-- Primer Seguimiento -->
              <td class="seguimiento-cell readonly">
                <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="seguimiento-card-readonly">
                  <div class="estado-icons">
                    <template v-if="estudiante.primer?.puntualidad_interno && estudiante.primer?.puntualidad_externo">
                      <div class="completado-badge">
                        <i class="fas fa-check-circle"></i>
                        <span>Completado</span>
                      </div>
                      <a :href="route('estudiante.impresiones.seguimientos.primer', estudiante.id)" 
                         class="download-link" 
                         title="Descargar 1° seguimiento"
                         target="_blank">
                        <img src="/images/UnoSegui.png" width="40" height="65" alt="Descargar">
                      </a>
                    </template>
                    <template v-else>
                      <div class="estado-item-readonly">
                        <div class="estado-asesor">
                          <img v-if="estudiante.primer?.puntualidad_interno" 
                               src="/images/IntSi.png" 
                               width="40" height="45" 
                               title="Asesor Interno calificó"
                               class="status-img">
                          <span v-else class="estado-pendiente">
                            <i class="fas fa-hourglass-half"></i> Int pendiente
                          </span>
                        </div>
                        <div class="estado-asesor">
                          <img v-if="estudiante.primer?.puntualidad_externo" 
                               src="/images/ExtSi.png" 
                               width="40" height="45" 
                               title="Asesor Externo calificó"
                               class="status-img">
                          <span v-else class="estado-pendiente">
                            <i class="fas fa-hourglass-half"></i> Ext pendiente
                          </span>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>
                <div v-if="!proyecto.estudiantes?.length" class="no-data-small">
                  Sin datos
                </div>
              </td>

              <!-- Segundo Seguimiento -->
              <td class="seguimiento-cell readonly">
                <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="seguimiento-card-readonly">
                  <div class="estado-icons">
                    <template v-if="estudiante.segundo?.puntualidad_interno && estudiante.segundo?.puntualidad_externo">
                      <div class="completado-badge">
                        <i class="fas fa-check-circle"></i>
                        <span>Completado</span>
                      </div>
                      <a :href="route('estudiante.impresiones.seguimientos.segundo', estudiante.id)" 
                         class="download-link" 
                         title="Descargar 2° seguimiento"
                         target="_blank">
                        <img src="/images/DosSegui.png" width="40" height="65" alt="Descargar">
                      </a>
                    </template>
                    <template v-else>
                      <div class="estado-item-readonly">
                        <div class="estado-asesor">
                          <img v-if="estudiante.segundo?.puntualidad_interno" 
                               src="/images/IntSi.png" 
                               width="40" height="45" 
                               class="status-img">
                          <span v-else class="estado-pendiente">
                            <i class="fas fa-hourglass-half"></i> Int pendiente
                          </span>
                        </div>
                        <div class="estado-asesor">
                          <img v-if="estudiante.segundo?.puntualidad_externo" 
                               src="/images/ExtSi.png" 
                               width="40" height="45" 
                               class="status-img">
                          <span v-else class="estado-pendiente">
                            <i class="fas fa-hourglass-half"></i> Ext pendiente
                          </span>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>
                <div v-if="!proyecto.estudiantes?.length" class="no-data-small">
                  Sin datos
                </div>
             </td>

              <!-- Tercer Seguimiento -->
              <td class="seguimiento-cell readonly">
                <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="seguimiento-card-readonly">
                  <div class="estado-icons">
                    <template v-if="estudiante.ultimo?.promedio_interno && estudiante.ultimo?.promedio_externo">
                      <div class="completado-badge">
                        <i class="fas fa-check-circle"></i>
                        <span>Completado</span>
                      </div>
                      <a :href="route('estudiante.impresiones.seguimientos.ultimo', estudiante.id)" 
                         class="download-link" 
                         title="Descargar 3° seguimiento"
                         target="_blank">
                        <img src="/images/TresSegui.png" width="40" height="65" alt="Descargar">
                      </a>
                    </template>
                    <template v-else>
                      <div class="estado-item-readonly">
                        <div class="estado-asesor">
                          <img v-if="estudiante.ultimo?.promedio_interno" 
                               src="/images/IntSi.png" 
                               width="40" height="45" 
                               class="status-img">
                          <span v-else class="estado-pendiente">
                            <i class="fas fa-hourglass-half"></i> Int pendiente
                          </span>
                        </div>
                        <div class="estado-asesor">
                          <img v-if="estudiante.ultimo?.promedio_externo" 
                               src="/images/ExtSi.png" 
                               width="40" height="45" 
                               class="status-img">
                          <span v-else class="estado-pendiente">
                            <i class="fas fa-hourglass-half"></i> Ext pendiente
                          </span>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>
                <div v-if="!proyecto.estudiantes?.length" class="no-data-small">
                  Sin datos
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Mensaje si no hay proyectos -->
      <div v-if="proyectos.length === 0" class="empty-state">
        <i class="fas fa-folder-open"></i>
        <p>No hay proyectos en el periodo seleccionado</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const props = defineProps({
  proyectos: Array,
  periodos: Array,
  periodoSeleccionado: Number,
  periodoActual: Object,
  carreraActual: Object
})

const periodoSeleccionado = ref(props.periodoSeleccionado || null)

const cambiarPeriodo = () => {
  const params = periodoSeleccionado.value 
    ? { periodo_id: periodoSeleccionado.value }
    : {}
  
  router.get(route('coordinadores.historico'), params, {
    preserveState: true,
    preserveScroll: true
  })
}
</script>

<script>
export default {
  layout: AppLayout
}
</script>

<style scoped>
.page-wrapper {
  background: linear-gradient(135deg, #f5f7fa 0%, #e9edf5 100%);
 
}

/* Filtro */
.filtro-container {
  margin-bottom: 2rem;
}

.filtro-card {
  background: white;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.filtro-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #e5e7eb;
  flex-wrap: wrap;
}

.filtro-header i {
  font-size: 1.5rem;
  color: rgb(40, 95, 139);
}

.filtro-header h3 {
  font-size: 1.3rem;
  color: rgb(19, 46, 68);
  margin: 0;
}

.rol-badge {
  background: linear-gradient(135deg, #002455, #050E3C);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
}

.carrera-badge {
  background: #e8f4fd;
  color: #002455;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  margin-left: auto;
}

.filtro-control {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
}

.filtro-control label {
  font-weight: 600;
  color: rgb(19, 46, 68);
}

.periodo-select {
  padding: 0.5rem 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 0.9rem;
  background: white;
  cursor: pointer;
  
}

.periodo-select:focus {
  outline: none;
  border-color: rgb(40, 95, 139);
}

.info-badge {
  background: #e8f4fd;
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-size: 0.8rem;
  color: rgb(40, 95, 139);
}

.info-badge i {
  margin-right: 0.25rem;
}

/* Tabla */
.table-container {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.table-responsive {
  overflow-x: auto;
}

.proyectos-table {
  width: 100%;
  border-collapse: collapse;
 
}

.thfondo {
  background-color: rgb(204, 216, 228);
  font-weight: bold;
  padding: 12px;
  border: 1px solid rgb(40, 95, 139);
  text-align: center;
}

.proyectos-table td {
  border: 1px solid rgb(40, 95, 139);
  padding: 8px;
  vertical-align: top;
}

/* Periodo cell */
.periodo-cell {
  text-align: center;
  vertical-align: middle !important;
}

.periodo-badge {
  display: inline-block;
  background: #e8f4fd;
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  color: rgb(19, 46, 68);
}

.periodo-badge.periodo-actual {
  background: #d4edda;
  color: #155724;
  border: 1px solid #28a745;
}

/* Proyecto cell */


.proyecto-detalles {
  margin-top: 0.5rem;
  padding-top: 0.5rem;
  border-top: 1px dashed #e5e7eb;
  font-size: 0.7rem;
  color: #6c757d;
}

/* Asesor cell */

.asesor-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.asesor-info i {
  color: rgb(40, 95, 139);
}

/* Empresa cell */

.empresa-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.empresa-info i {
  color: rgb(19, 46, 68);
}

.externo-info {
  margin-top: 0.5rem;
  padding-top: 0.5rem;
  border-top: 1px dashed #e5e7eb;
}

/* Estudiantes cell */


.estudiante-card {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  background: #f9fafb;
}

.estudiante-card:last-child {
  margin-bottom: 0;
}

.estudiante-info {
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
}

.estudiante-info i {
  color: rgb(40, 95, 139);
}

.estudiante-detalles {
  font-size: 0.7rem;
  color: #6c757d;
  margin-top: 0.25rem;
}

/* Seguimiento readonly */


.seguimiento-card-readonly {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  text-align: center;
  background: white;
}

.seguimiento-card-readonly:last-child {
  margin-bottom: 0;
}

.estado-item-readonly {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.estado-asesor {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
}

.estado-pendiente {
  font-size: 0.6rem;
  color: #856404;
  background: #fff3cd;
  padding: 0.15rem 0.3rem;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  gap: 0.2rem;
}

.completado-badge {
  background: #d4edda;
  color: #155724;
  padding: 0.2rem 0.4rem;
  border-radius: 12px;
  font-size: 0.65rem;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  margin-bottom: 0.25rem;
}

.status-img {
  max-width: 35px;
  height: auto;
}

.download-link {
  display: inline-block;
  transition: transform 0.2s ease;
}

.download-link:hover {
  transform: scale(1.1);
}

.no-data {
  color: #ef4444;
  font-size: 0.7rem;
  text-align: center;
}

.no-data-small {
  color: #6c757d;
  font-size: 0.65rem;
  text-align: center;
  padding: 0.25rem;
}

/* Empty state */
.empty-state {
  text-align: center;
  padding: 3rem;
  color: #6c757d;
}

.empty-state i {
  font-size: 3rem;
  margin-bottom: 0.5rem;
  color: #adb5bd;
}

/* Responsive */
@media (max-width: 768px) {
  .page-wrapper {
    padding: 1rem;
  }
  
  .filtro-control {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .periodo-select {
    width: 100%;
  }
  
  .carrera-badge {
    margin-left: 0;
  }
  
  .proyectos-table {
    font-size: 11px;
  }
  
  .thfondo {
    padding: 6px;
  }
  
  .proyectos-table td {
    padding: 6px;
  }
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>