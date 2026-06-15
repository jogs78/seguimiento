<template>
  <div class="wizard-container">
    <!-- Progress Steps -->
    <div class="wizard-steps">
      <div class="step" :class="{ active: currentStep >= 1, completed: currentStep > 1 }">
        <div class="step-number">1</div>
        <div class="step-label">Datos Personales</div>
      </div>
      <div class="step-line" :class="{ active: currentStep > 1 }"></div>
      
      <div class="step" :class="{ active: currentStep >= 2, completed: currentStep > 2 }">
        <div class="step-number">2</div>
        <div class="step-label">Información Académica</div>
      </div>
      <div class="step-line" :class="{ active: currentStep > 2 }"></div>
      
      <div class="step" :class="{ active: currentStep >= 3 }">
        <div class="step-number">3</div>
        <div class="step-label">Seguridad Social y Documentos</div>
      </div>
    </div>

    <!-- Formulario por pasos -->
    <form @submit.prevent="submit" class="registro-form">
      
      <!-- PASO 1: Datos Personales -->
      <div v-show="currentStep === 1" class="step-content">
        <h2 class="step-title">Datos Personales</h2>
        
        <div class="form-row three-cols">
          <div class="form-group">
            <label class="parrafo">Nombre(s) <span class="required">*</span></label>
            <input type="text" v-model="form.nombre" class="form-control" :class="{ 'is-invalid': form.errors.nombre }" placeholder="Ingresa tu nombre">
            <div v-if="form.errors.nombre" class="error-message">{{ form.errors.nombre }}</div>
          </div>

          <div class="form-group">
            <label class="parrafo">Apellido Paterno <span class="required">*</span></label>
            <input type="text" v-model="form.apellido_paterno" class="form-control" :class="{ 'is-invalid': form.errors.apellido_paterno }" placeholder="Primer apellido">
            <div v-if="form.errors.apellido_paterno" class="error-message">{{ form.errors.apellido_paterno }}</div>
          </div>

          <div class="form-group">
            <label class="parrafo">Apellido Materno</label>
            <input type="text" v-model="form.apellido_materno" class="form-control" :class="{ 'is-invalid': form.errors.apellido_materno }" placeholder="Segundo apellido">
            <div v-if="form.errors.apellido_materno" class="error-message">{{ form.errors.apellido_materno }}</div>
          </div>
        </div>

        <div class="form-row two-cols">
          <div class="form-group">
            <label class="parrafo">Correo Electrónico <span class="required">*</span></label>
            <input type="email" v-model="form.correo_electronico" class="form-control" :class="{ 'is-invalid': form.errors.correo_electronico }" placeholder="ejemplo@tuxtla.tecnm.mx">
            <div v-if="form.errors.correo_electronico" class="error-message">{{ form.errors.correo_electronico }}</div>
          </div>

          <div class="form-group">
            <label class="parrafo">Teléfono <span class="required">*</span></label>
            <input type="tel" v-model="form.telefono" class="form-control" :class="{ 'is-invalid': form.errors.telefono }" placeholder="10 dígitos">
            <div v-if="form.errors.telefono" class="error-message">{{ form.errors.telefono }}</div>
          </div>
        </div>

        <div class="form-row single-col">
          <div class="form-group">
            <label class="parrafo">Dirección <span class="required">*</span></label>
            <input type="text" v-model="form.direccion" class="form-control" :class="{ 'is-invalid': form.errors.direccion }" placeholder="Calle, número, colonia, ciudad">
            <div v-if="form.errors.direccion" class="error-message">{{ form.errors.direccion }}</div>
          </div>
        </div>

        <div class="form-row two-cols">
          <div class="form-group">
            <label class="parrafo">Contraseña <span class="required">*</span></label>
            <input type="password" v-model="form.contraseña" class="form-control" :class="{ 'is-invalid': form.errors.contraseña }" placeholder="Mínimo 8 caracteres">
            <div v-if="form.errors.contraseña" class="error-message">{{ form.errors.contraseña }}</div>
            <small class="form-text">La contraseña debe tener al menos 8 caracteres</small>
          </div>

          <div class="form-group">
            <label class="parrafo">Confirmar Contraseña <span class="required">*</span></label>
            <input type="password" v-model="confirmarContraseña" class="form-control" :class="{ 'is-invalid': passwordMismatch }" placeholder="Repite tu contraseña">
            <div v-if="passwordMismatch" class="error-message">Las contraseñas no coinciden</div>
          </div>
        </div>
      </div>

      <!-- PASO 2: Información Académica -->
      <div v-show="currentStep === 2" class="step-content">
        <h2 class="step-title">Información Académica</h2>
        
        <div class="form-row two-cols">
          <div class="form-group">
            <label class="parrafo">Número de Control <span class="required">*</span></label>
            <input type="text" v-model="form.numero_de_control" class="form-control" :class="{ 'is-invalid': form.errors.numero_de_control }" placeholder="Ej: A12345678">
            <div v-if="form.errors.numero_de_control" class="error-message">{{ form.errors.numero_de_control }}</div>
          </div>

          <div class="form-group">
            <label class="parrafo">Carrera <span class="required">*</span></label>
            <select v-model="form.carrera_id" class="form-control" :class="{ 'is-invalid': form.errors.carrera_id }">
              <option value="" disabled>Selecciona una carrera</option>
              <option v-for="carrera in carreras" :key="carrera.id" :value="carrera.id">{{ carrera.nombre }}</option>
            </select>
            <div v-if="form.errors.carrera_id" class="error-message">{{ form.errors.carrera_id }}</div>
          </div>
        </div>
      </div>

      <!-- PASO 3: Seguridad Social y Documentos -->
      <div v-show="currentStep === 3" class="step-content">
        <h2 class="step-title">Seguridad Social y Documentos</h2>
        
        <div class="form-row two-cols">
          <div class="form-group">
            <label class="parrafo">Institución de Seguridad Social <span class="required">*</span></label>
            <select v-model="form.institucion_seguridad_social" class="form-control" :class="{ 'is-invalid': form.errors.institucion_seguridad_social }">
              <option value="" disabled>Selecciona una opción</option>
              <option value="IMSS">IMSS</option>
              <option value="ISSSTE">ISSSTE</option>
              <option value="OTROS">OTROS</option>
            </select>
            <div v-if="form.errors.institucion_seguridad_social" class="error-message">{{ form.errors.institucion_seguridad_social }}</div>
          </div>

          <div class="form-group">
            <label class="parrafo">Número de Seguridad Social (NSS)</label>
            <input type="text" v-model="form.numero_de_seguridad_social" class="form-control" :class="{ 'is-invalid': form.errors.numero_de_seguridad_social }" placeholder="Ej: 12345678901">
            <div v-if="form.errors.numero_de_seguridad_social" class="error-message">{{ form.errors.numero_de_seguridad_social }}</div>
          </div>
        </div>

        <!-- Subida de documento según institución (CÓDIGO SIMPLIFICADO SIN DUPLICACIÓN) -->
        <div class="documento-section">
          <h3 class="documento-title">
            <i class="fas fa-file-upload"></i>
            Subir comprobante de afiliación
          </h3>
          
          <!-- Mensaje según institución seleccionada -->
          <div v-if="form.institucion_seguridad_social" class="documento-card">
            <p>
              <i class="fas fa-shield-alt"></i> 
              Sube tu comprobante del 
              <strong>{{ form.institucion_seguridad_social }}</strong>
            </p>
            <div class="file-upload-area">
              <input 
                type="file" 
                ref="fileInput"
                @change="handleFileChange"
                accept=".pdf,.jpg,.jpeg,.png" 
                class="file-input"
              />
              <button type="button" class="btn-upload" @click="abrirSelectorArchivo">
                <i class="fas fa-folder-open"></i> Seleccionar archivo
              </button>
              <span v-if="archivoSeleccionado" class="file-name">{{ archivoSeleccionado.name }}</span>
            </div>
            <div v-if="documentoSubido" class="documento-subido">
              <i class="fas fa-check-circle"></i> Documento subido: {{ documentoSubido }}
            </div>
          </div>

          <div v-if="!form.institucion_seguridad_social" class="info-message">
            <i class="fas fa-info-circle"></i> Selecciona una institución de seguridad social para subir tu comprobante
          </div>
        </div>
      </div>

      <!-- Botones de navegación -->
      <div class="wizard-buttons">
        <button v-if="currentStep > 1" type="button" class="btn-prev" @click="prevStep">
          <i class="fas fa-arrow-left"></i> Anterior
        </button>
        
        <button v-if="currentStep < totalSteps" type="button" class="btn-next" @click="nextStep" :disabled="!canGoNext">
          Siguiente <i class="fas fa-arrow-right"></i>
        </button>
        
        <button v-if="currentStep === totalSteps" type="submit" class="btn-submit" :disabled="form.processing || !canSubmit">
          <i class="fas" :class="form.processing ? 'fa-spinner fa-spin' : 'fa-save'"></i>
          {{ form.processing ? 'Registrando...' : 'Registrarse' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'


const props = defineProps({
  carreras: {
    type: Array,
    default: () => []
  },
  flash: {
    type: Object,
    default: () => ({})
  }
})

// Obtener page de forma segura
const page = usePage()

// Estado del wizard
const currentStep = ref(1)
const totalSteps = 3
const confirmarContraseña = ref('')
const archivoSeleccionado = ref(null)
const documentoSubido = ref(null)
const fileInput = ref(null)

// Formulario
const form = useForm({
  nombre: '',
  apellido_paterno: '',
  apellido_materno: '',
  correo_electronico: '',
  numero_de_control: '',
  telefono: '',
  carrera_id: '',
  direccion: '',
  institucion_seguridad_social: '',
  numero_de_seguridad_social: '',
  contraseña: '',
  documento_afiliacion: null
})

// Validaciones
const passwordMismatch = computed(() => {
  return form.contraseña && confirmarContraseña.value && form.contraseña !== confirmarContraseña.value
})

const canGoNext = computed(() => {
  if (currentStep.value === 1) {
    return form.nombre && form.apellido_paterno && form.correo_electronico && form.telefono && 
           form.direccion && form.contraseña && confirmarContraseña.value && !passwordMismatch.value
  }
  if (currentStep.value === 2) {
    return form.numero_de_control && form.carrera_id
  }
  return true
})

const canSubmit = computed(() => {
  return form.institucion_seguridad_social && form.numero_de_seguridad_social
})



// ✅ Watch seguro para form.errors
watch(
  () => form.errors,
  (errors) => {
    if (errors && Object.keys(errors).length > 0) {
      const primerError = Object.values(errors)[0]
      if (primerError) {
        Swal.fire({
          icon: 'error',
          title: 'Error de validación',
          text: Array.isArray(primerError) ? primerError[0] : primerError,
          confirmButtonText: 'OK'
        })
      }
    }
  },
  { deep: true }
)

// Navegación
const nextStep = () => {
  if (currentStep.value < totalSteps && canGoNext.value) {
    currentStep.value++
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

// Manejo de archivo
const abrirSelectorArchivo = () => {
  if (fileInput.value) {
    fileInput.value.click()
  }
}

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 5 * 1024 * 1024) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'El archivo no debe superar los 5MB',
        confirmButtonText: 'OK'
      })
      return
    }
    archivoSeleccionado.value = file
    documentoSubido.value = file.name
    form.documento_afiliacion = file
  }
}

