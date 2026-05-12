<template>
  <AppLayout>
    <div class="bodydiv">
      <!-- Título -->
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-calendar-alt"></i>
          Actualizar Período
        </p>
      </div>

      <!-- Formulario -->
      <div class="centro">
        <div class="form-card">
          <form @submit.prevent="actualizarPeriodo" class="formulario">
            
            <!-- Nombre del Período -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-tag"></i>
                Nombre del Período
              </label>
              <div class="input-group">
                <i class="fas fa-calendar input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.nombre"
                  class="input-text"
                  :class="{ 'error': errores.nombre }"
                  placeholder="Ej: Enero-Junio 2024"
                  required
                />
              </div>
              <span v-if="errores.nombre" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.nombre }}
              </span>
            </div>

            <!-- Fechas del Período -->
            <div class="date-group">
              <label class="parrafo">
                <i class="fas fa-calendar-week"></i>
                Fechas del Período
              </label>
              <div class="date-row">
                <div class="date-item">
                  <label>Fecha de Inicio</label>
                  <div class="input-group">
                    <i class="fas fa-play input-icon"></i>
                    <input 
                      type="date" 
                      v-model="form.fecha_inicio"
                      class="input-text"
                      :class="{ 'error': errores.fecha_inicio }"
                      required
                    />
                  </div>
                </div>
                <div class="date-separator">
                  <i class="fas fa-arrow-right"></i>
                </div>
                <div class="date-item">
                  <label>Fecha de Conclusión</label>
                  <div class="input-group">
                    <i class="fas fa-stop input-icon"></i>
                    <input 
                      type="date" 
                      v-model="form.fecha_final"
                      class="input-text"
                      :class="{ 'error': errores.fecha_final }"
                      required
                    />
                  </div>
                </div>
              </div>
              <span v-if="errores.fecha_inicio" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.fecha_inicio }}
              </span>
              <span v-if="errores.fecha_final" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.fecha_final }}
              </span>
            </div>

            <div class="section-divider">
              <hr>
              <span><i class="fas fa-chart-line"></i> Reportes de Seguimiento</span>
              <hr>
            </div>

            <!-- Rango 1er Reporte -->
            <div class="date-group">
              <label class="parrafo">
                <i class="fas fa-flag-checkered"></i>
                1er Reporte de Seguimiento
              </label>
              <div class="date-row">
                <div class="date-item">
                  <label>Fecha de Inicio</label>
                  <div class="input-group">
                    <i class="fas fa-play input-icon"></i>
                    <input 
                      type="date" 
                      v-model="form.fecha_inicio_1er_reporte"
                      class="input-text"
                      :class="{ 'error': errores.fecha_inicio_1er_reporte }"
                    />
                  </div>
                </div>
                <div class="date-separator">
                  <i class="fas fa-arrow-right"></i>
                </div>
                <div class="date-item">
                  <label>Fecha Final</label>
                  <div class="input-group">
                    <i class="fas fa-stop input-icon"></i>
                    <input 
                      type="date" 
                      v-model="form.fecha_final_1er_reporte"
                      class="input-text"
                      :class="{ 'error': errores.fecha_final_1er_reporte }"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Rango 2do Reporte -->
            <div class="date-group">
              <label class="parrafo">
                <i class="fas fa-flag-checkered"></i>
                2do Reporte de Seguimiento
              </label>
              <div class="date-row">
                <div class="date-item">
                  <label>Fecha de Inicio</label>
                  <div class="input-group">
                    <i class="fas fa-play input-icon"></i>
                    <input 
                      type="date" 
                      v-model="form.fecha_inicio_2do_reporte"
                      class="input-text"
                      :class="{ 'error': errores.fecha_inicio_2do_reporte }"
                    />
                  </div>
                </div>
                <div class="date-separator">
                  <i class="fas fa-arrow-right"></i>
                </div>
                <div class="date-item">
                  <label>Fecha Final</label>
                  <div class="input-group">
                    <i class="fas fa-stop input-icon"></i>
                    <input 
                      type="date" 
                      v-model="form.fecha_final_2do_reporte"
                      class="input-text"
                      :class="{ 'error': errores.fecha_final_2do_reporte }"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Rango Reporte Final -->
            <div class="date-group">
              <label class="parrafo">
                <i class="fas fa-trophy"></i>
                Reporte Final
              </label>
              <div class="date-row">
                <div class="date-item">
                  <label>Fecha de Inicio</label>
                  <div class="input-group">
                    <i class="fas fa-play input-icon"></i>
                    <input 
                      type="date" 
                      v-model="form.fecha_inicio_reporte_final"
                      class="input-text"
                      :class="{ 'error': errores.fecha_inicio_reporte_final }"
                    />
                  </div>
                </div>
                <div class="date-separator">
                  <i class="fas fa-arrow-right"></i>
                </div>
                <div class="date-item">
                  <label>Fecha Final</label>
                  <div class="input-group">
                    <i class="fas fa-stop input-icon"></i>
                    <input 
                      type="date" 
                      v-model="form.fecha_final_reporte_final"
                      class="input-text"
                      :class="{ 'error': errores.fecha_final_reporte_final }"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Botones -->
            <div class="botones-container">
              <button 
                type="submit" 
                class="btn-actualizar"
                :disabled="cargando"
              >
                <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-save'"></i>
                {{ cargando ? 'Actualizando...' : 'Actualizar Período' }}
              </button>
              
              <Link :href="route('periodos.index')" class="btn-cancelar">
                <i class="fas fa-times"></i>
                Cancelar
              </Link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  periodo: {
    type: Object,
    required: true
  },
  flash: Object
})

