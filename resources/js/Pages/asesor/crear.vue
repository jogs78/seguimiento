<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-user-plus"></i>
          Registrar un Asesor Interno
        </p>
      </div>

      <div class="centro">
        <div class="form-card">
          <form @submit.prevent="registrarAsesor" class="formulario">
            
            <!-- Campo: Nombre -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-user"></i>
                Nombre(s)
              </label>
              <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.nombre"
                  class="input-text"
                  :class="{ 'error': errores.nombre }"
                  placeholder="Ingrese el nombre del asesor"
                  required
                />
              </div>
              <span v-if="errores.nombre" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.nombre }}
              </span>
            </div>

            <!-- Campo: Apellido Paterno -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-user-tag"></i>
                Apellido Paterno
              </label>
              <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.apellido_paterno"
                  class="input-text"
                  :class="{ 'error': errores.apellido_paterno }"
                  placeholder="Ingrese el apellido paterno"
                  required
                />
              </div>
              <span v-if="errores.apellido_paterno" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.apellido_paterno }}
              </span>
            </div>

            <!-- Campo: Apellido Materno -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-user-tag"></i>
                Apellido Materno
              </label>
              <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.apellido_materno"
                  class="input-text"
                  :class="{ 'error': errores.apellido_materno }"
                  placeholder="Ingrese el apellido materno (opcional)"
                />
              </div>
              <span v-if="errores.apellido_materno" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.apellido_materno }}
              </span>
            </div>

            <!-- Campo: Correo Electrónico -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-envelope"></i>
                Correo Electrónico
              </label>
              <div class="input-group">
                <i class="fas fa-envelope input-icon"></i>
                <input 
                  type="email" 
                  v-model="form.correo_electronico"
                  class="input-text"
                  :class="{ 'error': errores.correo_electronico }"
                  placeholder="ejemplo@dominio.com"
                  required
                />
              </div>
              <span v-if="errores.correo_electronico" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.correo_electronico }}
              </span>
              <small class="help-text">Este correo se usará para iniciar sesión en el sistema</small>
            </div>

            <!-- Campo: Profesión -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-briefcase"></i>
                Profesión
              </label>
              <div class="input-group">
                <i class="fas fa-graduation-cap input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.profesion"
                  class="input-text"
                  :class="{ 'error': errores.profesion }"
                  placeholder="Ej: Ingeniero, Licenciado, Maestro, Doctor"
                />
              </div>
              <span v-if="errores.profesion" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.profesion }}
              </span>
            </div>

            <!-- Campo: Carrera -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-graduation-cap"></i>
                Carrera
              </label>
              <div class="input-group">
                <i class="fas fa-university input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.carrera"
                  class="input-text"
                  :class="{ 'error': errores.carrera }"
                  placeholder="Ej: Ingeniería en Sistemas, Contaduría"
                />
              </div>
              <span v-if="errores.carrera" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.carrera }}
              </span>
            </div>

            <!-- Campo: Número de Cédula -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-id-card"></i>
                Número de Cédula
              </label>
              <div class="input-group">
                <i class="fas fa-id-card input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.numero_cedula"
                  class="input-text"
                  :class="{ 'error': errores.numero_cedula }"
                  placeholder="Ej: 12345678"
                />
              </div>
              <span v-if="errores.numero_cedula" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.numero_cedula }}
              </span>
              <small class="help-text">Número de cédula profesional (opcional pero recomendado)</small>
            </div>

            <!-- Botones -->
            <div class="botones-container">
              <button 
                type="submit" 
                class="btn-registrar"
                :disabled="cargando"
              >
                <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-save'"></i>
                {{ cargando ? 'Registrando...' : 'Registrar Asesor' }}
              </button>
              
              <Link :href="route('asesores.index')" class="btn-cancelar">
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
  flash: Object
})

// Formulario reactivo
const form = ref({
  nombre: '',
  apellido_paterno: '',
  apellido_materno: '',
  correo_electronico: '',
  profesion: '',
  carrera: '',
  numero_cedula: ''
})

const cargando = ref(false)
const errores = ref({})

// Validar formulario localmente
const validarFormulario = () => {
  const nuevosErrores = {}
  
  if (!form.value.nombre.trim()) {
    nuevosErrores.nombre = 'El nombre es requerido'
  }
  
  if (!form.value.apellido_paterno.trim()) {
    nuevosErrores.apellido_paterno = 'El apellido paterno es requerido'
  }
  
  if (!form.value.correo_electronico.trim()) {
    nuevosErrores.correo_electronico = 'El correo electrónico es requerido'
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.correo_electronico)) {
    nuevosErrores.correo_electronico = 'Ingrese un correo electrónico válido'
  }
  
  errores.value = nuevosErrores
  return Object.keys(nuevosErrores).length === 0
}

// Registrar asesor
const registrarAsesor = () => {
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
  errores.value = {}
  
  router.post(route('asesores.store'), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Registrado!',
        text: 'El asesor interno ha sido registrado correctamente',
        confirmButtonText: 'OK'
      }).then(() => {
        router.visit(route('asesores.index'))
      })
    },
    onError: (errors) => {
      const nuevosErrores = {}
      if (errors.nombre) nuevosErrores.nombre = errors.nombre
      if (errors.apellido_paterno) nuevosErrores.apellido_paterno = errors.apellido_paterno
      if (errors.apellido_materno) nuevosErrores.apellido_materno = errors.apellido_materno
      if (errors.correo_electronico) nuevosErrores.correo_electronico = errors.correo_electronico
      if (errors.profesion) nuevosErrores.profesion = errors.profesion
      if (errors.carrera) nuevosErrores.carrera = errors.carrera
      if (errors.numero_cedula) nuevosErrores.numero_cedula = errors.numero_cedula
      
      errores.value = nuevosErrores
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: Object.values(errors)[0] || 'No se pudo registrar el asesor. Verifica los campos.',
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

/* Mensajes de ayuda y error */
.help-text {
  display: block;
  margin-top: 5px;
  font-size: 12px;
  color: #6c757d;
}

.help-text i {
  margin-right: 4px;
}

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

.btn-registrar {
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

.btn-registrar:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-registrar:disabled {
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
}
</style>