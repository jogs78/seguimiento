<template>
  <div class="page-wrapper">
    <header class="app-header">
      <div class="header-bars">
        <div class="bar-blue"></div>
        <div class="bar-dark"></div>
      </div>
      
      <div class="header-main">
        <div class="header-left">
          <div class="logo-area">
            <img id="logo_1" src="/images/logo_tecnm_tuxtla.png" alt="Logo">
            <h2 class="logo-text">Sistema de Seguimiento</h2>
          </div>
        </div>
        
        <div class="header-actions">
          <Link :href="route('welcome')" class="nav-link home">
            <i class="fas fa-home"></i>
            <span>Inicio</span>
          </Link>
          <Link :href="route('Inicio_Sesion')" class="nav-link login">
            <i class="fas fa-sign-in-alt"></i>
            <span>Iniciar Sesión</span>
          </Link>
        </div>
      </div>
      
      <div class="header-line"></div>
    </header>

    <main class="main-content">
      <div class="reset-container">
        <div class="reset-card">
          <div class="icon-container">
            <i class="fas fa-key"></i>
          </div>
          
          <h2 class="reset-title">Nueva Contraseña</h2>
          <p class="reset-description">
            Ingresa tu nueva contraseña para restablecer el acceso a tu cuenta.
          </p>

          <!-- ✅ Mostrar mensajes flash -->
          <div v-if="$page.props.flash?.success" class="alert-success">
            <i class="fas fa-check-circle"></i> {{ $page.props.flash.success }}
          </div>
          <div v-if="$page.props.flash?.error" class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ $page.props.flash.error }}
          </div>

          <form @submit.prevent="submit" class="reset-form">
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-lock"></i>
                Nueva Contraseña
              </label>
              <div class="password-wrapper">
                <input
                  :type="showPassword ? 'text' : 'password'"
                  v-model="form.password"
                  class="form-input"
                  :class="{ 'is-invalid': form.errors.password }"
                  placeholder="Mínimo 8 caracteres"
                  required
                />
                <button type="button" class="toggle-password" @click="showPassword = !showPassword">
                  <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <p v-if="form.errors.password" class="error-message">{{ form.errors.password }}</p>
            </div>

            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-check-circle"></i>
                Confirmar Contraseña
              </label>
              <div class="password-wrapper">
                <input
                  :type="showConfirmPassword ? 'text' : 'password'"
                  v-model="form.password_confirmation"
                  class="form-input"
                  :class="{ 'is-invalid': form.errors.password_confirmation }"
                  placeholder="Repite tu nueva contraseña"
                  required
                />
                <button type="button" class="toggle-password" @click="showConfirmPassword = !showConfirmPassword">
                  <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <p v-if="form.errors.password_confirmation" class="error-message">{{ form.errors.password_confirmation }}</p>
            </div>

            <div v-if="passwordsMatch && form.password && form.password_confirmation" class="success-hint">
              <i class="fas fa-check-circle"></i> Las contraseñas coinciden
            </div>

            <button type="submit" :disabled="form.processing" class="btn-submit">
              <i v-if="form.processing" class="fas fa-spinner fa-spin"></i>
              <i v-else class="fas fa-save"></i>
              {{ form.processing ? 'Actualizando...' : 'Actualizar Contraseña' }}
            </button>

            <div class="back-link">
              <Link :href="route('Inicio_Sesion')" class="back-btn">
                <i class="fas fa-arrow-left"></i> Volver al inicio de sesión
              </Link>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  token: String
})

const showPassword = ref(false)
const showConfirmPassword = ref(false)

const form = useForm({
  password: '',
  password_confirmation: '',
  token: props.token
})

const passwordsMatch = computed(() => {
  return form.password && form.password_confirmation && form.password === form.password_confirmation
})

const submit = () => {
  //  Verificar que el token existe
  if (!form.token) {
    console.error('Token no encontrado')
    return
  }
  
  console.log('Enviando formulario con token:', form.token)
  
  // Usar la ruta correcta (POST)
  form.post('/reset-password', {
    onSuccess: () => {
      console.log('Éxito al actualizar contraseña')
    },
    onError: (errors) => {
      console.error('Errores:', errors)
    }
  })
}
</script>

