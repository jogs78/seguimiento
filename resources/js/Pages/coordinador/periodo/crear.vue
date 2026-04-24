<template>
  <div class="page-wrapper">

    <main class="main-content">
      <div class="container-form">
        <div class="horizontal" style="margin-top:20px;">
          <p class="subtitulo">Crear Periodo</p>
        </div>

        <div class="centro" style="margin-top: 60px;">
          <form @submit.prevent="submit" class="periodo-form">
            <div class="caja">
              <div class="form-group">
                <label for="nombreperiodo" class="parrafo">Nombre del Periodo</label>
                <div v-if="form.errors.nombre" class="error-message">
                  <i class="fas fa-exclamation-triangle"></i> {{ form.errors.nombre }}
                </div>
                <input 
                  type="text" 
                  id="nombreperiodo"
                  v-model="form.nombre" 
                  :class="['llenar', { 'is-invalid': form.errors.nombre }]"
                >
              </div>

              <div class="form-group">
                <label for="fechainicio" class="parrafo">Fecha de Inicio del periodo</label>
                <div v-if="form.errors.fecha_inicio" class="error-message">
                  <i class="fas fa-exclamation-triangle"></i> {{ form.errors.fecha_inicio }}
                </div>
                <input 
                  type="date" 
                  name="fecha_inicio" 
                  id="fechainicio" 
                  v-model="form.fecha_inicio"
                  :class="['llenar', { 'is-invalid': form.errors.fecha_inicio }]"
                >
              </div>

              <div class="form-group">
                <label for="fechaconclusion" class="parrafo">Fecha de Conclusion del periodo</label>
                <div v-if="form.errors.fecha_final" class="error-message">
                  <i class="fas fa-exclamation-triangle"></i> {{ form.errors.fecha_final }}
                </div>
                <input 
                  type="date" 
                  name="fecha_final" 
                  id="fechaconclusion" 
                  v-model="form.fecha_final"
                  :class="['llenar', { 'is-invalid': form.errors.fecha_final }]"
                  style="margin-bottom: 20px;"
                >
              </div>
            </div>

            <!-- 1er Reporte -->
            <div class="form-section">
              <label class="parrafo">Rango de Fechas del 1° Reporte</label>
              <div class="date-range">
                <div class="date-group">
                  <label class="parrafo-small">Inicia</label>
                  <div v-if="form.errors.fecha_inicio_1er_reporte" class="error-message">
                    {{ form.errors.fecha_inicio_1er_reporte }}
                  </div>
                  <input 
                    type="date" 
                    name="fecha_inicio_1er_reporte" 
                    v-model="form.fecha_inicio_1er_reporte"
                    :class="['llenar', { 'is-invalid': form.errors.fecha_inicio_1er_reporte }]"
                  >
                </div>
                <div class="date-group">
                  <label class="parrafo-small">Termina</label>
                  <div v-if="form.errors.fecha_final_1er_reporte" class="error-message">
                    {{ form.errors.fecha_final_1er_reporte }}
                  </div>
                  <input 
                    type="date" 
                    name="fecha_final_1er_reporte" 
                    v-model="form.fecha_final_1er_reporte"
                    :class="['llenar', { 'is-invalid': form.errors.fecha_final_1er_reporte }]"
                  >
                </div>
              </div>
            </div>

            <!-- 2do Reporte -->
            <div class="form-section">
              <label class="parrafo">Rango de Fechas del 2° Reporte</label>
              <div class="date-range">
                <div class="date-group">
                  <label class="parrafo-small">Inicia</label>
                  <div v-if="form.errors.fecha_inicio_2do_reporte" class="error-message">
                    {{ form.errors.fecha_inicio_2do_reporte }}
                  </div>
                  <input 
                    type="date" 
                    name="fecha_inicio_2do_reporte" 
                    v-model="form.fecha_inicio_2do_reporte"
                    :class="['llenar', { 'is-invalid': form.errors.fecha_inicio_2do_reporte }]"
                  >
                </div>
                <div class="date-group">
                  <label class="parrafo-small">Termina</label>
                  <div v-if="form.errors.fecha_final_2do_reporte" class="error-message">
                    {{ form.errors.fecha_final_2do_reporte }}
                  </div>
                  <input 
                    type="date" 
                    name="fecha_final_2do_reporte" 
                    v-model="form.fecha_final_2do_reporte"
                    :class="['llenar', { 'is-invalid': form.errors.fecha_final_2do_reporte }]"
                  >
                </div>
              </div>
            </div>

            <!-- Reporte Final -->
            <div class="form-section">
              <label class="parrafo">Rango de Fechas del Reporte Final</label>
              <div class="date-range">
                <div class="date-group">
                  <label class="parrafo-small">Inicia</label>
                  <div v-if="form.errors.fecha_inicio_reporte_final" class="error-message">
                    {{ form.errors.fecha_inicio_reporte_final }}
                  </div>
                  <input 
                    type="date" 
                    name="fecha_inicio_reporte_final" 
                    v-model="form.fecha_inicio_reporte_final"
                    :class="['llenar', { 'is-invalid': form.errors.fecha_inicio_reporte_final }]"
                  >
                </div>
                <div class="date-group">
                  <label class="parrafo-small">Termina</label>
                  <div v-if="form.errors.fecha_final_reporte_final" class="error-message">
                    {{ form.errors.fecha_final_reporte_final }}
                  </div>
                  <input 
                    type="date" 
                    name="fecha_final_reporte_final" 
                    v-model="form.fecha_final_reporte_final"
                    :class="['llenar', { 'is-invalid': form.errors.fecha_final_reporte_final }]"
                  >
                </div>
              </div>
            </div>

            <div class="button-group">
              <button 
                type="submit" 
                class="boton" 
                :disabled="form.processing"
              >
                <i class="fas" :class="form.processing ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                {{ form.processing ? 'Guardando...' : 'Guardar Periodo' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

