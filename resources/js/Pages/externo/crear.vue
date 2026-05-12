<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-user-plus"></i>
          Registrar un Asesor Externo
        </p>
      </div>

      <div class="centro">
        <div class="form-card">
          <form @submit.prevent="registrarAsesorExterno" class="formulario">
            
            <!-- Campo: Título -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-tag"></i>
                Título (Abreviatura)
              </label>
              <div class="input-group">
                <i class="fas fa-medal input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.titulo"
                  class="input-text"
                  :class="{ 'error': errores.titulo }"
                  placeholder="Ej: Dr., Mtro., Ing., Lic."
                />
              </div>
              <span v-if="errores.titulo" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.titulo }}
              </span>
              <small class="help-text">Título académico o profesional (opcional)</small>
            </div>

            <!-- Campo: Nombre -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-user"></i>
                Nombre(s) <span class="required">*</span>
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
                Apellido Paterno <span class="required">*</span>
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
                Correo Electrónico <span class="required">*</span>
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

            <!-- Campo: Puesto -->
            <div class="form-group">
              <label class="parrafo">
                <i class="fas fa-briefcase"></i>
                Puesto
              </label>
              <div class="input-group">
                <i class="fas fa-building input-icon"></i>
                <input 
                  type="text" 
                  v-model="form.puesto"
                  class="input-text"
                  :class="{ 'error': errores.puesto }"
                  placeholder="Ej: Gerente, Director, Supervisor"
                />
              </div>
              <span v-if="errores.puesto" class="error-mensaje">
                <i class="fas fa-exclamation-circle"></i>
                {{ errores.puesto }}
              </span>
              <small class="help-text">Puesto que ocupa en la empresa/organización</small>
            </div>

            <!-- Información adicional -->
            <div class="info-card">
              <i class="fas fa-info-circle"></i>
              <div class="info-text">
                <strong>Información importante:</strong>
                <ul>
                  <li>Al registrar un asesor externo, se creará automáticamente una cuenta de usuario</li>
                  <li>El asesor recibirá sus credenciales en su correo electrónico</li>
                  <li>La contraseña inicial será su correo electrónico</li>
                </ul>
              </div>
            </div>

            <!-- Botones -->
            <div class="botones-container">
              <button 
                type="submit" 
                class="btn-registrar"
                :disabled="cargando"
              >
                <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-save'"></i>
                {{ cargando ? 'Registrando...' : 'Registrar Asesor Externo' }}
              </button>
              
              <Link :href="route('externos.index')" class="btn-cancelar">
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
  titulo: '',
  nombre: '',
  apellido_paterno: '',
  apellido_materno: '',
  correo_electronico: '',
  puesto: ''
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

// Registrar asesor externo
const registrarAsesorExterno = () => {
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
  
  router.post(route('externos.store'), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Registrado!',
        text: 'El asesor externo ha sido registrado correctamente. Se ha creado su cuenta de usuario.',
        confirmButtonText: 'OK'
      }).then(() => {
        router.visit(route('externos.index'))
      })
    },
    onError: (errors) => {
      const nuevosErrores = {}
      if (errors.titulo) nuevosErrores.titulo = errors.titulo
      if (errors.nombre) nuevosErrores.nombre = errors.nombre
      if (errors.apellido_paterno) nuevosErrores.apellido_paterno = errors.apellido_paterno
      if (errors.apellido_materno) nuevosErrores.apellido_materno = errors.apellido_materno
      if (errors.correo_electronico) nuevosErrores.correo_electronico = errors.correo_electronico
      if (errors.puesto) nuevosErrores.puesto = errors.puesto
      
      errores.value = nuevosErrores
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: Object.values(errors)[0] || 'No se pudo registrar el asesor externo. Verifica los campos.',
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
  max-width: 650px;
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

.required {
  color: #dc3545;
  margin-left: 4px;
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

/* Tarjeta de información */
.info-card {
  background-color: #e8f4fd;
  border-radius: 12px;
  padding: 18px;
  display: flex;
  gap: 15px;
  margin: 25px 0;
  border-left: 4px solid #050E3C;
}

.info-card i {
  font-size: 24px;
  color: #050E3C;
}

.info-text {
  flex: 1;
  font-size: 13px;
  color: #333;
}

.info-text strong {
  display: block;
  margin-bottom: 8px;
  color: #050E3C;
}

.info-text ul {
  margin: 5px 0 0 20px;
  padding: 0;
}

.info-text li {
  margin: 5px 0;
}

/* Botones */
.botones-container {
  display: flex;
  gap: 15px;
  margin-top: 15px;
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
  
  .info-card {
    flex-direction: column;
  }
}
</style>