// Enviar formulario
const submit = () => {
  if (passwordMismatch.value) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Las contraseñas no coinciden',
      confirmButtonText: 'OK'
    })
    return
  }
  
  const formData = new FormData()
  Object.keys(form).forEach(key => {
    if (form[key] !== null && form[key] !== undefined && key !== 'processing' && key !== 'errors') {
      formData.append(key, form[key])
    }
  })
  
 form.post('/estudiantes', {
  forceFormData: true,
  preserveScroll: true,

  onSuccess: () => {
    Swal.fire({
      icon: 'success',
      title: 'Registro exitoso',
      text: 'Tu cuenta fue creada correctamente'
    }).then(() => {
      window.location.href = route('Inicio_Sesion')
    })
  },

  onError: (errors) => {

    let mensaje = 'Verifica los datos'

    if (errors.numero_de_control) {
      mensaje = errors.numero_de_control
    }

    if (errors.correo_electronico) {
      mensaje = errors.correo_electronico
    }

    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: mensaje
    })
  }
})
}
</script>

<style scoped>
.wizard-container {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  max-width: 900px;
  margin: 0 auto;
}

/* Steps */
.wizard-steps {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 2rem;
  padding: 0 1rem;
}

.step {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  flex: 1;
}

.step-number {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #e9ecef;
  color: #6c757d;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  margin-bottom: 0.5rem;
  transition: all 0.3s;
}

