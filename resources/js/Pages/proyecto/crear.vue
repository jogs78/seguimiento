<template>
  <AppLayout>
    <div class="bodydiv">
      <div class="horizontal">
        <p class="subtitulo">
          <i class="fas fa-project-diagram"></i>
          Proyecto
        </p>
      </div>

      <!-- Sección: Unirse a proyecto existente -->
      <div class="card-section">
        <div class="card-header">
          <i class="fas fa-users"></i>
          Unirse a un Proyecto ya registrado
        </div>
        <div class="card-body">
          <form @submit.prevent="unirseProyecto" class="form-inline">
            <div class="form-row">
              <div class="form-group">
                <label>ID del Proyecto</label>
                <input type="number" v-model="unirseForm.id" class="input-text" required />
              </div>
              <div class="form-group" style="position: relative;">
                <label>Nombre del Proyecto</label>
                <input 
                  type="text" 
                  v-model="unirseForm.nombre"
                  @input="buscarSugerencias"
                  @blur="cerrarSugerencias"
                  class="input-text"
                  autocomplete="off"
                  required
                />
                <div v-if="sugerencias.length > 0" class="sugerencias-box">
                  <div 
                    v-for="sug in sugerencias" 
                    :key="sug"
                    class="sugerencia-item"
                    @click="seleccionarSugerencia(sug)"
                  >
                    {{ sug }}
                  </div>
                </div>
              </div>
            </div>
            <div class="form-row">
              <button type="submit" class="btn-unirse">
                <i class="fas fa-handshake"></i>
                Unirse al Proyecto
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Sección: Registrar nuevo proyecto -->
      <div class="card-section">
        <div class="card-header">
          <i class="fas fa-plus-circle"></i>
          Registrar un proyecto
        </div>
        <div class="card-body">
          <form @submit.prevent="registrarProyecto" class="form-grid">
            <!-- Datos del proyecto -->
            <div class="form-group full-width">
              <label>Nombre del proyecto <span class="required">*</span></label>
              <input type="text" v-model="form.nombre" class="input-text" required />
              <span v-if="errores.nombre" class="error">{{ errores.nombre }}</span>
            </div>

            <div class="form-group full-width">
              <label>Objetivo General <span class="required">*</span></label>
              <input type="text" v-model="form.objetivo_general" class="input-text" required />
              <span v-if="errores.objetivo_general" class="error">{{ errores.objetivo_general }}</span>
            </div>

            <div class="form-group">
              <label>Lugar <span class="required">*</span></label>
              <input type="text" v-model="form.lugar" class="input-text" required />
            </div>

            <div class="form-group">
              <label>Información</label>
              <input type="text" v-model="form.informacion" class="input-text" />
            </div>

            <div class="form-group full-width">
              <label>Justificación</label>
              <textarea v-model="form.justificacion" rows="4" class="textarea"></textarea>
            </div>

            <!-- Asesor Interno -->
            <div class="form-group">
              <label>Propón a tu Asesor Interno <span class="required">*</span></label>
              <select v-model="form.asesor_id" class="select">
                <option v-for="asesor in asesores" :key="asesor.id" :value="asesor.id">
                  {{ asesor.nombre }} {{ asesor.apellido_paterno }} {{ asesor.apellido_materno }}
                </option>
              </select>
            </div>

            <!-- Asesor Externo -->
            <div class="form-group">
              <label>Correo del Asesor Externo</label>
              <input type="email" v-model="form.correo_ae" class="input-text" />
            </div>

            <div class="form-group">
              <label>Título del Asesor Externo</label>
              <input type="text" v-model="form.titulo_ae" class="input-text" placeholder="Ej: Dr., Mtro., Ing." />
            </div>

            <div class="form-group">
              <label>Nombre del Asesor Externo</label>
              <input type="text" v-model="form.nombre_ae" class="input-text" />
            </div>

            <div class="form-group">
              <label>Apellido paterno</label>
              <input type="text" v-model="form.apellido_paterno_ae" class="input-text" />
            </div>

            <div class="form-group">
              <label>Apellido materno</label>
              <input type="text" v-model="form.apellido_materno_ae" class="input-text" />
            </div>

            <div class="form-group">
              <label>Puesto del Asesor Externo</label>
              <input type="text" v-model="form.puesto_ae" class="input-text" />
            </div>

            <!-- Empresa -->
            <div class="form-group">
              <label>Empresa <span class="required">*</span></label>
              <select v-model="form.empresa_id" class="select" @change="onEmpresaChange">
                <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                  {{ empresa.nombre }}
                </option>
                <option value="-1">LA EMPRESA NO ESTÁ DADA DE ALTA</option>
              </select>
            </div>

            <!-- Datos de nueva empresa (se muestra solo si empresa_id == -1) -->
            <div v-if="form.empresa_id == '-1'" class="nueva-empresa">
              <h4><i class="fas fa-building"></i> Datos de la nueva empresa</h4>
              
              <div class="form-group">
                <label>Nombre de la Empresa <span class="required">*</span></label>
                <input type="text" v-model="form.nombre_e" class="input-text" />
              </div>

              <div class="form-group">
                <label>Giro, Ramo o Sector <span class="required">*</span></label>
                <div class="radio-group">
                  <label><input type="radio" value="industrial" v-model="form.giro" /> Industrial</label>
                  <label><input type="radio" value="servicios" v-model="form.giro" /> Servicios</label>
                  <label><input type="radio" value="publico" v-model="form.giro" /> Público</label>
                  <label><input type="radio" value="privado" v-model="form.giro" /> Privado</label>
                  <label><input type="radio" value="otro" v-model="form.giro" /> Otro</label>
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>RFC</label>
                  <input type="text" v-model="form.rfc" class="input-text" />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Calle</label>
                  <input type="text" v-model="form.direccion" class="input-text" />
                </div>
                <div class="form-group">
                  <label>Número</label>
                  <input type="text" v-model="form.numero" class="input-text" placeholder="123 o S/N" />
                </div>
                <div class="form-group">
                  <label>Código Postal</label>
                  <input type="text" v-model="form.codigo_postal" class="input-text" />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Estado</label>
                  <input type="text" v-model="form.estado" class="input-text" />
                </div>
                <div class="form-group">
                  <label>Ciudad</label>
                  <input type="text" v-model="form.ciudad" class="input-text" />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Teléfono</label>
                  <input type="tel" v-model="form.telefono" class="input-text" />
                </div>
                <div class="form-group">
                  <label>Correo Electrónico</label>
                  <input type="email" v-model="form.correo" class="input-text" />
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Nombre del Titular</label>
                  <input type="text" v-model="form.titular" class="input-text" />
                </div>
                <div class="form-group">
                  <label>Puesto del Titular</label>
                  <input type="text" v-model="form.puesto_titular" class="input-text" />
                </div>
              </div>

              <div class="form-group full-width">
                <label>Información adicional</label>
                <input type="text" v-model="form.informacion_e" class="input-text" />
              </div>
            </div>

            <!-- Período -->
            <div class="form-group">
              <label>Período</label>
              <input type="text" :value="periodo?.nombre" class="input-text" disabled />
            </div>

            <div class="form-actions">
              <button type="submit" class="btn-submit" :disabled="cargando">
                <i class="fas" :class="cargando ? 'fa-spinner fa-pulse' : 'fa-save'"></i>
                {{ cargando ? 'Registrando...' : 'Registrar Proyecto' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/appLayout.vue'

import axios from 'axios'

const props = defineProps({
  asesores: Array,
  empresas: Array,
  periodo: Object,
  flash: Object
})

// Formulario para unirse a proyecto
const unirseForm = ref({
  id: '',
  nombre: ''
})

// Formulario para nuevo proyecto
const form = ref({
  nombre: '',
  objetivo_general: '',
  lugar: '',
  informacion: '',
  justificacion: '',
  asesor_id: props.asesores?.[0]?.id || '',
  correo_ae: '',
  titulo_ae: '',
  nombre_ae: '',
  apellido_paterno_ae: '',
  apellido_materno_ae: '',
  puesto_ae: '',
  empresa_id: props.empresas?.[0]?.id || '',
  // Campos para nueva empresa
  nombre_e: '',
  giro: '',
  rfc: '',
  direccion: '',
  numero: '',
  codigo_postal: '',
  ciudad: '',
  estado: '',
  telefono: '',
  correo: '',
  titular: '',
  puesto_titular: '',
  informacion_e: '',
  periodo_id: props.periodo?.id || ''
})

const cargando = ref(false)
const errores = ref({})
const sugerencias = ref([])
let timeoutSugerencias = null

// Buscar sugerencias de proyectos
const buscarSugerencias = () => {
  clearTimeout(timeoutSugerencias)
  
  if (unirseForm.value.nombre.length < 2) {
    sugerencias.value = []
    return
  }
  
  timeoutSugerencias = setTimeout(() => {
    axios.get('/buscar-proyectos', {
      params: { q: unirseForm.value.nombre }
    }).then(response => {
      sugerencias.value = response.data
    }).catch(error => {
      console.error('Error al buscar sugerencias:', error)
    })
  }, 300)
}

const cerrarSugerencias = () => {
  setTimeout(() => {
    sugerencias.value = []
  }, 200)
}

const seleccionarSugerencia = (nombre) => {
  unirseForm.value.nombre = nombre
  sugerencias.value = []
}

// Unirse a proyecto existente
const unirseProyecto = () => {
  if (!unirseForm.value.id || !unirseForm.value.nombre) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Debes ingresar ID y nombre del proyecto'
    })
    return
  }
  
  Swal.fire({
    title: '¿Deseas unirte a este proyecto?',
    html: `Proyecto: <strong>${unirseForm.value.nombre}</strong><br>ID: <strong>${unirseForm.value.id}</strong>`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(route('proyectos.unirse'), {
        id: unirseForm.value.id,
        nombre: unirseForm.value.nombre
      })
    }
  })
}

