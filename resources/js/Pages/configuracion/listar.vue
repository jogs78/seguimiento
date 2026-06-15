<template>
  <div class="page-wrapper">
   

    <!-- Contenido principal -->
    <main class="main-content">
      <div class="container-table">
        <div class="horizontal" style="margin-top:20px;">
          <p class="subtitulo">Configuraciones</p>
        </div>

        <div style="margin-bottom: 40px;" class="centro">
          <table class="configuraciones-table">
            <thead>
              <tr>
                <th class="thfondo">ID</th>
                <th class="thfondo">VARIABLE</th>
                <th class="thfondo">VALOR</th>
                <th class="thfondo">TIPO</th>
                <th class="thfondo">ACCIONES</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="configuracion in configuraciones" :key="configuracion.id">
                <td style="padding:5px;">{{ configuracion.id }}</td>
                <td style="padding:5px;">{{ configuracion.variable }}</td>
                <td style="padding:5px;">{{ configuracion.valor }}</td>
                <td style="padding:5px;">{{ configuracion.tipo }}</td>
                <td style="padding:8px;">
                  <Link 
                    :href="route('configuraciones.edit', configuracion.id)" 
                    class="botonEditar"
                  >
                    Editar
                  </Link>
                  <form 
                    @submit.prevent="confirmarEliminacion(configuracion.id)" 
                    style="display: inline-block;"
                  >
                    <button 
                      type="submit" 
                      class="botonBorrar"
                      :disabled="eliminando === configuracion.id"
                    >
                      {{ eliminando === configuracion.id ? '...' : 'Borrar' }}
                    </button>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="centro">
          <Link :href="route('configuraciones.create')" class="boton">
            Agregar una configuracion
          </Link>
        </div>
      </div>

      
    </main>

    <!-- Toast para mensajes flash -->
    <div v-if="successMessage" class="toast-notification success">
      <i class="fas fa-check-circle"></i>
      {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="toast-notification error">
      <i class="fas fa-exclamation-circle"></i>
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const props = defineProps({
  configuraciones: Array,
  flash: Object
})

const eliminando = ref(null)
const successMessage = ref(props.flash?.success || null)
const errorMessage = ref(props.flash?.error || null)

// Ocultar mensajes después de 5 segundos
if (successMessage.value) {
  setTimeout(() => successMessage.value = null, 5000)
}
if (errorMessage.value) {
  setTimeout(() => errorMessage.value = null, 5000)
}

const confirmarEliminacion = (configuracionId) => {
  window.Swal.fire({
    title: '⚠️ ¿Estás seguro?',
    html: 'Al eliminar esta Configuración ya no se podrá restaurar.<br><strong>¿Seguro que deseas eliminar la Configuración?</strong>',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      eliminarConfiguracion(configuracionId)
    }
  })
}

const eliminarConfiguracion = (configuracionId) => {
  eliminando.value = configuracionId
  
  router.delete(route('configuraciones.destroy', configuracionId), {
    preserveScroll: true,
    onSuccess: () => {
      eliminando.value = null
      window.Swal.fire({
        icon: 'success',
        title: '¡Eliminado!',
        text: 'La configuración ha sido eliminada correctamente',
        confirmButtonText: 'OK'
      })
    },
    onError: (errors) => {
      eliminando.value = null
      window.Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errors.message || 'No se pudo eliminar la configuración',
        confirmButtonText: 'OK'
      })
    }
  })
}
</script>

<script>

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

.container-table {
  max-width: 1000px;
  margin: 0 auto;
  background: white;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.subtitulo {
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  color: rgb(19, 46, 68);
  margin-bottom: 1.5rem;
}

.centro {
  display: flex;
  justify-content: center;
}

.configuraciones-table {
  width: 100%;
  border: 2px solid rgb(19, 46, 68);
  border-collapse: collapse;
  margin-top: 20px;
}

.configuraciones-table th {
  border: 1px solid rgb(40, 95, 139);
  padding: 12px;
  background-color: rgb(204, 216, 228);
  font-weight: bold;
}

.configuraciones-table td {
  border: 1px solid rgb(40, 95, 139);
  padding: 10px;
  text-align: center;
}

.boton {
  background: linear-gradient(135deg, rgb(19, 46, 68), rgb(40, 95, 139));
  color: white;
  border: none;
  padding: 0.8rem 2rem;
  border-radius: 50px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  text-decoration: none;
}

.boton:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(19, 46, 68, 0.3);
}

.botonEditar {
  background-color: rgb(25, 118, 210);
  color: white;
  cursor: pointer;
  text-decoration: none;
  padding: 5px 10px;
  border-radius: 5px;
  border: none;
  font-size: 0.85rem;
  display: inline-block;
  margin: 2px;
}

.botonEditar:hover {
  background-color: rgb(74, 139, 204);
}

.botonBorrar {
  background-color: rgb(210, 25, 25);
  color: white;
  cursor: pointer;
  text-decoration: none;
  padding: 5px 10px;
  border-radius: 5px;
  border: none;
  font-size: 0.85rem;
  margin-top: 5px;
}

.botonBorrar:hover {
  background-color: rgb(204, 74, 74);
}

.botonBorrar:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

/* Notificaciones toast */
.toast-notification {
  position: fixed;
  bottom: 20px;
  right: 20px;
  padding: 12px 20px;
  border-radius: 8px;
  color: white;
  font-weight: 500;
  z-index: 9999;
  animation: slideIn 0.3s ease-out;
}

.toast-notification.success {
  background: linear-gradient(135deg, #22c55e, #16a34a);
}

.toast-notification.error {
  background: linear-gradient(135deg, #ef4444, #dc2626);
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
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
  
  .container-table {
    padding: 1rem;
    overflow-x: auto;
  }
  
  .subtitulo {
    font-size: 1.5rem;
  }
  
  .configuraciones-table {
    font-size: 0.85rem;
    min-width: 600px;
  }
  
  .configuraciones-table th,
  .configuraciones-table td {
    padding: 6px;
  }
  
  .botonEditar, .botonBorrar {
    padding: 3px 6px;
    font-size: 0.75rem;
  }
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>