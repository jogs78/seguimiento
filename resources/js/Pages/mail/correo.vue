<template>
  <AppLayout>
    <div class="bodydiv">
      <!-- Título con icono -->
      <div class="centro">
        <div class="titulo-container">
          <i class="fas fa-envelope-open-text"></i>
          <h1 class="titulo">
            Enviar Correo a 
            <span class="nombre-destinatario">
              <i class="fas fa-user-circle"></i>
              {{ nombreCompleto }}
            </span>
          </h1>
        </div>
      </div>

      <!-- Formulario -->
      <div class="centro">
        <div class="formulario-container">
          <form @submit.prevent="enviarCorreo" class="formulario">
            
            <!-- Campo correo destino -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-envelope"></i>
                Correo destino
              </label>
              <div class="input-group">
                <i class="fas fa-at input-icon"></i>
                <input 
                  type="email" 
                  :value="usuario.correo" 
                  disabled
                  class="input-disabled"
                />
              </div>
            </div>

            <!-- Campo asunto -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-tag"></i>
                Asunto
              </label>
              <div class="input-group">
                <i class="fas fa-pencil-alt input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.subject"
                  class="input-text"
                  :class="{ 'error': errores.subject }"
                  placeholder="Escribe el asunto del correo..."
                  required
                />
              </div>
              <span v-if="errores.subject" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.subject }}
              </span>
            </div>

            <!-- Campo contenido -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-file-alt"></i>
                Contenido
              </label>
              <div class="textarea-group">
                <textarea 
                  v-model="form.content"
                  class="textarea-contenido"
                  :class="{ 'error': errores.content }"
                  rows="8"
                  placeholder="Escribe el mensaje que deseas enviar..."
                  required
                ></textarea>
              </div>
              <div class="caracteres-info">
                <i class="fas fa-info-circle"></i>
                {{ form.content.length }} caracteres
              </div>
              <span v-if="errores.content" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.content }}
              </span>
            </div>

            <!-- Botones de acción -->
            <div class="botones-container">
              <button 
                type="submit" 
                class="btn-enviar"
                :disabled="enviando"
              >
                <i class="fas" :class="enviando ? 'fa-spinner fa-pulse' : 'fa-paper-plane'"></i>
                {{ enviando ? 'Enviando...' : 'Enviar Correo' }}
              </button>
              
              <Link :href="route('home')" class="btn-cancelar">
                <i class="fas fa-times"></i>
                Cancelar
              </Link>
            </div>
          </form>
        </div>
      </div>

      <!-- Información adicional -->
      <div class="centro">
        <div class="info-card">
          <i class="fas fa-lightbulb"></i>
          <div class="info-text">
            <strong>Consejo:</strong> Revisa bien el contenido antes de enviar. 
            El destinatario recibirá este correo en su bandeja de entrada.
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'


const props = defineProps({
  usuario: {
    type: Object,
    required: true
  },
  remitente: {
    type: Object,
    default: () => ({ tipo: 'Sistema', nombre: 'Sistema de Residencias' })
  },
  flash: Object
})

// Formulario reactivo
const form = ref({
  email: props.usuario.correo,
  subject: '',
  content: '',
  destinatario_nombre: props.usuario.nombre_completo || props.usuario.nombre
})

const enviando = ref(false)
const errores = ref({})

// Computed para nombre completo
const nombreCompleto = computed(() => {
  if (props.usuario.nombre) {
    return `${props.usuario.nombre} ${props.usuario.apellido_paterno || ''} ${props.usuario.apellido_materno || ''}`.trim()
  }
  return 'Usuario'
})

// Validar formulario
const validarFormulario = () => {
  const nuevosErrores = {}
  
  if (!form.value.subject.trim()) {
    nuevosErrores.subject = 'El asunto es requerido'
  }
  
  if (!form.value.content.trim()) {
    nuevosErrores.content = 'El contenido es requerido'
  } else if (form.value.content.length < 10) {
    nuevosErrores.content = 'El contenido debe tener al menos 10 caracteres'
  }
  
  errores.value = nuevosErrores
  return Object.keys(nuevosErrores).length === 0
}

