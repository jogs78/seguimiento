<template>
  <div class="page-wrapper">
    <!-- Encabezado con barras de colores -->
    <header class="page-header">
      <div class="hderecho" style="width: 100%; height: 20px;">
        <div class="cmenor" style="width: 20%;"></div>
        <div class="cmayor" style="width: 80%;"></div>
      </div>
      
      <div class="header-content">
        <div class="header-left">
          <img src="/storage/img/logo.png" alt="Logo" class="header-logo">
          <h2 class="header-title">Sistema de Seguimiento</h2>
        </div>
        <div class="header-right">
          <Link href="/" class="header-link home-link">
            <i class="fas fa-home"></i> Inicio
          </Link>
          <Link class="header-link logout-link" :href="route('salida')" >
            <i class="fas fa-sign-out-alt"></i> Salir
          </Link>
        </div>
      </div>
      
      <div class="horizontal">
        <div class="linea"></div>
      </div>
    </header>

    <!-- Contenido principal -->
    <main class="main-content">
      <div class="container-select">
        <!-- Ícono decorativo -->
        <div class="icon-container">
          <i class="fas fa-graduation-cap"></i>
        </div>

        <h1 class="select-title">Selecciona la carrera</h1>
        <p class="select-subtitle">Elige la carrera con la que deseas trabajar en esta sesión</p>

        <form @submit.prevent="submit" class="select-form">
          <div class="form-group">
            <label for="carrera" class="form-label">
              <i class="fas fa-university"></i> Carrera
            </label>
            <div class="select-wrapper">
              <select 
                id="carrera"
                v-model="form.carrera_id"
                class="form-select"
                required
              >
                <option value="" disabled selected>
                  -- Selecciona una opción --
                </option>
                <option
                  v-for="carrera in carreras"
                  :key="carrera.id"
                  :value="carrera.id"
                >
                  {{ carrera.nombre }}
                </option>
              </select>
              <i class="fas fa-chevron-down select-arrow"></i>
            </div>
          </div>

          <div class="button-group">
            <button 
              type="submit" 
              class="btn-submit"
              :disabled="form.processing || !form.carrera_id"
            >
              <i class="fas" :class="form.processing ? 'fa-spinner fa-spin' : 'fa-arrow-right'"></i>
              {{ form.processing ? 'Procesando...' : 'Continuar' }}
            </button>
          </div>
        </form>

        <!-- Mensaje informativo -->
        <div class="info-message">
          <i class="fas fa-info-circle"></i>
          <span>Selecciona la carrera para acceder a sus proyectos y estudiantes</span>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="page-footer">
      <div class="footer-content">
        <p>&copy; 2024 Sistema de Seguimiento a Residencias. Todos los derechos reservados.</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  carreras: Array
})

const form = useForm({
  carrera_id: ''
})

const submit = () => {
  form.post('/seleccionar-carrera')
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.page-wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: linear-gradient(135deg, #f0f4f8 0%, #e6ecf5 100%);
}

/* ===== HEADER (estilos consistentes con tu sistema) ===== */
.page-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.hderecho {
  display: flex;
  justify-content: right;
}

.cmenor {
  background-color: rgb(40, 95, 139);
  height: 100%;
}

.cmayor {
  background-color: rgb(19, 46, 68);
  height: 100%;
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

.header-logo {
  height: 45px;
  width: auto;
  filter: brightness(0) invert(1);
}

.header-title {
  color: white;
  font-size: 1.3rem;
  font-weight: 600;
  margin: 0;
}

.header-right {
  display: flex;
  gap: 1rem;
}

.header-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.2rem;
  border-radius: 50px;
  font-weight: 500;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  border: none;
}

.home-link {
  background: rgba(255, 255, 255, 0.15);
  color: white;
}

.home-link:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-2px);
}

.logout-link {
  background: rgba(239, 68, 68, 0.2);
  color: white;
  border: 1px solid rgba(239, 68, 68, 0.5);
}

.logout-link:hover {
  background: rgba(239, 68, 68, 0.3);
  transform: translateY(-2px);
  border-color: #ef4444;
}

