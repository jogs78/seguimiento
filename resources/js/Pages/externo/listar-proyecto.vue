<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">Lista de Proyectos Asignados</p>
      </div>

      <div style="margin-top: 30px; overflow-x: auto;">
        <table class="proyectos-table">
          <thead>
            <tr>
              <th class="thfondo">Nombre proyecto</th>
              <th class="thfondo">Nombre del Coasesor</th>
              <th class="thfondo">Nombre Estudiante</th>
              <th class="thfondo">Primer Seguimiento</th>
              <th class="thfondo">Segundo Seguimiento</th>
              <th class="thfondo">Seguimiento Final</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="proyecto in proyectos" :key="proyecto.id">
              <!-- Columna: Nombre proyecto -->
              <td class="project-cell">
                <strong>{{ proyecto.nombre }}</strong>
                <br><br>
                <p class="mail-label">Enviar un correo al coordinador:</p>
                <button 
                    @click="enviarCorreo('coordinador', proyecto.coordinador.id)"
                  class="btn-mail"
                  :disabled="!proyecto.periodo?.coordinador?.id"
                >
                  <img src="/images/mail.png" width="40" height="25" alt="Email">
                </button>
                <p class="mail-label">
                    {{ proyecto.coordinador.nombre }} {{ proyecto.coordinador.apellido_paterno || '' }}
                </p>
                <p v-if="!proyecto.coordinador?.id" class="no-data">
                  No hay coordinador asignado
                </p>
              </td>

              <!-- Columna: Coasesor (Asesor Interno) -->
              <td class="coasesor-cell">
                <div v-if="proyecto.asesor">
                  {{ proyecto.asesor.nombre }} {{ proyecto.asesor.apellido_paterno }} {{ proyecto.asesor.apellido_materno }}
                  <button 
                    @click="enviarCorreo('asesor', proyecto.asesor.id)"
                    class="btn-mail"
                    title="Enviar correo al asesor interno"
                  >
                    <img src="/images/mail.png" width="40" height="25" alt="Email">
                  </button>
                </div>
                <p v-else class="no-data">Sin asesor interno</p>
              </td>

              <!-- Columna: Estudiantes -->
              <td class="students-cell">
                <div 
                  v-for="estudiante in proyecto.estudiantes" 
                  :key="estudiante.id"
                  class="student-card"
                >
                  {{ estudiante.nombre }} {{ estudiante.apellido_paterno }} {{ estudiante.apellido_materno }}
                  <button 
                    @click="enviarCorreo('estudiante', estudiante.id)"
                    class="btn-mail small"
                    title="Enviar correo al estudiante"
                  >
                    <img src="/images/mail.png" width="30" height="18" alt="Email">
                  </button>
                </div>
              </td>

              <!-- Primer Seguimiento -->
              <td class="seguimiento-cell">
                <div 
                  v-for="estudiante in proyecto.estudiantes" 
                  :key="estudiante.id"
                  class="seguimiento-card"
                >
                  <!-- Botón para dar seguimiento si no ha calificado el externo -->
                  <div v-if="!estudiante.primer?.puntualidad_externo" class="seguimiento-action">
                    <a 
                      :href="route('realizar-seguimientos', [estudiante.id, 'primer'])"
                      class="link-seguimiento"
                    >
                      📝 Dar Seguimiento a {{ estudiante.nombre }}
                    </a>
                  </div>

                  <!-- Estado del seguimiento -->
                  <div class="status-icons">
                    <template v-if="estudiante.primer?.puntualidad_interno && estudiante.primer?.puntualidad_externo">
                      <button @click="descargarSeguimiento(estudiante.id, 'primer')" class="btn-download">
                        <img src="/images/UnoSegui.png" alt="Descargar" class="status-icon">
                      </button>
                    </template>
                    <template v-else>
                      <img 
                        v-if="estudiante.primer?.puntualidad_interno" 
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
                      
                      <img 
                        v-if="estudiante.primer?.puntualidad_externo" 
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
                    </template>
                  </div>
                </div>
              </td>

              <!-- Segundo Seguimiento -->
              <td class="seguimiento-cell">
                <div 
                  v-for="estudiante in proyecto.estudiantes" 
                  :key="estudiante.id"
                  class="seguimiento-card"
                >
                  <div v-if="!estudiante.segundo?.puntualidad_externo" class="seguimiento-action">
                    <a 
                      :href="route('realizar-seguimientos', [estudiante.id, 'segundo'])"
                      class="link-seguimiento"
                    >
                      📝 Dar Seguimiento a {{ estudiante.nombre }}
                    </a>
                  </div>

                  <div class="status-icons">
                    <template v-if="estudiante.segundo?.puntualidad_interno && estudiante.segundo?.puntualidad_externo">
                      <button @click="descargarSeguimiento(estudiante.id, 'segundo')" class="btn-download">
                        <img src="/images/DosSegui.png" alt="Descargar" class="status-icon">
                      </button>
                    </template>
                    <template v-else>
                      <img 
                        v-if="estudiante.segundo?.puntualidad_interno" 
                        src="/images/IntSi.png" 
                        class="status-icon"
                        title="Asesor Interno ya calificó"
                      >
                      <img 
                        v-else 
                        src="/images/IntNo.png" 
                        class="status-icon"
                        title="Asesor Interno no ha calificado"
                      >
                      <img 
                        v-if="estudiante.segundo?.puntualidad_externo" 
                        src="/images/ExtSi.png" 
                        class="status-icon"
                        title="Asesor Externo ya calificó"
                      >
                      <img 
                        v-else 
                        src="/images/ExtNo.png" 
                        class="status-icon"
                        title="Asesor Externo no ha calificado"
                      >
                    </template>
                  </div>
                </div>
              </td>

              <!-- Seguimiento Final -->
              <td class="seguimiento-cell">
                <div 
                  v-for="estudiante in proyecto.estudiantes" 
                  :key="estudiante.id"
                  class="seguimiento-card"
                >
                  <div v-if="!estudiante.ultimo?.promedio_externo" class="seguimiento-action">
                    <a 
                      :href="route('realizar-seguimientos', [estudiante.id, 'ultimo'])"
                      class="link-seguimiento"
                    >
                      📝 Dar Seguimiento a {{ estudiante.nombre }}
                    </a>
                  </div>

                  <div class="status-icons">
                    <template v-if="estudiante.ultimo?.promedio_interno && estudiante.ultimo?.promedio_externo">
                      <button @click="descargarSeguimiento(estudiante.id, 'ultimo')" class="btn-download">
                        <img src="/images/TresSegui.png" alt="Descargar" class="status-icon">
                      </button>
                    </template>
                    <template v-else>
                      <img 
                        v-if="estudiante.ultimo?.promedio_interno" 
                        src="/images/IntSi.png" 
                        class="status-icon"
                        title="Asesor Interno ya calificó"
                      >
                      <img 
                        v-else 
                        src="/images/IntNo.png" 
                        class="status-icon"
                        title="Asesor Interno no ha calificado"
                      >
                      <img 
                        v-if="estudiante.ultimo?.promedio_externo" 
                        src="/images/ExtSi.png" 
                        class="status-icon"
                        title="Asesor Externo ya calificó"
                      >
                      <img 
                        v-else 
                        src="/images/ExtNo.png" 
                        class="status-icon"
                        title="Asesor Externo no ha calificado"
                      >
                    </template>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const props = defineProps({
  proyectos: {
    type: Array,
    required: true,
    default: () => []
  },
  periodo_id: {
    type: Number,
    required: false
  }
})