// Cambiar selección de empresa
const onEmpresaChange = () => {
  // Limpiar errores
  if (form.value.empresa_id != '-1') {
    // Limpiar campos de nueva empresa
    form.value.nombre_e = ''
    form.value.giro = ''
    form.value.rfc = ''
    form.value.direccion = ''
    form.value.numero = ''
    form.value.codigo_postal = ''
    form.value.ciudad = ''
    form.value.estado = ''
    form.value.telefono = ''
    form.value.correo = ''
    form.value.titular = ''
    form.value.puesto_titular = ''
    form.value.informacion_e = ''
  }
}

// Registrar nuevo proyecto
const registrarProyecto = () => {
  cargando.value = true
  errores.value = {}
  
  router.post(route('proyectos.store'), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: 'Proyecto registrado correctamente',
        confirmButtonText: 'Aceptar'
      }).then(() => {
        router.visit(route('home'))
      })
    },
    onError: (errors) => {
      if (errors.error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: errors.error,
          confirmButtonText: 'Aceptar'
        })
      } else {
        errores.value = errors
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Verifica los campos del formulario',
          confirmButtonText: 'Aceptar'
        })
      }
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

/* Tarjetas */
.card-section {
  border: 2px solid #050E3C;
  border-radius: 12px;
  margin-bottom: 30px;
  overflow: hidden;
}

