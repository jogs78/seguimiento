<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-cogs"></i>
          Actualizar Configuración
        </p>
      </div>

      <div class="centro">
        <div class="form-card">
          <form @submit.prevent="actualizarConfiguracion" class="formulario">
            
            <!-- Campo: Variable -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-tag"></i>
                Variable
              </label>
              <div class="input-group">
                <i class="fas fa-code-branch input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.variable"
                  class="input-text"
                  :class="{ 'error': errores.variable }"
                  placeholder="Ej: periodo_id, max_estudiantes, etc."
                  required
                />
              </div>
              <span v-if="errores.variable" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.variable }}
              </span>
            </div>

            <!-- Campo: Valor -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-dollar-sign"></i>
                Valor
              </label>
              <div class="input-group">
                <i class="fas fa-value input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.valor"
                  class="input-text"
                  :class="{ 'error': errores.valor }"
                  placeholder="Ej: 2024, true, configuracion.json"
                  required
                />
              </div>
              <span v-if="errores.valor" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.valor }}
              </span>
            </div>

            <!-- Campo: Tipo -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-database"></i>
                Tipo
              </label>
              <div class="input-group">
                <i class="fas fa-type input-icon"></i>
                <select v-model="form.tipo" class="select" :class="{ 'error': errores.tipo }">
                  <option value="Numero">🔢 Número</option>
                  <option value="Cadena">📝 Cadena</option>
                  <option value="Bd">🗄️ Base de Datos</option>
                </select>
              </div>
              <span v-if="errores.tipo" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.tipo }}
              </span>
              <small class="help-text">
                <i class="fas fa-info-circle"></i>
                Selecciona "Base de Datos" si el valor se obtiene de una tabla/campo específico
              </small>
            </div>

            <!-- Campos adicionales para tipo BD -->
            <div v-if="form.tipo === 'Bd'" class="campos-bd">
              <div class="section-divider">
                <hr>
                <span><i class="fas fa-table"></i> Configuración de Base de Datos</span>
                <hr>
              </div>

              <div class="form-group">
                <label class="parrafo">
                  <i class="fas fa-table"></i>
                  Tabla
                </label>
                <div class="input-group">
                  <i class="fas fa-database input-icon"></i>
                  <input 
                    type="text" 
                    v-model="form.tabla"
                    class="input-text"
                    :class="{ 'error': errores.tabla }"
                    placeholder="Ej: periodos, usuarios, configuraciones"
                  />
                </div>
                <span v-if="errores.tabla" class="error-mensaje">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ errores.tabla }}
                </span>
                <small class="help-text">Nombre de la tabla en la base de datos</small>
              </div>

              <div class="form-group">
                <label class="parrafo">
                  <i class="fas fa-columns"></i>
                  Campo
                </label>
                <div class="input-group">
                  <i class="fas fa-field input-icon"></i>
                  <input 
                    type="text" 
                    v-model="form.campo"
                    class="input-text"
                    :class="{ 'error': errores.campo }"
                    placeholder="Ej: nombre, id, valor"
                  />
                </div>
                <span v-if="errores.campo" class="error-mensaje">
                  <i class="fas fa-exclamation-circle"></i>
                  {{ errores.campo }}
                </span>
                <small class="help-text">Nombre del campo dentro de la tabla</small>
              </div>
            </div>

            <!-- Información adicional según el tipo -->
            <div class="info-card" v-if="form.tipo">
              <i class="fas fa-lightbulb"></i>
              <div class="info-text">
                <strong>Información:</strong>
                <template v-if="form.tipo === 'Numero'">
                  Este valor será tratado como número entero o decimal.
                </template>
                <template v-else-if="form.tipo === 'Cadena'">
                  Este valor será tratado como texto plano.
                </template>
                <template v-else-if="form.tipo === 'Bd'">
                  Este valor se obtendrá dinámicamente desde la tabla <strong>{{ form.tabla || '?' }}</strong> 
                  en el campo <strong>{{ form.campo || '?' }}</strong>.
                </template>
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
                {{ cargando ? 'Actualizando...' : 'Actualizar Configuración' }}
              </button>
              
              <Link :href="route('configuraciones.index')" class="btn-cancelar">
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
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  configuracion: {
    type: Object,
    required: true
  },
  flash: Object
})