// Formulario reactivo con los datos del período
const form = ref({
  nombre: props.periodo.nombre || '',
  fecha_inicio: props.periodo.fecha_inicio || '',
  fecha_final: props.periodo.fecha_final || '',
  fecha_inicio_1er_reporte: props.periodo.fecha_inicio_1er_reporte || '',
  fecha_final_1er_reporte: props.periodo.fecha_final_1er_reporte || '',
  fecha_inicio_2do_reporte: props.periodo.fecha_inicio_2do_reporte || '',
  fecha_final_2do_reporte: props.periodo.fecha_final_2do_reporte || '',
  fecha_inicio_reporte_final: props.periodo.fecha_inicio_reporte_final || '',
  fecha_final_reporte_final: props.periodo.fecha_final_reporte_final || ''
})

const cargando = ref(false)
const errores = ref({})

// Validar formulario
const validarFormulario = () => {
  const nuevosErrores = {}
  
  if (!form.value.nombre.trim()) {
    nuevosErrores.nombre = 'El nombre del período es requerido'
  }
  
  if (!form.value.fecha_inicio) {
    nuevosErrores.fecha_inicio = 'La fecha de inicio es requerida'
  }
  
  if (!form.value.fecha_final) {
    nuevosErrores.fecha_final = 'La fecha de conclusión es requerida'
  } else if (form.value.fecha_inicio && form.value.fecha_final <= form.value.fecha_inicio) {
    nuevosErrores.fecha_final = 'La fecha de conclusión debe ser posterior a la fecha de inicio'
  }
  
  // Validar rangos de reportes
  if (form.value.fecha_inicio_1er_reporte && form.value.fecha_final_1er_reporte) {
    if (form.value.fecha_final_1er_reporte < form.value.fecha_inicio_1er_reporte) {
      nuevosErrores.fecha_final_1er_reporte = 'La fecha final debe ser igual o posterior a la fecha de inicio'
    }
  }
  
  if (form.value.fecha_inicio_2do_reporte && form.value.fecha_final_2do_reporte) {
    if (form.value.fecha_final_2do_reporte < form.value.fecha_inicio_2do_reporte) {
      nuevosErrores.fecha_final_2do_reporte = 'La fecha final debe ser igual o posterior a la fecha de inicio'
    }
  }
  
  if (form.value.fecha_inicio_reporte_final && form.value.fecha_final_reporte_final) {
    if (form.value.fecha_final_reporte_final < form.value.fecha_inicio_reporte_final) {
      nuevosErrores.fecha_final_reporte_final = 'La fecha final debe ser igual o posterior a la fecha de inicio'
    }
  }
  
  errores.value = nuevosErrores
  return Object.keys(nuevosErrores).length === 0
}

