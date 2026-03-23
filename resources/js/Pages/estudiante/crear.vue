<template>
  <div class="registro-page">
    <!-- Header fijo -->
    <header class="header-fixed">
      <!-- Barra superior de colores -->
      <div class="hderecho" style="width: 100%; height: 20px;">
        <div class="cmenor" style="width: 20%;"></div>
        <div class="cmayor" style="width: 80%;"></div>
      </div>

      <!-- Contenido del header -->
      <div class="header-content">
        <div class="header-left">
          <img 
            id="logo" 
            src="../../../../storage/app/public/img/logo.png"
            alt="Logo"
            class="logo-img"
          >
          <h1 class="header-title">Sistema de Seguimiento</h1>
        </div>
        
        <div class="header-right">
          <Link href="/" class="nav-link home">
            <i class="fas fa-home"></i>
            <span>Inicio</span>
          </Link>
          <Link href="/ejemplo" class="nav-link login">
            <i class="fas fa-sign-in-alt"></i>
            <span>Iniciar Sesión</span>
          </Link>
        </div>
      </div>

      <!-- Línea decorativa -->
      <div class="horizontal">
        <div class="linea"></div>
      </div>
    </header>

    <!-- Contenido principal (scrollable) -->
    <main class="main-content">
      <div class="container">
        <h1 class="titulo">Registro de Estudiante</h1>
        
        <!-- Mensajes de error generales -->
        <div v-if="Object.keys(form.errors).length > 0" class="alert alert-danger">
          <h4><i class="fas fa-exclamation-circle"></i> Por favor corrige los siguientes errores:</h4>
          <ul>
            <li v-for="(error, field) in form.errors" :key="field">
              {{ error }}
            </li>
          </ul>
        </div>

        <form @submit.prevent="submit" class="registro-form">
          <!-- Fila: Nombre completo (3 campos) -->
          <div class="form-row three-cols">
            <div class="form-group">
              <label for="nombre" class="parrafo">
                Nombre(s) <span class="required">*</span>
              </label>
              <input 
                type="text" 
                id="nombre"
                v-model="form.nombre"
                :class="['form-control', { 'is-invalid': form.errors.nombre }]"
                placeholder="Ingresa tu nombre"
              >
              <div v-if="form.errors.nombre" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.nombre }}
              </div>
            </div>

            <div class="form-group">
              <label for="apellido_paterno" class="parrafo">
                Apellido Paterno <span class="required">*</span>
              </label>
              <input 
                type="text" 
                id="apellido_paterno"
                v-model="form.apellido_paterno"
                :class="['form-control', { 'is-invalid': form.errors.apellido_paterno }]"
                placeholder="Primer apellido"
              >
              <div v-if="form.errors.apellido_paterno" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.apellido_paterno }}
              </div>
            </div>

            <div class="form-group">
              <label for="apellido_materno" class="parrafo">
                Apellido Materno
              </label>
              <input 
                type="text" 
                id="apellido_materno"
                v-model="form.apellido_materno"
                :class="['form-control', { 'is-invalid': form.errors.apellido_materno }]"
                placeholder="Segundo apellido"
              >
              <div v-if="form.errors.apellido_materno" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.apellido_materno }}
              </div>
            </div>
          </div>

          <!-- Fila: Correo y Teléfono -->
          <div class="form-row two-cols">
            <div class="form-group">
              <label for="correo_electronico" class="parrafo">
                Correo Electrónico <span class="required">*</span>
              </label>
              <input 
                type="email" 
                id="correo_electronico"
                v-model="form.correo_electronico"
                :class="['form-control', { 'is-invalid': form.errors.correo_electronico }]"
                placeholder="ejemplo@correo.com"
              >
              <div v-if="form.errors.correo_electronico" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.correo_electronico }}
              </div>
            </div>

            <div class="form-group">
              <label for="telefono" class="parrafo">
                Teléfono <span class="required">*</span>
              </label>
              <input 
                type="tel" 
                id="telefono"
                v-model="form.telefono"
                :class="['form-control', { 'is-invalid': form.errors.telefono }]"
                placeholder="10 dígitos"
              >
              <div v-if="form.errors.telefono" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.telefono }}
              </div>
            </div>
          </div>

          <!-- Fila: Número de Control y Carrera -->
          <div class="form-row two-cols">
            <div class="form-group">
              <label for="numero_de_control" class="parrafo">
                Número de Control <span class="required">*</span>
              </label>
              <input 
                type="text" 
                id="numero_de_control"
                v-model="form.numero_de_control"
                :class="['form-control', { 'is-invalid': form.errors.numero_de_control }]"
                placeholder="Ej: 12345678"
              >
              <div v-if="form.errors.numero_de_control" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.numero_de_control }}
              </div>
            </div>

            <div class="form-group">
              <label for="carrera_id" class="parrafo">
                Carrera <span class="required">*</span>
              </label>
              <select 
                id="carrera_id"
                v-model="form.carrera_id"
                :class="['form-control', { 'is-invalid': form.errors.carrera_id }]"
              >
                <option value="" disabled selected>Selecciona una carrera</option>
                <option 
                  v-for="carrera in carreras" 
                  :key="carrera.id" 
                  :value="carrera.id"
                >
                  {{ carrera.nombre }}
                </option>
              </select>
              <div v-if="form.errors.carrera_id" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.carrera_id }}
              </div>
            </div>
          </div>

          <!-- Fila: Dirección (sola) -->
          <div class="form-row single-col">
            <div class="form-group">
              <label for="direccion" class="parrafo">
                Dirección <span class="required">*</span>
              </label>
              <input 
                type="text" 
                id="direccion"
                v-model="form.direccion"
                :class="['form-control', { 'is-invalid': form.errors.direccion }]"
                placeholder="Calle, número, colonia, ciudad"
              >
              <div v-if="form.errors.direccion" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.direccion }}
              </div>
            </div>
          </div>

          <!-- Fila: Institución y Número de Seguridad Social -->
          <div class="form-row two-cols">
            <div class="form-group">
              <label for="institucion_seguridad_social" class="parrafo">
                Institución de Seguridad Social
              </label>
              <select 
                id="institucion_seguridad_social"
                v-model="form.institucion_seguridad_social"
                :class="['form-control', { 'is-invalid': form.errors.institucion_seguridad_social }]"
              >
                <option value="" disabled selected>Selecciona una opción</option>
                <option value="IMSS">IMSS</option>
                <option value="ISSSTE">ISSSTE</option>
                <option value="OTROS">OTROS</option>
              </select>
              <div v-if="form.errors.institucion_seguridad_social" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.institucion_seguridad_social }}
              </div>
            </div>

            <div class="form-group">
              <label for="numero_de_seguridad_social" class="parrafo">
                Número de Seguridad Social
              </label>
              <input 
                type="text" 
                id="numero_de_seguridad_social"
                v-model="form.numero_de_seguridad_social"
                :class="['form-control', { 'is-invalid': form.errors.numero_de_seguridad_social }]"
                placeholder="NSS (opcional)"
              >
              <div v-if="form.errors.numero_de_seguridad_social" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.numero_de_seguridad_social }}
              </div>
            </div>
          </div>

          <!-- Fila: Contraseña (sola) -->
          <div class="form-row single-col">
            <div class="form-group">
              <label for="contraseña" class="parrafo">
                Contraseña <span class="required">*</span>
              </label>
              <input 
                type="password" 
                id="contraseña"
                v-model="form.contraseña"
                :class="['form-control', { 'is-invalid': form.errors.contraseña }]"
                placeholder="Mínimo 8 caracteres"
              >
              <div v-if="form.errors.contraseña" class="error-message">
                <i class="fas fa-exclamation-triangle"></i> {{ form.errors.contraseña }}
              </div>
              <small class="form-text text-muted">
                La contraseña debe tener al menos 8 caracteres
              </small>
            </div>
          </div>

          <!-- Botón de envío -->
          <div class="form-row button-row">
            <button 
              type="submit" 
              class="boton"
              :disabled="form.processing"
            >
              <i class="fas" :class="form.processing ? 'fa-spinner fa-spin' : 'fa-save'"></i>
              {{ form.processing ? 'Registrando...' : 'Registrarse' }}
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  carreras: Array
})

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
  contraseña: ''
})

