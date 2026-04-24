<template>
  <div class="page-wrapper">
   

    <!-- Contenido principal -->
    <main class="main-content">
      <div style="margin-top:20px;">
        <div class="horizontal">
          <p class="subtitulo">Actualizar Estudiante</p>
        </div>

        <div class="centro" style="margin-top: 60px;">
          <form @submit.prevent="submit" class="estudiante-form">
            <!-- Nombre -->
            <div class="form-group">
              <label for="nombre" class="parrafo">Nombre/s</label>
              <div v-if="form.errors.nombre" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.nombre }}
              </div>
              <input 
                type="text" 
                id="nombre"
                v-model="form.nombre"
                :class="['llenar', { 'is-invalid': form.errors.nombre }]"
              >
            </div>

            <!-- Apellido paterno -->
            <div class="form-group">
              <label for="apellido_paterno" class="parrafo">Apellido paterno</label>
              <div v-if="form.errors.apellido_paterno" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.apellido_paterno }}
              </div>
              <input 
                type="text" 
                id="apellido_paterno"
                v-model="form.apellido_paterno"
                :class="['llenar', { 'is-invalid': form.errors.apellido_paterno }]"
              >
            </div>

            <!-- Apellido materno -->
            <div class="form-group">
              <label for="apellido_materno" class="parrafo">Apellido materno</label>
              <div v-if="form.errors.apellido_materno" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.apellido_materno }}
              </div>
              <input 
                type="text" 
                id="apellido_materno"
                v-model="form.apellido_materno"
                :class="['llenar', { 'is-invalid': form.errors.apellido_materno }]"
              >
            </div>

            <!-- Correo electrónico -->
            <div class="form-group">
              <label for="correo_electronico" class="parrafo">Correo Electronico</label>
              <div v-if="form.errors.correo_electronico" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.correo_electronico }}
              </div>
              <input 
                type="email" 
                id="correo_electronico"
                v-model="form.correo_electronico"
                :class="['llenar', { 'is-invalid': form.errors.correo_electronico }]"
              >
            </div>

            <div class="centro">
              <button 
                type="submit" 
                class="boton"
                :disabled="form.processing"
              >
                <i class="fas" :class="form.processing ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                {{ form.processing ? 'Actualizando...' : 'Actualizar' }}
              </button>
            </div>
          </form>
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
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

// Props que recibe del controlador
const props = defineProps({
  estudiante: Object,
  flash: Object
})

// Mensajes flash
const successMessage = ref(props.flash?.success || null)
const errorMessage = ref(props.flash?.error || null)

// Inicializar el formulario con los datos del estudiante
const form = useForm({
  nombre: props.estudiante.nombre || '',
  apellido_paterno: props.estudiante.apellido_paterno || '',
  apellido_materno: props.estudiante.apellido_materno || '',
  correo_electronico: props.estudiante.correo_electronico || ''
})

// Enviar formulario
const submit = () => {
  form.put(route('estudiantes.update', props.estudiante.id), {
    preserveScroll: true,
    onSuccess: () => {
      successMessage.value = 'Estudiante actualizado exitosamente'
      setTimeout(() => successMessage.value = null, 5000)
    },
    onError: (errors) => {
      errorMessage.value = Object.values(errors)[0] || 'Error al actualizar el estudiante'
      setTimeout(() => errorMessage.value = null, 5000)
    }
  })
}

// Ocultar mensajes después de 5 segundos
if (successMessage.value) {
  setTimeout(() => successMessage.value = null, 5000)
}
if (errorMessage.value) {
  setTimeout(() => errorMessage.value = null, 5000)
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

.centro {
  display: flex;
  justify-content: center;
}

.subtitulo {
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  color: rgb(19, 46, 68);
  margin-bottom: 1rem;
}

.estudiante-form {
  max-width: 500px;
  width: 100%;
  background: white;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.form-group {
  margin-bottom: 1.5rem;
}

.parrafo {
  font-size: 1rem;
  font-weight: 600;
  color: rgb(19, 46, 68);
  margin-bottom: 0.5rem;
  display: block;
}

.llenar {
  width: 100%;
  padding: 0.75rem;
  font-size: 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.llenar:focus {
  outline: none;
  border-color: rgb(40, 95, 139);
  box-shadow: 0 0 0 3px rgba(40, 95, 139, 0.2);
}

.llenar.is-invalid {
  border-color: #ef4444;
  background-color: #fef2f2;
}

.error-message {
  color: #ef4444;
  font-size: 0.85rem;
  margin-top: 0.25rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.boton {
  background: linear-gradient(135deg, rgb(19, 46, 68), rgb(40, 95, 139));
  color: white;
  border: none;
  padding: 0.8rem 2rem;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.boton:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(19, 46, 68, 0.3);
}

.boton:disabled {
  opacity: 0.7;
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
  
  .estudiante-form {
    padding: 1.5rem;
  }
  
  .subtitulo {
    font-size: 1.5rem;
  }
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>