// Enviar correo
const enviarCorreo = (tipo, id) => {
  if (!id) {
    return
  }
  router.get(route('correo.create', { type: tipo, id: id }))
}

// Descargar seguimiento
const descargarSeguimiento = (estudianteId, tipo) => {
  const rutas = {
    primer: 'estudiante.impresiones.seguimientos.primer',
    segundo: 'estudiante.impresiones.seguimientos.segundo',
    ultimo: 'estudiante.impresiones.seguimientos.ultimo'
  }
  
  window.open(route(rutas[tipo], estudianteId), '_blank')
}
</script>

<style scoped>
.bodydiv {
  margin-left: 20px;
  margin-right: 20px;
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
}

/* Tabla */
.proyectos-table {
  width: 100%;
  border-collapse: collapse;
  border: 2px solid rgb(19, 46, 68);
}

.proyectos-table th,
.proyectos-table td {
  border: 1px solid rgb(40, 95, 139);
  padding: 12px 8px;
  vertical-align: top;
}

.thfondo {
  background-color: rgb(204, 216, 228);
  text-align: center;
}

/* Celdas */
.project-cell {
  background-color: #f9f9f9;
  min-width: 180px;
}

.coasesor-cell {
  background-color: #f9f9f9;
  
}

.students-cell {
  background-color: #f9f9f9;
 
}

.seguimiento-cell {
  background-color: #f9f9f9;
 
}

/* Tarjetas de estudiantes */
.student-card {
  border: 2px solid rgb(40, 95, 139);
  border-radius: 10px;
  padding: 12px;
  margin-bottom: 10px;
  text-align: center;
  background-color: white;
}

.student-card:last-child {
  margin-bottom: 0;
}

/* Tarjetas de seguimiento */
.seguimiento-card {
  border: 2px solid rgb(40, 95, 139);
  border-radius: 10px;
  padding: 12px;
  margin-bottom: 10px;
  text-align: center;
  background-color: white;
}

.seguimiento-card:last-child {
  margin-bottom: 0;
}

/* Botones de correo */
.btn-mail {
  background: none;
  border: none;
  cursor: pointer;
  padding: 5px;
  margin-left: 8px;
  vertical-align: middle;
}

.btn-mail:hover {
  opacity: 0.7;
}

.btn-mail.small {
  margin-left: 5px;
}

.mail-label {
  font-size: 12px;
  margin: 5px 0;
  color: #666;
}

.no-data {
  color: #999;
  font-style: italic;
  font-size: 12px;
  margin-top: 8px;
}

/* Enlace de seguimiento */
.link-seguimiento {
  display: inline-block;
  background-color: #28a745;
  color: white;
  text-decoration: none;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 12px;
  margin-bottom: 10px;
}

.link-seguimiento:hover {
  background-color: #218838;
  text-decoration: none;
  color: white;
}

/* Iconos de estado */
.status-icons {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.status-icon {
  width: 40px;
  height: 40px;
  object-fit: contain;
}

.btn-download {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
}

.btn-download:hover {
  opacity: 0.8;
}

/* Responsive */
@media (max-width: 768px) {
  .proyectos-table {
    font-size: 12px;
  }
  
  .status-icon {
    width: 30px;
    height: 30px;
  }
  
  .subtitulo {
    font-size: 24px;
  }
}
</style>