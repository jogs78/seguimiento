<template>
  <AppLayout>
    <div class="page-container">
      <!-- Título -->
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-key"></i>
          Cambiar contraseña
        </p>
      </div>

      <!-- Formulario -->
      <div class="contenedor-formulario">
        <div class="form-card">
          <form @submit.prevent="cambiarPassword" class="formulario">
            
            <!-- Contraseña actual -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-lock"></i>
                Contraseña actual
              </label>
              <div class="input-group">
                <i class="fas fa-key input-icon"></i>
                <input 
                  :type="mostrarActual ? 'text' : 'password'"
                  v-model="form.password_actual"
                  class="input-text"
                  :class="{ 'error': errores.password_actual }"
                  placeholder="Ingresa tu contraseña actual"
                  required
                />
                <button 
                  type="button"
                  class="toggle-password"
                  @click="mostrarActual = !mostrarActual"
                >
                  <i :class="mostrarActual ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <span v-if="errores.password_actual" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.password_actual }}
              </span>
            </div>

            <!-- Contraseña nueva -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-lock"></i>
                Contraseña nueva
              </label>
              <div class="input-group">
                <i class="fas fa-plus-circle input-icon"></i>
                <input 
                  :type="mostrarNueva ? 'text' : 'password'"
                  v-model="form.password"
                  class="input-text"
                  :class="{ 'error': errores.password }"
                  placeholder="Nueva contraseña (mínimo 6 caracteres)"
                  required
                />
                <button 
                  type="button"
                  class="toggle-password"
                  @click="mostrarNueva = !mostrarNueva"
                >
                  <i :class="mostrarNueva ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <div class="password-strength" v-if="form.password">
                <div class="strength-bar">
                  <div 
                    class="strength-level"
                    :class="fortalezaClase"
                    :style="{ width: fortalezaPorcentaje + '%' }"
                  ></div>
                </div>
                <span class="strength-text">{{ fortalezaTexto }}</span>
              </div>
              <span v-if="errores.password" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.password }}
              </span>
            </div>

            <!-- Confirmar contraseña -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-check-circle"></i>
                Confirmar contraseña
              </label>
              <div class="input-group">
                <i class="fas fa-repeat input-icon"></i>
                <input 
                  :type="mostrarConfirmacion ? 'text' : 'password'"
                  v-model="form.password_confirmation"
                  class="input-text"
                  :class="{ 'error': errores.password_confirmation }"
                  placeholder="Confirma tu nueva contraseña"
                  required
                />
                <button 
                  type="button"
                  class="toggle-password"
                  @click="mostrarConfirmacion = !mostrarConfirmacion"
                >
                  <i :class="mostrarConfirmacion ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <span v-if="errores.password_confirmation" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.password_confirmation }}
              </span>
              <span v-else-if="form.password && form.password_confirmation && form.password === form.password_confirmation" class="success-mensaje">
                <i class="fas fa-check-circle"></i>
                Las contraseñas coinciden
              </span>
            </div>

            <!-- Botones -->
            <div class="botones-container">
              <button 
                type="submit" 
                class="btn-cambiar"
                :disabled="cargando"
              >
                <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-save'"></i>
                {{ cargando ? 'Cambiando...' : 'Cambiar contraseña' }}
              </button>
              
              <Link :href="route('home')" class="btn-cancelar">
                <i class="fas fa-times"></i>
                Cancelar
              </Link>
            </div>
          </form>
        </div>
      </div>

      <!-- Información de seguridad -->
      <div class="centro">
        <div class="info-card">
          <i class="fas fa-shield-alt"></i>
          <div class="info-text">
            <strong>Consejos de seguridad:</strong>
            <ul>
              <li>Usa al menos 6 caracteres</li>
              <li>Combina letras mayúsculas, minúsculas y números</li>
              <li>No uses contraseñas que hayas usado antes</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  flash: Object
})

// Formulario
const form = ref({
  password_actual: '',
  password: '',
  password_confirmation: ''
})

const cargando = ref(false)
const errores = ref({})

// Mostrar/ocultar contraseñas
const mostrarActual = ref(false)
const mostrarNueva = ref(false)
const mostrarConfirmacion = ref(false)

// Fortaleza de contraseña
const fortalezaPorcentaje = ref(0)
const fortalezaTexto = ref('')
const fortalezaClase = ref('')

