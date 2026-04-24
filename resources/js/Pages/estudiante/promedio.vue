<template>
  <div class="page-wrapper">
    <main class="main-content">
      <div style="margin-top:20px;">
        <div class="horizontal">
          <p class="subtitulo">Verifica aquí tus seguimientos</p>
        </div>
        
        <!-- Contenedor de seguimientos en fila -->
        <div class="seguimientos-fila">
          <!-- Primer Seguimiento -->
          <div class="seguimiento-card">
            <div class="card-header">
              <i class="fas fa-file-alt"></i>
              <h3 class="card-titulo">Seguimiento 1</h3>
            </div>
            
            <div v-if="!primer" class="seguimiento-vacio">
              <i class="fas fa-hourglass-half"></i>
              <p>No hay primer seguimiento</p>
            </div>
            <div v-else class="seguimiento-contenido">
              <div class="estado-asesores">
                <!-- Asesor Interno -->
                <div class="estado-item">
                  <span class="estado-label">Asesor Interno</span>
                  <img 
                    v-if="primer.promedio_interno != null"
                    src="/images/IntSi.png" 
                    alt="Calificado" 
                    class="estado-icon"
                    title="Asesor Interno ya calificó"
                  >
                  <img 
                    v-else 
                    src="/images/IntNo.png" 
                    alt="Pendiente" 
                    class="estado-icon"
                    title="Asesor Interno no ha calificado"
                  >
                  <span v-if="primer.promedio_interno != null" class="promedio-nota">
                    {{ primer.promedio_interno }}
                  </span>
                </div>
                <!-- Asesor Externo -->
                <div class="estado-item">
                  <span class="estado-label">Asesor Externo</span>
                  <img 
                    v-if="primer.promedio_externo != null"
                    src="/images/ExtSi.png" 
                    alt="Calificado" 
                    class="estado-icon"
                    title="Asesor Externo ya calificó"
                  >
                  <img 
                    v-else 
                    src="/images/ExtNo.png" 
                    alt="Pendiente" 
                    class="estado-icon"
                    title="Asesor Externo no ha calificado"
                  >
                  <span v-if="primer.promedio_externo != null" class="promedio-nota">
                    {{ primer.promedio_externo }}
                  </span>
                </div>
              </div>
              <!-- Botón descargar (solo si ambos calificaron) -->
              <div v-if="primer.promedio_interno != null && primer.promedio_externo != null" class="descargar-wrapper">
                <a 
                  :href="route('estudiante.impresiones.seguimientos.primer')" 
                  class="boton-descargar"
                >
                  <i class="fas fa-download"></i>
                  Descargar
              </a>
              </div>
              <div v-else class="pendiente-texto">
                <i class="fas fa-clock"></i>
                <span>Esperando calificaciones</span>
              </div>
            </div>
          </div>

          <!-- Segundo Seguimiento -->
          <div class="seguimiento-card">
            <div class="card-header">
              <i class="fas fa-file-alt"></i>
              <h3 class="card-titulo">Seguimiento 2</h3>
            </div>
            
            <div v-if="!segundo" class="seguimiento-vacio">
              <i class="fas fa-hourglass-half"></i>
              <p>No hay segundo seguimiento</p>
            </div>
            <div v-else class="seguimiento-contenido">
              <div class="estado-asesores">
                <div class="estado-item">
                  <span class="estado-label">Asesor Interno</span>
                  <img 
                    v-if="segundo.promedio_interno != null"
                    src="/images/IntSi.png" 
                    alt="Calificado" 
                    class="estado-icon"
                  >
                  <img 
                    v-else 
                    src="/images/IntNo.png" 
                    alt="Pendiente" 
                    class="estado-icon"
                  >
                  <span v-if="segundo.promedio_interno != null" class="promedio-nota">
                    {{ segundo.promedio_interno }}
                  </span>
                </div>
                <div class="estado-item">
                  <span class="estado-label">Asesor Externo</span>
                  <img 
                    v-if="segundo.promedio_externo != null"
                    src="/images/ExtSi.png" 
                    alt="Calificado" 
                    class="estado-icon"
                  >
                  <img 
                    v-else 
                    src="/images/ExtNo.png" 
                    alt="Pendiente" 
                    class="estado-icon"
                  >
                  <span v-if="segundo.promedio_externo != null" class="promedio-nota">
                    {{ segundo.promedio_externo }}
                  </span>
                </div>
              </div>
              <div v-if="segundo.promedio_interno != null && segundo.promedio_externo != null" class="descargar-wrapper">
                <a 
                  :href="route('estudiante.impresiones.seguimientos.segundo')" 
                  class="boton-descargar"
                >
                  <i class="fas fa-download"></i>
                  Descargar
              </a>
              </div>
              <div v-else class="pendiente-texto">
                <i class="fas fa-clock"></i>
                <span>Esperando calificaciones</span>
              </div>
            </div>
          </div>

          <!-- Tercer Seguimiento (Final) -->
          <div class="seguimiento-card">
            <div class="card-header">
              <i class="fas fa-file-alt"></i>
              <h3 class="card-titulo">Seguimiento Final</h3>
            </div>
            
            <div v-if="!ultimo" class="seguimiento-vacio">
              <i class="fas fa-hourglass-half"></i>
              <p>No hay seguimiento final</p>
            </div>
            <div v-else class="seguimiento-contenido">
              <div class="estado-asesores">
                <div class="estado-item">
                  <span class="estado-label">Asesor Interno</span>
                  <img 
                    v-if="ultimo.promedio_interno != null"
                    src="/images/IntSi.png" 
                    alt="Calificado" 
                    class="estado-icon"
                  >
                  <img 
                    v-else 
                    src="/images/IntNo.png" 
                    alt="Pendiente" 
                    class="estado-icon"
                  >
                  <span v-if="ultimo.promedio_interno != null" class="promedio-nota">
                    {{ ultimo.promedio_interno }}
                  </span>
                </div>
                <div class="estado-item">
                  <span class="estado-label">Asesor Externo</span>
                  <img 
                    v-if="ultimo.promedio_externo != null"
                    src="/images/ExtSi.png" 
                    alt="Calificado" 
                    class="estado-icon"
                  >
                  <img 
                    v-else 
                    src="/images/ExtNo.png" 
                    alt="Pendiente" 
                    class="estado-icon"
                  >
                  <span v-if="ultimo.promedio_externo != null" class="promedio-nota">
                    {{ ultimo.promedio_externo }}
                  </span>
                </div>
              </div>
              <div v-if="ultimo.promedio_interno != null && ultimo.promedio_externo != null" class="descargar-wrapper">
                <a :href="route('estudiante.impresiones.seguimientos.ultimo')" 
                  class="boton-descargar"
                >
                  <i class="fas fa-download"></i>
                  Descargar
              </a>
              </div>
              <div v-else class="pendiente-texto">
                <i class="fas fa-clock"></i>
                <span>Esperando calificaciones</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de correos (se mantiene igual) -->
        <table class="correos-table">
          <thead>
            <tr>
              <th colspan="3">Redactar un correo para tu:</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <!-- Coordinador -->
              <td>
                Coordinador
                <div v-if="coordinadorId" class="email-action">
                  <form @submit.prevent="enviarCorreo('coordinador', coordinadorId)">
                    <button type="submit" title="Redactar un correo al coordinador" class="email-button">
                      <img src="/images/mail.png" width="50" height="32" alt="Email">
                    </button>
                  </form>
                </div>
                <p v-else class="no-data">No hay coordinador asignado</p>
              </td>

              <!-- Asesor Interno -->
              <td>
                Asesor Interno
                <div v-if="proyecto.asesor?.id" class="email-action">
                  <form @submit.prevent="enviarCorreo('asesor', proyecto.asesor.id)">
                    <button type="submit" title="Redactar un correo al asesor interno" class="email-button">
                      <img src="/images/mail.png" width="50" height="32" alt="Email">
                    </button>
                  </form>
                </div>
                <p v-else class="no-data">No hay asesor interno asignado</p>
              </td>

              <!-- Asesor Externo -->
              <td>
                Asesor Externo
                <div v-if="proyecto.externo?.id" class="email-action">
                  <form @submit.prevent="enviarCorreo('externo', proyecto.externo.id)">
                    <button type="submit" title="Redactar un correo al asesor externo" class="email-button">
                      <img src="/images/mail.png" width="50" height="32" alt="Email">
                    </button>
                  </form>
                </div>
                <p v-else class="no-data">No hay asesor externo asignado</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>