/* ===== CONTENIDO PRINCIPAL ===== */
.main-content {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.container-select {
  max-width: 500px;
  width: 100%;
  background: white;
  border-radius: 30px;
  padding: 3rem 2.5rem;
  box-shadow: 0 20px 40px rgba(19, 46, 68, 0.15);
  text-align: center;
  position: relative;
  overflow: hidden;
  animation: slideUp 0.5s ease-out;
}

.container-select::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 6px;
  background: linear-gradient(90deg, rgb(19, 46, 68), rgb(40, 95, 139), #ef4444);
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.icon-container {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, rgb(19, 46, 68), rgb(40, 95, 139));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  box-shadow: 0 10px 20px rgba(19, 46, 68, 0.3);
}

.icon-container i {
  font-size: 2.5rem;
  color: white;
}

.select-title {
  font-size: 2.2rem;
  font-weight: 700;
  color: rgb(19, 46, 68);
  margin-bottom: 0.5rem;
  letter-spacing: -0.5px;
}

.select-subtitle {
  color: #6b7280;
  font-size: 1rem;
  margin-bottom: 2rem;
  line-height: 1.5;
}

/* ===== FORMULARIO ===== */
.select-form {
  margin-top: 1rem;
}

.form-group {
  text-align: left;
  margin-bottom: 2rem;
}

.form-label {
  display: block;
  font-size: 1rem;
  font-weight: 600;
  color: rgb(19, 46, 68);
  margin-bottom: 0.5rem;
}

.form-label i {
  color: rgb(40, 95, 139);
  margin-right: 0.5rem;
}

.select-wrapper {
  position: relative;
}

.form-select {
  width: 100%;
  padding: 1rem 1.2rem;
  font-size: 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 15px;
  background-color: #f9fafb;
  color: #1f2937;
  appearance: none;
  cursor: pointer;
  transition: all 0.3s ease;
  font-weight: 500;
}

.form-select:hover {
  border-color: rgb(40, 95, 139);
  background-color: white;
}

.form-select:focus {
  outline: none;
  border-color: rgb(19, 46, 68);
  box-shadow: 0 0 0 4px rgba(19, 46, 68, 0.1);
  background-color: white;
}

.form-select:invalid {
  color: #9ca3af;
}

.select-arrow {
  position: absolute;
  right: 1.2rem;
  top: 50%;
  transform: translateY(-50%);
  color: rgb(40, 95, 139);
  pointer-events: none;
  font-size: 0.9rem;
}

.button-group {
  margin-top: 1rem;
}

.btn-submit {
  width: 100%;
  padding: 1rem 2rem;
  background: linear-gradient(135deg, rgb(19, 46, 68), rgb(40, 95, 139));
  color: white;
  border: none;
  border-radius: 50px;
  font-size: 1.2rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(19, 46, 68, 0.3);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  position: relative;
  overflow: hidden;
}

.btn-submit::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 25px rgba(19, 46, 68, 0.4);
}

.btn-submit:hover:not(:disabled)::before {
  left: 100%;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  background: #9ca3af;
  box-shadow: none;
}

.btn-submit i {
  font-size: 1.1rem;
}

/* MENSAJE INFORMATIVO */
.info-message {
  margin-top: 2rem;
  padding: 1rem;
  background-color: #f0f9ff;
  border-radius: 12px;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.9rem;
  color: rgb(19, 46, 68);
  border-left: 4px solid rgb(10, 105, 163);
  text-align: left;
}

.info-message i {
  color: rgb(10, 105, 163);
  font-size: 1.2rem;
  flex-shrink: 0;
}

/*  FOOTER */
.page-footer {
  background: rgb(19, 46, 68);
  color: white;
  padding: 1.5rem;
  margin-top: auto;
}

.footer-content {
  max-width: 1400px;
  margin: 0 auto;
  text-align: center;
  font-size: 0.9rem;
  opacity: 0.9;
}


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
    font-size: 1.1rem;
  }
  
  .container-select {
    padding: 2rem 1.5rem;
  }
  
  .select-title {
    font-size: 1.8rem;
  }
  
  .select-subtitle {
    font-size: 0.9rem;
  }
}

@media (max-width: 480px) {
  .main-content {
    padding: 1rem;
  }
  
  .container-select {
    padding: 1.5rem 1rem;
  }
  
  .icon-container {
    width: 60px;
    height: 60px;
  }
  
  .icon-container i {
    font-size: 1.8rem;
  }
  
  .select-title {
    font-size: 1.5rem;
  }
  
  .btn-submit {
    padding: 0.9rem 1.5rem;
    font-size: 1rem;
  }
}


@keyframes pulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

.icon-container {
  animation: pulse 2s infinite ease-in-out;
}

/* Estilos para las opciones del select */
option {
  padding: 0.5rem;
  font-size: 1rem;
}

/* Asegurar que Font Awesome esté disponible */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
</style>