const calcularFortaleza = () => {
  const password = form.value.password
  if (!password) {
    fortalezaPorcentaje.value = 0
    fortalezaTexto.value = ''
    fortalezaClase.value = ''
    return
  }
  
  let fuerza = 0
  if (password.length >= 6) fuerza += 25
  if (password.length >= 8) fuerza += 25
  if (/[A-Z]/.test(password)) fuerza += 20
  if (/[0-9]/.test(password)) fuerza += 15
  if (/[^A-Za-z0-9]/.test(password)) fuerza += 15
  
  fortalezaPorcentaje.value = Math.min(fuerza, 100)
  
  if (fuerza < 40) {
    fortalezaTexto.value = 'Débil'
    fortalezaClase.value = 'weak'
  } else if (fuerza < 70) {
    fortalezaTexto.value = 'Media'
    fortalezaClase.value = 'medium'
  } else {
    fortalezaTexto.value = 'Fuerte'
    fortalezaClase.value = 'strong'
  }
}

watch(() => form.value.password, () => {
  calcularFortaleza()
})

// Cambiar contraseña
const cambiarPassword = () => {
  errores.value = {}
  
  // Validación local básica
  if (!form.value.password_actual) {
    errores.value.password_actual = 'La contraseña actual es requerida'
    return
  }
  
  if (!form.value.password) {
    errores.value.password = 'La nueva contraseña es requerida'
    return
  }
  
  if (form.value.password.length < 6) {
    errores.value.password = 'La contraseña debe tener al menos 6 caracteres'
    return
  }
  
  if (form.value.password !== form.value.password_confirmation) {
    errores.value.password_confirmation = 'Las contraseñas no coinciden'
    return
  }
  
  cargando.value = true
  
  router.post(route('usuario.cambiar-password'), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'Contraseña cambiada exitosamente',
        confirmButtonText: 'Ok'
      }).then(() => {
        router.visit(route('home'))
      })
    },
    onError: (errors) => {
      if (errors.password_actual) {
        errores.value.password_actual = errors.password_actual
      }
      if (errors.password) {
        errores.value.password = errors.password
      }
      if (errors.password_confirmation) {
        errores.value.password_confirmation = errors.password_confirmation
      }
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errors.message || 'No se pudo cambiar la contraseña',
        confirmButtonText: 'Ok'
      })
    },
    onFinish: () => {
      cargando.value = false
    }
  })
}

// Mostrar mensajes flash
if (props.flash?.error) {
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: props.flash.error,
    confirmButtonText: 'Ok'
  })
}
</script>

<style scoped>
.page-container {
  margin: 20px;
}

.horizontal {
  display: flex;
  justify-content: center;
  width: 100%;
}

.centro {
  display: flex;
  justify-content: center;
  margin-top: 30px;
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

.contenedor-formulario {
  display: flex;
  justify-content: center;
  align-items: center;
}

.form-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  padding: 40px;
  width: 100%;
  max-width: 500px;
}

.formulario {
  width: 100%;
}

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
  padding: 12px 40px 12px 40px;
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

.toggle-password {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  cursor: pointer;
  color: #999;
  font-size: 16px;
}

.toggle-password:hover {
  color: #333;
}

/* Fortaleza de contraseña */
.password-strength {
  margin-top: 8px;
}

.strength-bar {
  height: 4px;
  background-color: #e0e0e0;
  border-radius: 2px;
  overflow: hidden;
  margin-bottom: 4px;
}

.strength-level {
  height: 100%;
  transition: width 0.3s ease;
}

.strength-level.weak {
  background-color: #dc3545;
}

.strength-level.medium {
  background-color: #ffc107;
}

.strength-level.strong {
  background-color: #28a745;
}

.strength-text {
  font-size: 11px;
  color: #666;
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

.success-mensaje {
  display: block;
  margin-top: 5px;
  font-size: 12px;
  color: #28a745;
}

.success-mensaje i {
  margin-right: 4px;
}

/* Botones */
.botones-container {
  display: flex;
  gap: 15px;
  margin-top: 30px;
}

.btn-cambiar {
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

.btn-cambiar:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(5, 14, 60, 0.3);
}

.btn-cambiar:disabled {
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

/* Tarjeta de información */
.info-card {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  gap: 15px;
  max-width: 500px;
  width: 100%;
  border-left: 4px solid #ffc107;
}

.info-card i {
  font-size: 24px;
  color: #ffc107;
}

.info-text {
  color: #666;
  font-size: 14px;
}

.info-text strong {
  color: #333;
  display: block;
  margin-bottom: 8px;
}

.info-text ul {
  margin: 5px 0 0 20px;
  padding: 0;
}

.info-text li {
  margin-bottom: 4px;
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
}
</style>