.step.active .step-number {
  background-color: #050E3C;
  color: white;
}

.step.completed .step-number {
  background-color: #28a745;
  color: white;
}

.step-label {
  font-size: 0.85rem;
  color: #6c757d;
}

.step.active .step-label {
  color: #050E3C;
  font-weight: 600;
}

.step-line {
  flex: 1;
  height: 2px;
  background-color: #e9ecef;
  margin: 0 0.5rem;
}

.step-line.active {
  background-color: #28a745;
}

/* Step Content */
.step-content {
  animation: fadeIn 0.3s ease;
}

.step-title {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #050E3C;
  border-left: 4px solid #050E3C;
  padding-left: 1rem;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateX(20px); }
  to { opacity: 1; transform: translateX(0); }
}

/* Documentos */
.documento-section {
  margin-top: 2rem;
  padding-top: 1rem;
  border-top: 1px solid #e9ecef;
}

.documento-title {
  font-size: 1.2rem;
  margin-bottom: 1rem;
  color: #333;
}

.documento-card {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 1rem;
  margin-top: 1rem;
}

.file-upload-area {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
  margin-top: 0.5rem;
}

.file-input {
  display: none;
}

.btn-upload {
  background-color: #6c757d;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
}

.btn-upload:hover {
  background-color: #5a6268;
}

