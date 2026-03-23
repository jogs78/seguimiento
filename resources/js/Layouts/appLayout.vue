<!-- resources/js/Layouts/AppLayout.vue -->
<template>
  <!-- Solo mostrar el layout si hay usuario autenticado -->
  <div v-if="user">
    
    <div style="width: 100%; margin-bottom:10px;">
      <div class="hderecho" style="width: 100%; height: 20px; margin-bottom:10px;">
        <div class="cmenor" style="width: 20%;"></div>
        <div class="cmayor" style="width: 80%;"></div>
      </div>
      
      <div class="hderecho" style="width: 100%;">
        <div style="width:30%; margin-top: 15px;">
          <Link class="boton" style="margin-right: 10px; margin-left: 10px;" :href="route('salida')">
            Salir
          </Link>
          <Link class="boton" :href="route('Cambiar_Contraseña')">
            Cambiar la Contraseña
          </Link>
        </div>
        
        
        <div style="width:70%; margin-right: 10px;" class="hderecho">
          <p style="margin-right: 10px; margin-bottom: 10px;" class="parrafo">Bienvenido:</p>
          <p class="parrafo" v-if="user.usa">
             {{ user.usa.nombre }} {{ user.usa.apellido_paterno }} {{ user.usa.apellido_materno }}
            ({{ userType }})
            <span v-if="carreraActual" class="carrera-badge">
              <i class="fas fa-graduation-cap"></i> {{ carreraActual }}
            </span>
          </p>
        </div>
      </div>
      
      <div class="horizontal">
        <div style="margin-top: 10px;" class="linea"></div>
      </div>
    </div>

    <!-- Cuerpo principal con menú lateral y contenido -->
    <div class="cuerpo">
      <!-- Menú lateral según el rol -->
      <div class="menu">
        <!-- Opciones para Coordinador -->
        <div v-if="user.usa_type === 'App\\Models\\Coordinador'">
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('coordinadores.tabla')">
              TABLA DE PROYECTOS
            </Link>
          </div>
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('estudiantes.index')">
              LISTA DE ESTUDIANTES
            </Link>
          </div>
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('periodos.index')">
              GESTIONAR PERIODO
            </Link>
          </div>
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('asesores.index')">
              ASESORES INTERNOS
            </Link>
          </div>
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('externos.index')">
              ASESORES EXTERNOS
            </Link>
          </div>
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('configuraciones.index')">
              CONFIGURACIONES
            </Link>
          </div>
        </div>

        <!-- Opciones para Estudiante -->
        <div v-else-if="user.usa_type === 'App\\Models\\Estudiante'">
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('estudiantes.edit', user.usa_id)">
              ACTUALIZA TUS DATOS
            </Link>
          </div>
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('proyectos.create')">
              PROYECTO
            </Link>
          </div>
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('estudiante.impresiones.solicitud')">
              IMPRIMIR SOLICITUD
            </Link>
          </div>
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('estudiante.impresiones.anteproyecto')">
              IMPRIMIR ANTEPROYECTO
            </Link>
          </div>
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('estudiante.promedio')">
              VERIFICA TUS SEGUIMIENTOS
            </Link>
          </div>
        </div>

        <!-- Opciones para Asesor Interno -->
        <div v-else-if="user.usa_type === 'App\\Models\\Asesor'">
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('asesor.listar-proyectos')">
              PROYECTOS ASIGNADOS
            </Link>
          </div>
        </div>

        <!-- Opciones para Asesor Externo -->
        <div v-else-if="user.usa_type === 'App\\Models\\Externo'">
          <div style="margin-top: 25px;">
            <Link style="text-decoration: none;" class="opcion" :href="route('externo.lista-de-proyectos')">
              PROYECTOS ASIGNADOS
            </Link>
          </div>
        </div>
      </div>

      <!-- Contenido principal de la página -->
      <div class="contenido">
        <slot />
      </div>
    </div>
  </div>
  
  <!-- Si no hay usuario, mostrar un loading o redirigir -->
  <div v-else class="loading">
    Cargando...
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth?.user)
const carreraActual = computed(() => page.props.carrera_actual?.nombre)

// Mapeo de tipos para mostrar nombres legibles
const userType = computed(() => {
  if (!user.value) return ''
  
  const types = {
    'App\\Models\\Estudiante': 'Estudiante',
    'App\\Models\\Asesor': 'Asesor Interno',
    'App\\Models\\Externo': 'Asesor Externo',
    'App\\Models\\Coordinador': 'Coordinador'
  }
  return types[user.value.usa_type] || user.value.usa_type
})
</script>

<style scoped>
/* Todos los estilos que ya tenías */
.cuerpo {
  grid-template-columns: 20% 80%;
  display: grid;
  height: 2000px;
}

.menu {
  border: 3px solid rgb(19, 46, 68);
  border-radius: 10px;
}

.contenido {
  margin-left: 10px;
}

.cmenor {
  background-color: rgb(40, 95, 139);
}

.cmayor {
  background-color: rgb(19, 46, 68);
}

.linea {
  background-color: rgb(10, 105, 163);
  height: 4px;
  border-radius: 2px;
  width: 95%;
}

.hderecho {
  display: flex;
  justify-content: right;
}

.horizontal {
  display: flex;
  justify-content: center;
  width: 100%;
}

.boton {
  background-color: rgb(25, 118, 210);
  padding: 15px;
  border-radius: 5px;
  color: white;
  text-decoration: none;
  cursor: pointer;
  display: inline-block;
}

.boton:hover {
  background-color: rgb(74, 139, 204);
}

.parrafo {
  font-size: 25px;
  margin-top: 10px;
}

.opcion {
  background-color: rgb(234, 245, 255);
  margin-left: 10px;
  margin-right: 10px;
  padding: 5px;
  border: 1px solid rgb(19, 46, 68);
  border-radius: 5px;
  display: block;
  text-align: center;
}

.opcion:hover {
  background-color: rgb(255, 255, 255);
}

* {
  margin: 0;
  padding: 0;
}

.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  font-size: 20px;
}
</style>