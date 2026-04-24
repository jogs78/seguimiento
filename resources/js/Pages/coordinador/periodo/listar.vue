<template>
  <div class="page-wrapper">
    
    <main class="main-content">
      <div class="container-table">
        <div class="horizontal" style="margin-top:20px;">
          <p class="subtitulo">Lista de Periodos</p>
        </div>

        <div style="margin-bottom: 40px;" class="centro">
          <table class="periodos-table">
            <thead>
              <tr>
                <th class="thfondo">ID</th>
                <th class="thfondo">ACTUAL</th>
                <th class="thfondo">NOMBRE</th>
                <th class="thfondo">ACCIONES</th>
              </tr>
            </thead>
            <tbody>
              <tr 
                v-for="periodo in periodos" 
                :key="periodo.id"
                :class="{ 'actual': Number(periodo.id) === Number(actual) }"
              >
                <td>{{ periodo.id }}</td>
                <td>
                  <form @submit.prevent="asignarActual(periodo.id)">
                    <input type="hidden" name="valor" :value="periodo.id">
                    <button 
                      type="submit" 
                      class="botonEditar"
                      :disabled="Number(periodo.id) === Number(actual) || asignando"
                    >
                      {{ Number(periodo.id) === Number(actual) ? 'ACTUAL' : 'ASIGNAR ACTUAL' }}
                    </button>
                  </form>
                </td>
                <td>{{ periodo.nombre }}</td>
                <td style="padding:8px;">
                  <Link 
                    :href="route('periodos.edit', periodo.id)" 
                    class="botonEditar"
                  >
                    EDITAR
                  </Link>
                  <form 
                    @submit.prevent="confirmarEliminacion(periodo.id)" 
                    style="display: inline-block;"
                  >
                    <button 
                      type="submit" 
                      class="botonBorrar"
                      :disabled="eliminando === periodo.id"
                    >
                      {{ eliminando === periodo.id ? '...' : 'BORRAR' }}
                    </button>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="centro">
          <Link :href="route('periodos.create')" class="boton">
            Agregar un periodo
          </Link>
        </div>
      </div>
    </main>

    <!-- SweetAlert para mensajes -->
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
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const props = defineProps({
  periodos: Array,
  actual: Number,
  configuracion: Object,
  flash: Object
})

const asignando = ref(false)
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

const asignarActual = (periodoId) => {
  window.Swal.fire({
    title: '¿Estás seguro?',
    text: `¿Deseas asignar este periodo como periodo actual?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, asignar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      asignando.value = true
      
      router.put(route('configuraciones.update', props.configuracion?.id), {
        valor: periodoId
      }, {
        preserveScroll: true,
        onSuccess: () => {
          router.reload()
          asignando.value = false
          window.Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'Periodo actualizado correctamente',
            confirmButtonText: 'OK'
          })
        },
        onError: (errors) => {
          asignando.value = false
          window.Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo asignar el periodo',
            confirmButtonText: 'OK'
          })
        }
      })
    }
  })
}

const confirmarEliminacion = (periodoId) => {
  window.Swal.fire({
    title: '⚠️ ¿Estás seguro?',
    html: 'Al eliminar este Periodo ya no se podrá restaurar.<br><strong>¿Seguro que deseas eliminar el Periodo?</strong>',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      eliminarPeriodo(periodoId)
    }
  })
}

const eliminarPeriodo = (periodoId) => {
  eliminando.value = periodoId
  
  router.delete(route('periodos.destroy', periodoId), {
    preserveScroll: true,
    onSuccess: () => {
      eliminando.value = null
      window.Swal.fire({
        icon: 'success',
        title: '¡Eliminado!',
        text: 'El periodo ha sido eliminado correctamente',
        confirmButtonText: 'OK'
      })
    },
    onError: (errors) => {
      eliminando.value = null
      window.Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errors.message || 'No se pudo eliminar el periodo',
        confirmButtonText: 'OK'
      })
    }
  })
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

.periodos-table {
  width: 100%;
  border: 2px solid rgb(19, 46, 68);
  border-collapse: collapse;
  margin-top: 20px;
}

.periodos-table th {
  border: 1px solid rgb(40, 95, 139);
  padding: 12px;
  background-color: rgb(204, 216, 228);
  font-weight: bold;
}

.periodos-table td {
  border: 1px solid rgb(40, 95, 139);
  padding: 10px;
  text-align: center;
}

/* Estilo para el período actual - Azul corporativo */
.periodos-table tr.actual {
    background: #b2ebf2;
    border: 2px solid #050E3C !important;
    box-shadow: 0 2px 8px rgba(5, 14, 60, 0.15);
    position: relative;
}

.periodos-table tr.actual td {
    border: 2px solid #050E3C !important;
    border-top: none !important;
    border-bottom: none !important;
    padding: 12px 8px !important;
}

.periodos-table tr.actual td:first-child {
    border-left: 2px solid #050E3C !important;
    border-top: 2px solid #050E3C !important;
    border-bottom: 2px solid #050E3C !important;
}

.periodos-table tr.actual td:last-child {
    border-right: 2px solid #050E3C !important;
    border-top: 2px solid #050E3C !important;
    border-bottom: 2px solid #050E3C !important;
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

.botonEditar:disabled {
  background-color: #9c98af;
  cursor: not-allowed;
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
  }
  
  .subtitulo {
    font-size: 1.5rem;
  }
  
  .periodos-table {
    font-size: 0.85rem;
  }
  
  .periodos-table th,
  .periodos-table td {
    padding: 6px;
  }
  
  .botonEditar, .botonBorrar {
    padding: 3px 6px;
    font-size: 0.75rem;
  }

  .actual {
  font-style: italic;
  background-color: #e0f7fa;
}

.actual:hover {
  background-color: #b2ebf2;
}
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>