<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const props = defineProps({
  proyecto: Object,
  primer: Object,
  segundo: Object,
  ultimo: Object
})

// Computed para obtener el coordinador
const coordinadorId = computed(() => {
  const estudiante = props.proyecto?.estudiantes?.[0]
  const carrera = estudiante?.carrera
  const coordinador = carrera?.coordinador
  return coordinador?.id || null
})

const enviarCorreo = (tipo, id) => {
  window.open(route('correo.create', { type: tipo, id: id }), '_blank')
}
</script>

<script>
// Esta es la forma de asignar layout en Vue 3 con Options API
export default {
  layout: AppLayout
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.page-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #e9edf5 100%);
}

/* Header */
.page-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.hderecho {
  display: flex;
  justify-content: right;
}

.cmenor {
  background-color: rgb(40, 95, 139);
  height: 100%;
}

.cmayor {
  background-color: rgb(19, 46, 68);
  height: 100%;
}

.horizontal {
  display: flex;
  justify-content: center;
  width: 100%;
  padding: 0.5rem 0;
  background: white;
}

.linea {
  background-color: rgb(10, 105, 163);
  height: 4px;
  border-radius: 2px;
  width: 95%;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background: linear-gradient(90deg, rgb(19, 46, 68) 0%, rgb(40, 95, 139) 100%);
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.header-logo {
  height: 45px;
  width: auto;
  filter: brightness(0) invert(1);
}

.header-title {
  color: white;
  font-size: 1.3rem;
  font-weight: 600;
  margin: 0;
}

.header-right {
  display: flex;
  gap: 1rem;
}

.header-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.2rem;
  border-radius: 50px;
  font-weight: 500;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  border: none;
}