const form = useForm({
  nombre: '',
  fecha_inicio: '',
  fecha_final: '',
  fecha_inicio_1er_reporte: '',
  fecha_final_1er_reporte: '',
  fecha_inicio_2do_reporte: '',
  fecha_final_2do_reporte: '',
  fecha_inicio_reporte_final: '',
  fecha_final_reporte_final: ''
})

const submit = () => {
  form.post(route('periodos.store'), {
    onSuccess: () => {
      // Puedes redirigir o mostrar un mensaje de éxito
      form.reset()
    },
    onError: (errors) => {
      console.error('Errores:', errors)
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

.container-form {
  max-width: 800px;
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
  margin-bottom: 1rem;
}

.centro {
  display: flex;
  justify-content: center;
}

.periodo-form {
  width: 100%;
}

.caja {
  border: 2px solid rgb(40, 95, 139);
  border-radius: 15px;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1rem;
}

.parrafo {
  font-size: 1rem;
  font-weight: 600;
  color: rgb(19, 46, 68);
  margin-bottom: 0.5rem;
  display: block;
}

.parrafo-small {
  font-size: 0.9rem;
  font-weight: 500;
  color: rgb(19, 46, 68);
  margin-bottom: 0.25rem;
  display: block;
}

.llenar {
  width: 100%;
  padding: 0.75rem;
  font-size: 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 10px;
  transition: all 0.3s ease;
  margin-top: 0.25rem;
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

.form-section {
  margin-bottom: 1.5rem;
  padding: 1rem;
  background: #f9fafb;
  border-radius: 15px;
}

.date-range {
  display: flex;
  gap: 1.5rem;
  margin-top: 0.5rem;
  flex-wrap: wrap;
}

.date-group {
  flex: 1;
  min-width: 200px;
}

.error-message {
  color: #ef4444;
  font-size: 0.8rem;
  margin-top: 0.25rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.button-group {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
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
  
  .container-form {
    padding: 1rem;
  }
  
  .date-range {
    flex-direction: column;
    gap: 1rem;
  }
  
  .subtitulo {
    font-size: 1.5rem;
  }
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>