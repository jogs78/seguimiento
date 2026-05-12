<template>
  <AppLayout>
    <div class="bodydiv">
      <!-- Barra de búsqueda -->
      <div class="search-container">
        <form @submit.prevent="buscarExternos" class="search-form">
          <input
            type="text"
            v-model="terminoBusqueda"
            placeholder="Buscar por nombre, apellidos o título"
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
          Asesores Externos del período 
          <span class="periodo-nombre">{{ periodoActual?.nombre || 'Actual' }}</span>
        </p>
      </div>

      <!-- Tabla -->
      <div style="margin-bottom: 40px" class="centro">
        <table border="1" class="externos-table">
          <thead>
            <tr>
              <th class="thfondo">ID</th>
              <th class="thfondo">NOMBRE</th>
              <th class="thfondo">APELLIDOS</th>
              <th class="thfondo">ACCIONES</th>
              <th class="thfondo">USUARIO</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="externo in todos" :key="externo.id">
              <td style="padding: 5px">{{ externo.id }}</td>
              <td style="padding: 5px">{{ externo.titulo }} {{ externo.nombre }}</td>
              <td style="padding: 5px">
                {{ externo.apellido_paterno }} {{ externo.apellido_materno }}
              </td>
              <td style="padding: 8px">
                <Link :href="route('externos.edit', externo.id)" class="btn-editar">
                  Editar
                </Link>
                
                <div v-if="!externo.proyecto_actual">
                  <form @submit.prevent="confirmarEliminacion(externo.id)">
                    <button 
                      type="submit" 
                      class="btn-borrar" 
                      :disabled="eliminando === externo.id"
                      style="margin-top: 5px;"
                    >
                      {{ eliminando === externo.id ? '...' : 'Borrar' }}
                    </button>
                  </form>
                </div>
                <div v-else class="proyecto-asignado">
                  Asesor asignado al proyecto:<br />
                  <strong>{{ externo.proyecto_actual.nombre }}</strong>
                </div>
              </td>
              <td style="padding: 5px">
                <div v-if="!externo.usuario || externo.usuario?.nombre_usuario === 'Sin cuenta'">
                  <form @submit.prevent="crearCuenta(externo.id)">
                    <button type="submit" class="btn-crear-cuenta" :disabled="creandoCuenta === externo.id">
                      {{ creandoCuenta === externo.id ? '...' : 'Crear Cuenta' }}
                    </button>
                  </form>
                </div>
                <div v-else class="usuario-existente">
                  {{ externo.usuario.nombre_usuario }}
                </div>
              </td>
            </tr>
            
            <tr v-if="todos.length === 0">
              <td colspan="5" class="sin-datos">
                No hay asesores externos registrados en el período actual
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Botones de acción -->
      <div class="acciones">
        
        <a :href="route('imprimir-externos.excel')" class="btn-descargar">
          Descargar lista
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
  flash: Object
})

// Estado reactivo
const terminoBusqueda = ref(props.filtroBuscar || '')
const sugerencias = ref([])
const eliminando = ref(null)
const creandoCuenta = ref(null)
let timeoutSugerencias = null

// Método para buscar asesores externos
const buscarExternos = () => {
  router.get(route('externos.index'), 
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
    axios.get('/externos/buscar-externo', {
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
  buscarExternos()
}

// Confirmar eliminación
const confirmarEliminacion = (id) => {
  Swal.fire({
    title: '¿Estás seguro?',
    text: '⚠️ Al eliminar este Asesor Externo ya no se podrá restaurar.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      eliminarExterno(id)
    }
  })
}

// Eliminar asesor externo
const eliminarExterno = (id) => {
  eliminando.value = id
  
  router.delete(route('externos.destroy', id), {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire('Eliminado', 'El asesor externo ha sido eliminado', 'success')
    },
    onError: (errors) => {
      Swal.fire('Error', errors.message || 'No se pudo eliminar el asesor externo', 'error')
    },
    onFinish: () => {
      eliminando.value = null
    }
  })
}

// Crear cuenta de usuario
const crearCuenta = (id) => {
  creandoCuenta.value = id
  
  router.put(route('externos.crearcuenta', id), {}, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire('Éxito', 'Cuenta creada correctamente', 'success')
    },
    onError: (errors) => {
      Swal.fire('Error', errors.message || 'No se pudo crear la cuenta', 'error')
    },
    onFinish: () => {
      creandoCuenta.value = null
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
.externos-table {
  border: 2px solid rgb(19, 46, 68);
  border-collapse: collapse;
  margin-top: 20px;
  width: 90%;
}

.externos-table th,
.externos-table td {
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

.proyecto-asignado {
  margin-top: 8px;
  font-size: 12px;
  color: #666;
  background: #f5f5f5;
  padding: 5px;
  border-radius: 4px;
}

.proyecto-asignado strong {
  color: #050E3C;
}

.usuario-existente {
  font-weight: bold;
  color: #28a745;
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

.btn-borrar:disabled,
.btn-crear-cuenta:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-crear-cuenta {
  background-color: rgb(25, 118, 210);
  color: white;
  cursor: pointer;
  text-decoration: none;
  padding: 5px 12px;
  border-radius: 4px;
  border: none;
}

.btn-crear-cuenta:hover {
  background-color: rgb(74, 139, 204);
}

/* Acciones */
.acciones {
  display: flex;
  justify-content: center;
  gap: 15px;
  margin-top: 20px;
  margin-bottom: 40px;
}

.btn-descargar {
  background-color: rgb(25, 118, 210);
  color: white;
  text-decoration: none;
  padding: 8px 20px;
  border-radius: 5px;
  cursor: pointer;
  display: inline-block;
}

.btn-descargar:hover {
  background-color: rgb(74, 139, 204);
}
</style>