<template>
    <AppLayout>
        <div class="page-wrapper">
            
            <!-- Contenido principal -->
            <main class="main-content">
            <div style="margin-top:20px;">
                <div class="horizontal">
                <p class="subtitulo">Lista de Proyectos Asignados</p>
                </div>
                
                <div class="table-responsive">
                <table class="proyectos-table">
                    <thead>
                    <tr>
                        <th class="thfondo">Nombre proyecto</th>
                        <th class="thfondo">Nombre empresa</th>
                        <th class="thfondo">Nombre Estudiante</th>
                        <th class="thfondo">Primer Seguimiento</th>
                        <th class="thfondo">Segundo Seguimiento</th>
                        <th class="thfondo">Seguimiento Final</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="proyecto in proyectos" :key="proyecto.id">
                        <!-- Columna: Nombre proyecto -->
                        <td class="proyecto-cell">
                        {{ proyecto.nombre }}
                        <br><br>
                        <p>Enviar un correo al coordinador:</p>
                        
                        <!-- Correo al coordinador -->
                        <div v-if="obtenerCoordinadorId(proyecto)" class="email-container">
                            <form @submit.prevent="enviarCorreo('coordinador', obtenerCoordinadorId(proyecto))">
                            <button type="submit" class="email-btn" title="Redactar un correo al coordinador">
                                <img src="/images/mail.png" width="50" height="32" alt="Email">
                            </button>
                            </form>
                        </div>
                        <p v-else class="no-data">No hay coordinador asignado</p>
                        </td>

                        <!-- Columna: Nombre empresa -->
                        <td class="empresa-cell">
                        {{ proyecto.empresa?.nombre || 'Sin empresa' }}
                        
                        <!-- Correo al asesor externo -->
                        <div v-if="proyecto.externo?.id" class="email-container">
                            <form @submit.prevent="enviarCorreo('externo', proyecto.externo.id)">
                            <button type="submit" class="email-btn" title="Redactar un correo al asesor externo">
                                <img src="/images/mail.png" width="50" height="32" alt="Email">
                            </button>
                            </form>
                        </div>
                        <p v-else class="no-data">No hay asesor externo asignado</p>
                        <br>
                        </td>

                        <!-- Columna: Nombre Estudiante -->
                        <td class="estudiantes-cell">
                        <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="estudiante-card">
                            {{ estudiante.nombre }} {{ estudiante.apellido_paterno }} {{ estudiante.apellido_materno }}
                            
                            <!-- Correo al estudiante -->
                            <form @submit.prevent="enviarCorreo('estudiante', estudiante.id)" class="email-form">
                            <button type="submit" class="email-btn-small" title="Redactar un correo al estudiante">
                                <img src="/images/mail.png" width="35" height="22" alt="Email">
                            </button>
                            </form>
                        </div>
                        </td>

                        <!-- Columna: Primer Seguimiento -->
                        <td class="seguimiento-cell">
                        <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="seguimiento-card">
                            <!-- Botón para dar seguimiento (si no ha calificado interno) -->
                            <div v-if="!estudiante.primer?.puntualidad_interno" class="seguimiento-action">
                            <Link :href="route('realizar-seguimientos', [estudiante.id, 'primer'])" class="btn-seguimiento">
                                Dar Seguimiento a {{ estudiante.nombre }}
                            </Link>
                            </div>
                            
                            <!-- Estado de calificaciones -->
                            <div class="estado-icons">
                            <!-- Ambos calificaron -->
                            <template v-if="estudiante.primer?.promedio_interno && estudiante.primer?.promedio_externo">
                                <a :href="route('estudiante.impresiones.seguimientos.primer', estudiante.id)" class="download-link" title="Descargar 1° seguimiento">
                                <img src="/images/UnoSegui.png" width="40" height="65" alt="Descargar">
                                </a>
                            </template>
                            <template v-else>
                                <!-- Solo interno calificó -->
                                <img v-if="estudiante.primer?.promedio_interno" 
                                    src="/images/IntSi.png" 
                                    width="65" height="70" 
                                    title="Asesor Interno ya calificó"
                                    class="status-img">
                                
                                <!-- Solo externo calificó -->
                                <img v-if="estudiante.primer?.promedio_externo" 
                                    src="/images/ExtSi.png" 
                                    width="65" height="70" 
                                    title="Asesor Externo ya calificó"
                                    class="status-img">
                                
                                <!-- Externo pendiente -->
                                <img v-if="!estudiante.primer?.promedio_externo" 
                                    src="/images/ExtNo.png" 
                                    width="65" height="70" 
                                    title="Asesor Externo no ha calificado"
                                    class="status-img">
                            </template>
                            </div>
                        </div>
                        </td>

                        <!-- Columna: Segundo Seguimiento -->
                        <td class="seguimiento-cell">
                        <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="seguimiento-card">
                            <div v-if="!estudiante.segundo?.promedio_interno" class="seguimiento-action">
                            <Link :href="route('realizar-seguimientos', [estudiante.id, 'segundo'])" class="btn-seguimiento">
                                Dar Seguimiento a {{ estudiante.nombre }}
                            </Link>
                            </div>
                            
                            <div class="estado-icons">
                            <template v-if="estudiante.segundo?.promedio_interno && estudiante.segundo?.promedio_externo">
                                <a :href="route('estudiante.impresiones.seguimientos.segundo', estudiante.id)" class="download-link" title="Descargar 2° seguimiento">
                                <img src="/images/DosSegui.png" width="40" height="65" alt="Descargar">
                                </a>
                            </template>
                            <template v-else>
                                <img v-if="estudiante.segundo?.promedio_interno" 
                                    src="/images/IntSi.png" 
                                    width="65" height="70" 
                                    title="Asesor Interno ya calificó"
                                    class="status-img">
                                
                                <img v-if="estudiante.segundo?.promedio_externo" 
                                    src="/images/ExtSi.png" 
                                    width="65" height="70" 
                                    title="Asesor Externo ya calificó"
                                    class="status-img">
                                
                                <img v-if="!estudiante.segundo?.promedio_externo" 
                                    src="/images/ExtNo.png" 
                                    width="65" height="70" 
                                    title="Asesor Externo no ha calificado"
                                    class="status-img">
                            </template>
                            </div>
                        </div>
                        </td>

                        <!-- Columna: Seguimiento Final -->
                        <td class="seguimiento-cell">
                        <div v-for="estudiante in proyecto.estudiantes" :key="estudiante.id" class="seguimiento-card">
                            <div v-if="!estudiante.ultimo?.promedio_interno" class="seguimiento-action">
                            <Link :href="route('realizar-seguimientos', [estudiante.id, 'ultimo'])" class="btn-seguimiento">
                                Dar Seguimiento a {{ estudiante.nombre }}
                            </Link>
                            </div>
                            
                            <div class="estado-icons">
                            <template v-if="estudiante.ultimo?.promedio_interno && estudiante.ultimo?.promedio_externo">
                                <a :href="route('estudiante.impresiones.seguimientos.ultimo', estudiante.id)" class="download-link" title="Descargar 3° seguimiento">
                                <img src="/images/TresSegui.png" width="40" height="65" alt="Descargar">
                                </a>
                            </template>
                            <template v-else>
                                <img v-if="estudiante.ultimo?.promedio_interno" 
                                    src="/images/IntSi.png" 
                                    width="65" height="70" 
                                    title="Asesor Interno ya calificó"
                                    class="status-img">
                                
                                <img v-if="estudiante.ultimo?.promedio_externo" 
                                    src="/images/ExtSi.png" 
                                    width="65" height="70" 
                                    title="Asesor Externo ya calificó"
                                    class="status-img">
                                
                                <img v-if="!estudiante.ultimo?.promedio_externo" 
                                    src="/images/ExtNo.png" 
                                    width="65" height="70" 
                                    title="Asesor Externo no ha calificado"
                                    class="status-img">
                            </template>
                            </div>
                        </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </main>
        </div>
    </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const props = defineProps({
  proyectos: Array,
  tipo: String // 'asesor' o 'externo'
})