.home-link {
  background: rgba(255, 255, 255, 0.15);
  color: white;
}

.home-link:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-2px);
}

.logout-link {
  background: rgba(239, 68, 68, 0.2);
  color: white;
  border: 1px solid rgba(239, 68, 68, 0.5);
}

.logout-link:hover {
  background: rgba(239, 68, 68, 0.3);
  transform: translateY(-2px);
}

/* Contenido principal */
.main-content {
  padding: 2rem;
  min-height: calc(100vh - 140px);
}

.subtitulo {
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  color: rgb(19, 46, 68);
  margin-bottom: 1rem;
}

.tercio {
  border: 2px solid rgb(19, 46, 68);
  width: 30%;
  padding: 20px;
  border-radius: 10px;
  margin: 15px;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  background: white;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.boton {
  background: linear-gradient(135deg, rgb(19, 46, 68), rgb(40, 95, 139));
  color: white;
  border: none;
  padding: 0.8rem 1.5rem;
  border-radius: 50px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
}

.boton:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(19, 46, 68, 0.3);
}
.seguimientos-fila {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin: 2rem 0;
  flex-wrap: wrap;
}

/* Tarjeta de seguimiento */
.seguimiento-card {
  flex: 1;
  min-width: 280px;
  max-width: 350px;
  background: white;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.seguimiento-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

/* Cabecera de la tarjeta */
.card-header {
  background: linear-gradient(135deg, rgb(19, 46, 68), rgb(40, 95, 139));
  color: white;
  padding: 1rem;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
}

.card-header i {
  font-size: 1.5rem;
}

.card-titulo {
  font-size: 1.2rem;
  font-weight: 600;
  margin: 0;
}

/* Contenido */
.seguimiento-contenido {
  padding: 1.5rem;
}

.seguimiento-vacio {
  padding: 2rem;
  text-align: center;
  color: #6c757d;
}

.seguimiento-vacio i {
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
  color: #adb5bd;
}

.seguimiento-vacio p {
  margin: 0;
  font-size: 0.9rem;
}

/* Estados de asesores */
.estado-asesores {
  display: flex;
  justify-content: space-around;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.estado-item {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  text-align: center;
}

.estado-label {
  font-size: 0.75rem;
  font-weight: 600;
  color: #4b5563;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.estado-icon {
  width: 45px;
  height: 45px;
  object-fit: contain;
}

.promedio-nota {
  font-size: 0.85rem;
  font-weight: 700;
  color: #28a745;
  background: #d4edda;
  padding: 0.2rem 0.6rem;
  border-radius: 20px;
  margin-top: 0.25rem;
}

/* Botón descargar */
.descargar-wrapper {
  margin-top: 1rem;
  text-align: center;
}

.boton-descargar {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
  padding: 0.6rem 1.2rem;
  border-radius: 50px;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.boton-descargar:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Texto pendiente */
.pendiente-texto {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 1rem;
  padding: 0.6rem;
  background: #fff3cd;
  border-radius: 8px;
  color: #856404;
  font-size: 0.8rem;
}

.pendiente-texto i {
  font-size: 1rem;
}

/* Tabla de correos (estilos existentes) */
.correos-table {
  border-collapse: separate;
  border-spacing: 0;
  width: 70%;
  margin: 40px auto;
  border: 2px solid rgb(40, 95, 139);
  border-radius: 12px;
  overflow: hidden;
  background: white;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.correos-table thead th {
  background-color: rgb(40, 95, 139);
  color: white;
  padding: 15px;
  text-align: center;
  font-size: 1.2rem;
}

.correos-table tbody td {
  border-top: 2px solid rgb(40, 95, 139);
  border-right: 2px solid rgb(40, 95, 139);
  padding: 15px;
  text-align: center;
  font-size: 1rem;
  vertical-align: middle;
}

.correos-table tbody td:last-child {
  border-right: none;
}

.email-action {
  margin-top: 10px;
  display: flex;
  justify-content: center;
}

.email-button {
  background: none;
  border: none;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.email-button:hover {
  transform: scale(1.1);
}

.no-data {
  color: #ef4444;
  font-size: 0.85rem;
  margin-top: 8px;
}

/* Responsive */
@media (max-width: 1024px) {
  .seguimientos-fila {
    gap: 1rem;
  }
  
  .seguimiento-card {
    min-width: 250px;
  }
}

@media (max-width: 768px) {
  .seguimientos-fila {
    flex-direction: column;
    align-items: center;
  }
  
  .seguimiento-card {
    width: 100%;
    max-width: 100%;
  }
  
  .correos-table {
    width: 95%;
    font-size: 0.85rem;
  }
  
  .correos-table thead th {
    font-size: 1rem;
    padding: 10px;
  }
}

@media (max-width: 480px) {
  .estado-asesores {
    flex-direction: column;
    gap: 1rem;
  }
  
  .estado-icon {
    width: 35px;
    height: 35px;
  }
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>