.card-header {
  background-color: #050E3C;
  color: white;
  padding: 15px 20px;
  font-size: 18px;
  font-weight: bold;
}

.card-header i {
  margin-right: 10px;
}

.card-body {
  padding: 25px;
}

/* Formularios */
.form-inline {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.form-row {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}

.form-group {
  flex: 1;
  min-width: 200px;
}

.form-group.full-width {
  flex: 100%;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 5px;
  color: #333;
}

.form-group label i {
  margin-right: 5px;
}

.required {
  color: #dc3545;
}

.input-text, .select, .textarea {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 6px;
  transition: all 0.3s;
}

.input-text:focus, .select:focus, .textarea:focus {
  outline: none;
  border-color: #050E3C;
  box-shadow: 0 0 0 2px rgba(5, 14, 60, 0.1);
}

.textarea {
  resize: vertical;
  font-family: inherit;
}

.error {
  color: #dc3545;
  font-size: 12px;
  margin-top: 5px;
  display: block;
}

/* Sugerencias */
.sugerencias-box {
  position: absolute;
  background: white;
  border: 1px solid #ccc;
  z-index: 1000;
  width: 100%;
  max-height: 200px;
  overflow-y: auto;
  top: 100%;
  left: 0;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.sugerencia-item {
  padding: 8px 12px;
  cursor: pointer;
}

.sugerencia-item:hover {
  background-color: #f0f0f0;
}

/* Radio group */
.radio-group {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  margin-top: 5px;
}

.radio-group label {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-weight: normal;
}

/* Nueva empresa */
.nueva-empresa {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-top: 10px;
  width: 100%;
}

.nueva-empresa h4 {
  margin: 0 0 15px 0;
  color: #050E3C;
}

.nueva-empresa h4 i {
  margin-right: 8px;
}

/* Botones */
.btn-unirse, .btn-submit {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  border: none;
  padding: 10px 24px;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-unirse:hover, .btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.form-actions {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  width: 100%;
}

/* Responsive */
@media (max-width: 768px) {
  .subtitulo {
    font-size: 24px;
  }
  
  .form-row {
    flex-direction: column;
  }
  
  .radio-group {
    flex-direction: column;
    gap: 8px;
  }
}
</style>