// Formulario reactivo con los datos de la configuración
const form = ref({
  variable: props.configuracion.variable || '',
  valor: props.configuracion.valor || '',
  tipo: props.configuracion.tipo || 'Cadena',
  tabla: props.configuracion.tabla || '',
  campo: props.configuracion.campo || ''
})

const cargando = ref(false)
const errores = ref({})

// Validar formulario localmente
const validarFormulario = () => {
  const nuevosErrores = {}
  
  if (!form.value.variable.trim()) {
    nuevosErrores.variable = 'La variable es requerida'
  }
  
  if (!form.value.valor.toString().trim()) {
    nuevosErrores.valor = 'El valor es requerido'
  }
  
  if (!form.value.tipo) {
    nuevosErrores.tipo = 'El tipo es requerido'
  }
  
  if (form.value.tipo === 'Bd') {
    if (!form.value.tabla.trim()) {
      nuevosErrores.tabla = 'La tabla es requerida para tipo Base de Datos'
    }
    if (!form.value.campo.trim()) {
      nuevosErrores.campo = 'El campo es requerido para tipo Base de Datos'
    }
  }
  
  errores.value = nuevosErrores
  return Object.keys(nuevosErrores).length === 0
}

// Actualizar configuración
const actualizarConfiguracion = () => {
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
  
  router.put(route('configuraciones.update', props.configuracion.id), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Actualizado!',
        text: 'La configuración ha sido actualizada correctamente',
        confirmButtonText: 'OK'
      }).then(() => {
        router.visit(route('configuraciones.index'))
      })
    },
    onError: (errors) => {
      // Procesar errores del backend
      const nuevosErrores = {}
      if (errors.variable) nuevosErrores.variable = errors.variable
      if (errors.valor) nuevosErrores.valor = errors.valor
      if (errors.tipo) nuevosErrores.tipo = errors.tipo
      if (errors.tabla) nuevosErrores.tabla = errors.tabla
      if (errors.campo) nuevosErrores.campo = errors.campo
      
      errores.value = nuevosErrores
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo actualizar la configuración. Verifica los campos.',
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
  color: #050E3C;
}

.subtitulo i {
  margin-right: 12px;
}

/* Tarjeta del formulario */
.form-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  padding: 40px;
  width: 100%;
  max-width: 600px;
  margin: 20px 0;
}

.formulario {
  width: 100%;
}

/* Grupos de formulario */
.form-group {
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

.input-text, .select {
  width: 100%;
  padding: 12px 12px 12px 40px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  transition: all 0.3s ease;
  background-color: white;
}

.input-text:focus, .select:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.input-text.error, .select.error {
  border-color: #dc3545;
}

.select {
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 12px center;
  background-size: 16px;
}

/* Campos BD */
.campos-bd {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 12px;
  margin-top: 10px;
  border: 1px solid #e9ecef;
}

.section-divider {
  display: flex;
  align-items: center;
  gap: 15px;
  margin: 0 0 20px 0;
}

.section-divider hr {
  flex: 1;
  border: none;
  border-top: 1px solid #dee2e6;
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

.help-text {
  display: block;
  margin-top: 5px;
  font-size: 12px;
  color: #6c757d;
}

.help-text i {
  margin-right: 4px;
}

/* Tarjeta de información */
.info-card {
  background-color: #e8f4fd;
  border-radius: 8px;
  padding: 15px;
  display: flex;
  gap: 12px;
  margin: 20px 0;
  border-left: 4px solid #050E3C;
}

.info-card i {
  font-size: 20px;
  color: #050E3C;
}

.info-text {
  font-size: 13px;
  color: #333;
  line-height: 1.5;
}

.info-text strong {
  display: block;
  margin-bottom: 5px;
  color: #050E3C;
}

/* Botones */
.botones-container {
  display: flex;
  gap: 15px;
  margin-top: 30px;
}

.btn-actualizar {
  flex: 1;
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
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
  
  .botones-container {
    flex-direction: column;
  }
  
  .section-divider span {
    font-size: 11px;
  }
}
</style>