<style scoped>
/* ===== VARIABLES ===== */
:root {
  --primary-dark: #050E3C;
  --primary-medium: #002455;
  --primary-light: #1a3a6e;
  --danger: #DC0000;
  --danger-light: #FF3838;
  --success: #28a745;
  --white: #ffffff;
  --gray-100: #f8f9fa;
  --gray-200: #e9ecef;
  --gray-300: #dee2e6;
  --gray-400: #ced4da;
  --gray-500: #adb5bd;
  --gray-600: #6c757d;
  --gray-700: #495057;
  --gray-800: #343a40;
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  --transition-fast: 0.2s ease;
  --transition-normal: 0.3s ease;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.page-wrapper {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #e9edf5 100%);
}

/* ===== HEADER ===== */
.app-header {
  background: var(--white);
  box-shadow: var(--shadow-md);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-bars {
  display: flex;
  height: 6px;
}

.bar-blue {
  width: 20%;
  background-color: var(--primary-medium);
}

.bar-dark {
  width: 80%;
  background-color: var(--primary-dark);
}

.header-main {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background: linear-gradient(90deg, var(--primary-dark) 0%, var(--primary-medium) 100%);
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.logo-area {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

#logo_1 {
  height: 45px;
  width: auto;
  filter: brightness(0) invert(1);
}

.logo-text {
  color: var(--white);
  font-size: 1.3rem;
  font-weight: 600;
  letter-spacing: -0.5px;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

.nav-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.2rem;
  border-radius: 50px;
  font-weight: 500;
  font-size: 0.95rem;
  text-decoration: none;
  transition: all var(--transition-normal);
  cursor: pointer;
  border: none;
}

.nav-link.home {
  background: rgba(255, 255, 255, 0.15);
  color: var(--white);
}

.nav-link.home:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-2px);
}

.nav-link.login {
  background: rgba(220, 0, 0, 0.2);
  color: var(--white);
  border: 1px solid rgba(220, 0, 0, 0.5);
}

.nav-link.login:hover {
  background: rgba(220, 0, 0, 0.35);
  transform: translateY(-2px);
}

.header-line {
  height: 4px;
  background: linear-gradient(90deg, var(--primary-light), var(--primary-medium), var(--danger-light));
}

/* ===== MAIN CONTENT ===== */
.main-content {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: calc(100vh - 140px);
  padding: 2rem;
}

.reset-container {
  width: 100%;
  max-width: 500px;
}

.reset-card {
  background: var(--white);
  border-radius: 24px;
  padding: 2.5rem;
  box-shadow: var(--shadow-lg);
  animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
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
  background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  box-shadow: var(--shadow-md);
}

.icon-container i {
  font-size: 2.5rem;
  color: var(--white);
}

.reset-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--primary-dark);
  margin-bottom: 0.5rem;
  text-align: center;
}

.reset-description {
  color: var(--gray-600);
  font-size: 0.9rem;
  margin-bottom: 2rem;
  line-height: 1.5;
  text-align: center;
}

/* ===== FORM ===== */
.reset-form {
  text-align: left;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--primary-dark);
  margin-bottom: 0.5rem;
}

.form-label i {
  color: var(--primary-medium);
  margin-right: 0.5rem;
}

.password-wrapper {
  position: relative;
}

.form-input {
  width: 100%;
  padding: 0.75rem 2.5rem 0.75rem 1rem;
  font-size: 1rem;
  border: 2px solid var(--gray-300);
  border-radius: 12px;
  transition: all var(--transition-fast);
  background-color: var(--gray-100);
}

.form-input:focus {
  outline: none;
  border-color: var(--primary-medium);
  box-shadow: 0 0 0 3px rgba(0, 36, 85, 0.1);
  background-color: var(--white);
}

.form-input.is-invalid {
  border-color: var(--danger);
  background-color: #fef2f2;
}

.toggle-password {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: var(--gray-500);
  font-size: 1rem;
  transition: color var(--transition-fast);
}

.toggle-password:hover {
  color: var(--primary-medium);
}

.error-message {
  color: var(--danger);
  font-size: 0.75rem;
  margin-top: 0.25rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.password-hint {
  display: block;
  font-size: 0.7rem;
  color: var(--gray-500);
  margin-top: 0.25rem;
}

.success-hint {
  background: #d4edda;
  color: #155724;
  padding: 0.5rem 0.75rem;
  border-radius: 8px;
  font-size: 0.8rem;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

/* ===== BUTTONS ===== */
.btn-submit {
  width: 100%;
  background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium));
  color: var(--white);
  border: none;
  padding: 0.9rem 1.5rem;
  border-radius: 50px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all var(--transition-normal);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.back-link {
  text-align: center;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--gray-600);
  text-decoration: none;
  font-size: 0.85rem;
  transition: all var(--transition-fast);
}

.back-btn:hover {
  color: var(--primary-medium);
  transform: translateX(-3px);
}

/* ===== TOAST NOTIFICATIONS ===== */
.toast-notification {
  position: fixed;
  bottom: 20px;
  right: 20px;
  padding: 12px 20px;
  border-radius: 10px;
  color: white;
  font-weight: 500;
  z-index: 9999;
  animation: slideInRight 0.3s ease-out;
  box-shadow: var(--shadow-md);
}

.toast-notification.success {
  background: linear-gradient(135deg, #28a745, #20c997);
}

.toast-notification.error {
  background: linear-gradient(135deg, #dc3545, #c82333);
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .header-main {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .header-left {
    width: 100%;
    justify-content: center;
  }
  
  .header-actions {
    width: 100%;
    justify-content: center;
  }
  
  .main-content {
    padding: 1rem;
  }
  
  .reset-card {
    padding: 1.5rem;
  }
  
  .reset-title {
    font-size: 1.5rem;
  }
  
  .icon-container {
    width: 60px;
    height: 60px;
  }
  
  .icon-container i {
    font-size: 1.8rem;
  }
}

@media (max-width: 480px) {
  .reset-card {
    padding: 1.25rem;
  }
  
  .btn-submit {
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
  }
  
  .logo-text {
    font-size: 1rem;
  }
  
  #logo_1 {
    height: 35px;
  }
}

.alert-success {
  background: #d4edda;
  color: #155724;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background: #f8d7da;
  color: #721c24;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  border: 1px solid #f5c6cb;
}
</style>