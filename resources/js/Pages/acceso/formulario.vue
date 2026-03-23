<template>
  <div class="inicio">
    <div class="container" id="container">
    

      <!-- Panel de inicio de sesión (sign-in) - Aquí va tu lógica -->
      <div class="form-container sign-in">
        <form @submit.prevent="submit">
          <h1 id="titulo">Inicia Sesión</h1>

          

          <!-- Campo: Correo (nombre) -->
          <div v-if="$page.props.flash?.errorsesion" class="alert alert-danger">
            {{ $page.props.flash.errorsesion }}
          </div>
          <input
            type="text"
            v-model="form.nombre"
            placeholder="Correo"
            required
          >
          <span class="error-message" v-if="form.errors.nombre">
            {{ form.errors.nombre }}
          </span>

          <!-- Campo: Contraseña (contra) -->
          <div v-if="$page.props.flash?.errorcontra" class="alert alert-danger">
            {{ $page.props.flash.errorcontra }}
          </div>
          <input
            type="password"
            v-model="form.contra"
            placeholder="Contraseña"
            required
          >
          <span class="error-message" v-if="form.errors.contra">
            {{ form.errors.contra }}
          </span>

          <a href="#"><h3 id="forget">¿Olvidaste tu contraseña?</h3></a>
          <button type="submit" :disabled="form.processing">
            {{ form.processing ? 'Enviando...' : 'Entrar' }}
          </button>
        </form>
      </div>

      <!-- Panel de toggle (parte azul) -->
      <div class="toggle-container">
        <div class="toggle">
        
          <div class="toggle-panel toggle-right">
            <img id="logo" src="../../../../storage/app/public/img/logo.png" alt="Logo">
            <h1>Seguimiento de Residencias</h1>
            <p>Ingresa con tu correo institucional</p>
            <div class="login-text">
              <p>
                ¿Aún no tienes una cuenta?
                <Link :href="route('estudiantes.create')" class="nav-link register">
                  Regístrate
                </Link>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'

// Activar/desactivar modo debug , quitar despues
const debug = ref(true)

const form = useForm({
  nombre: '',
  contra: ''
})

const submit = () => {
  console.log(' Enviando formulario con datos:', form.data())

  form.post('/adentro', {
    preserveScroll: true,
    onStart: () => console.log('Iniciando envío...'),
    onSuccess: (page) => {
      console.log(' Éxito!', page)
      form.reset('contra')
    },
    onError: (errors) => {
      console.log(' Errores del servidor:', errors)
      console.log(' Flash messages:', $page?.props?.flash)
    },
    onFinish: () => console.log(' Envío completado')
  })
}
</script>

<style scoped>

.error-message {
  color: #dc3545;
  font-size: 12px;
  margin-top: 4px;
  display: block;
  text-align: left;
  width: 100%;
}

.alert-danger {
  background-color: #f8d7da;
  color: #721c24;
  padding: 8px;
  border-radius: 8px;
  margin-bottom: 10px;
  font-size: 13px;
  width: 100%;
  text-align: center;
}

.debug-info {
  background-color: #f0f0f0;
  border: 1px solid #ccc;
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 15px;
  font-size: 12px;
  text-align: left;
  width: 100%;
  max-height: 150px;
  overflow: auto;
}

.debug-info pre {
  margin: 5px 0;
  white-space: pre-wrap;
}
</style>