// Enviar correo
const enviarCorreo = () => {
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
  
  enviando.value = true
  
 router.post(route('correo.send'), {
    email: form.value.email,
    subject: form.value.subject,
    content: form.value.content,
    destinatario_nombre: form.value.destinatario_nombre
  }, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Correo enviado!',
        text: 'El mensaje se ha enviado correctamente',
        confirmButtonText: 'OK'
      }).then(() => {
        router.visit(route('home'))
      })
    },
    onError: (errors) => {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errors.message || 'No se pudo enviar el correo',
        confirmButtonText: 'OK'
      })
    },
    onFinish: () => {
      enviando.value = false
    }
  })
}

// Mostrar mensajes flash
if (props.flash?.error) {
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text: props.flash.error,
    confirmButtonText: 'OK'
  })
}
</script>

<style scoped>
.bodydiv {
  margin-left: 20px;
  margin-right: 20px;
  padding: 20px;
}

.centro {
  display: flex;
  justify-content: center;
  margin-bottom: 1.5rem;
}

/* Título */
.titulo-container {
  text-align: center;
  margin: 30px 0;
}

.titulo-container i {
  font-size: 48px;
  color: #050E3C;
  margin-bottom: 15px;
}

.titulo {
  font-size: 28px;
  font-weight: bold;
  color: #333;
  margin: 0;
}

.nombre-destinatario {
  background: linear-gradient(135deg, #050E3C 0%, #0a1a6e 100%);
  color: white;
  padding: 5px 15px;
  border-radius: 25px;
  display: inline-block;
  font-size: 24px;
}

.nombre-destinatario i {
  font-size: 20px;
  color: #ffd700;
  margin-right: 8px;
}

/* Formulario */
.formulario-container {
  width: 100%;
  max-width: 800px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  padding: 30px;
}

.formulario {
  width: 100%;
}

.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 1.5rem;
}

.parrafo {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 8px;
  color: #333;
  display: flex;
  align-items: center;
  gap: 8px;
}

.parrafo i {
  color: #050E3C;
  width: 20px;
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
  font-size: 14px;
}

.input-text {
  width: 100%;
  padding: 12px 12px 12px 38px;
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

.input-disabled {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  background-color: #f5f5f5;
  color: #666;
  cursor: not-allowed;
}

/* Textarea */
.textarea-group {
  width: 100%;
}

.textarea-contenido {
  width: 100%;
  padding: 12px;
  font-size: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
  resize: vertical;
  font-family: inherit;
  transition: all 0.3s ease;
}

.textarea-contenido:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.textarea-contenido.error {
  border-color: #dc3545;
}

.caracteres-info {
  margin-top: 5px;
  font-size: 12px;
  color: #666;
}

.caracteres-info i {
  margin-right: 4px;
}

/* Botones */
.botones-container {
  display: flex;
  gap: 15px;
  margin-top: 30px;
}

.btn-enviar {
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

.btn-enviar:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-enviar:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-cancelar {
  flex: 1;
  background: #6c757d;
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
  background: #5a6268;
  text-decoration: none;
  color: white;
}

/* Error messages */
.error-mensaje {
  margin-top: 5px;
  font-size: 12px;
  color: #dc3545;
  display: flex;
  align-items: center;
  gap: 4px;
}

/* Información adicional */
.info-card {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 15px 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  max-width: 800px;
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
}

/* Responsive */
@media (max-width: 768px) {
  .formulario-container {
    padding: 20px;
  }
  
  .titulo {
    font-size: 20px;
  }
  
  .nombre-destinatario {
    font-size: 18px;
    display: block;
    margin-top: 10px;
  }
  
  .botones-container {
    flex-direction: column;
  }
}
</style>