.file-name {
  font-size: 0.85rem;
  color: #28a745;
}

.documento-subido {
  margin-top: 0.5rem;
  padding: 0.5rem;
  background-color: #d4edda;
  border-radius: 6px;
  color: #155724;
  font-size: 0.85rem;
}

.info-message {
  background-color: #e8f4fd;
  padding: 1rem;
  border-radius: 8px;
  margin-top: 1rem;
  color: #050E3C;
  text-align: center;
}

/* Botones de navegación */
.wizard-buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 2rem;
  padding-top: 1rem;
  border-top: 1px solid #e9ecef;
}

.btn-prev, .btn-next, .btn-submit {
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s;
  border: none;
}

.btn-prev {
  background-color: #6c757d;
  color: white;
}

.btn-prev:hover {
  background-color: #5a6268;
}

.btn-next {
  background-color: #050E3C;
  color: white;
}

.btn-next:hover:not(:disabled) {
  background-color: #0a1a6e;
  transform: translateX(2px);
}

.btn-submit {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-next:disabled, .btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Formulario */
.form-row {
  display: grid;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.single-col { grid-template-columns: 1fr; }
.two-cols { grid-template-columns: 1fr 1fr; }
.three-cols { grid-template-columns: 1fr 1fr 1fr; }

.form-group {
  display: flex;
  flex-direction: column;
}

.parrafo {
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #333;
}

.required {
  color: #dc3545;
}

.form-control {
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.3s;
}

.form-control:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.form-control.is-invalid {
  border-color: #dc3545;
}

.error-message {
  color: #dc3545;
  font-size: 0.75rem;
  margin-top: 0.25rem;
}

.form-text {
  font-size: 0.7rem;
  color: #6c757d;
  margin-top: 0.25rem;
}

@media (max-width: 768px) {
  .two-cols, .three-cols {
    grid-template-columns: 1fr;
  }
  
  .wizard-steps {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .step-line {
    width: 2px;
    height: 20px;
  }
  
  .step {
    flex-direction: row;
    gap: 1rem;
    width: 100%;
  }
  
  .wizard-buttons {
    flex-direction: column;
    gap: 1rem;
  }
  
  .btn-prev, .btn-next, .btn-submit {
    width: 100%;
  }
}
</style>