const submit = () => {
  form.post('/estudiantes', {
    preserveScroll: true,
    onSuccess: () => {
      // Redirigir o mostrar mensaje de éxito
      form.reset()
    }
  })
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.registro-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #e9edf5 100%);
}

/* ===== HEADER FIJO ===== */
.header-fixed {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  z-index: 1000;
}

.cmenor {
  background-color: rgb(40, 95, 139);
}

.cmayor {
  background-color: rgb(19, 46, 68);
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

.logo-img {
  height: 50px;
  width: auto;
  filter: brightness(0) invert(1);
}

.header-title {
  color: white;
  font-size: 1.5rem;
  font-weight: 600;
  margin: 0;
}

.header-right {
  display: flex;
  gap: 1rem;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1.2rem;
  border-radius: 50px;
  font-weight: 500;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.3s ease;
  color: white;
  background: rgba(255, 255, 255, 0.1);
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-2px);
}

.nav-link.home {
  background: rgba(239, 68, 68, 0.3);
}

.nav-link.home:hover {
  background: rgba(239, 68, 68, 0.5);
}

.nav-link.login {
  background: rgb(239, 68, 68);
}

.nav-link.login:hover {
  background: rgb(220, 38, 38);
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

/* ===== CONTENIDO PRINCIPAL ===== */
.main-content {
  margin-top: 140px; /* Altura del header fijo */
  padding: 2rem;
  min-height: calc(100vh - 140px);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  background: white;
  border-radius: 20px;
  padding: 2.5rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.titulo {
  text-align: center;
  font-size: 2.5rem;
  font-weight: 700;
  color: rgb(19, 46, 68);
  margin-bottom: 2rem;
  position: relative;
}

.titulo::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 4px;
  background: linear-gradient(90deg, rgb(19, 46, 68), rgb(40, 95, 139), #ef4444);
  border-radius: 2px;
}

/* ===== ALERTAS ===== */
.alert {
  padding: 1rem;
  border-radius: 10px;
  margin-bottom: 2rem;
}

.alert-danger {
  background-color: #fee2e2;
  border-left: 4px solid #ef4444;
  color: #991b1b;
}

.alert-danger h4 {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.alert-danger ul {
  list-style: none;
  padding-left: 1.5rem;
}

.alert-danger li {
  margin-bottom: 0.25rem;
  position: relative;
}

.alert-danger li::before {
  content: '•';
  color: #ef4444;
  font-weight: bold;
  position: absolute;
  left: -1rem;
}

/* ===== FORMULARIO ===== */
.registro-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-row {
  display: grid;
  gap: 1.5rem;
}

.single-col {
  grid-template-columns: 1fr;
}

.two-cols {
  grid-template-columns: 1fr 1fr;
}

.three-cols {
  grid-template-columns: 1fr 1fr 1fr;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.parrafo {
  font-size: 1rem;
  font-weight: 600;
  color: rgb(19, 46, 68);
  margin-bottom: 0.5rem;
}

.required {
  color: #ef4444;
  margin-left: 2px;
}

.form-control {
  padding: 0.75rem 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background-color: #f9fafb;
}

.form-control:focus {
  outline: none;
  border-color: rgb(40, 95, 139);
  box-shadow: 0 0 0 3px rgba(40, 95, 139, 0.2);
  background-color: white;
}

.form-control.is-invalid {
  border-color: #ef4444;
  background-color: #fef2f2;
}

.form-control.is-invalid:focus {
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
}

select.form-control {
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1.25rem;
}

.error-message {
  color: #ef4444;
  font-size: 0.85rem;
  margin-top: 0.25rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.form-text {
  font-size: 0.85rem;
  color: #6b7280;
  margin-top: 0.25rem;
}

.button-row {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
}

.boton {
  background: linear-gradient(135deg, rgb(19, 46, 68), rgb(40, 95, 139));
  color: white;
  border: none;
  padding: 1rem 3rem;
  border-radius: 50px;
  font-size: 1.2rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(19, 46, 68, 0.3);
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.boton:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(19, 46, 68, 0.4);
}

.boton:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* ===== RESPONSIVE ===== */
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
  
  .header-title {
    font-size: 1.2rem;
  }
  
  .main-content {
    margin-top: 180px;
    padding: 1rem;
  }
  
  .container {
    padding: 1.5rem;
  }
  
  .titulo {
    font-size: 2rem;
  }
  
  .two-cols, .three-cols {
    grid-template-columns: 1fr;
  }
  
  .form-row {
    gap: 1rem;
  }
  
  .boton {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .main-content {
    margin-top: 200px;
  }
  
  .header-right {
    flex-direction: column;
    width: 100%;
  }
  
  .nav-link {
    justify-content: center;
  }
  
  .titulo {
    font-size: 1.5rem;
  }
}

/* Animaciones */
@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.alert {
  animation: slideDown 0.3s ease-out;
}

@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>