// Actualizar período
const actualizarPeriodo = () => {
  if (!validarFormulario()) {
    Swal.fire({
      icon: 'error',
      title: 'Errores en el formulario',
      html: '<ul style="text-align: left;">' + 
        Object.values(errores.value).map(e => `<li><i class="fas fa-times"></i> ${e}</li>`).join('') + 
        '</ul>',
      confirmButtonText: 'Corregir'
    })
    return
  }
  
  cargando.value = true
  
  router.put(route('periodos.update', props.periodo.id), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Actualizado!',
        text: 'El período ha sido actualizado correctamente',
        confirmButtonText: 'OK'
      }).then(() => {
        router.visit(route('periodos.index'))
      })
    },
    onError: (errors) => {
      const nuevosErrores = {}
      if (errors.nombre) nuevosErrores.nombre = errors.nombre
      if (errors.fecha_inicio) nuevosErrores.fecha_inicio = errors.fecha_inicio
      if (errors.fecha_final) nuevosErrores.fecha_final = errors.fecha_final
      if (errors.fecha_inicio_1er_reporte) nuevosErrores.fecha_inicio_1er_reporte = errors.fecha_inicio_1er_reporte
      if (errors.fecha_final_1er_reporte) nuevosErrores.fecha_final_1er_reporte = errors.fecha_final_1er_reporte
      if (errors.fecha_inicio_2do_reporte) nuevosErrores.fecha_inicio_2do_reporte = errors.fecha_inicio_2do_reporte
      if (errors.fecha_final_2do_reporte) nuevosErrores.fecha_final_2do_reporte = errors.fecha_final_2do_reporte
      if (errors.fecha_inicio_reporte_final) nuevosErrores.fecha_inicio_reporte_final = errors.fecha_inicio_reporte_final
      if (errors.fecha_final_reporte_final) nuevosErrores.fecha_final_reporte_final = errors.fecha_final_reporte_final
      
      errores.value = nuevosErrores
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo actualizar el período. Verifica los campos.',
        confirmButtonText: 'OK'
      })
    },
    onFinish: () => {
      cargando.value = false
    }
  })
}
</script>

<style scoped>
.bodydiv {
  margin-left: 20px;
  margin-right: 20px;
  padding: 20px;
}

.horizontal {
  display: flex;
  justify-content: center;
  width: 100%;
}

.centro {
  display: flex;
  justify-content: center;
}

.subtitulo {
  text-align: center;
  font-size: 32px;
  font-weight: bold;
  margin: 20px 0;
  color: #333;
}

.subtitulo i {
  margin-right: 12px;
  color: #050E3C;
}

/* Tarjeta del formulario */
.form-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  padding: 40px;
  width: 100%;
  max-width: 700px;
  margin: 20px 0;
}

.formulario {
  width: 100%;
}

/* Grupos de formulario */
.form-group, .date-group {
  margin-bottom: 24px;
}

.parrafo {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 8px;
  display: block;
  color: #333;
}

.parrafo i {
  margin-right: 8px;
  color: #050E3C;
}

/* Inputs */
.input-group {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 12px;
  color: #999;
  font-size: 16px;
}

.input-text {
  width: 100%;
  padding: 12px 12px 12px 40px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.input-text:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.input-text.error {
  border-color: #dc3545;
}

/* Fechas en fila */
.date-row {
  display: flex;
  align-items: center;
  gap: 15px;
  flex-wrap: wrap;
}

.date-item {
  flex: 1;
  min-width: 180px;
}

.date-item label {
  font-size: 12px;
  color: #666;
  margin-bottom: 4px;
  display: block;
}

.date-separator {
  font-size: 20px;
  color: #050E3C;
}

/* Separador de sección */
.section-divider {
  display: flex;
  align-items: center;
  gap: 15px;
  margin: 30px 0 20px;
}

.section-divider hr {
  flex: 1;
  border: none;
  border-top: 1px solid #e0e0e0;
}

.section-divider span {
  font-size: 14px;
  font-weight: 600;
  color: #050E3C;
  white-space: nowrap;
}

.section-divider span i {
  margin-right: 6px;
}

/* Mensajes de error */
.error-mensaje {
  display: block;
  margin-top: 5px;
  font-size: 12px;
  color: #dc3545;
}

.error-mensaje i {
  margin-right: 4px;
}

/* Botones */
.botones-container {
  display: flex;
  gap: 15px;
  margin-top: 30px;
}

.btn-actualizar {
  flex: 1;
  background: linear-gradient(135deg, #050E3C 0%, #0a1a6e 100%);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.btn-actualizar:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(5, 14, 60, 0.3);
}

.btn-actualizar:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancelar {
  flex: 1;
  background-color: #6c757d;
  color: white;
  text-decoration: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  text-align: center;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.btn-cancelar:hover {
  background-color: #5a6268;
  text-decoration: none;
  color: white;
}

/* Responsive */
@media (max-width: 768px) {
  .form-card {
    padding: 25px;
  }
  
  .subtitulo {
    font-size: 24px;
  }
  
  .date-row {
    flex-direction: column;
    gap: 10px;
  }
  
  .date-separator {
    transform: rotate(90deg);
    align-self: center;
  }
  
  .date-item {
    width: 100%;
  }
  
  .botones-container {
    flex-direction: column;
  }
  
  .section-divider span {
    font-size: 12px;
  }
}
</style>