// Función para obtener el ID del coordinador
const obtenerCoordinadorId = (proyecto) => {
  const estudiante = proyecto.estudiantes?.[0]
  const carrera = estudiante?.carrera
  const coordinador = carrera?.coordinador
  return coordinador?.id || null
}

// Función para enviar correo
const enviarCorreo = (tipo, id) => {
  window.open(route('correo.create', { type: tipo, id: id }), '_blank')
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.page-wrapper {
  
  background: linear-gradient(135deg, #f5f7fa 0%, #e9edf5 100%);
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


.subtitulo {
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  color: rgb(19, 46, 68);
  margin-bottom: 1.5rem;
}

/* Tabla */
.table-responsive {
  overflow-x: auto;
}

.proyectos-table {
  width: 100%;
  border: 2px solid rgb(19, 46, 68);
  border-collapse: collapse;
  background: white;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.thfondo {
  background-color: rgb(204, 216, 228);
  font-weight: bold;
  padding: 12px;
  border: 1px solid rgb(40, 95, 139);
}

.proyectos-table td {
  border: 1px solid rgb(40, 95, 139);
  padding: 10px;
  vertical-align: top;
}

/* Columnas */
.proyecto-cell, .empresa-cell {
  min-width: 200px;
}

.estudiantes-cell {
  min-width: 200px;
}

.seguimiento-cell {
  min-width: 150px;
}

/* Tarjetas de estudiantes */
.estudiante-card {
  border: 2px solid rgb(40, 95, 139);
  border-radius: 10px;
  padding: 15px;
  margin-bottom: 10px;
  text-align: center;
  background: #f8f9fa;
}

.estudiante-card:last-child {
  margin-bottom: 0;
}

/* Tarjetas de seguimiento */
.seguimiento-card {
  border: 2px solid rgb(40, 95, 139);
  border-radius: 10px;
  padding: 10px;
  margin-bottom: 10px;
  text-align: center;
}

.seguimiento-card:last-child {
  margin-bottom: 0;
}

/* Botones */
.email-container {
  margin-top: 10px;
  text-align: center;
}

.email-btn, .email-btn-small {
  background: none;
  border: none;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.email-btn:hover, .email-btn-small:hover {
  transform: scale(1.1);
}

.email-form {
  margin-top: 8px;
}

.btn-seguimiento {
  display: inline-block;
  background-color: rgb(25, 118, 210);
  color: white;
  padding: 6px 12px;
  border-radius: 5px;
  text-decoration: none;
  font-size: 12px;
  margin-bottom: 8px;
}

.btn-seguimiento:hover {
  background-color: rgb(74, 139, 204);
}

/* Estados e iconos */
.estado-icons {
  display: flex;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
}

.status-img {
  max-width: 65px;
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
  font-size: 12px;
  margin-top: 5px;
}

/* Responsive */
@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .header-left {
    flex-direction: column;
    text-align: center;
  }
  
  .main-content {
    padding: 1rem;
  }
  
  .subtitulo {
    font-size: 1.5rem;
  }
  
  .proyectos-table {
    font-size: 12px;
  }
  
  .thfondo {
    padding: 8px;
  }
  
  .proyectos-table td {
    padding: 6px;
  }
  
  .status-img {
    max-width: 40px;
  }
  
  .btn-seguimiento {
    font-size: 10px;
    padding: 4px 8px;
  }
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>